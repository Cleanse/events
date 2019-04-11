<?php

namespace Cleanse\Event\Components;

use Cleanse\Event\Classes\ManageEvent;
use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Team;

class AdminPlacement extends ComponentBase
{
    public $event;

    public function componentDetails()
    {
        return [
            'name'        => 'Event Team Placement',
            'description' => 'Seed/rank/place your teams into event matches.'
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
            Redirect::to('/event/create');
            return;
        }

        $this->addJs('assets/js/events.js');

        $this->page['teams'] = $this->event->teams()->orderBy('pivot_seed', 'asc')->get();
        $this->page['config_event'] = $this->fixJsonEncode();
    }

    public function onReorderPlacement()
    {
        $this->reorderPlacement();

        return Redirect::back();
    }

    private function getEventData()
    {
        $id = $this->property('event');

        $event = Event::find($id);

        return $event;
    }

    private function reorderPlacement()
    {
        $placement = post('placement');
        $eventId = post('id');

        $event = Event::find($eventId);

        $i = 1;
        foreach($placement as $key => $value) {
            $event->teams()->updateExistingPivot($key, ['seed' => $i]);

            $i++;
        }
    }

    private function placementPreview()
    {
        $event = Event::find(1);

        $preview = (new ManageEvent())->generateSchedule($event);
        return $preview;
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
