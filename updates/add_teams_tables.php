<?php

namespace Cleanse\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddTeamsTables extends Migration
{
    public function up()
    {
        Schema::create('cleanse_event_teams', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('region')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_event_team', function($table)
        {
            $table->integer('event_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->integer('seed')->unsigned()->nullable();
            $table->integer('placement')->unsigned()->nullable();
            $table->primary(['event_id', 'team_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cleanse_event_event_team');
        Schema::dropIfExists('cleanse_event_teams');
    }
}
