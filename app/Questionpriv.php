<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionpriv extends Model
{
    protected $table = "question_privileges";	
    public $incrementing = false;
	
    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
