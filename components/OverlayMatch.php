<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\ManageLocale;
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
            ],
            'language' => [
                'title'       => 'Broadcast Language',
                'description' => 'Will use language selected by the url.',
                'default'     => '{{ :language }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->match = $this->getEventData();

        $this->addCss('assets/css/overlay.css');
        $this->addJs('assets/js/overlay.js');

        $this->page['locale'] = $this->setLocale();
        $this->page['broadcast'] = $this->property('id');
        $this->page['match'] = $this->match;
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

    private function setLocale()
    {
        $language = $this->property('language');
        $languages = ['en', 'jp'];

        if (in_array($language, $languages)) {
            return $locale = (new ManageLocale)->getLocale($language);
        }

        return $locale = (new ManageLocale)->getLocale('en');
    }
}
