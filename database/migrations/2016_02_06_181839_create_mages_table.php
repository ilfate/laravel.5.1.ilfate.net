<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mages');
        Schema::create('mages', function($table)
        {
            $table->increments('id');

            $table->integer('player_id');
            $table->integer('world_id');
            $table->enum('class', [
                'apprentice',
                'wizard',
                'archmage',
                'sorcerer',
                'magus',
                'druid',
                'elementalist',
                'arcanist',
                'shadowmage',
                'pyromancer',
                'geomancer',
                'aeromancer',
                'necromancer',
                'ice_caller',
                'warlock',
                'summoner',
                'bloodmage',
                'spellbinder',
                'shaman',
                'enchanter',
                'illusionist',
                'invoker',
                'dragon_mage',
                'time_master',
                'techno_mage',
            ])->default('apprentice');
            $table->string('name');

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
        Schema::dropIfExists('mages');
    }
}
