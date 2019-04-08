<?php

namespace Cleanse\Event\Classes\Formats\RoundRobin;

class Validator
{
    public function rules()
    {
        return [
            'validation' => [
                'event_config.number_of_groups' => 'required_if:event-type,round-robin',
            ],
            'messages' => [
                'event_config.number_of_groups' => 'Please enter the # of groups needed.',
            ]
        ];
    }
}
