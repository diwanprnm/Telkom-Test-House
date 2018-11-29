<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionerDynamic extends Model
{
    protected $table = "questioner_dynamic";
	public $incrementing = false;
	
	public function qq()
    {
        return $this->hasOne('App\QuestionerQuestion', 'id', 'question_id');
    }
}
