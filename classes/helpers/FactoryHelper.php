<?php

namespace Cleanse\Event\Classes\Helpers;

class FactoryHelper
{
    public function getInstance($namespace, $eventType)
    {
        $className = $namespace . $this->classifyString($eventType);
        return new $className();
    }

    private function classifyString($eventType)
    {
        $type = str_replace('-', ' ', $eventType);
        $type = ucwords($type);

        return str_replace(' ', '', $type);
    }
}
