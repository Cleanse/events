<?php

namespace Cleanse\Event\Classes\Generators;

use Exception;

use Cleanse\Event\Classes\Helpers\RoundRobinHelper;
use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;

class RoundRobin
{
    public $teams;
    public $groups;
    public $cycles;
    public $randomize;

    public function __construct($config)
    {
        $this->teams = isset($config['teams']) ? $config['teams'] : [];
        $this->groups = isset($config['groups']) ? $config['groups'] : 1;
        $this->cycles = isset($config['cycles']) ? $config['cycles'] : 1;
        if (!$this->cycles > 0) {
            $this->cycles = 1;
        }
        $this->randomize = isset($config['randomize']) ? $config['randomize'] : false;
    }

    public function generate()
    {
        if (count($this->teams) <= 1) {
            //Will be handled already.
            throw new Exception('You need to specify at least 2 teams.');
        }

        return $this->makeSchedule();
    }

    public function create($event)
    {
        $newEvent = new Event;

        $newEvent->name = $event['name'];
        $newEvent->description = $event['description'];
        $newEvent->event_type = $event['event_type'];

        $newEvent->save();

        //return $newEvent;
        //create Matches

        return 'Add teams.';
    }

    private function makeSchedule()
    {
        if ($this->randomize) {
            shuffle($this->teams);
        }

        //Divide teams into groups.
        $groupTeams = RoundRobinHelper::array_partition($this->teams, $this->groups);

        $matches = [];
        foreach ($groupTeams as $group) {
            $matches[] = $this->generateGroupMatches($group);
        }

        return $matches;
    }

    /**
     * Thanks https://en.wikipedia.org/wiki/Round-robin_tournament#Scheduling_algorithm
     */
    private function generateGroupMatches($teams)
    {
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
}
