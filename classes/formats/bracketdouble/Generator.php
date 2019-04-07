<?php

namespace Cleanse\Event\Classes\Formats\BracketDouble;

/**
 * http://www.gottfriedville.net/mathprob/misc-dblelim.html
 * http://www.denegames.ca/tournaments/index.html
 * https://www.slideshare.net/MontecriZz/single-and-double-elimination-tournament
 * Number of matches =（N-1）× 2 ＋ 1
 */
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

    public function scheduleEvent($event, $create)
    {
        return $this->makeSchedule();
    }

    private function makeSchedule()
    {
        return [];
    }
}
