<?php

namespace Cleanse\Event\Classes;

class ValidateEvent
{
    private $default;
    private $type;

    public function __construct()
    {
        $this->default = [
            'event-title' => 'required',
            'event-type' => 'required'
        ];
    }

    //Should I pass a real value here and not rely on post()?
    public function validateEvent()
    {
        if (!post('event-type') == '') {
            $eventType = $this->classifyEvent();
            $this->type = ($this->getInstance($eventType))->rules();
        }

        return $this->mergeConfigs();
    }

    private function mergeConfigs()
    {
        $this->type = isset($this->type) ? $this->type : [];
        return array_merge($this->default, $this->type);
    }

    private function classifyEvent()
    {
        $type = str_replace('-', ' ', post('event-type'));
        $type = ucwords($type);
        return str_replace(' ', '', $type);
    }

    private function getInstance($eventType)
    {
        $className = 'Cleanse\\Event\\Classes\Formats\\' . $eventType;
        return new $className();
    }
}
