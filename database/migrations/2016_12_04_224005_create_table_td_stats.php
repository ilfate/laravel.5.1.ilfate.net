<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTdStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('td_stats');
        Schema::create('td_stats', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->integer('waves')->unsigned();

            $table->string('ip', 16);
            $table->string('laravel_session', 100);
            $table->string('name', 30)->nullable();

            $table->dateTime('created_at');
            $table->dateTime('updated_at');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('td_stats');
    }
}
