<?php

namespace Cleanse\Event\Classes\Formats;

class DoubleElimination
{
    public function config()
    {
        return [
            'number_of_teams' => ['default' => 2],
            'randomize' => ['default' => 0],
            'grand_finals' => ['default' => 1],
            'hold_third_place_match' => ['default' => 0]
        ];
    }
}
