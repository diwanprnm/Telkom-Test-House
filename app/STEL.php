<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class STEL extends Model
{
    protected $table = "stels";

    public function examinationLab()
    {
        return $this->belongsTo('App\ExaminationLab', 'type');
    }
	
	static function autocomplet_stel($query){
        $data1 = DB::table('stels')
                ->select('code as autosuggest')
				->where('stel_type','=','1')
                ->where('code', 'like','%'.$query.'%')
				->orderBy('code')
                ->take(3)
                ->distinct()
                ->get();
		$data2 = DB::table('stels')
                ->select('name as autosuggest')
				->where('stel_type','=','1')
                ->where('code', 'like','%'.$query.'%')
				->orderBy('name')
                ->take(3)
				->distinct()
                ->get();
		$auto_complete_result = array_merge($data1,$data2);
        return $auto_complete_result;
    }
	
	static function autocomplet_stsel($query){
        $data1 = DB::table('stels')
                ->select('code as autosuggest')
				->where('stel_type','=','2')
                ->where('code', 'like','%'.$query.'%')
				->orderBy('code')
                ->take(3)
				->distinct()
                ->get();
		$data2 = DB::table('stels')
                ->select('name as autosuggest')
				->where('stel_type','=','2')
                ->where('code', 'like','%'.$query.'%')
				->orderBy('name')
                ->take(3)
				->distinct()
                ->get();
		$auto_complete_result = array_merge($data1,$data2);
        return $auto_complete_result;
    }
	
	static function adm_stel_autocomplet($query){
        $auto_complete_result = DB::table('stels')
                ->select('name as autosuggest')
				->where('name', 'like','%'.$query.'%')
				->orderBy('name')
                ->take(5)
				->distinct()
                ->get();
        return $auto_complete_result;
    }
}
