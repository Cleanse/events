<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class ManageEvent
{
    public function generateEvent($data, $namespace, $cud = 'create')
    {
        if ($cud = 'update') {
            return ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->updateEvent($data);
        }

        if ($cud = 'delete') {
            return ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->deleteEvent($data);
        }

        return ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->createEvent($data);
    }

    public function deleteEvent($event)
    {
        $getEvent = Event::find($event['id']);

        $getEvent->active = false;

        $getEvent->save();
    }

    //todo: return proper value after scheduling
    public function generateSchedule($data, $namespace)
    {
        return ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->scheduleEvent();
    }
}
