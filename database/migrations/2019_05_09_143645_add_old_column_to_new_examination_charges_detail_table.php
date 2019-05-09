<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOldColumnToNewExaminationChargesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_examination_charges_detail', function (Blueprint $table) {
            $table->string('old_device_name');
            $table->string('old_stel');
            $table->string('old_category');
            $table->string('old_duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_examination_charges_detail', function (Blueprint $table) {
            //
        });
    }
}
