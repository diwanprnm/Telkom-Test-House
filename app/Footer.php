<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Footer extends Model
{
    protected $table = "footers";
    public $incrementing = false;
	
	static function autocomplet($query){
		$auto_complete_result = DB::table('footers')
				->select('description as autosuggest')
				->where('description', 'like','%'.$query.'%')
                ->orderBy('description')
                ->take(5)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
}
