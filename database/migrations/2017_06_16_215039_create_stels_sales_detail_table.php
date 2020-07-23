<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStelsSalesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('stels_sales_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stels_sales_id');  
            $table->integer('stels_id');
            $table->integer('qty')->default(1);
            $table->string('attachment')->nullable();
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
         Schema::drop('stels_sales_detail');
    }
}
