<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Equipment extends Model
{
    protected $table = "equipments";

    public $incrementing = false;
	
	public function examination()
    {
        return $this->belongsTo('App\Examination');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
	
	
}
