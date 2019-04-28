<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Match;

class OverlayMatch extends ComponentBase
{
    private $match;

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
            'id' => [
                'title'       => 'Broadcast ID',
                'description' => 'Event identification.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->match = $this->getEventData();

        $this->page['match'] = $this->match;
        $this->addCss('assets/css/overlay.css');
    }

    private function getEventData()
    {
        $id = $this->property('id');
        $broadcast = Broadcast::find($id);

        if (!isset($broadcast->active_match)) {
            return [];
        }

        $match = Match::where('id', '=', $broadcast->active_match)
            ->with(['one', 'two'])
            ->first();

        if (!$match) {
            return [];
        }

        return $match;
    }
}
