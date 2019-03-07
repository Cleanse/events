<?php

namespace Cleanse\Event\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddEventsTables extends Migration
{
    public function up()
    {
        Schema::create('cleanse_event_configs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('event_id');
            $table->text('config_json')->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_event_types', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('cleanse_event_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('information')->nullable();
            $table->integer('event_type')->unsigned();
            $table->foreign('event_type')->references('id')->on('cleanse_event_event_types');
            $table->integer('config_id')->unsigned();
            $table->foreign('config_id')->references('id')->on('cleanse_event_configs');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cleanse_event_events');
        Schema::dropIfExists('cleanse_event_event_types');
        Schema::dropIfExists('cleanse_event_configs');
    }
}
