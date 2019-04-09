<?php

namespace Cleanse\Event\Classes\Generators;

use Cleanse\Event\Models\Event;

class MatchGenerator
{
    public function createMatch($id, $teamOne, $teamTwo)
    {
        $event = Event::find($id);

        $event->addMatch($teamOne, $teamTwo);
    }
}
