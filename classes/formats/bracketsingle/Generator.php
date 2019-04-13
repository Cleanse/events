<?php

namespace Cleanse\Event\Classes\Formats\BracketSingle;

use Cleanse\Event\Classes\Generators\MatchGenerator;
use Cleanse\Event\Models\Event;

/**
 * https://github.com/xoco70/laravel-tournaments/blob/master/src/TreeGen/CreateSingleEliminationTree.php
 * https://www.slideshare.net/MontecriZz/single-and-double-elimination-tournament
 * G = N - 1 (Game amount equals Number of entries minus 1.
 * Bye = least power2 higher than N. 2x2=4x2=8x2=16x2=32...
 * 6 teams, 8-6 = 2 byes.
 */
class Generator
{
    private $event;

    public function scheduleEvent($event, $create = true)
    {
        $this->event = $event;

        $matches = $this->makeSchedule();

        $this->createMatches($matches);

        return $this->event->id;
    }

    private function makeSchedule()
    {
        if ($this->event->config['randomize'] > 0) {
            $teams = $this->event->teams->pluck('id')->toArray();
            shuffle($teams);

            $teamSeed = $this->fauxSeeding($teams);

//            foreach($teams as $key => $value) {
//                dd($value);
//            }

            dd($teamSeed); //compare this to below
        } else {
            $teams = $this->event->teams->pluck('id', 'pivot.seed')->toArray();

            //Create seeded groups.
            $groupTeams = RoundRobinHelper::seed_partition($teams, $this->event->config['number_of_groups']);
        }

        $matches = [];
        foreach ($groupTeams as $group) {
            $matches[] = $this->generateGroupMatches($group);
        }

        return $matches;
    }

    private function createMatches($matches)
    {
        $order = 1;
        foreach ($matches as $group) {
            foreach ($group as $match) {
                (new MatchGenerator)->createMatch($this->event->id, $match, $order);
            }
            $order++;
        }
    }

//    private function reorderPlacement()
//    {
//        $placement = post('placement');
//        $eventId = post('id');
//
//        $event = Event::find($eventId);
//
//        $i = 1;
//        foreach($placement as $key => $value) {
//            $event->teams()->updateExistingPivot($key, ['seed' => $i]);
//
//            $i++;
//        }
//    }

    private function fauxSeeding($placement)
    {
        $event = Event::find($this->event->id);

        $i = 1;
        $seeds = [];
        foreach($placement as $value) {
            $seeds[] = $i;
            $event->teams()->updateExistingPivot($value, ['seed' => $i]);

            $i++;
        }

        return array_combine($seeds, $placement);
    }
}
