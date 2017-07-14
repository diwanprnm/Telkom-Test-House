<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_type_id');
            $table->uuid('company_id');
            $table->uuid('device_id');
            $table->uuid('examination_lab_id')->nullable();
            $table->string('spk_code')->nullable();
            $table->boolean('registration_status');
            $table->boolean('spb_status');
            $table->boolean('payment_status');
            $table->boolean('spk_status');
            $table->boolean('examination_status');
            $table->boolean('resume_status');
            $table->boolean('qa_status');
            $table->boolean('certificate_status');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();

            $table->foreign('examination_type_id')->references('id')->on('examination_types');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('examination_lab_id')->references('id')->on('examination_labs');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('examinations');
    }
}
