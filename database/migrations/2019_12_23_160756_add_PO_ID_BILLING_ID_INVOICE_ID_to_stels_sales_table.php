<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPOIDBILLINGIDINVOICEIDToStelsSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stels_sales', function (Blueprint $table) {
            $table->string('PO_ID')->nullable();
            $table->string('BILLING_ID')->nullable();
            $table->string('INVOICE_ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stels_sales', function (Blueprint $table) {
            //
        });
    }
}
