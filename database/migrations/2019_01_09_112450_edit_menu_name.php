<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditMenuName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            DB::table('menus')->where('url', 'feedback')
                ->update(['name' => 'Questions and Answers (QnA)']);
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
