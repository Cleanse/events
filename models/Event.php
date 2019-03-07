<?php

namespace Cleanse\Event\Models;

use Model;

/**
 * Class Event
 * @package Cleanse\Event\Models
 * @property string name
 * @property string slug
 * @property string description
 * @property string type
 */
class Event extends Model
{
    protected $table = 'cleanse_event_events';
}
