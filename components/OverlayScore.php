<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Match;

class OverlayScore extends ComponentBase
{
    private $event;

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
            'slug' => [
                'title'       => 'Event Slug',
                'description' => 'Event identification.',
                'default'     => '{{ :slug }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        //$this->event = $this->getEventData();

//        if (!$this->event) {
//            $this->page['event'] = [];
//            return;
//        }

        $this->addCss('assets/css/overlay.css');
        $this->addCss('assets/css/overlay-score.css');

        $this->page['event'] = $this->event;
    }

    private function getEventData()
    {
        $slug = $this->property('slug');

        return Match::find($slug);
    }
}
