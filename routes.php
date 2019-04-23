<?php

use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Match;
use Cleanse\Event\Models\Team;
use Cleanse\Event\Models\Broadcast;

Route::get('/api/broadcast/{broadcast}/score', function ($broadcastId) {
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

//Route::get('/api/broadcast/schedule', function () {
//    $event = Event::whereNull('completed')
//        ->orWhere('completed', 0)
//        ->with(['matches' => function ($q) {
//            $q->select('event_id', 'match_id', 'claws_score', 'fangs_score');
//            $q->orderBy('order', 'asc');
//        }])
//        ->orderBy('id', 'asc')
//        ->first();
//
//    return Response::json($event);
//});
//
//Route::get('/api/broadcast/score  ', function () {
//    $event = Event::whereNull('completed')
//        ->orWhere('completed', 0)
//        ->orderBy('id', 'asc')
//        ->first();
//
//    if (isset($event->active_match)) {
//        $match = Match::whereId($event->active_match)
//            ->with(['claws.team.logo', 'fangs.team.logo'])
//            ->first();
//
//        return Response::json($match);
//    }
//
//    return Response::json([]);
//});
//
//Route::get('/api/broadcast/tourney/score', function () {
//    $match = EventMatch::with(['one.team.logo', 'two.team.logo'])
//        ->whereNull('winner_id')
//        ->where([
//            'matchable_type' => 'tourney'
//        ])
//        ->orderBy('takes_place_during', 'asc')
//        ->first();
//
//    $casters = BroadcastCasters::find(1);
//
//    return Response::json(['match' => $match, 'casters' => $casters->names]);
//});
//
//Route::get('/api/broadcast/rising/stages', function () {
//    $event = Event::whereNull('completed')
//        ->orWhere('completed', 0)
//        ->with(['matches' => function ($q) {
//            $q->orderBy('order', 'asc');
//            $q->with(['claws', 'fangs']);
//        }])
//        ->orderBy('id', 'asc')
//        ->first();
//
//    $points = Team::where('qualified', '=', 1)
//        ->orderBy('points', 'desc')
//        ->take(3)
//        ->get();
//
//    $activeMatch = $event->matches->where('id', '=', $event->active_match)->first();
//
//    return Response::json([
//        'event' => $event,
//        'points' => $points,
//        'highlight' => $activeMatch
//    ]);
//});
//
//Route::get('/api/broadcast/rising/scoreboard', function () {
//    $id = Event::orderBy('id', 'desc')
//        ->first()->active_match;
//
//    $match = Match::with(['claws', 'fangs'])
//        ->where([
//            'id' => $id
//        ])
//        ->first();
//
//    $casters = BroadcastCasters::find(1);
//
//    return Response::json(['match' => $match, 'casters' => $casters->names]);
//});