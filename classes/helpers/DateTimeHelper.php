<?php

namespace Cleanse\Event\Classes\Helpers;

use DateTime;

class DateTimeHelper
{
    public static function editDateTimeFormat($day, $time)
    {
        if (isset($day) && isset($time)) {
            $dt = $day . " " . $time; // just put a space between them
            $dt = new DateTime( $dt ); // convert from string to PHP DateTime

            return $dt->format( "Y-n-j G:i:s" ); // convert from DateTime to MySQL format!
        }

        return null;
    }
}
