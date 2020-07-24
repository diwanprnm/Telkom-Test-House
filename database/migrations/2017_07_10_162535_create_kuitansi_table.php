<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuitansiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuitansi', function (Blueprint $table) {
			$table->uuid('id')->primary();
            $table->string('number');  
            $table->string('from');  
            $table->double('price', 15, 0);
            $table->string('for');
            $table->date('kuitansi_date')->nullable();
			$table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kuitansi');
    }
}
