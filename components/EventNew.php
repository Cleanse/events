<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Type;

class EventNew extends ComponentBase
{
    public $event_types;

    public function componentDetails()
    {
        return [
            'name' => 'New Event Form',
            'description' => 'Displays a form for new event creation.'
        ];
    }

    public function onRun()
    {
        $this->addJs('assets/js/events.js');

        $this->event_types = $this->page['event_types'] = $this->loadEventTypes();
        $this->page['title'] = 'New Event';
    }

    private function loadEventTypes()
    {
        return Type::all();
    }

    public function onEventSave()
    {
        $this->page['result'] = input('event-title');
        return ['#myDiv' => $this->renderPartial('mypartial')];
    }
}
