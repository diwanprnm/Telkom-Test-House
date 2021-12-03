<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToTableFaqAndUpdateAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq', function (Blueprint $table) {
            // $table->text("answer")->change();
            $table->enum("category", [1, 2, 3, 4, 5, 6, 7])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faq', function (Blueprint $table) {
            // $table->string("answer")->change();
            $table->dropColumn("category");
        });
    }
}
