<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSaHexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sa_hexes');
        Schema::create('sa_hexes', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('galaxy_id')->unsigned();
            $table->integer('empire_id')->nullable();
            $table->integer('x');
            $table->integer('y');
            $table->boolean('is_hidden')->default(false);

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
        Schema::dropIfExists('sa_hexes');
    }
}
