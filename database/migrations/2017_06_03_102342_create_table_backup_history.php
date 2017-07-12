<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBackupHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('backup_history', function (Blueprint $table) {
            $table->increments('id');  
            $table->uuid('user_id');
            $table->string('file')->nullable();   
            $table->tinyInteger('is_active')->default(1);
            $table->uuid('restore_by');
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
       Schema::drop('backup_history');
    }
}
