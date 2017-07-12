<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersMenusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('users_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id');
            $table->uuid('user_id');   
            $table->uuid('created_by'); 
            $table->uuid('updated_by'); 
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
          Schema::drop('users_menus');
    }
}
