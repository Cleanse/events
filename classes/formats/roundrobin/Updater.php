<?php

namespace Cleanse\Event\Classes\Formats\RoundRobin;

use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;

class Updater
{
    private $match;

    public function advance($match)
    {
        $this->match = $match;
        $matchResult = $this->setMatchWinner();
        return $matchResult;
    }

    public function undo($match)
    {
        $this->match = $match;
        $this->match->winner_id = null;
        $this->match->save();
    }

    private function setMatchWinner()
    {
        if ($this->match->team_one_score > $this->match->team_two_score) {
            $this->match->winner_id = $this->match->team_one;
            $this->match->save();
        } elseif ($this->match->team_two_score > $this->match->team_one_score) {
            $this->match->winner_id = $this->match->team_two;
            $this->match->save();
        } else {
            return false;
        }

        $this->setMatchPoints();
    }

    private function setMatchPoints()
    {
        $teams = [
            $this->match->team_one,
            $this->match->team_two
        ];

        foreach ($teams as $team) {
            if ($team == $this->match->winner_id) {
                call_user_func([$this, 'awardWinner'], $team);
            }
        }
    }

    private function awardWinner($team)
    {
        $this->match->event->teams()->updateExistingPivot($team, ['points' => $team->pivot->points + 1]);

        $this->setEventWinner();
    }

    private function setEventWinner()
    {
        $check = Event::where([
            'id' => $this->match->event_id
        ])
            ->with([
                'matches' => function ($q) {
                    $q->whereNotNull('winner_id');
                }
            ])
            ->get();

        if (count($check->matches) > 0) {
            return;
        } else {
            $check->winner_id = $this->match->winner_id;
            $check->save();
        }
    }
}
