<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionPrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_privileges', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('question_id');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('question_id')->references('id')->on('question_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('question_privileges');
    }
}
