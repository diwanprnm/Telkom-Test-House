<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlideshowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slideshows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('image');
            $table->string('headline')->nullable();
            $table->string('color')->nullable();
            $table->integer('timeout')->default(0);
            $table->integer('position')->nullable();
            $table->boolean('is_active');
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
        Schema::drop('slideshows');
    }
}
