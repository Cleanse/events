<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class ManageEvent
{
    private $namespace;

    public function __construct()
    {
        $this->namespace = 'Cleanse\\Event\\Classes\\Generators\\';
    }

    public function generateEvent($data, $cud = 'create')
    {
        if ($cud == 'update') {
            return ((new FactoryHelper)->getInstance($this->namespace, $data['event-type']))->updateEvent($data);
        } else if ($cud == 'delete') {
            $this->deleteEvent($data);
            return true;
        }

        return ((new FactoryHelper)->getInstance($this->namespace, $data['event-type']))->createEvent($data);
    }

    public function deleteEvent($event)
    {
        $getEvent = Event::find($event['id']);

        $getEvent->active = false;

        $getEvent->save();
    }

    //todo: return proper value after scheduling
    public function generateSchedule($data)
    {
        return ((new FactoryHelper)->getInstance($this->namespace, $data['event-type']))->scheduleEvent($data);
    }
}
