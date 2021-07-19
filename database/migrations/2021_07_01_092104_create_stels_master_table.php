<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStelsMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stels_master', function (Blueprint $table) {
            $table->uuid('id');
            $table->enum('type', ['1','2','3','4','5','6','7']);
            $table->string('code');
            $table->string('lab');
            $table->enum('lang', ['IDN', 'ENG'])->default('IDN');
            $table->integer('total');
            
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
        Schema::drop('stels_master');
    }
}
