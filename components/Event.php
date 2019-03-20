<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event as EventData;

class Event extends ComponentBase
{
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
        $this->addJs('assets/js/events.js');

        $this->page['event'] = $this->getEventData();
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return EventData::find($id);
    }
}
