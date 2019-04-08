<?php

namespace Cleanse\Event\Classes\Formats\Swiss;

class Validator
{
    public function rules()
    {
        return [
            'validation' => [
                'event_config.points_per_victory' => 'required_if:event-type,swiss',
                'event_config.points_per_match_tie' => 'required_if:event-type,swiss',
                'event_config.points_per_win' => 'required_if:event-type,swiss',
                'event_config.points_per_tie' => 'required_if:event-type,swiss',
                'event_config.points_per_bye' => 'required_if:event-type,swiss'
            ],
            'messages' => [
                'event_config.points_per_victory.*'   => 'Enter a value for Points Per Match Win',
                'event_config.points_per_match_tie.*' => 'Enter a value for Points Per Match Tie',
                'event_config.points_per_win.*'       => 'Enter a value for Points Per Game Win',
                'event_config.points_per_tie.*'       => 'Enter a value for Points Per Game Tie',
                'event_config.points_per_bye.*'       => 'Enter a value for Points Per Round Bye'
            ]
        ];
    }
}
