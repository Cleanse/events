<?php

namespace Cleanse\Event;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Event Plugin',
            'description' => 'Create a round-robin or bracket event.',
            'author'      => 'Paul E. Lovato',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents()
    {
        return [
            'Cleanse\Event\Components\Create' => 'cleanseEventCreateEvent',
            'Cleanse\Event\Components\Edit'   => 'cleanseEventEditEvent',
        ];
    }
}
