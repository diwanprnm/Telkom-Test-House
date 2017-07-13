<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExaminationHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_histories', function (Blueprint $table) {
            $table->uuid('examination_id');
            $table->date('date_action');
            $table->string('tahap');
            $table->boolean('status');
            $table->text('keterangan');
            $table->uuid('created_by');
            $table->dateTime('created_at');
			
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
        Schema::drop('examination_histories');
    }
}
