<?php

namespace Cleanse\Event\Models;

use Model;

/**
 * Class Broadcast
 * @package Cleanse\Event\Models
 * @property integer id
 * @property integer event_id
 * @property string  name
 * @property string  description
 * @property string  url
 * @property string  scheduled_at
 */
class Broadcast extends Model
{
    protected $table = 'cleanse_event_broadcasts';

    protected $fillable = ['name', 'description', 'url', 'scheduled_at'];

    public $belongsTo = [
        'event' => 'Cleanse\Event\Models\Event'
    ];

    public $hasMany = [
        'matches' => 'Cleanse\Event\Models\Match'
    ];
}
