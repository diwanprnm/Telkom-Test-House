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
	
	static function adm_feedback_autocomplet($query){
		return DB::table('feedbacks')
				->select('subject as autosuggest')
				->where('subject', 'like','%'.$query.'%')
				->orderBy('subject')
                ->take(5)
				->distinct()
                ->get();
	}
}
