<?php

namespace Cleanse\Event\Classes\Helpers;

class BracketHelper
{
    public static function getBracketSize($size)
    {
        $bracketSize = 2;

        switch ($size) {
            case ($size <= 2):
                $bracketSize = 2;
                break;
            case ($size <= 4):
                $bracketSize = 4;
                break;
            case ($size <= 8):
                $bracketSize = 8;
                break;
            case ($size <= 16):
                $bracketSize = 16;
                break;
            case ($size <= 32):
                $bracketSize = 32;
                break;
        }

        return $bracketSize;
    }

    public static function getBracketRoundName($matchNumber, $size, $type = 'bracket-double')
    {
        if ($type === 'bracket-double') {
            return self::getDoubleFormat($matchNumber, $size);
        } else {
            return self::getSingleFormat($matchNumber, $size);
        }
    }

    private static function getDoubleFormat($matchNumber, $size)
    {
        if ($size === 4) {
            return self::getFormatDouble4($matchNumber);
        } elseif ($size === 8) {
            return self::getFormatDouble8($matchNumber);
        }

        return 'Bracket Match';
    }

    private static function getFormatDouble4($matchNumber)
    {
        switch ($matchNumber) {
            case ($matchNumber <= 2):
                return 'Upper Round 1';
                break;
            case ($matchNumber === 3):
                return 'Lower Round 1';
                break;
            case ($matchNumber === 4):
                return 'Semi-Finals';
                break;
            case ($matchNumber === 5):
                return 'Lower Round 2';
                break;
            case ($matchNumber === 6):
                return 'Upper Finals';
                break;
            case ($matchNumber === 7):
                return 'Grand Finals';
                break;
            default:
                return 'Round 1';
                break;
        }
    }

    private static function getFormatDouble8($matchNumber)
    {
        switch ($matchNumber) {
            case ($matchNumber <= 4):
                return 'Upper Round 1';
                break;
            case ($matchNumber <= 6):
                return 'Lower Round 1';
                break;
            case ($matchNumber <= 8):
                return 'Round 2';
                break;
            case ($matchNumber <= 10):
                return 'Lower Round 2';
                break;
            case ($matchNumber === 11):
                return 'Lower Round 3';
                break;
            case ($matchNumber === 12):
                return 'Semi-Finals';
                break;
            case ($matchNumber === 13):
                return 'Lower Finals';
                break;
            case ($matchNumber === 14):
                return 'Upper Finals';
                break;
            case ($matchNumber === 15):
                return 'Grand Finals';
                break;
            default:
                return 'Round 1';
                break;
        }
    }

    //Need to add support for third place toggle
    private static function getSingleFormat($matchNumber, $size)
    {
        if ($size === 4) {
            return self::getFormatSingle4($matchNumber);
        } elseif ($size === 8) {
            return self::getFormatSingle8($matchNumber);
        }

        return 'Bracket Match';
    }

    private static function getFormatSingle4($matchNumber)
    {
        switch ($matchNumber) {
            case ($matchNumber <= 2):
                return 'Semi-Finals';
                break;
            case ($matchNumber === 3):
                return 'Third-Place';
                break;
            case ($matchNumber === 4):
                return 'Grand Finals';
                break;
            default:
                return 'Bracket Round';
                break;
        }
    }

    private static function getFormatSingle8($matchNumber)
    {
        switch ($matchNumber) {
            case ($matchNumber <= 4):
                return 'Quarter-Finals';
                break;
            case ($matchNumber <= 6):
                return 'Semi-Finals';
                break;
            case ($matchNumber === 7):
                return 'Third-Place';
                break;
            case ($matchNumber === 8):
                return 'Grand Finals';
                break;
            default:
                return 'Bracket Round';
                break;
        }
    }
}
