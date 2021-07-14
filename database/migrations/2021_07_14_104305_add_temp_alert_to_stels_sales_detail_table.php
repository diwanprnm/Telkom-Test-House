<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTempAlertToStelsSalesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stels_sales_detail', function (Blueprint $table) {
            $table->enum('temp_alert', ['0', '1', '2'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stels_sales_detail', function (Blueprint $table) {
            $table->dropColumn('temp_alert');
        });
    }
}
