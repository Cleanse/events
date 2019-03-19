<?php

namespace Cleanse\Event\Classes\Formats;

class Swiss
{
    public function config()
    {
        return [
            'points_per_victory' => ['default' => 1.0],
            'points_per_match_tie' => ['default' => 0.5],
            'points_per_win' => ['default' => 0],
            'points_per_tie' => ['default' => 0],
            'points_per_bye' => ['default' => 1.0],
            'swiss_rounds'
        ];
    }
}
