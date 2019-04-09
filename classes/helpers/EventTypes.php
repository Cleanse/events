<?php

namespace Cleanse\Event\Classes\Helpers;

class EventTypes
{
    public static function load()
    {
        //Put in config file?
        return [
            [
                'value'   => 'round-robin',
                'display' => 'Round Robin'
            ],
            [
                'value'   => 'bracket-single',
                'display' => 'Bracket: Single Elimination'
            ],
            [
                'value'   => 'bracket-double',
                'display' => 'Bracket: Double Elimination'
            ],
            [
                'value'   => 'swiss',
                'display' => 'Swiss'
            ]
        ];
    }
}
