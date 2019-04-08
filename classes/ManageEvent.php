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

    public function generateEvent($data, $cud = 'create')
    {
        if ($cud == 'update') {
            return ((new FactoryHelper)->getInstance($this->source, $data['event-type']))->updateEvent($data);
        } else if ($cud == 'delete') {
            $this->deleteEvent($data);
            return true;
        }

        return ((new FactoryHelper)->getInstance($this->source, $data['event-type']))->createEvent($data);
    }

    public function deleteEvent($event)
    {
        $getEvent = Event::find($event['id']);

        $getEvent->active = false;

        $getEvent->save();
    }

    //todo: return proper value after scheduling
    public function generateSchedule($data, $create = false)
    {
        return ((new FactoryHelper)->getInstance($this->source, $data['event-type']))->scheduleEvent($data, $create);
    }
}
