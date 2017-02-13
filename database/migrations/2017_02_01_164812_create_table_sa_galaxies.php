<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSaGalaxies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sa_galaxies');
        Schema::create('sa_galaxies', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name', 30);
            $table->string('type', 15)->default('spiral');
            $table->string('status', 15)->default('new');
            $table->integer('population')->default(0)->unsigned();
            $table->boolean('is_public')->default(false);

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
        Schema::dropIfExists('sa_galaxies');
    }
}
