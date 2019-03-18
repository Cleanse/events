<?php

namespace Cleanse\Event\Components;

use Exception;
use Flash;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ValidateEvent;
use Cleanse\Event\Classes\GenerateEvent;

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

        $namespace = 'Cleanse\\Event\\Classes\Formats\\';
        $rules = (new ValidateEvent())->validateEvent(post('event-type'), $namespace);

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        try {
            echo 'Success.';
            //Dynamically Generate event based on post 'event-type'
            //new GenerateEvent();
        } catch (Exception $exception) {
            throw new $exception;
        }
    }
}
