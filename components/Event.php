<?php

namespace Cleanse\Event\Components;

use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event as EventData;

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
            return Redirect::to('/events');
        }
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return EventData::find($id);
    }
}
