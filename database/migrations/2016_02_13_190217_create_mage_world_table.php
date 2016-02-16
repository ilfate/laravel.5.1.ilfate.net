<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMageWorldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mage_worlds');
        Schema::create('mage_worlds', function($table)
        {
            $table->increments('id');

            $table->integer('player_id');
            $table->integer('type')->default(1);
            $table->text('map');
            $table->text('objects');
            $table->text('units');

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
        Schema::dropIfExists('mage_worlds');
    }
}
