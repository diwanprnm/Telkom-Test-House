<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdminRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->boolean('registration_status');
            $table->boolean('spb_status');
            $table->boolean('payment_status');
            $table->boolean('spk_status');
            $table->boolean('examination_status');
            $table->boolean('resume_status');
            $table->boolean('qa_status');
            $table->boolean('certificate_status');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_roles');
    }
}
