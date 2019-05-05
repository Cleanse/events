<?php

use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;
use Cleanse\Event\Models\Team;
use Cleanse\Event\Models\Broadcast;

Route::get('/api/broadcast/{broadcast}/match', function ($broadcastId)
{
    $broadcast = Broadcast::where(['id' => $broadcastId])
    ->first();

    if (isset($broadcast->active_match)) {
        $match = Match::whereId($broadcast->active_match)
            ->with(['one.logo', 'two.logo'])
            ->first();

        return Response::json($match);
    }

    return Response::json([]);
})->where(['broadcast' => '[0-9]+']);

Route::get('/api/broadcast/{broadcast}/bracket', function ($broadcastId)
{
    $broadcast = Broadcast::where(['id' => $broadcastId])
        ->first();

    if (isset($broadcast->active_match)) {
        $event = Event::whereId($broadcast->event_id)
            ->with(['winner', 'matches' => function($q)
            {
                $q->with(['one.logo', 'two.logo']);
            }])
            ->first();

        $placement = $event->teams()->orderBy('pivot_placement')->get();

        return Response::json([
            'event' => $event,
            'broadcast' => $broadcast,
            'placement' => $placement
        ]);
    }

    return Response::json([]);
})->where(['broadcast' => '[0-9]+']);

Route::get('/api/broadcast/{broadcast}/group', function ($broadcastId)
{
    $broadcast = Broadcast::where(['id' => $broadcastId])
        ->first();

    if (isset($broadcast->active_match)) {
        $event = Event::where(['id' => $broadcast->event_id])
            ->with(['teams', 'winner', 'matches' => function($q) use ($broadcast)
            {
                $q->where(['takes_place_during' => $broadcast->roundRobinGroup()->takes_place_during]);
                $q->with(['one.logo', 'two.logo']);
            }])
            ->first();

        $groupTeams = [];
        foreach ($event->matches as $match) {
            $groupTeams[] = $match->one->id;
            $groupTeams[] = $match->two->id;
        }
        $groupTeams = array_unique($groupTeams);

        $eventTeams = [];
        foreach ($event->teams()->orderByDesc('pivot_placement')->get() as $team) {
            if (in_array($team->id, $groupTeams)) {
                $eventTeams[] = $team;
            }
        }

        return Response::json([
            'event' => $event,
            'broadcast' => $broadcast,
            'placement' => $eventTeams,
            'group_number' => $event->matches[0]->takes_place_during
        ]);
    }

    return Response::json([]);
})->where(['broadcast' => '[0-9]+']);