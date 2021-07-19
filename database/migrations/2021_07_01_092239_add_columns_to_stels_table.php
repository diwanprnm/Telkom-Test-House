<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToStelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stels', function (Blueprint $table) {
            $table->date('publish_date')->nullable();
            $table->uuid('stels_master_id');

            // $table->foreign('stels_master_id')->references('id')->on('stels_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stels', function (Blueprint $table) {
            $table->dropColumn('publish_date');
            $table->dropColumn('stels_master_id');
        });
    }
}
