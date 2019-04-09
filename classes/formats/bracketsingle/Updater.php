<?php

namespace Cleanse\Event\Classes\Formats\BracketSingle;

use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;

class Updater
{
    public function __construct($config)
    {
    }

    public function update()
    {
    }
}

class StageFormat
{
    private $match;

    public function __construct($matchId)
    {
        $this->match = Match::find($matchId);
    }

    public function getNextRound()
    {
        $teams = [
            $this->match->claws_team_id,
            $this->match->fangs_team_id
        ];

        foreach ($teams as $team) {
            if ($team == $this->match->winner_id) {
                $this->advanceWinner($team);
            }
        }

        return true;
    }

    private function advanceWinner($team)
    {
        switch ($this->match->order) {
            case 1:
                //Winner goes to Match 4 and 5 as Claws.
                $this->updateMatch($team, 4, 'claws');
                $this->updateMatch($team, 5, 'claws');
                $this->qualifyTeam($team);
                break;
            case 2:
                //Winner goes to Match 4 as Fangs and 6 as Claws.
                $this->updateMatch($team, 4, 'fangs');
                $this->updateMatch($team, 6, 'claws');
                $this->qualifyTeam($team);
                break;
            case 3:
                //Winner goes to Matches 5 and 6 as Fangs.
                $this->updateMatch($team, 5, 'fangs');
                $this->updateMatch($team, 6, 'fangs');
                $this->qualifyTeam($team);
                break;
            case 4:
                //Winner gains a point.
                $this->scorePoint($team);
                break;
            case 5:
                //Winner gains a point.
                $this->scorePoint($team);
                break;
            case 6:
                //Winner gains a point and we generate Finals.
                $this->scorePoint($team);
                $this->generateGrandFinals();
                break;
            case 7:
                //Winners flagged as the Champions.
                break;
        }
    }

    private function updateMatch($teamId, $match, $team)
    {
        $getMatch = Match::where('order', '=', $match)
            ->orderBy('event_id', 'desc')
            ->first();

        if ($team == 'claws') {
            $getMatch->claws_team_id = $teamId;
        } elseif ($team == 'fangs') {
            $getMatch->fangs_team_id = $teamId;
        }

        $getMatch->save();
    }

    private function qualifyTeam($team)
    {
        $team = Team::find($team);
        $team->qualified = 1;
        $team->save();
    }

    private function scorePoint($team)
    {
        $team = Team::find($team);
        $team->points = $team->points + 1;
        $team->save();
    }

    private function generateGrandFinals()
    {
        $teams = Team::where('qualified', '=', 1)
            ->orderBy('points', 'desc')
            ->take(2)
            ->get();

        $this->updateMatch($teams[0]->id, 7, 'claws');
        $this->updateMatch($teams[1]->id, 7, 'fangs');
    }
}

class BracketFormat
{
    private $match;

    public function __construct($matchId)
    {
        $this->match = Match::find($matchId);
    }

    public function getNextRound()
    {
        $teams = [
            $this->match->team_one,
            $this->match->team_two
        ];

        foreach ($teams as $team) {
            if ($team == $this->match->winner_id) {
                $this->advanceWinner($team);
            } else {
                $this->updateLosingTeam($team);
            }
        }

        return true;
    }

