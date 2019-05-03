<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewExaminationChargesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_examination_charges_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('new_exam_charges_id');
            $table->uuid('examination_charges_id');
            $table->integer('price')->default(0);
            $table->integer('vt_price')->default(0);
            $table->integer('ta_price')->default(0);
            $table->integer('new_price')->default(0);
            $table->integer('new_vt_price')->default(0);
            $table->integer('new_ta_price')->default(0);

            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();

            $table->foreign('new_exam_charges_id')->references('id')->on('new_examination_charges');
            $table->foreign('examination_charges_id')->references('id')->on('examination_charges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('new_examination_charges_detail');
    }
}
