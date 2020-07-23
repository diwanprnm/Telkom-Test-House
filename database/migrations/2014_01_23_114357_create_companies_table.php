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
            $table->string('name')->default();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->default();
            $table->string('postal_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax')->nullable();
            $table->string('npwp_number')->nullable();
            $table->string('npwp_file')->nullable();
            $table->string('siup_number')->nullable();
            $table->string('siup_file')->nullable();
            $table->date('siup_date')->nullable();
            $table->string('qs_certificate_number')->nullable();
            $table->string('qs_certificate_file')->nullable();
            $table->date('qs_certificate_date')->nullable();
            $table->boolean('is_active')->default(0);
            $table->text('keterangan')->nullable();
            $table->string('plg_id')->default();
            $table->string('nib')->default();
            $table->rememberToken();
            $table->uuid('created_by')->default();
            $table->uuid('updated_by')->default();
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
