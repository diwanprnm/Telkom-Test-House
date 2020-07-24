<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('logs', function (Blueprint $table) {
            $table->uuid('id')->primary();  
            $table->uuid('user_id');
            $table->string('action')->nullable();  
            $table->text('data')->nullable();  
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps(); 

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logs');
    }
}
