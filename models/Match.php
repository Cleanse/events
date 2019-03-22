<?php

namespace Cleanse\Event\Models;

use Model;

/**
 * Class Match
 * @package Cleanse\Event\Models
 * @property integer event_id
 * @property integer team_one
 * @property integer team_two
 * @property integer team_one_score
 * @property integer team_two_score
 * @property integer winner_id
 */
class Match extends Model
{
    protected $table = 'cleanse_event_matches';

    public $belongsTo = [
        'event' => 'Cleanse\Event\Models\Event'
    ];

    public $hasOne = [
        'one' => [
            'Cleanse\Event\Models\Team',
            'key' => 'id',
            'otherKey' => 'team_one'
        ],
        'two' => [
            'Cleanse\Event\Models\Team',
            'key' => 'id',
            'otherKey' => 'team_two'
        ],
        'winner' => [
            'Cleanse\Event\Models\Team',
            'key' => 'id',
            'otherKey' => 'winner_id'
        ]
    ];
}
