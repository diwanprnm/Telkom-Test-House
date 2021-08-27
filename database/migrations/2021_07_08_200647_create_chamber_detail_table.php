<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChamberDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamber_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('chamber_id');
            $table->date('date');
            $table->timestamps();
            
            $table->foreign('chamber_id')->references('id')->on('chamber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chamber_detail');
    }
}
