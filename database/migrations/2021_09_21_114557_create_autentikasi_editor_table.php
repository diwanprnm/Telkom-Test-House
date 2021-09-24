<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutentikasiEditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autentikasi_editor', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('dir_name');
            $table->string('logo');
            $table->text('content');
            $table->text('signature');
            $table->text('sign_by');

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
        Schema::drop('autentikasi_editor');
    }
}
