<?php

namespace Cleanse\Event\Models;

use Model;

use Cleanse\Event\Models\Team;
use Cleanse\Event\Models\Match;

/**
 * Class Broadcast
 * @package Cleanse\Event\Models
 * @property integer id
 * @property integer event_id
 * @property string  name
 * @property string  information
 * @property string  url
 * @property integer active_match
 * @property string  scheduled_at
 */
class Broadcast extends Model
{
    protected $table = 'cleanse_event_broadcasts';

    protected $fillable = [
        'name',
        'information',
        'url',
        'scheduled_at'
    ];

    public $belongsTo = [
        'event' => 'Cleanse\Event\Models\Event'
    ];

    public $belongsToMany = [
        'matches' => [
            'Cleanse\Event\Models\Match',
            'table' => 'cleanse_event_broadcast_match',
            'pivot' => ['lineup'],
            'order' => 'pivot_lineup asc'
        ]
    ];

    public function addMatch($matchData, $order = 1)
    {
        $matchKey = $matchData;
        if (!$this->getAttribute('matches')->contains($matchKey)) {
            $this->matches()->attach($matchKey, ['lineup' => $order]);
        }

        return $this;
    }

    public function broadcastableMatches()
    {
        return Match::where('event_id', '=', $this->event_id)
            ->whereDoesntHave('broadcasts')
            ->orderBy('order', 'asc')
            ->get();
    }

    public function roundRobinGroup()
    {
        return Match::where('id', '=', $this->active_match)->first();
    }
}
