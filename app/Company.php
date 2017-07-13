<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    protected $table = "companies";
    public $incrementing = false;
	
	static function autocomplet($query){
		$auto_complete_result = DB::table('companies')
				->select('name as autosuggest')
				->where('name', 'like','%'.$query.'%')
                ->orderBy('name')
                ->take(5)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
}
