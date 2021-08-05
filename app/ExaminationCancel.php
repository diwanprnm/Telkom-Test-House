<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExaminationCancel extends Model
{
    protected $table = "examination_cancels";
	public $timestamps = false;
	
    public function examination()
    {
        return $this->belongsTo('App\Examination');
    }

    public function reason()
    {
        return $this->belongsTo('App\ReasonCancel');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

}
