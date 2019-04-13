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
 * @property integer order
 */
class Match extends Model
{
    protected $table = 'cleanse_event_matches';

    protected $fillable = [
        'team_one',
        'team_two',
        'team_one_score',
        'team_two_score',
        'winner_id',
        'takes_place_during',
        'order'
    ];

    public $belongsTo = [
        'event'     => 'Cleanse\Event\Models\Event'
    ];

    public $belongsToMany = [
        'broadcasts' => [
            'Cleanse\Event\Models\Broadcast',
            'table' => 'cleanse_event_broadcast_match',
            'pivot' => ['lineup'],
            'order' => 'pivot_lineup asc'
        ]
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
