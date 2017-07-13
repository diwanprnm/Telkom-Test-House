<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStelsSalesAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('stels_sales_attachment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('stel_sales_id',36);  
            $table->string('attachment');   
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
        //
    }
}
