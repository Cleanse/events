<?php

namespace Cleanse\Event\Models;

use Model;

/**
 * Class Team
 * @package Cleanse\Event\Models
 * @property string name
 * @property string slug
 * @property string initials
 * @property string logo
 * @property string description
 */
class Team extends Model
{
    use \October\Rain\Database\Traits\Sluggable;

    protected $table = 'cleanse_event_teams';

    /**
     * @var array Generate slugs for this attribute.
     */
    protected $slugs = ['slug' => 'name'];

    protected $fillable = ['name', 'description', 'config'];

    protected $casts = [
        'config' => 'array',
    ];

    /**
     * Logo
     * @var array
     */
    public $attachOne = [
        'logo' => ['System\Models\File']
    ];

    public $belongsToMany = [
        'events' => [
            'Cleanse\Event\Models\Event',
            'table' => 'cleanse_event_event_team',
            'pivot' => ['seed']
        ]
    ];
}
