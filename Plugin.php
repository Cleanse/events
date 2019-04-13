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
            //Admin
            'Cleanse\Event\Components\AdminCreate'    => 'cleanseEventAdminCreate',
            'Cleanse\Event\Components\AdminEdit'      => 'cleanseEventAdminEdit',
            'Cleanse\Event\Components\AdminManage'    => 'cleanseEventAdminManage',
            'Cleanse\Event\Components\AdminTeams'     => 'cleanseEventAdminTeams',
            'Cleanse\Event\Components\AdminPlacement' => 'cleanseEventAdminPlacement',
            'Cleanse\Event\Components\AdminBroadcast' => 'cleanseEventAdminBroadcast',

            //Frontend
            'Cleanse\Event\Components\Events' => 'cleanseEventEvents',
            'Cleanse\Event\Components\Event'  => 'cleanseEventViewEvent',

            //Broadcast
            'Cleanse\Event\Components\OverlayMatch'   => 'cleanseEventOverlayMatch',
            'Cleanse\Event\Components\OverlayScore'   => 'cleanseEventOverlayScore',
            'Cleanse\Event\Components\OverlayBracket' => 'cleanseEventOverlayBracket'
        ];
    }
}
