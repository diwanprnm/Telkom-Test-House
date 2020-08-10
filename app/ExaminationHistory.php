<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExaminationHistory extends Model
{
    protected $table = "examination_histories";
	public $timestamps = false;
		
    public function examination()
    {
        return $this->belongsTo('App\Examination');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

}
