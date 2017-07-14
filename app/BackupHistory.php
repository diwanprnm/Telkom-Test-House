<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackupHistory extends Model
{
   protected $table = "backup_history";
    public $incrementing = false;
}
