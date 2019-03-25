<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event;

class Events extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Events List',
            'description' => 'Displays a list of events.'
        ];
    }

    public function onRun()
    {
        $this->page['events'] = $this->getEvents();
    }

    public function getEvents()
    {
        return Event::orderBy('id', 'desc')
        ->get();
    }
}
