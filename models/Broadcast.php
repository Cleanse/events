<?php

namespace Cleanse\Event\Models;

use Model;

/**
 * Class Broadcast
 * @package Cleanse\Event\Models
 * @property integer event_id
 * @property integer broadcast_id
 */
class Broadcast extends Model
{
    protected $table = 'cleanse_event_broadcasts';

    public $belongsTo = [
        'event' => 'Cleanse\Event\Models\Event'
    ];

    public $hasMany = [
        'matches' => 'Cleanse\Event\Models\Match'
    ];
}
