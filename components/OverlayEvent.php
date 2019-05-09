<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ManageLocale;
use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Event;

class OverlayEvent extends ComponentBase
{
    private $broadcast;
    private $event;

    public function componentDetails()
    {
        return [
            'name' => 'Overlay for Event Format',
            'description' => 'Displays event type overlay.'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'Broadcast ID',
                'description' => 'Broadcast identification.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ],
            'language' => [
                'title'       => 'Broadcast Language',
                'description' => 'Will use language selected by the url.',
                'default'     => '{{ :language }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->getEventData();

        $this->page['locale'] = $this->setLocale();
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

        if ($this->broadcast->event->type == 'round-robin') {
            $this->getGroupsFormatting();
        } else {
            $this->getBracketFormatting();
        }
    }

    private function getGroupsFormatting()
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

        $this->page['event'] = $this->event;
        $this->page['groups'] = $groupsTeams;
    }

    private function getBracketFormatting()
    {
        $this->event = Event::where('id', '=', $this->broadcast->event_id)
            ->with(['matches'])
            ->first();

        if (!$this->event) {
            return [];
        }

        $this->page['event'] = $this->event;
        $this->page['size'] = $this->getBracketSize(count($this->event->teams));
    }

    private function getBracketSize($size)
    {
        $bracketSize = 2;

        switch ($size) {
            case ($size <= 2):
                $bracketSize = 2;
                break;
            case ($size <= 4):
                $bracketSize = 4;
                break;
            case ($size <= 8):
                $bracketSize = 8;
                break;
            case ($size <= 16):
                $bracketSize = 16;
                break;
            case ($size <= 32):
                $bracketSize = 32;
                break;
        }

        return $bracketSize;
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
