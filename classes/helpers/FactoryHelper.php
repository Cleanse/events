<?php

namespace Cleanse\Event\Classes\Helpers;

class FactoryHelper
{
    public function getInstance($source, $eventType)
    {
        $className = $source['namespace'] . $this->classifyString($eventType) . '\\' . $source['target'];
        return new $className();
    }

    private function classifyString($eventType)
    {
        $type = str_replace('-', ' ', $eventType);
        $type = ucwords($type);

        return str_replace(' ', '', $type);
    }
}
