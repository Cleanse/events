<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;

class AdminBroadcast extends ComponentBase
{
    public $broadcast;

    public function componentDetails()
    {
        return [
            'name' => 'Edit Event Broadcast',
            'description' => 'Pick matches for your event broadcast.'
        ];
    }

    public function defineProperties()
    {
        return [
            'event' => [
                'title'       => 'Broadcast ID',
                'description' => 'Broadcast identification.',
                'default'     => '{{ :broadcast }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->broadcast = $this->page['broadcast'] = $this->getBroadcastData();

        if (!$this->broadcast) {
            return Redirect::to('/events/manage');
        }
    }

    private function getBroadcastData()
    {
        $id = $this->property('broadcast');

        return Broadcast::find($id);
    }
}
