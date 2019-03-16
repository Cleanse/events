<?php

namespace Cleanse\Event\Classes\Formats;

class SingleElimination
{
    public function config()
    {
        return [
            'number_of_teams' => ['default' => 2],
            'randomize' => ['default' => 0],
            'hold_third_place_match' => ['default' => 0]
        ];
    }
}
