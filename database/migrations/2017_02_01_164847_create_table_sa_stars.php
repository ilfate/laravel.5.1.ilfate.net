<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSaStars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sa_stars');
        Schema::create('sa_stars', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('type', 20)->default('yellow dwarf');
            $table->integer('galaxy_id')->unsigned();
            $table->integer('hex_id')->unsigned();
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
        Schema::dropIfExists('sa_stars');
    }
}
