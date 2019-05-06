<?php

namespace Cleanse\Event\Classes\Generators;

use Cleanse\Event\Classes\UpdateEvent;
use Cleanse\Event\Models\Match;

class MatchUpdater
{
    public function updateMatch($post)
    {
        $match = Match::find($post['match']);
        $match->team_one_score = $post['one-score'];
        $match->team_two_score = $post['two-score'];
        $match->save();
    }

    public function finalizeMatch($post)
    {
        $match = Match::find($post['match']);
        (new UpdateEvent())->advanceMatch($match);
    }

    public function undoMatchResult($post)
    {
        $match = Match::find($post['match']);
        (new UpdateEvent())->undoMatch($match);
    }
}
