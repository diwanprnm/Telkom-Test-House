<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuisionerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questioners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_id');
			$table->date('questioner_date');
            $table->integer('quest1_eks');
            $table->integer('quest1_perf');
            $table->integer('quest2_eks');
            $table->integer('quest2_perf');
            $table->integer('quest3_eks');
            $table->integer('quest3_perf');
            $table->integer('quest4_eks');
            $table->integer('quest4_perf');
            $table->integer('quest5_eks');
            $table->integer('quest5_perf');
            $table->text('quest6');
			$table->integer('quest7_eks');
            $table->integer('quest7_perf');
            $table->integer('quest8_eks');
            $table->integer('quest8_perf');
            $table->integer('quest9_eks');
            $table->integer('quest9_perf');
            $table->integer('quest10_eks');
            $table->integer('quest10_perf');
            $table->integer('quest11_eks');
            $table->integer('quest11_perf');
			$table->integer('quest12_eks');
            $table->integer('quest12_perf');
            $table->integer('quest13_eks');
            $table->integer('quest13_perf');
            $table->integer('quest14_eks');
            $table->integer('quest14_perf');
            $table->integer('quest15_eks');
            $table->integer('quest15_perf');
			$table->integer('quest16_eks');
            $table->integer('quest16_perf');
            $table->integer('quest17_eks');
            $table->integer('quest17_perf');
			$table->integer('quest18_eks');
            $table->integer('quest18_perf');
            $table->integer('quest19_eks');
            $table->integer('quest19_perf');
			$table->integer('quest20_eks');
            $table->integer('quest20_perf');
            $table->integer('quest21_eks');
            $table->integer('quest21_perf');
            $table->integer('quest22_eks');
            $table->integer('quest22_perf');
            $table->integer('quest23_eks');
            $table->integer('quest23_perf');
			$table->integer('quest24_eks');
            $table->integer('quest24_perf');
            $table->integer('quest25_eks');
            $table->integer('quest25_perf');
            
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();

            $table->foreign('examination_id')->references('id')->on('examinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questioners');
    }
}
