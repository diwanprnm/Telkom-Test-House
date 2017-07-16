<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSpkCreatedFunctionStatusTEAndPICFunctionTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examinations', function ($table) {
			$table->boolean('is_spk_created');
			$table->boolean('function_test_TE');
			$table->string('function_test_PIC');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
