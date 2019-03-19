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

        $this->page['event_types'] = $this->loadEventTypes();
    }

    public function onEventSave()
    {
        $data = post();

        $validationNamespace = 'Cleanse\\Event\\Classes\Formats\\';
        $rules = (new ValidateEvent())->validateEvent($data['event-type'], $validationNamespace);

        $validation = Validator::make($data, $rules['validation'], $rules['messages']);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        try {
            $namespace = 'Cleanse\\Event\\Classes\\Generators\\';
            (new GenerateEvent())->generateEvent($data, $namespace);
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    private function loadEventTypes()
    {
        return [
            [
                'value'   => 'round-robin',
                'display' => 'Round Robin'],
            [
                'value'   => 'single-elimination-bracket',
                'display' => 'Single Elimination Bracket'],
            [
                'value'   => 'double-elimination-bracket',
                'display' => 'Double Elimination Bracket'],
            [
                'value'   => 'swiss',
                'display' => 'Swiss']
        ];
    }
}
