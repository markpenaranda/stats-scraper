<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Player extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('jersey_number');
            $table->string('position');
            $table->string('country');
            $table->string('url');
            $table->integer('team_id');

        });

        Schema::create('career_stats', function(Blueprint $table){
            $table->increments('id');
            $table->string('player_id');
            $table->json('total_stats');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
