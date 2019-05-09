<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Event;

class OverlayGroups extends ComponentBase
{
    private $event;
    private $broadcast;

    public function componentDetails()
    {
        return [
            'name' => 'View Broadcast Groups',
            'description' => 'Displays general broadcast schedule.'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'Broadcast ID',
                'description' => 'Event identification.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->match = $this->page['groups'] = $this->getEventData();
        $this->page['event_title'] = $this->event->name;
        $this->page['broadcast'] = $this->property('id');

        $this->addCss('assets/css/overlay.css');
        $this->addJs('assets/js/overlay.js');
    }

    private function getEventData()
    {
        $id = $this->property('id');
        $this->broadcast = Broadcast::find($id);

        if (!$this->broadcast) {
            return [];
        }

        $this->event = Event::where(['id' => $this->broadcast->event_id])
            ->with(['matches' => function($q)
            {
                $q->with(['one.logo', 'two.logo']);
            }])
            ->first();

        if (!$this->event) {
            return [];
        }

        $groups = $this->event->matches->groupBy('takes_place_during');

        $groupsTeams = [];
        foreach ($groups as $group) {
            $groupTeams = [];
            foreach ($group as $match) {
                $groupTeams[] = $match->one->id;
                $groupTeams[] = $match->two->id;
            }

            $groupTeams = array_unique($groupTeams);

            $eventTeams = [];
            foreach ($this->event->teams()->orderByDesc('pivot_points')->get() as $team) {
                if (in_array($team->id, $groupTeams)) {
                    $eventTeams[] = $team;
                }
            }

            $groupsTeams[] = $eventTeams;
        }

        return $groupsTeams;
    }
}
