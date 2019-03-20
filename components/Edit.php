<?php

namespace Cleanse\Event\Components;

use ApplicationException;
use Exception;
use Flash;
use Redirect;
use ValidationException;
use Validator;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\Helpers\EventTypes;
use Cleanse\Event\Models\Event;

class Edit extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Edit Event Form',
            'description' => 'Displays a form for event updating.'
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
        $this->page['event_types'] = EventTypes::load(); //trigger js to load config ??
    }

    public function onEventUpdate()
    {
        return 'Ok.';
    }

    public function onAddItem()
    {
        $items = post('items', []);

        if (($newItem = post('newItem')) != '') {
            $items[] = $newItem;
        }

        $this->page['items'] = $items;
    }

    private function getEventData()
    {
        $id = $this->property('event');

        return Event::find($id);
    }
}
