<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Match;

class OverlayTeamNames extends ComponentBase
{
    private $match;

    public function componentDetails()
    {
        return [
            'name' => 'View Broadcast Groups',
            'description' => 'Displays general broadcast schedule.'
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
            ],
            'team' => [
                'title'       => 'Teams',
                'type'        => 'dropdown',
                'default'     => 'claws',
                'placeholder' => 'Select a team',
                'options'     => ['claws' => 'Claws', 'fangs' => 'Fangs']
            ]
        ];
    }

    public function onRun()
    {
        $this->match = $this->getEventData();

        $this->addCss('assets/css/overlay.css');
        $this->addJs('assets/js/overlay.js');

        $this->page['team'] = $this->property('team');
        $this->page['match'] = $this->match;
        $this->page['broadcast'] = $this->property('id');
    }

    private function getEventData()
    {
        $id = $this->property('id');
        $broadcast = Broadcast::find($id);

        if (!isset($broadcast->active_match)) {
            return [];
        }

        $match = Match::where('id', '=', $broadcast->active_match)
            ->with(['one.logo', 'two.logo'])
            ->first();

        if (!$match) {
            return [];
        }

        return $match;
    }
}
