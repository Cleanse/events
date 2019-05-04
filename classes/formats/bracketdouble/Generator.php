<?php

namespace Cleanse\Event\Classes\Formats\BracketDouble;

use Cleanse\Event\Classes\Helpers\BracketHelper;
use Cleanse\Event\Classes\Generators\MatchGenerator;
use Cleanse\Event\Models\Event;

/**
 * http://www.gottfriedville.net/mathprob/misc-dblelim.html
 * http://www.denegames.ca/tournaments/index.html
 * https://www.slideshare.net/MontecriZz/single-and-double-elimination-tournament
 * Number of matches =（N-1）× 2 ＋ 1
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

    private function createMatches($matches)
    {
        $order = 1;
        foreach ($matches as $match) {
            (new MatchGenerator)->createMatch($this->event->id, $match, $order);
            $order++;
        }

        //if count is not within bracketSize...
        $teamCount = BracketHelper::getBracketSize(count($this->event->teams));

        $maxMatches = ($teamCount - 1) * 2 + 1; //（N-1）× 2 ＋ 1
        if (isset($this->event->config['grand_finals']) && $this->event->config['grand_finals'] == 1) {
            $maxMatches = $maxMatches - 1;
        }

        $leftover = $maxMatches - ($order - 1);

        for ($i = 1; $i <= $leftover; $i++) {
            $match = [null, null];
            (new MatchGenerator)->createMatch($this->event->id, $match, $order);

            $order++;
        }
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
}
