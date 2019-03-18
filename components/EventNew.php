<?php

namespace Cleanse\Event\Components;

use Flash;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ValidateEvent;

class EventNew extends ComponentBase
{
    public $event_types;

    public function componentDetails()
    {
        return [
            'name' => 'New Event Form',
            'description' => 'Displays a form for new event creation.'
        ];
    }

    public function onRun()
    {
        $this->addJs('assets/js/events.js');

        $this->event_types = $this->page['event_types'] = $this->loadEventTypes();
    }

    private function loadEventTypes()
    {
        return [
            ['value' => 'round-robin', 'display' => 'Round Robin'],
            ['value' => 'single-elimination', 'display' => 'Single Elimination Bracket'],
            ['value' => 'double-elimination', 'display' => 'Double Elimination Bracket'],
            ['value' => 'swiss', 'display' => 'Swiss']
        ];
    }

    public function onEventSave()
    {
        $data = post();

        $rules = (new ValidateEvent())->validateEvent();

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        Flash::success('Jobs done!');

        return;

        //Array merge the default event config with the event type config
        $this->mergeConfigs($data('event-type'));

        Flash::success('Worked!');

        $this->page['result'] = input('event-title');

        //Return and redirect to the event page.
        return Redirect::to('event/:id');
    }
}
