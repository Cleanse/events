<?php

namespace Cleanse\Event\Updates;

use Seeder;
use Cleanse\Event\Models\Type;

class SeedInitialEventTables extends Seeder
{
    public function run()
    {
        Type::create([
            'name' => 'Round Robin',
            'slug' => 'round-robin',
            'description' => 'A round robin (group play) event.',
            'form_config' => []
        ]);

        Type::create([
            'name' => 'Single Elimination Bracket',
            'slug' => 'single-elimination-bracket',
            'description' => 'A single elimination bracket event.',
            'form_config' => []
        ]);

        Type::create([
            'name' => 'Double Elimination Bracket',
            'slug' => 'double-elimination-bracket',
            'description' => 'A double elimination bracket event.',
            'form_config' => []
        ]);

        Type::create([
            'name' => 'Swiss',
            'slug' => 'swiss',
            'description' => 'Competitors meet one-to-one in each round and are paired using a set of rules designed to 
                              ensure that each competitor plays opponents with a similar running score, but not the 
                              same opponent more than once.',
            'form_config' => []
        ]);
    }
}
