<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStelsSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stels_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->string('invoice');

            $table->string('name');
            $table->string('exp');
            $table->string('cvc');
            $table->string('cvv');
            $table->string('type');
            $table->string('no_card');
            $table->string('no_telp');
            $table->string('email');
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('postal_code');
            $table->date('birthdate');

            $table->integer('payment_method');
            $table->integer('payment_status');
            $table->string('total');
            $table->double('cust_price_payment', 15, 0)->default(0);
            $table->text('payment_code')->nullable();
            $table->string('id_kuitansi')->nullable();
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
          Schema::drop('stels_sales');
    }
}
