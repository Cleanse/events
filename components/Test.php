<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Classes\Generators\RoundRobin;

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
            'teams' => ['Niners'],
            'randomize' => true,
            'groups' => 1,
            'cycles' => 1
        ];

        $rr = new RoundRobin($config);
        $schedule = $rr->generate();

        return $rr->create($schedule);
    }
}
