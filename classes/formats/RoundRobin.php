<?php

namespace Cleanse\Event\Classes\Formats;

class RoundRobin
{
    public function validation()
    {
        return [
            'validation' => [
                'number_of_groups' => 'required_if:event-type,round-robin',
            ],
            'messages' => [
                'number_of_groups.*' => 'Please enter the # of groups needed.',
            ]
        ];
    }
}
