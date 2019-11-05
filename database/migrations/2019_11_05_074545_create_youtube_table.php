<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube', function (Blueprint $table) {
            $table->increments('id');
            $table->string('profile_url');
            $table->string('buy_stel_url');
            $table->string('qa_url');
            $table->string('ta_url');
            $table->string('vt_url');
            $table->string('playlist_url');
            
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
        Schema::drop('youtube');
    }
}
