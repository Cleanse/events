<?php

namespace Cleanse\Event\Components;

use Exception;
use Redirect;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\Helpers\EventTypes;
use Cleanse\Event\Classes\ValidateEvent;
use Cleanse\Event\Models\Event;

class AdminCreate extends ComponentBase
{
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

        $this->page['event_types'] = EventTypes::load();
    }

    public function onCreateEvent()
    {
        $data = post();

        $rules = (new ValidateEvent())->validateEvent($data['event-type']);

        $validation = Validator::make($data, $rules['validation'], $rules['messages']);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        try {
            $event = $this->createEvent($data);

            return Redirect::to('/event/'.$event.'/edit');
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    public function createEvent($event)
    {
        $newEvent = new Event;

        $newEvent->name = $event['event-title'];
        $newEvent->description = $event['event-description'];
        $newEvent->type = $event['event-type'];
        $newEvent->config = $event['event_config'];

        $newEvent->save();

        return $newEvent->id;
    }
}
