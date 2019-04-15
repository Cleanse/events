<?php

namespace Cleanse\Event\Components;

use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event as EventModel;

class Event extends ComponentBase
{
    private $event;

    public function componentDetails()
    {
        return [
            'name' => 'View Event',
            'description' => 'Displays event to public.'
        ];
    }

    public function defineProperties()
    {
        return [
            'event' => [
                'title'       => 'Event Slug',
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
            return Redirect::to('/events');
        }

        $this->page['matches'] = $this->orderMatches();
    }

    private function getEventData()
    {
        $slug = $this->property('event');

        $event = EventModel::where('slug', '=', $slug)->first();

        return $event;
    }

    private function orderMatches()
    {
        if ($this->event->type == 'round-robin') {
            return $this->event->matches->groupBy('takes_place_during');
        } else {
            return $this->event->matches->groupBy('order');
        }
    }
}
