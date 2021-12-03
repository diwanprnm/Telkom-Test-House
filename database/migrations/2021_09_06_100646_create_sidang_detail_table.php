<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSidangDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidang_detail', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('sidang_id');
            $table->uuid('examination_id');
            $table->enum('result', ['0','1', '-1', '2']); // 0:No Result, 1:Comply, -1:Not Comply, 2:Pending
            $table->string('status'); //SM and CIGS, Eligible or Not
            $table->date('valid_from')->nullable();
            $table->date('valid_thru')->nullable();
            $table->integer('valid_range')->default(36);
            $table->text('catatan')->nullable();
            
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
            
            $table->foreign('sidang_id')->references('id')->on('sidang');
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
        Schema::drop('sidang_detail');
    }
}
