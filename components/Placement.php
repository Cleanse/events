<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event;

class Placement extends ComponentBase
{
    public $event;

    public function componentDetails()
    {
        return [
            'name' => 'Event Team Placement',
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

        $this->page['teams'] = $this->event->teams()->orderBy('pivot_seed', 'asc')->get();
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return Event::find($id);
    }
}
