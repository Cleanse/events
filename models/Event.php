<?php

namespace Cleanse\Event\Models;

use DB;
use Model;

use Cleanse\Event\Models\Team;
use Cleanse\Event\Models\Match;

/**
 * Class Event
 * @package Cleanse\Event\Models
 * @property integer id
 * @property string  name
 * @property string  slug
 * @property string  description
 * @property string  type
 * @property string  config
 * @property integer active
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Sluggable;

    protected $table = 'cleanse_event_events';

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    protected $jsonable = ['config'];

    protected $fillable = [
        'name', 'config'
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public $hasMany = [
        'matches'    => 'Cleanse\Event\Models\Match',
        'broadcasts' => 'Cleanse\Event\Models\Broadcast'
    ];

    public $belongsToMany = [
        'teams' => [
            'Cleanse\Event\Models\Team',
            'table' => 'cleanse_event_event_team',
            'pivot' => ['seed']
        ]
    ];

    public function addTeam($teamData)
    {
        $team = Team::firstOrCreate([
            'name' => $teamData
        ]);

        $teamKey = $team->getKey();
        if (!$this->getAttribute('teams')->contains($teamKey)) {
            $this->teams()->attach($teamKey);
        }

        return $this;
    }

    public function addMatch($teamOne, $teamTwo, $order = 1)
    {
        $match = [
            'team_one' => $teamOne,
            'team_two' => $teamTwo
        ];

        if ($this->type == 'round-robin') {
            $type = ['takes_place_during' => $order];
        } else if ($this->type == 'bracket-single' || $this->type === 'bracket-double') {
            $type = ['order' => $order];
        } else {
            $type = ['order' => $order];
        }

        $matchMerge = array_merge($match, $type);

        $this->matches()->create($matchMerge);

        return $this;
    }

    public function availableTeams()
    {
        return Team::whereDoesntHave('events', function ($query) {
            $query->whereId($this->id);
        })
            ->orderBy('name', 'asc')
            ->get();
    }
}
