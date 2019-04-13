<?php

namespace Cleanse\Event\Components;

use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;
use Cleanse\Event\Models\Match;

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

    public function onAddBroadcastMatch()
    {
        $post = post();

        $this->addBroadcastMatch($post);

        return Redirect::to('/event/broadcast/'.$post['broadcast']);
    }

    public function onRemoveBroadcastMatch()
    {
        $post = post();

        $this->removeBroadcastMatch($post);

        return Redirect::to('/event/broadcast/'.$post['broadcast']);
    }

    private function getBroadcastData()
    {
        $id = $this->property('broadcast');

        return Broadcast::find($id);
    }

    private function addBroadcastMatch($post)
    {
        $broadcast = Broadcast::find($post['broadcast']);

        $broadcast->addMatch($post['match'], $post['count']);
    }

    private function removeBroadcastMatch($post)
    {
        $broadcast = Broadcast::find($post['broadcast']);
        $match = Match::find($post['match']);

        $broadcast->matches()->remove($match);

        //check broadcast matches, re-order
        $this->reorderMatches($post['broadcast']);
    }

    private function reorderMatches($broadcast)
    {
        $b = Broadcast::whereId($broadcast)
            ->with(['matches' => function($q) {
                $q->orderBy('pivot_lineup', 'asc');
            }])
            ->first();

        $i = 1;
        foreach ($b->matches as $match) {
            $b->matches()->updateExistingPivot($match->id, ['lineup' => $i]);
            $i++;
        }
    }
}
