<?php

namespace Cleanse\Event\Components;

use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Team as TeamModel;

class Team extends ComponentBase
{
    private $team;

    public function componentDetails()
    {
        return [
            'name' => 'View Event Team',
            'description' => 'Displays event team to public.'
        ];
    }

    public function defineProperties()
    {
        return [
            'team' => [
                'title'       => 'Team Slug',
                'description' => 'Team identification.',
                'default'     => '{{ :team }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->team = $this->page['team'] = $this->getTeamData();

        if (!$this->team) {
            return Redirect::to('/events');
        }
    }

    private function getTeamData()
    {
        $slug = $this->property('team');

        $team = TeamModel::where('slug', '=', $slug)->first();

        return $team;
    }

    private function orderMatches()
    {
        if ($this->event->type == 'round-robin') {
            return $this->event->matches->groupBy('takes_place_during');
        } else {
            return $this->event->matches->groupBy('order');
        }
    }
}
