<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMageUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mage_users');
        Schema::create('mage_users', function($table)
        {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('role')->default(1);
            $table->string('iseed', 10);
            $table->text('flags');
            $table->text('talants');
            $table->text('stats');
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
        Schema::dropIfExists('mage_users');
    }
}
