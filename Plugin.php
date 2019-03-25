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
            'Cleanse\Event\Components\Manage' => 'cleanseEventManageEvents',

            'Cleanse\Event\Components\Events'  => 'cleanseEventEvents',
            'Cleanse\Event\Components\Event' => 'cleanseEventViewEvent',
        ];
    }
}
