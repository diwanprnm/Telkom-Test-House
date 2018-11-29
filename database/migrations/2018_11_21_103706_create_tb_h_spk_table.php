<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbHSpkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_h_spk', function (Blueprint $table) {
            $table->string('ID', 50);
            $table->string('SPK_NUMBER', 50);
            $table->text('ACTION');
            $table->text('REMARK');
            $table->string('CREATED_BY');
            $table->dateTime('CREATED_DT');
            $table->string('UPDATED_BY');
            $table->dateTime('UPDATED_DT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tb_h_spk');
    }
}
