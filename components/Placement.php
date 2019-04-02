<?php

namespace Cleanse\Event\Components;

use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event;
use Cleanse\Event\Models\Team;

class Placement extends ComponentBase
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
            return Redirect::to('/event/create');
        }

        $this->addJs('assets/js/events.js');

        $this->page['teams'] = $this->event->teams()->orderBy('pivot_seed', 'asc')->get();
    }

    public function onReorderPlacement()
    {
        $this->reorderPlacement();

        return Redirect::back();
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return Event::find($id);
    }

    private function reorderPlacement()
    {
        $placement = post('placement');
        $eventId = post('id');

        $event = Event::find($eventId);

        $i = 1;
        $test[] = [];
        foreach($placement as $key => $value) {
            //$test[] = [$key, $value, $i];
            $event->teams()->updateExistingPivot($key, ['seed' => $i]);

            $i++;
        }

        //return $test;
    }
}
