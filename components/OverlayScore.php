<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

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
                'title'       => 'Match ID',
                'description' => 'Match identification.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->match = $this->getMatchData();

        if (!$this->match) {
            $this->page['match'] = [];
            return;
        }

        $this->addCss('assets/css/overlay.css');
        $this->addCss('assets/css/overlay-score.css');

        $this->addJs('assets/js/overlay.js');

        $this->page['match'] = $this->match;
    }

    private function getMatchData()
    {
        $id = $this->property('id');

        return Match::where('id', '=', $id)
            ->with(['one', 'two'])
            ->first();
    }
}
