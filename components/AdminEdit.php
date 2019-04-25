<?php

namespace Cleanse\Event\Components;

use Redirect;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ValidateEvent;
use Cleanse\Event\Classes\ManageEvent;
use Cleanse\Event\Classes\Helpers\DateTimeHelper;
use Cleanse\Event\Classes\Helpers\EventTypes;
use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Team;
use Cleanse\Event\Models\Match;
use Cleanse\Event\Models\Broadcast;

class AdminEdit extends ComponentBase
{
    private $event;

    public function componentDetails()
    {
        return [
            'name' => 'Edit Event Form',
            'description' => 'Displays a form for event updating.'
        ];
    }

    public function defineProperties()
    {
        return [
            'event' => [
                'title'       => 'Event ID',
                'description' => 'Event identification.',
                'default'     => '{{ :event }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->event = $this->page['event'] = $this->getEventData();

        if (!$this->event) {
            return Redirect::to('/event/create');
        }

        $this->addCss('assets/css/events.css');
        $this->addJs('assets/js/events.js');

        $this->page['event_types']  = EventTypes::load();
        $this->page['config_event'] = $this->fixJsonEncode();
        $this->page['matches']      = $this->orderMatches();
    }

    //Event
    public function onEventUpdate()
    {
        $data = post();

        $rules = (new ValidateEvent())->validateEvent($data['event-type']);

        $validation = Validator::make($data, $rules['validation'], $rules['messages']);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        try {
            $event = $this->updateEvent($data);
            return Redirect::to('/event/'.$event.'/edit');
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    public function onEventDelete()
    {
        $data = post();

        try {
            $this->deleteEvent($data);
            return Redirect::to('/event/'.$data['id'].'/edit');
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    //Team
    public function onCreateTeam()
    {
        $eId = $this->createTeam();

        return Redirect::to('/event/'.$eId.'/edit');
    }

    public function onRemoveTeam()
    {
        $eventId = post('event');
        $teamId = post('team');

        $event = Event::find($eventId);
        $team = Team::find($teamId);

        $event->teams()->remove($team);

        return Redirect::to('/event/'.$eventId.'/edit');
    }

    public function onEventSchedule()
    {
        $event = Event::find(post('id'));
        $eventId = (new ManageEvent())->generateSchedule($event);

        return Redirect::to('/event/'.$eventId.'/edit');
    }

    public function onCreateBroadcast()
    {
        $broadcastId = $this->createBroadcast();

        return Redirect::to('/event/broadcast/'.$broadcastId);
    }

    public function onDeleteBroadcast()
    {
        $broadcastId = post('id');
        $eventId = post('event');

        $this->deleteBroadcast($broadcastId);

        return Redirect::to('/event/'.$eventId.'/edit');
    }

    public function onRequestMatchUpdate()
    {
        $this->page['match'] = Match::find(post('id'));
    }

    /**
     * Class only.
     */
    private function updateEvent($event)
    {
        $getEvent = Event::find($event['eid']);

        $getEvent->name = $event['event-title'];
        $getEvent->description = $event['event-description'];
        $getEvent->type = $event['event-type'];
        $getEvent->config = $event['event_config'];

        $getEvent->save();

        return $getEvent->id;
    }

    private function deleteEvent($event)
    {
        $getEvent = Event::find($event['id']);

        if ($getEvent->active) {
            $getEvent->active = false;
        } else {
            $getEvent->active = true;
        }

        $getEvent->save();
    }

    private function createTeam()
    {
        $eventId = post('eid') ?: post('event');
        $team = post('new-team') ?: post('available-teams');
        if (!isset($team) || !isset($eventId)) {
            return [];
        }

        $event = Event::find($eventId);

        if (count($event->matches) > 0) {
            dd('no');
            return $eventId;
        }

        $event->addTeam($team);

        return $eventId;
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return Event::find($id);
    }

    private function fixJsonEncode()
    {
        $json = [];
        foreach ($this->event->config as $key => $config) {
            $json[$key] = $config;
        }

        return json_encode($json);
    }

    private function orderMatches()
    {
        if ($this->event->type == 'round-robin') {
            return $this->event->matches->groupBy('takes_place_during');
        } else {
            return $this->event->matches->sortBy('order');
        }
    }

    private function createBroadcast()
    {
        $post = post();

        $day = $post['date'];
        $time = $post['time'];

        $eventDateTime = DateTimeHelper::editDateTimeFormat($day, $time);

        $broadcast = [
            'name'         => $post['name'],
            'description'  => $post['description'] ?: '',
            'url'          => $post['url'] ?: '',
            'scheduled_at' => $eventDateTime
        ];

        $event = Event::find($post['be_id']);

        if (!isset($event)) {
            return $post['be_id'];
        }

        $test = $event->broadcasts()->create($broadcast);

        return $test->id;
    }

    private function deleteBroadcast($id)
    {
        $broadcast = Broadcast::find($id);

        if (!isset($broadcast)) {
            return;
        }

        $broadcast->delete();
    }
}
