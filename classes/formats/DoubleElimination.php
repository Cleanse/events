<?php

namespace Cleanse\Event\Classes\Formats;

class DoubleElimination
{
    public function config()
    {
        return [
            'randomize' => ['default' => 0],
            'grand_finals' => ['default' => 1],
            'hold_third_place_match' => ['default' => 0]
        ];
    }
}
