<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSaLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sa_locations');
        Schema::create('sa_locations', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('type', 20)->default('planet');
            $table->string('name', 30)->nullabble();
            $table->integer('galaxy_id')->unsigned();
            $table->integer('hex_id')->unsigned();
            $table->integer('star_id')->unsigned();
            $table->integer('orbit_id')->unsigned();
            $table->boolean('is_landable')->default(false);
            $table->boolean('is_habitable')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->text('resources');
            $table->text('conditions');

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
        Schema::dropIfExists('sa_locations');
    }
}
