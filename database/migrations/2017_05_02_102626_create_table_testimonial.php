<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTestimonial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonial', function (Blueprint $table) {
            $table->uuid('id');
			$table->uuid('examination_id');
            $table->text('message');
            $table->boolean('is_active');
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
        Schema::drop('testimonial');
    }
}
