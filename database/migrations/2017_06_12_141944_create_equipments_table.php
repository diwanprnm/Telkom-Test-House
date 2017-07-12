<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_id');
            $table->string('name');
            $table->double('qty', 15, 0);
            $table->string('unit');
            $table->boolean('location');
            $table->string('pic');
            $table->string('remarks');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
			
			$table->foreign('examination_id')->references('id')->on('examinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
