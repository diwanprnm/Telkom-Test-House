<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
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
            $table->date('siup_date');
            $table->string('qs_certificate_number');
            $table->string('qs_certificate_file');
            $table->date('qs_certificate_date');
            $table->boolean('is_active');
            $table->rememberToken();
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
        Schema::drop('companies');
    }
}
