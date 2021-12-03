<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->text('audience');
            $table->integer('jml_perangkat')->default(0);
            $table->integer('jml_comply')->default(0);
            $table->integer('jml_not_comply')->default(0);
            $table->integer('jml_pending')->default(0);
            $table->enum('status', ['PRATINJAU', 'DRAFT', 'ON GOING', 'DONE'])->default('DRAFT');
            
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
        Schema::drop('sidang');
    }
}
