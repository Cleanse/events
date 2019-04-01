<?php

namespace Cleanse\Event\Classes\Generators;

use Exception;

use Cleanse\Event\Classes\Helpers\RoundRobinHelper;
use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;

class RoundRobin
{
    public function createEvent($event)
    {
        $newEvent = new Event;

        $newEvent->name = $event['event-title'];
        $newEvent->description = $event['event-description'];
        $newEvent->type = $event['event-type'];
        $newEvent->config = json_encode($event['event_config']);

        $newEvent->save();

        return $newEvent->id;
    }

    public function updateEvent($event)
    {
        $getEvent = Event::find($event['eid']);

        $getEvent->name = $event['event-title'];
        $getEvent->description = $event['event-description'];
        $getEvent->type = $event['event-type'];
        $getEvent->config = json_encode($event['event_config']);

        $getEvent->save();

        return $getEvent->id;
    }

    public function scheduleEvent($event)
    {
        if (count($event->teams) <= 1) {
            throw new Exception('You need to specify at least 2 teams.');
        }

        return $this->makeSchedule();
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
     * ToDo: Reminder to support seeding to balance multiple groups/divisions
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
