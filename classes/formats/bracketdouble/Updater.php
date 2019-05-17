<?php

namespace Cleanse\Event\Classes\Formats\BracketDouble;

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

        $this->getBracketSize();
    }

    private function getBracketSize()
    {
        $size = count($this->match->event->teams);

        switch ($size) {
            case ($size <= 4):
                $bracketSize = 4;
                break;
            case ($size <= 8):
                $bracketSize = 8;
                break;
            default:
                $bracketSize = 4;
                break;
        }

        $teams = [
            $this->match->team_one,
            $this->match->team_two
        ];

        foreach ($teams as $team) {
            if ($team == $this->match->winner_id) {
                call_user_func([$this, 'advanceWinner' . $bracketSize], $team);
            } else {
                call_user_func([$this, 'advanceDefeated' . $bracketSize], $team);
            }
        }
    }

    private function advanceWinner4()
    {
        switch ($this->match->order) {
            case 1:
                $this->updateMatch(4, 1, $this->match->winner_id);
                break;
            case 2:
                $this->updateMatch(4, 2, $this->match->winner_id);
                break;
            case 3:
                $this->updateMatch(5, 2, $this->match->winner_id);
                break;
            case 4:
                $this->updateMatch(6, 1, $this->match->winner_id);
                break;
            case 5:
                $this->updateMatch(6, 2, $this->match->winner_id);
                break;
            case 6:
                if ($this->match->event->config['grand_finals'] == 2) {
                    if ($this->match->winner_id === $this->match->team_one) {
                        $this->updateFinalPlacement($this->match->winner_id, 1);
                        $this->setEventWinner();
                    } else {
                        $this->updateMatch(7, 1, $this->match->winner_id);
                    }
                } else {
                    $this->updateFinalPlacement($this->match->winner_id, 1);
                    $this->setEventWinner();
                }
                break;
            case 7:
                $this->updateFinalPlacement($this->match->winner_id, 1);
                $this->setEventWinner();
                break;
            default:
                break;
        }
    }

    private function advanceWinner8()
    {
        switch ($this->match->order) {
            case 1:
                $this->updateMatch(7, 1, $this->match->winner_id);
                break;
            case 2:
                $this->updateMatch(7, 2, $this->match->winner_id);
                break;
            case 3:
                $this->updateMatch(8, 1, $this->match->winner_id);
                break;
            case 4:
                $this->updateMatch(8, 2, $this->match->winner_id);
                break;
            case 5:
                $this->updateMatch(10, 2, $this->match->winner_id);
                break;
            case 6:
                $this->updateMatch(9, 2, $this->match->winner_id);
                break;
            case 7:
                $this->updateMatch(12, 1, $this->match->winner_id);
                break;
            case 8:
                $this->updateMatch(12, 2, $this->match->winner_id);
                break;
            case 9:
                $this->updateMatch(11, 2, $this->match->winner_id);
                break;
            case 10:
                $this->updateMatch(11, 1, $this->match->winner_id);
                break;
            case 11:
                $this->updateMatch(13, 2, $this->match->winner_id);
                break;
            case 12:
                $this->updateMatch(14, 1, $this->match->winner_id);
                break;
            case 13:
                $this->updateMatch(14, 2, $this->match->winner_id);
                break;
            case 14:
                if ($this->match->event->config['grand_finals'] == 2) {
                    if ($this->match->winner_id === $this->match->team_one) {
                        $this->updateFinalPlacement($this->match->winner_id, 1);
                        $this->setEventWinner();
                    } else {
                        $this->updateMatch(15, 1, $this->match->winner_id);
                    }
                } else {
                    $this->updateFinalPlacement($this->match->winner_id, 1);
                    $this->setEventWinner();
                }
                break;
            case 15:
                $this->updateFinalPlacement($this->match->winner_id, 1);
                $this->setEventWinner();
                break;
            default:
                break;
        }
    }

    private function advanceDefeated4($team)
    {
        switch ($this->match->order) {
            case 1:
                $this->updateMatch(3, 1, $team);
                break;
            case 2:
                $this->updateMatch(3, 2, $team);
                break;
            case 3:
                $this->updateFinalPlacement($team, 4);
                break;
            case 4:
                $this->updateMatch(5, 1, $team);
                break;
            case 5:
                $this->updateFinalPlacement($team, 3);
                break;
            case 6:
                if ($this->match->event->config['grand_finals'] == 2) {
                    if ($this->match->winner_id === $this->match->team_one) {
                        $this->updateFinalPlacement($team, 2);
                    } else {
                        $this->updateMatch(7, 2, $team);
                    }
                } else {
                    $this->updateFinalPlacement($team, 2);
                }
                break;
            case 7:
                $this->updateFinalPlacement($team, 2);
                break;
            default:
                break;
        }
    }

    private function advanceDefeated8($team)
    {
        switch ($this->match->order) {
            case 1:
                $this->updateMatch(5, 1, $team);
                break;
            case 2:
                $this->updateMatch(5, 2, $team);
                break;
            case 3:
                $this->updateMatch(6, 1, $team);
                break;
            case 4:
                $this->updateMatch(6, 2, $team);
                break;
            case 5:
                $this->updateFinalPlacement($team, 8);
                break;
            case 6:
                $this->updateFinalPlacement($team, 8);
                break;
            case 7:
                $this->updateMatch(9, 1, $team);
                break;
            case 8:
                $this->updateMatch(10, 1, $team);
                break;
            case 9:
                $this->updateFinalPlacement($team, 6);
                break;
            case 10:
                $this->updateFinalPlacement($team, 6);
                break;
            case 11:
                $this->updateFinalPlacement($team, 4);
                break;
            case 12:
                $this->updateMatch(13, 1, $team);
                break;
            case 13:
                $this->updateFinalPlacement($team, 3);
                break;
            case 14:
                if ($this->match->event->config['grand_finals'] == 2) {
                    if ($this->match->winner_id === $this->match->team_one) {
                        $this->updateFinalPlacement($team, 2);
                    } else {
                        $this->updateMatch(15, 2, $team);
                    }
                } else {
                    $this->updateFinalPlacement($team, 2);
                }
                break;
            case 15:
                $this->updateFinalPlacement($team, 2);
                break;
            default:
                break;
        }
    }

    private function updateMatch($order, $team, $teamId)
    {
        $getMatch = Match::where([
            'order' => $order,
            'event_id' => $this->match->event_id
        ])->first();

        if ($team == 1) {
            $getMatch->team_one = $teamId;
        } elseif ($team == 2) {
            $getMatch->team_two = $teamId;
        }

        $getMatch->save();
    }

    private function updateFinalPlacement($team, $placement)
    {
        $this->match->event->teams()->updateExistingPivot($team, ['placement' => $placement]);
    }

    private function setEventWinner()
    {
        $event = Event::find($this->match->event_id);
        $event->winner_id = $this->match->winner_id;
        $event->save();
    }
}
