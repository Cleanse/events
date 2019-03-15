<?php

namespace Cleanse\Event\Components;

use Flash;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Type;

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
        return Type::all();
    }

    public function onEventSave()
    {
        $data = post();

        $rules = [
            'event-title' => 'required',
            'event-type' => 'required',
            'number_of_teams' => 'required_if:event-type,round-robin', //Move to json?
            'number_of_groups' => 'required_if:event-type,round-robin', //Move to json?
            'cycles' => 'required_if:event-type,round-robin' //Move to json?
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        Flash::success('Worked!');

        $this->page['result'] = input('event-title');

        return ['#myDiv' => $this->renderPartial('mypartial')];
    }
}
