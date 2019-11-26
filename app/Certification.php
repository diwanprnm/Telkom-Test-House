<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Certification extends Model
{
    protected $table = "certifications";
    public $incrementing = false;
	
	static function autocomplet($query){
		$auto_complete_result = DB::table('ertifications')
				->select('title as autosuggest')
				->where('title', 'like','%'.$query.'%')
                ->orderBy('title')
                ->take(5)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
}
