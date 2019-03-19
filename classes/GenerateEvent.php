<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class GenerateEvent
{
    public $data;

    public function generateEvent($data, $namespace)
    {
        //Generate event format based on event-type & event-type-settings
        $this->data = ((new FactoryHelper)->getInstance($namespace, $data['event-type']))->create(); //not rules

        //todo: stuff prepping db insert

        return Redirect::to('/event/:slug');
    }
}
