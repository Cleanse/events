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
 * @property string config
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Sluggable;

    protected $table = 'cleanse_event_events';

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    protected $fillable = ['name'];

    public $hasMany = [
        'matches' => 'Cleanse\Event\Models\Match'
    ];

    public $belongsToMany = [
        'teams' => [
            'Cleanse\Event\Models\Team',
            'table' => 'cleanse_event_event_team'
        ]
    ];
}
