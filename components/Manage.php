<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Event;

class Manage extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Manage Events List',
            'description' => 'Displays a list of events to manage by the admin.'
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
