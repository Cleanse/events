<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ManageLocale;
use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Event;

class OverlayGroups extends ComponentBase
{
    private $event;
    private $broadcast;
    private $group;

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
            ],
            'group' => [
                'title'       => 'Group ID',
                'description' => 'Group # to display.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->page['broadcast'] = $this->property('id');
        $this->group = $this->page['group_number'] = $this->property('group');

        $this->getGroups();

        $this->page['locale'] = $this->setLocale();

        $this->addCss('assets/css/overlay.css');
        $this->addJs('assets/js/overlay.js');
    }

    private function getGroups()
    {
        $id = $this->property('id');
        $this->broadcast = Broadcast::find($id);

        if (!$this->broadcast) {
            return [];
        }

        $this->event = Event::where(['id' => $this->broadcast->event_id])
            ->with(['matches' => function($q)
            {
                $q->where(['takes_place_during' => $this->group]);
            }])
            ->first();

        if (!$this->event) {
            return [];
        }

        $groupTeams = [];
        foreach ($this->event->matches as $match) {
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

        $this->page['event'] = $this->event;
        $this->page['teams'] = $eventTeams;
    }

    private function setLocale()
    {
        $language = $this->property('language');
        $languages = ['en', 'jp'];

        if (in_array($language, $languages)) {
            return $locale = (new ManageLocale)->getLocale($language);
        }

        return $locale = (new ManageLocale)->getLocale('en');
    }
}
