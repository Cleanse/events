<?php

namespace Cleanse\Event\Components;

use Redirect;
use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\Generators\MatchUpdater;
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

        $this->addCss('assets/css/events.css');
        $this->addJs('assets/js/events.js');
    }

    public function onUpdateBroadcastMatch()
    {
        $post = post();

        $updater = new MatchUpdater();
        $updater->updateMatch($post);

        return Redirect::to('/event/broadcast/' . $post['broadcast']);
    }

    public function onFinalizeMatch()
    {
        $post = post();

        $updater = new MatchUpdater();
        $updater->finalizeMatch($post);

        return Redirect::to('/event/broadcast/' . $post['broadcast']);
    }

    public function onUndoMatchResult()
    {
        $post = post();

        $updater = new MatchUpdater();
        $updater->undoMatchResult($post);

        return Redirect::to('/event/broadcast/' . $post['broadcast']);
    }

    public function onSetActiveMatch()
    {
        $post = post();
        $this->setActiveMatch($post);
        return Redirect::to('/event/broadcast/' . $post['broadcast']);
    }

    public function onRemoveBroadcastMatch()
    {
        $post = post();

        $this->removeBroadcastMatch($post);

        return Redirect::to('/event/broadcast/'.$post['broadcast']);
    }

    public function onDeleteBroadcast()
    {
        $broadcastId = post('id');
        $eventId = post('event');

        $this->deleteBroadcast($broadcastId);

        return Redirect::to('/event/'.$eventId.'/edit');
    }

    public function onAddBroadcastMatch()
    {
        $post = post();

        $this->addBroadcastMatch($post);

        return Redirect::to('/event/broadcast/'.$post['broadcast']);
    }

    public function onUpdateInformationBox()
    {
        $post = post();

        $this->updateInformationBox($post);

        return Redirect::to('/event/broadcast/'.$post['broadcast']);
    }

    //private
    private function getBroadcastData()
    {
        $id = $this->property('broadcast');

        return Broadcast::find($id);
    }

    private function setActiveMatch($post)
    {
        $broadcast = Broadcast::find($post['broadcast']);
        $broadcast->active_match = $post['match'];
        $broadcast->save();
    }

    private function deleteBroadcast($id)
    {
        $broadcast = Broadcast::find($id);

        if (!isset($broadcast)) {
            return;
        }

        $broadcast->delete();
    }

    private function addBroadcastMatch($post)
    {
        $broadcast = Broadcast::find($post['broadcast']);

        if (is_null($broadcast->active_match)) {
            $broadcast->active_match = $post['match'];
            $broadcast->save();
        }

        $broadcast->addMatch($post['match'], $post['count']);
    }

    private function removeBroadcastMatch($post)
    {
        $broadcast = Broadcast::find($post['broadcast']);
        $match = Match::find($post['match']);

        $broadcast->matches()->remove($match);

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

    private function updateInformationBox($post)
    {
        $broadcast = Broadcast::find($post['broadcast']);
        $broadcast->information = $post['info'];
        $broadcast->save();
    }
}
