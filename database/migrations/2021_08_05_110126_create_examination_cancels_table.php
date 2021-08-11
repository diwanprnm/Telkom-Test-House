<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationCancelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_cancels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_id');
            $table->uuid('reason_cancel_id');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();

            $table->foreign('examination_id')->references('id')->on('examinations');
            $table->foreign('reason_cancel_id')->references('id')->on('reason_cancels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('examination_cancels');
    }
}
