<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMageWorldsTable extends Migration
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

            $table->enum('type', [
                'test',
                'training',
                'tutorial',
                'lava',
                'endless_forest',
                'dungeon',
                'survival',
                'survival_river',
            ]);
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
        Schema::dropIfExists('mage_worls');
    }
}
