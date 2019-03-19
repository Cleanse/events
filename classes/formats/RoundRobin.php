<?php

namespace Cleanse\Event\Classes\Formats;

class RoundRobin
{
    public function rules()
    {
        return [
            'number_of_groups' => 'required_if:event-type,round-robin',
            'cycles' => 'required_if:event-type,round-robin'
        ];
    }

    //Move into generator class?
    public function config()
    {
        return [
            'number_of_groups' => ['default' => 1],
            'cycles' => ['default' => 1],
            'randomize' => ['default' => 0]
        ];
    }
}
