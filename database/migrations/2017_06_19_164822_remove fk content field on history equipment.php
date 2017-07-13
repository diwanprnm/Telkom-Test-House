<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFkContentFieldOnHistoryEquipment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment_histories', function (Blueprint $table) {
            $table->dropForeign('equipment_histories_equipment_id_foreign');
            $table->dropColumn('equipment_id');
            
            $table->uuid('examination_id');
            $table->foreign('examination_id')->references('id')->on('examinations');
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
