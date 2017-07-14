<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFootersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('footers', function (Blueprint $table) {
            $table->char('id', 36);
            $table->string('description');
            $table->string('image');
            $table->tinyInteger('is_active');
            $table->char('created_by', 36);
            $table->char('updated_by', 36);
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
