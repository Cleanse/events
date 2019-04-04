<?php

namespace Cleanse\Event\Components;

use Redirect;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ValidateEvent;
use Cleanse\Event\Classes\ManageEvent;
use Cleanse\Event\Classes\Helpers\EventTypes;
use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Team;

class Edit extends ComponentBase
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

        $this->addJs('assets/js/events.js');

        $this->page['event_types']  = EventTypes::load();
        $this->page['config_event'] = $this->fixJsonEncode();
    }

    public function onEventUpdate()
    {
        $data = post();

        $rules = (new ValidateEvent())->validateEvent($data['event-type']);

        $validation = Validator::make($data, $rules['validation'], $rules['messages']);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        try {
            $event = (new ManageEvent())->generateEvent($data, 'update');

            return Redirect::to('/event/'.$event.'/edit');
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    public function onEventSchedule()
    {
        $event = Event::find(post('id'));
        $live = (new ManageEvent())->generateSchedule($event, true);
        return Redirect::to('/event/'.$live);
    }

    public function onEventDelete()
    {
        $data = post();

        try {
            (new ManageEvent())->deleteEvent($data);

            return Redirect::to('/events');
        } catch (Exception $exception) {
            throw new $exception;
        }
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

    public function onAddTeam()
    {
        $eId = $this->addTeam();

        return Redirect::to('/event/'.$eId.'/edit');
    }

    /**
     * Class only.
     */
    private function addTeam()
    {
        $eventId = post('eid') ?: post('event');
        $team = post('new-team') ?: post('available-teams');
        if (!isset($team) || !isset($eventId)) {
            return [];
        }

        Event::find($eventId)
            ->addTeam($team);

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
}
