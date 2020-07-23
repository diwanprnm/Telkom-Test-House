<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('mark');
            $table->string('capacity');
            $table->string('manufactured_by');
            $table->string('serial_number');
            $table->string('model');
            $table->string('test_reference');
            $table->string('certificate')->nullable();
            $table->boolean('status');
            $table->date('valid_from')->nullable();
            $table->date('valid_thru')->nullable();
            $table->string('cert_number')->nullable();
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
        Schema::drop('devices');
    }
}
