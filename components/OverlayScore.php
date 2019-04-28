<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Match;

class OverlayScore extends ComponentBase
{
    private $match;

    public function componentDetails()
    {
        return [
            'name' => 'View Score Overlay',
            'description' => 'Web source for OBS overlay.'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'Broadcast ID',
                'description' => 'Broadcast identification.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->match = $this->getMatchData();

        $this->addCss('assets/css/overlay.css');
        $this->addCss('assets/css/overlay-score.css');

        $this->addJs('assets/js/overlay.js');

        $this->page['match'] = $this->match;
    }

    private function getMatchData()
    {
        $id = $this->property('id');
        $broadcast = Broadcast::find($id);

        $match = Match::where('id', '=', $broadcast->active_match)
            ->with(['one', 'two'])
            ->first();

        if (!$match) {
            return [];
        }

        return $match;
    }
}
