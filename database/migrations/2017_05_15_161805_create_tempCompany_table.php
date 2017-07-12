<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_company', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('email');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->string('fax');
            $table->string('npwp_number');
            $table->string('npwp_file');
            $table->string('siup_number');
            $table->string('siup_file');
            $table->date('siup_date')->nullable();
            $table->string('qs_certificate_number');
            $table->string('qs_certificate_file');
            $table->date('qs_certificate_date')->nullable();
            $table->boolean('is_commited');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
			
			$table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('temp_company');
    }
}
