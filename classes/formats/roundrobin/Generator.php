<?php

namespace Cleanse\Event\Classes\Formats\RoundRobin;

use Exception;

use Cleanse\Event\Classes\Helpers\RoundRobinHelper;
use Cleanse\Event\Classes\Generators\MatchGenerator;

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

            //Divide teams into groups.
            $groupTeams = RoundRobinHelper::array_partition($teams, $this->event->config['number_of_groups']);
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

    /**
     * Thanks https://en.wikipedia.org/wiki/Round-robin_tournament#Scheduling_algorithm
     * https://phpro.org/examples/Create-Round-Robin-Using-PHP.html
     * ToDo: Reminder to support seeding to balance multiple groups/divisions
     *
     * @param $teams
     * @return array
     * @throws Exception
     */
    private function generateGroupMatches($teams)
    {
        $teamCount = count($teams);

        //Check if team count is odd.
        if ($teamCount % 2 !== 0) {
            array_push($teams, null);
            $teamCount += 1;
        }

        $halfTeamCount = $teamCount / 2;

        //Setup amount of times teams face-off.
        $cycle = $this->event->config['cycles'] * ($teamCount - 1);
        if ($cycle === null) {
            $cycle = $teamCount - 1;
        }
        //Create matches.
        $matches = [];
        for ($round = 1; $round <= $cycle; $round += 1) {
            foreach ($teams as $key => $team) {
                if ($key >= $halfTeamCount) {
                    break;
                }

                $team1 = $team;
                $team2 = $teams[$key + $halfTeamCount];

                //Avoid Empty teams / displaying "byes"
                if (is_null($team1) || is_null($team2)) {
                    continue;
                }

                $match = $round % 2 === 0 ? [$team1, $team2] : [$team2, $team1];

                $matches[] = $match;
            }

            RoundRobinHelper::rotate($teams);
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
}
