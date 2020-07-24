<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('role_id')->default();
            $table->uuid('company_id')->default();
            $table->string('name')->default();
            $table->string('email')->unique();
            $table->string('email2')->nullable();
            $table->string('email3')->nullable();
            $table->string('password')->default();
            $table->rememberToken();
            $table->boolean('is_active')->default(0);
            $table->string('picture')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax')->nullable();
            $table->boolean('is_deleted')->default(0);
			$table->string('deleted_by')->nullable();
			$table->timestamp('deleted_at')->nullable();
            $table->uuid('created_by')->default();
            $table->uuid('updated_by')->default();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
