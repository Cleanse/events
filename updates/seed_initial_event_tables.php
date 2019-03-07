<?php

namespace Cleanse\Event\Updates;

use Seeder;
use Cleanse\Event\Models\Type;

class SeedInitialEventTables extends Seeder
{
    public function run()
    {
        Type::create([
            'name'        => 'Round Robin',
            'slug'        => 'round-robin',
            'description' => 'A round robin (group play) event.',
        ]);

        Type::create([
            'name'        => 'Single Elimination Bracket',
            'slug'        => 'single-elimination-bracket',
            'description' => 'A single elimination bracket event.',
        ]);

        Type::create([
            'name'        => 'Double Elimination Bracket',
            'slug'        => 'double-elimination-bracket',
            'description' => 'A double elimination bracket event.',
        ]);

        Type::create([
            'name'        => 'Swiss',
            'slug'        => 'swiss',
            'description' => 'A round robin (group play) event.',
        ]);
    }
}
