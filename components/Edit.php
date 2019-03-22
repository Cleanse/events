<?php

namespace Cleanse\Event\Components;

use ApplicationException;
use Flash;
use Redirect;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

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

        $this->page['teams'] = $this->getTeamsList();
        $this->page['event_types'] = EventTypes::load();
    }

    public function onRemoveTeam()
    {
        $eventId = post('event');
        $teamId = post('team');

        $event = Event::find($eventId);
        $team = Team::find($teamId);

        $event->teams()->remove($team);

        $this->page['teams'] = $event->teams();
    }

    public function onAddTeam()
    {
        $this->addTeam();

        $event = Event::find(post('event'));

        $this->page['teams'] = $event->teams();
    }

    private function addTeam()
    {
        $event = Event::find(post('event'));
        $event->teams()->create([
            'name' => post('newTeam'),
        ]);
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return Event::find($id);
    }

    private function getTeamsList()
    {
        return Event::find(1)->teams;
    }
}
