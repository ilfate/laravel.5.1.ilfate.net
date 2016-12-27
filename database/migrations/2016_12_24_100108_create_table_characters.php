<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCharacters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('wh_characters');
        Schema::create('wh_characters', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('settlement_id');
            $table->integer('raid_id');
            $table->integer('building_id');
            $table->integer('location');
            $table->text('data');
            $table->text('traits');
            $table->text('items');

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
        Schema::dropIfExists('wh_characters');
    }
}
