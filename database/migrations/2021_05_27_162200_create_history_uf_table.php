<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryUfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_uf', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_id');
            $table->text('catatan')->nullable();
            $table->boolean('function_test_TE')->default(0);
            $table->string('function_test_PIC')->nullable();
            $table->date('function_test_date')->nullable();
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
        Schema::drop('history_uf');
    }
}
