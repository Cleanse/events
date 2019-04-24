<?php

namespace Cleanse\Event\Models;

use Model;

/**
 * Class Team
 * @package Cleanse\Event\Models
 * @property string name
 * @property string slug
 * @property string region
 * @property string description
 * @property string logo
 */
class Team extends Model
{
    use \October\Rain\Database\Traits\Sluggable;

    protected $table = 'cleanse_event_teams';

    protected $slugs = ['slug' => 'name'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'region'
    ];

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

    public function getLogoThumb($size = 48, $options = null)
    {
        if (is_string($options)) {
            $options = ['default' => $options];
        } elseif (!is_array($options)) {
            $options = [];
        }

        if ($this->logo) {
            return $this->logo->getThumb($size, $size, $options);
        }
    }
}
