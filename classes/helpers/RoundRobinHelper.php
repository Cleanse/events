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
     * Pool 1: Rank 1, 8, 9, 16, 17
     * Pool 2: Rank 2, 7, 10, 15
     * Pool 3: Rank 3, 6, 11, 14
     * Pool 4: Rank 4, 5, 12, 13
     */
    public static function seed_partition(array $teams, $groups = 1)
    {
        $teamCount = count($teams);

        $seeds = range(1, $teamCount);
        $seeds = array_chunk($seeds, $groups);

        $g = 1;
        $orderedList = [];
        foreach ($seeds as $chunk) {
            if ($g % 2 === 0) {
                $chunk = array_reverse($chunk);
            }

            foreach ($chunk as $item => $value) {
                $orderedList[] = $value;
            }

            $g++;
        }

        $divisions = [];
        $r = 1;
        foreach ($orderedList as $seed) {
            $divisions[$r][] = $teams[$seed];

            if ($r % $groups !== 0) {
                $r++;
            } else {
                $r = 1;
            }
        }

        return $divisions;
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
