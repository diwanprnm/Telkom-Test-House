<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionerDynamicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questioner_dynamic', function (Blueprint $table) {
            $table->uuid('question_id');
            $table->uuid('examination_id');
			$table->integer('order_question');  
			$table->boolean('is_essay');
            $table->date('questioner_date');
            $table->string('eks_answer');
            $table->string('perf_answer');
			
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
			
			$table->foreign('question_id')->references('id')->on('questioner_questions');
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
        Schema::drop('questioner_dynamic');
    }
}
