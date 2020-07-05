<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Footer extends Model
{
    protected $table = "footers";
    public $incrementing = false;
	
	static function autocomplet($query){
		return  DB::table('footers')
				->select('description as autosuggest')
				->where('description', 'like','%'.$query.'%')
                ->orderBy('description')
                ->take(5)
				->distinct()
                ->get();
		
	}
}
