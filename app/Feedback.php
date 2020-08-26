<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Feedback extends Model
{
    protected $table = "feedbacks";
    public $incrementing = false;
	
	public function user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
