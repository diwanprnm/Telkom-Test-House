<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEmailEditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_editors', function(Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('subject');
            $table->string('dir_name');
            $table->string('logo');
            $table->text('content');
            $table->text('signature');
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
        Schema::drop('email_editors');
    }
}
