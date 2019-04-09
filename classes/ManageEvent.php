<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class ManageEvent
{
    private $source;

    public function __construct()
    {
        $this->source = [
            'namespace' => 'Cleanse\\Event\\Classes\\Formats\\',
            'target'    => 'Generator'
        ];
    }

    public function generateSchedule($data, $create = false)
    {
        return ((new FactoryHelper)->getInstance($this->source, $data['event-type']))->scheduleEvent($data, $create);
    }
}
