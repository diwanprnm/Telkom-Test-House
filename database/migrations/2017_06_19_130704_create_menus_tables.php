<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('name');  
            $table->string('url');  
            $table->string('icon');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('menus');
    }
}
