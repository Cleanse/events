<?php

namespace Cleanse\Event\Updates;

use Seeder;
use Cleanse\Event\Models\Type;

class SeedInitialEventTables extends Seeder
{
    public function run()
    {
        Type::create([
            'name'        => 'user@example.com',
            'description' => 'user',
        ]);
    }
}
