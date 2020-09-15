<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVAToExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->integer('unique_code');
            $table->integer('include_pph');
            $table->integer('payment_method');
            $table->string('VA_name')->nullable();
            $table->string('VA_image_url')->nullable();
            $table->string('VA_number')->nullable();
            $table->double('VA_amount', 15, 0)->nullable();
            $table->string('VA_expired')->timestamp()->nullable();
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
