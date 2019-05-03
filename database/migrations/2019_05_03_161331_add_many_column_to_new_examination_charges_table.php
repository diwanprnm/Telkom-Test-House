<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManyColumnToNewExaminationChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_examination_charges_detail', function (Blueprint $table) {
            $table->string('device_name');
            $table->string('stel');
            $table->string('category');
            $table->string('duration');

            $table->dropForeign(['examination_charges_id']);
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
