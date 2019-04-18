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

    public function scheduleEvent($event)
    {
        $this->event = $event;

        $matches = $this->makeSchedule();

        $this->createMatches($matches);

        return $this->event->id;
    }

    private function makeSchedule()
    {
        $teamsWithSeed = $this->getTeamsWithSeeds();

        //Pair the teams for opening round
        $groupTeams = $this->getInitialRoundPrepared($teamsWithSeed);

        //Setup match order for bracket
        $orderedMatches = $this->reOrderMatches($groupTeams);

        return $orderedMatches;
    }

    private function getTeamsWithSeeds()
    {
        if ($this->event->config['randomize'] > 0) {
            $teams = $this->event->teams->pluck('id')->toArray();
            shuffle($teams);

            $teamsWithSeed = $this->fauxSeeding($teams);
        } else {
            $teamsWithSeed = $this->event->teams->pluck('id', 'pivot.seed')->toArray();
        }

        return $teamsWithSeed;
    }

    private function getInitialRoundPrepared($teams)
    {
        $teamCount = count($teams);
        $matchesCount = $teamCount / 2;

        $seeds = range(1, $teamCount);
        $seeds = array_chunk($seeds, $matchesCount);

        $g = 1;
        $orderedList = [];
        foreach ($seeds as $chunk) {
            if ($g % 2 === 0) {
                $chunk = array_reverse($chunk);
            }

            foreach ($chunk as $item => $value) {
                $orderedList[] = $value;
            }

            $g++;
        }

        $divisions = [];
        $r = 1;
        foreach ($orderedList as $seed) {
            $divisions[$r][] = $teams[$seed];

            if ($r % $matchesCount !== 0) {
                $r++;
            } else {
                $r = 1;
            }
        }

        return $divisions;
    }

    private function reOrderMatches($matches)
    {
        $branches = count($matches);

        $newOrder = [];
        for ($o = 1; $o <= $branches; $o++) {
            $match = reset($matches);
            $newOrder[$o] = $match;
            array_shift($matches);

            $matches = array_reverse($matches);
        }

        return $newOrder;
    }

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

    private function createMatches($matches)
    {
        $order = 1;
        foreach ($matches as $match) {
            (new MatchGenerator)->createMatch($this->event->id, $match, $order);
            $order++;
        }

        $maxMatches = count($this->event->teams) - 1;
        if (isset($this->event->config['third_place_match']) && $this->event->config['third_place_match'] > 0) {
            $maxMatches = $maxMatches + 1;
        }

        $leftover = $maxMatches - ($order - 1);

        for ($i = 1; $i <= $leftover; $i++) {
            $match = [null, null];
            (new MatchGenerator)->createMatch($this->event->id, $match, $order);

            $order++;
        }
    }
}
