<?php

namespace Cleanse\Event\Classes\Formats\Swiss;

class Generator
{
    public function createEvent($event)
    {
        return '';
    }

    public function updateEvent($event)
    {
        return '';
    }

    public function scheduleEvent($event)
    {
        return $this->makeSchedule();
    }

    private function makeSchedule()
    {
        return [];
    }
}
