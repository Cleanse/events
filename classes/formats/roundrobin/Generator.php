<?php

namespace Cleanse\Event\Classes\Formats\RoundRobin;

use Exception;

use Cleanse\Event\Classes\Helpers\RoundRobinHelper;
use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;

class Generator
{
    private $event;

    public function scheduleEvent($event, $create = true)
    {
        $this->event = $event;

        if ($create) {
            return $this->makeSchedule();
        }

        //Might scrap.
        return $this->makePlacement();
    }

    private function makeSchedule()
    {
        if ($this->event->config['randomize'] > 0) {
            $teams = $this->event->teams->pluck('id')->toArray();
            shuffle($teams);

            //Divide teams into groups.
            $groupTeams = RoundRobinHelper::array_partition($teams, $this->event->config['number_of_groups']);
        } else {
            $teams = $this->event->teams->pluck('pivot.seed')->toArray();
            //Divide teams into groups.
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
     * ToDo: Reminder to support seeding to balance multiple groups/divisions
     *
     * @param $teams
     * @return array
     * @throws Exception
     */
    private function generateGroupMatches($teams)
    {
        dd($teams);
        //if there is 0-1 teams in this group, error so a balanced group:team ratio is met.
        if (count($teams) <= 1) {
            throw new Exception('Your team to group ratio will not work.');
        }

        $teamCount = count($teams);

        //Check if team count is odd.
        if ($teamCount % 2 !== 0) {
            array_push($teams, null);
            $teamCount += 1;
        }

        $halfTeamCount = $teamCount / 2;

        //Setup amount of times teams face-off.
        $cycle = $this->cycles * ($teamCount - 1);
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

        /** Number of matches =【 N ×（ N-1 ) 】÷ 2 */
        return $matches;
    }

    private function makePlacement()
    {
        return 'Data for Preview.';
    }

    private function seedingAndGroups()
    {
        //Split up top seeds into each groups.
        return 'G Unit.';
    }
}
