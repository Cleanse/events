<?php

namespace Cleanse\Event\Classes\Generators;

/**
 * https://github.com/xoco70/laravel-tournaments/blob/master/src/TreeGen/CreateSingleEliminationTree.php
 * https://www.slideshare.net/MontecriZz/single-and-double-elimination-tournament
 * G = N - 1 (Game amount equals Number of entries minus 1.
 * Bye = least power2 higher than N. 2x2=4x2=8x2=16x2=32...
 * 6 teams, 8-6 = 2 byes.
 */
class SingleEliminationBracket
{
    public function createEvent($event)
    {
        return '';
    }

    public function updateEvent($event)
    {
        return '';
    }

    public function scheduleEvent()
    {
        return $this->makeSchedule();
    }

    private function makeSchedule()
    {
        return [];
    }
}
