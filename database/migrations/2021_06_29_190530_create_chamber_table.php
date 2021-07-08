<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChamberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamber', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('company_id');
            $table->string('invoice'); // CMB No.urut/Bulan Romawi/Tahun ex : CMB 0001/VII/2021

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration')->default(1);

            $table->string('spb_number')->nullable();
            $table->date('spb_date')->nullable();

            $table->double('price', 15, 0)->default(0);
            $table->double('tax', 15, 0)->default(0);
            $table->double('total', 15, 0)->default(0);

            $table->integer('payment_method')->nullable();
            $table->integer('payment_status')->default(0);
            $table->integer('include_pph')->default(0);
            $table->double('cust_price_payment', 15, 0)->default(0);

            $table->date('pay_date')->nullable();
            $table->date('approved_date')->nullable();
            
            $table->string('kuitansi_file')->nullable();
            $table->string('faktur_file')->nullable();
            $table->string('PO_ID')->nullable();
            $table->string('BILLING_ID')->nullable();
            $table->string('INVOICE_ID')->nullable();
            $table->string('VA_name')->nullable();
            $table->string('VA_image_url')->nullable();
            $table->string('VA_number')->nullable();
            $table->double('VA_amount', 15, 0)->nullable();
            $table->string('VA_expired')->timestamp()->nullable();

            $table->uuid('created_by'); 
            $table->uuid('updated_by');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chamber');
    }
}
