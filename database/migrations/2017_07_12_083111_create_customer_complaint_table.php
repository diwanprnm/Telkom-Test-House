<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_complaints', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_id');
			$table->date('complaint_date');
            $table->text('complaint');
			
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
        Schema::drop('customer_complaints');
    }
}
