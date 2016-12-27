<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBuildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('wh_buildings');
        Schema::create('wh_buildings', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('settlement_id');
            $table->string('type', 30);
            $table->text('data');

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
        Schema::dropIfExists('wh_buildings');
    }
}
