<?php

namespace Cleanse\Event\Updates;

use Seeder;
use Cleanse\Event\Models\Team;

class SeedTeamsTable extends Seeder
{
    public function run()
    {
        $teams = [
            ['name' => 'Anranku'],
            ['name' => 'Chance of the Offensive Aegis Boon'],
            ['name' => 'Coffee Break'],
            ['name' => 'CrimeWolf'],
            ['name' => 'Dark Knight'],
            ['name' => 'DOKI!! HARAHARA_BEACH'],
            ['name' => 'EFTS'],
            ['name' => 'F.A.M.E'],
            ['name' => 'F.C.O.H.'],
            ['name' => 'Gangster Inn'],
            ['name' => 'HAKATA_RAMENS'],
            ['name' => 'Hand_of_Mercy'],
            ['name' => 'LaLaLand'],
            ['name' => 'Princess Princess'],
            ['name' => 'Ray-Ban'],
            ['name' => 'Sfidante'],
            ['name' => 'Super Keitaro Blade'],
            ['name' => 'Team-Odamari'],
            ['name' => 'Thanks Keal'],
            ['name' => 'Waffle Waffle'],
            ['name' => 'Z†Fanclub'],
            ['name' => 'ダブルゴッドブレード / Double God Blade'],
            ['name' => 'ソープスプ～ン！ｗ / Soapspoon'],

            ['name' => 'Trois Pourcents'],
            ['name' => 'Chaos Leftovers'],
            ['name' => 'A-Pork-Calypse'],
            ['name' => 'Who?'],

            ['name' => 'ACES !-_-!'],
            ['name' => 'bUrself'],
            ['name' => 'Tenho'],
            ['name' => 'Suboptimal'],
            ['name' => 'Insert Name'],
            ['name' => 'Lester and Friends'],
            ['name' => 'Team Catfish'],
            ['name' => 'We Carry Crit']
        ];

        foreach ($teams as $team) {
            Team::create([
                'name' => $team['name'],
                'description' => 'The team known as ' . $team['name']
            ]);
        }
    }
}
