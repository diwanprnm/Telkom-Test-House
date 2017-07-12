<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $table = "admin_roles";

    public $primaryKey = "user_id";
	
    public $incrementing = false;
}
