<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVAToStelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stels_sales', function (Blueprint $table) {
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
