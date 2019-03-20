<?php

namespace Cleanse\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddEventsTables extends Migration
{
    public function up()
    {
        Schema::create('cleanse_event_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('type');
            $table->json('config')->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_matches', function($table)
        {
            $table->engine = 'InnoDB';
            $table->string('id')->unique()->index();
            $table->integer('event_id')->unsigned()->nullable();
            $table->integer('team_one')->unsigned()->nullable();
            $table->integer('team_two')->unsigned()->nullable();
            $table->integer('team_one_score')->unsigned()->nullable();
            $table->integer('team_two_score')->unsigned()->nullable();
            $table->integer('winner_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cleanse_event_matches');
        Schema::dropIfExists('cleanse_event_events');
    }
}
