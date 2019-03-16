<?php

namespace Cleanse\Event\Classes\Formats;

class RoundRobin
{
    public function config()
    {
        return [
            'number_of_teams' => ['default' => 2],
            'number_of_groups' => ['default' => 1],
            'cycles' => ['default' => 1],
            'randomize' => ['default' => 0]
        ];
    }
}
