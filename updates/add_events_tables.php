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
            $table->boolean('active')->default(false);
            $table->integer('winner_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_broadcasts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('event_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->integer('active_match')->unsigned()->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_matches', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('event_id')->unsigned()->nullable();
            $table->integer('team_one')->unsigned()->nullable();
            $table->integer('team_two')->unsigned()->nullable();
            $table->integer('team_one_score')->unsigned()->nullable();
            $table->integer('team_two_score')->unsigned()->nullable();
            $table->integer('winner_id')->unsigned()->nullable();
            $table->integer('takes_place_during')->unsigned()->nullable();
            $table->integer('order')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_broadcast_match', function($table)
        {
            $table->integer('broadcast_id')->unsigned();
            $table->integer('match_id')->unsigned();
            $table->integer('lineup')->unsigned()->nullable();
            $table->primary(['broadcast_id', 'match_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cleanse_event_broadcast_match');
        Schema::dropIfExists('cleanse_event_matches');
        Schema::dropIfExists('cleanse_event_broadcasts');
        Schema::dropIfExists('cleanse_event_events');
    }
}
