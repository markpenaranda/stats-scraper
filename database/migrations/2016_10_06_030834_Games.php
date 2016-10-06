<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Games extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('schedule');
            $table->string('match_url');
            $table->timestamps();

        });

        Schema::create('match_competitors', function(Blueprint $table){
            $table->increments('id');
            $table->integer('team_id');
            $table->string('remarks');
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
