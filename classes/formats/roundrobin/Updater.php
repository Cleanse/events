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
        $theValue = Event::whereId($this->match->event_id)->with([
            'teams' => function ($q) use ($team) {
                $q->whereId($team);
            }
        ])->first();

        $this->match->event->teams()->updateExistingPivot($team, ['points' => $theValue->teams[0]->pivot->points + 1]);

        $this->setEventWinner($this->match->event_id);
    }

    private function setEventWinner($eventId)
    {
        $check = Event::whereId($eventId)
            ->with([
                'matches' => function ($q) {
                    $q->whereNull('winner_id');
                }
            ])
            ->first();

        if (count($check->matches) > 0) {
            return;
        } else {
            $check->winner_id = $this->match->winner_id;
            $check->save();
        }
    }
}
