<?php

namespace Cleanse\Event\Classes\Helpers;

//Move to RR directory?
class RoundRobinHelper
{
    /**
     * Thanks https://gist.github.com/mcrumley/10396818
     */
    public static function array_partition(array $a, $np = 1, $pad = false)
    {
        $np = (int)$np;
        if ($np <= 0) {
            trigger_error('partition count must be greater than zero', E_USER_NOTICE);
            return array();
        }

        $c = count($a);
        $per_array = (int)floor($c / $np);
        $rem = $c % $np;

        if ($c === 0) {
            if ($pad) {
                $result = array_fill(0, $np, array());
            } else {
                $result = array();
            }
        } elseif ($rem === 0 || $rem == $np - 1 || $np >= $c) {
            $result = array_chunk($a, $per_array + ($rem > 0 ? 1 : 0));

            if ($pad && $np > $c) {
                $result = array_merge($result, array_fill(0, $np - $c, array()));
            }
        } else {
            $split = $rem * ($per_array + 1);
            $result = array_chunk(array_slice($a, 0, $split), $per_array + 1);
            $result = array_merge($result, array_chunk(array_slice($a, $split), $per_array));
        }

        return $result;
    }

    /**
     * Thanks to 'mnito'.
     */
    public static function rotate(array &$items)
    {
        $itemCount = count($items);
        if ($itemCount < 3) {
            return;
        }
        $lastIndex = $itemCount - 1;

        /**
         * Though not technically part of the round-robin algorithm, odd-even
         * factor differentiation included to have intuitive behavior for arrays
         * with an odd number of elements
         */
        $factor = (int)($itemCount % 2 === 0 ? $itemCount / 2 : ($itemCount / 2) + 1);
        $topRightIndex = $factor - 1;
        $topRightItem = $items[$topRightIndex];
        $bottomLeftIndex = $factor;
        $bottomLeftItem = $items[$bottomLeftIndex];
        for ($i = $topRightIndex; $i > 0; $i -= 1) {
            $items[$i] = $items[$i - 1];
        }
        for ($i = $bottomLeftIndex; $i < $lastIndex; $i += 1) {
            $items[$i] = $items[$i + 1];
        }
        $items[1] = $bottomLeftItem;
        $items[$lastIndex] = $topRightItem;
    }
}
