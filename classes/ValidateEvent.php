<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

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
    public function validateEvent($eventType, $namespace)
    {
        if (!$eventType == '') {
            $this->type = ((new FactoryHelper)->getInstance($namespace, $eventType))->rules();
        }

        return $this->mergeConfigs();
    }

    private function mergeConfigs()
    {
        $this->type = isset($this->type) ? $this->type : [];
        return array_merge($this->default, $this->type);
    }
}
