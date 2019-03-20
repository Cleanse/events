<?php

namespace Cleanse\Event\Classes;

use Redirect;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class GenerateEvent
{
    public function generateEvent($data, $namespace)
    {
        $event = ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->createEvent($data);

        return Redirect::to('/event/'.$event);
    }

    public function generateSchedule($data, $namespace)
    {
        $event = ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->scheduleEvent();

        return Redirect::to('/event/'.$event);
    }
}
