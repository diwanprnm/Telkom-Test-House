<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMSpkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_m_spk', function (Blueprint $table) {
            $table->string('ID', 50);
            $table->string('SPK_NUMBER', 50);
            $table->string('LAB_CODE')->nullable();
            $table->string('TESTING_TYPE')->nullable();
            $table->string('DEVICE_NAME');
            $table->string('COMPANY_NAME')->nullable();
            $table->string('FLOW_STATUS',2)->nullable();
            $table->string('CREATED_BY')->nullable();
            $table->dateTime('CREATED_DT')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->dateTime('UPDATED_DT')->nullable();

            $table->primary('ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_m_spk');
    }
}
