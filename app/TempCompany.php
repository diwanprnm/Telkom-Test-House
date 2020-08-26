<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TempCompany extends Model
{
    protected $table = "temp_company";
    public $incrementing = false;
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
