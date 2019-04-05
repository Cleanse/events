<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Match;

class OverlayMatch extends ComponentBase
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
        $this->event = $this->getEventData();

        if (!$this->event) {
            $this->page['event'] = [];
            return;
        }

        $this->page['event'] = $this->event;
        $this->addCss('assets/css/overlay.css');
    }

    private function getEventData()
    {
        $id = $this->property('event');

        //get current active match add to match? event? create broadcast pivot table for event?
        return true;
        return Match::find($id);
    }
}
