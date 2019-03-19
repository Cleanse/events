<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class ValidateEvent
{
    private $rules;
    private $type;

    public function __construct()
    {
        $this->rules = [
            'validation' => [
                'event-title' => 'required',
                'event-type'  => 'required'
            ],
            'messages' => [
                'event-title.required' => 'Please enter a title for your event.',
                'event-type.required'  => 'Please select an event type for your event.'
            ]
        ];
    }

    public function validateEvent($eventType, $namespace)
    {
        if (!$eventType == '') {
            $this->type = ((new FactoryHelper)->getInstance($namespace, $eventType))->validation();
        }

        return $this->mergeConfigs();
    }

    private function mergeConfigs()
    {
        $typeRules = isset($this->type) ? $this->type : ['validation' => [], 'messages' => []];

        return [
            'validation' => array_merge($this->rules['validation'], $typeRules['validation']),
            'messages' => array_merge($this->rules['messages'], $typeRules['messages'])
        ];
    }
}
