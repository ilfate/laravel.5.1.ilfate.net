<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSettlemets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('settlements');
        Schema::create('settlements', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id');

            $table->string('name')->nullable();
            $table->text('data')->nullable();
            $table->text('resources')->nullable();
            $table->text('inventory')->nullable();
            $table->text('events')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlements');
    }
}
