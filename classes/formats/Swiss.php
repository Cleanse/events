<?php

namespace Cleanse\Event\Classes\Formats;

class Swiss
{
    public function validation()
    {
        return [
            'validation' => [
                'points_per_victory' => 'required_if:event-type,swiss',
                'points_per_match_tie' => 'required_if:event-type,swiss',
                'points_per_win' => 'required_if:event-type,swiss',
                'points_per_tie' => 'required_if:event-type,swiss',
                'points_per_bye' => 'required_if:event-type,swiss'
            ],
            'messages' => [
                'points_per_victory.*'   => 'Enter a value for Points Per Match Win',
                'points_per_match_tie.*' => 'Enter a value for Points Per Match Tie',
                'points_per_win.*'       => 'Enter a value for Points Per Game Win',
                'points_per_tie.*'       => 'Enter a value for Points Per Game Tie',
                'points_per_bye.*'       => 'Enter a value for Points Per Round Bye'
            ]
        ];
    }
}
