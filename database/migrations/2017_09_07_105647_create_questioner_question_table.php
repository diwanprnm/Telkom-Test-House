<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionerQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questioner_questions', function (Blueprint $table) {
			$table->uuid('id')->primary();
            $table->string('question');  
            $table->integer('order_question');  
			$table->boolean('is_essay');
			$table->boolean('is_active');
			
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
        Schema::drop('questioner_questions');
    }
}
