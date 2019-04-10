<?php

namespace Cleanse\Event\Classes\Generators;

use Cleanse\Event\Models\Event;

class MatchGenerator
{
    public function createMatch($id, $match, $order)
    {
        $event = Event::find($id);

        $event->addMatch($match[0], $match[1], $order);
    }
}
