<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Event;

class OverlayEvent extends ComponentBase
{
    private $broadcast;
    private $event;

    public function componentDetails()
    {
        return [
            'name' => 'Overlay for Event Format',
            'description' => 'Displays event type overlay.'
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
        $this->getEventData();

        $this->page['broadcast'] = $this->property('id');

        $this->addCss('assets/css/overlay.css');
        $this->addJs('assets/js/overlay.js');
    }

    private function getBracketSize($size)
    {
        $bracketSize = 2;

        switch ($size) {
            case ($size <= 2):
                $bracketSize = 2;
                break;
            case ($size <= 4):
                $bracketSize = 4;
                break;
            case ($size <= 8):
                $bracketSize = 8;
                break;
            case ($size <= 16):
                $bracketSize = 16;
                break;
            case ($size <= 32):
                $bracketSize = 32;
                break;
        }

        return $bracketSize;
    }

    private function getEventData()
    {
        $this->setEventData();

//        $this->page['event'] = $this->event;
//        //todo: seeding
//        $this->page['seed_one'] = true;
//
//        if ($this->event->type == 'round-robin') {
//            //Get active_match group #
//            $this->page['groups'] = $this->event->matches->groupBy('takes_place_during')->toArray();
//        } else {
//            $this->page['size'] = $this->getBracketSize(count($this->event->teams));
//            dd($event);
//        }

        if (isset($this->event->type)) {
            if ($this->event->type == 'round-robin') {
                //do rr
            } else {
                //do bracket
            }
        }
    }

    private function setEventData()
    {
        $id = $this->property('id');
        $this->broadcast = Broadcast::find($id);

        if (!$this->broadcast) {
            return [];
        }

        $this->event = Event::where('id', '=', $this->broadcast->event_id)
            ->with(['matches'])
            ->first();

        if (!$this->event) {
            return [];
        }

        //temp
        $this->page['seed_one'] = true;
        $this->page['event'] = $this->event;
        $this->page['broadcast_model'] = $this->broadcast;
        $this->page['size'] = $this->getBracketSize(count($this->event->teams));

        return $this->event;
    }

    private function getRoundRobinData()
    {
        //get active_match
        $id = $this->property('id');
        $broadcast = Broadcast::find($id);

        //get active_match takes_place_during
        $rr = Broadcast::where('id', '=', $id)
            ->with(['event' => function($e)
            {
                $e->with(['matches' => function($q)
                {
                    $q->where('takes_place_during', '=', $this->broadcast->active_match->takes_place_during);
                }]);
            }])->first();

        //get matches with active_match's takes_place_during # matches

        //do we need to set up a point table, or can a query count for us?
    }
}
