<?php

namespace Cleanse\Event\Classes\Helpers;

class EventTypes
{
    public static function load()
    {
        //Load from database?
        return [
            [
                'value'   => 'round-robin',
                'display' => 'Round Robin'
            ],
            [
                'value'   => 'single-elimination-bracket',
                'display' => 'Single Elimination Bracket'
            ],
            [
                'value'   => 'double-elimination-bracket',
                'display' => 'Double Elimination Bracket'
            ],
            [
                'value'   => 'swiss',
                'display' => 'Swiss'
            ]
        ];
    }
}
