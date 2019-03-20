<?php

namespace Cleanse\Event\Classes\Generators;

class DoubleEliminationBracket
{
    public $teams;
    public $randomize;
    public $grandFinals;

    /**
     * http://www.gottfriedville.net/mathprob/misc-dblelim.html
     * http://www.denegames.ca/tournaments/index.html
     * https://www.slideshare.net/MontecriZz/single-and-double-elimination-tournament
     * Number of matches =（N-1）× 2 ＋ 1
     */
    public function __construct($config)
    {
        $this->teams = isset($config['teams']) ? $config['teams'] : [];
        $this->randomize = isset($config['randomize']) ? $config['randomize'] : false;
        $this->grandFinals = isset($config['grand_finals']) ? $config['grand_finals'] : false;
    }

    public function generate()
    {
        return $this->makeSchedule();
    }

    private function makeSchedule()
    {
        return [];
    }
}
