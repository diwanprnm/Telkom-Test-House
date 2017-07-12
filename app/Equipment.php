<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Equipment extends Model
{
    protected $table = "equipments";

    public $incrementing = false;
	
	public function examination()
    {
        return $this->belongsTo('App\Examination');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
	
	// static function autocomplet($query){
		// $auto_complete_result = DB::table('users')
				// ->select('name as autosuggest')
				// ->where('name', 'like','%'.$query.'%')
                // ->orderBy('name')
                // ->take(5)
				// ->distinct()
                // ->get();
		
		// return $auto_complete_result;
	// }
}
