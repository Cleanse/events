<?php

namespace Cleanse\Event\Classes\Generators;

use Exception;

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
            throw new Exception('You need to specify at least 2 teams.');
        }

        return $this->makeSchedule();
    }

    public function create($schedule)
    {
        return 'Do database stuff.';
    }

    private function makeSchedule()
    {
        if ($this->randomize) {
            shuffle($this->teams);
        }

        //Divide teams into groups.
        $groupTeams = $this->array_partition($this->teams, $this->groups);

        $matches = [];
        foreach ($groupTeams as $group) {
            $matches[] = $this->generateGroupMatches($group);
        }

        return $matches;
    }

    /** Thanks https://en.wikipedia.org/wiki/Round-robin_tournament#Scheduling_algorithm */
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

            $this->rotate($teams);
        }

        /** Number of matches =【 N ×（ N-1 ) 】÷ 2 */
        return $matches;
    }

    /** Thanks https://gist.github.com/mcrumley/10396818 */
    private function array_partition(array $a, $np = 1, $pad = false)
    {
        $np = (int)$np;
        if ($np <= 0) {
            throw new Exception('Array partition count must be greater than zero.');
        }

        $c = count($a);
        $per_array = (int)floor($c / $np);
        $rem = $c % $np;

        if ($c === 0) {
            if ($pad) {
                $result = array_fill(0, $np, array());
            } else {
                $result = array();
            }
        } elseif ($rem === 0 || $rem == $np - 1 || $np >= $c) {
            $result = array_chunk($a, $per_array + ($rem > 0 ? 1 : 0));

            if ($pad && $np > $c) {
                $result = array_merge($result, array_fill(0, $np - $c, array()));
            }
        } else {
            $split = $rem * ($per_array + 1);
            $result = array_chunk(array_slice($a, 0, $split), $per_array + 1);
            $result = array_merge($result, array_chunk(array_slice($a, $split), $per_array));
        }

        return $result;
    }

    /** Thanks to 'mnito'. */
    private function rotate(array &$items)
    {
        $itemCount = count($items);
        if ($itemCount < 3) {
            return;
        }
        $lastIndex = $itemCount - 1;

        /**
         * Though not technically part of the round-robin algorithm, odd-even
         * factor differentiation included to have intuitive behavior for arrays
         * with an odd number of elements
         */
        $factor = (int)($itemCount % 2 === 0 ? $itemCount / 2 : ($itemCount / 2) + 1);
        $topRightIndex = $factor - 1;
        $topRightItem = $items[$topRightIndex];
        $bottomLeftIndex = $factor;
        $bottomLeftItem = $items[$bottomLeftIndex];
        for ($i = $topRightIndex; $i > 0; $i -= 1) {
            $items[$i] = $items[$i - 1];
        }
        for ($i = $bottomLeftIndex; $i < $lastIndex; $i += 1) {
            $items[$i] = $items[$i + 1];
        }
        $items[1] = $bottomLeftItem;
        $items[$lastIndex] = $topRightItem;
    }
}
