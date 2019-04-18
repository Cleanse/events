<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event;

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
        $this->page['size'] = $this->getBracketSize(count($this->event->teams));
        $this->addCss('assets/css/overlay.css');
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

    private function getEventData()
    {
        $id = $this->property('event');

        return Event::find($id);
    }
}
