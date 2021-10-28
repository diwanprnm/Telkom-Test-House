<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStelToCallibrationChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_charges', function (Blueprint $table) {
            //
            $table->string('stel');
            $table->string('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_charges', function (Blueprint $table) {
            //
            $table->dropColumn('stel');
            $table->dropColumn('duration');
        });
    }
}