    private function advanceWinner($team)
    {
        switch ($this->match->takes_place_during) {
//            case '01':
//                //Wildcard - Do nothing
//                break;
//            case '02':
//                //Wildcard - Create match ups for both Rounds 3 & 4
//                $this->wildcardSetup();
//                break;
            case '01':
                $this->updateMatch($team, '03', 'fangs');
                echo "Winner goes to Match 3 as Fangs";
                break;
            case '02':
                $this->updateMatch($team, '04', 'fangs');
                echo "Winner goes to Match 4 as Fangs";
                break;
            case '03':
                $this->updateMatch($team, '08', 'claws');
                echo "Winner goes to Match 8 as Claws";
                break;
            case '04':
                $this->updateMatch($team, '08', 'fangs');
                echo "Winner goes to Match 8 as Fangs";
                break;
            case '05':
                $this->updateMatch($team, '07', 'fangs');
                echo "Winner goes to Match 7 as Fangs";
                break;
            case '06':
                $this->updateMatch($team, '07', 'claws');
                echo "Winner goes to Match 7 as Claws";
                break;
            case '07':
                $this->updateMatch($team, '09', 'fangs');
                echo "Winner goes to Match 9 as Fangs";
                break;
            case '08':
                $this->updateMatch($team, '10', 'claws');
                echo "Winner goes to Match 10 as Claws";
                break;
            case '09':
                $this->updateMatch($team, '10', 'fangs');
                echo "Winner goes to Match 10 as Fangs";
                break;
            case '10':
                //Set winner_id into tourney
                echo "Winner goes to Event Winner";
                break;
            default:
                break;
        }
    }

    private function updateLosingTeam($team)
    {
        switch ($this->match->takes_place_during) {
//            case '01':
//                //Wildcard - Do nothing Elimination
//                break;
//            case '02':
//                //Wildcard - Do nothing Elimination
//                break;
            case '01':
                $this->updateMatch($team, '06', 'fangs');
                echo "Loser goes to Match 6 Fangs";
                break;
            case '02':
                $this->updateMatch($team, '05', 'fangs');
                echo "Loser goes to Match 5 Fangs";
                break;
            case '03':
                $this->updateMatch($team, '06', 'claws');
                echo "Loser goes to Match 6 Claws";
                break;
            case '04':
                $this->updateMatch($team, '05', 'claws');
                echo "Loser goes to Match 5 Claws";
                break;
            case '05':
                echo "Elimination";
                break;
            case '06':
                echo "Elimination";
                break;
            case '07':
                echo "Elimination";
                break;
            case '08':
                $this->updateMatch($team, '09', 'claws');
                echo "Winner goes to Match 9 Claws";
                break;
            case '09':
                echo "Elimination";
                break;
            case '10':
                //Set winner_id into tourney
                echo "Elimination";
                break;
            default:
                break;
        }
    }

    /**
     * Done.
     */
    private function wildCardSetup()
    {
        $wildCard = Match::where([
            ['takes_place_during', '<=', 2],
            ['matchable_type', '=', 'tourney']
        ])
            ->with('winner')
            ->get();

        $wcWinners = [];
        foreach ($wildCard as $wc) {
            $wcWinners[] = [
                'team_id' => $wc->winner_id,
                'seed' => $wc->winner->tourney_seed
            ];
        }

        $collection = new Collection($wcWinners);
        $wcWin = $collection->sortBy('seed');

        $i = 2;
        foreach ($wcWin as $w) {
            $this->updateOpeningRounds($w['team_id'], $i);
            $i++;
        }
    }

    /**
     * Done
     * @param $winner_id
     * @param $order
     */
    private function updateOpeningRounds($winner_id, $order)
    {
        if ($order == 2) {
            $matchThree = Match::where('takes_place_during', '=', '03')
                ->orderBy('id', 'desc')
                ->first();

            $matchThree->team_two = $winner_id;
            $matchThree->save();
        }


        if ($order == 3) {
            $matchFour = Match::where('takes_place_during', '=', '04')
                ->orderBy('id', 'desc')
                ->first();

            $matchFour->team_two = $winner_id;
            $matchFour->save();
        }
    }

    /**
     * @param $teamId
     * @param $match
     * @param $team
     */
    private function updateMatch($teamId, $match, $team)
    {
        $getMatch = Match::where('takes_place_during', '=', $match)
            ->orderBy('matchable_id', 'desc')
            ->first();

        if ($team == 'claws') {
            $getMatch->team_one = $teamId;
        } elseif ($team == 'fangs') {
            $getMatch->team_two = $teamId;
        }

        $getMatch->save();
    }
}
