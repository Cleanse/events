<?php

namespace Cleanse\Event\Classes\Generators;

class Swiss
{
    public function createEvent($event)
    {
        return '';
    }

    public function updateEvent($event)
    {
        return '';
    }

    public function scheduleEvent($event, $create)
    {
        return $this->makeSchedule();
    }

    private function makeSchedule()
    {
        return [];
    }
}
