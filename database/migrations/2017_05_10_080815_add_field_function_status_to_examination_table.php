<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldFunctionStatusToExaminationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examinations', function ($table) {
			$table->date('urel_test_date')->nullable();
			$table->date('cust_test_date')->nullable();
			$table->date('deal_test_date')->nullable();
			$table->boolean('function_status');
			$table->text('catatan')->nullable();
			$table->date('function_date')->nullable();
			$table->boolean('contract_status');
			$table->date('contract_date')->nullable();
			$table->date('testing_start')->nullable();
			$table->date('testing_end')->nullable();
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
