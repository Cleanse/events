<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\Types\RoundRobin;

class Test extends ComponentBase
{
    public $test;

    public function componentDetails()
    {
        return [
            'name' => 'Event Test',
            'description' => 'Displays a test of event methods.'
        ];
    }

    public function onRun()
    {
        $this->test = $this->page['schedule'] = $this->testRoundRobin();
    }

    private function testRoundRobin()
    {
        $config = [
            'teams' => ['Niners', 'Broncos', 'Raiders', 'Cowboys', 'Pats', 'Colts'],
            'randomize' => true,
            'groups' => 2,
            'cycles' => 1
        ];

        $rr = new RoundRobin($config);
        return $schedule = $rr->generate();
    }
}
