<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class ValidateEvent
{
    private $rules;
    private $type;
    private $source;

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

        $this->source = [
            'namespace' => 'Cleanse\\Event\\Classes\Formats\\',
            'target'    => 'Validator'
        ];
    }

    public function validateEvent($eventType)
    {
        if (!$eventType == '') {
            $this->type = ((new FactoryHelper)->getInstance($this->source, $eventType))->rules();
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
