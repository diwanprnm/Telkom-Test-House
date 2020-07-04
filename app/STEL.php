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
        $where =   [
            ['stel_type', '=', '1'],
            ['code', 'like', '%'.$query.'%'] 
        ];
        $data1 = DB::table('stels')
                ->select('code as autosuggest')
				->where($where) 
				->orderBy('code')
                ->take(3)
                ->distinct()
                ->get();
		$data2 = DB::table('stels')
                ->select('name as autosuggest')
				->where($where) 
				->orderBy('name')
                ->take(3)
				->distinct()
                ->get(); 
        return array_merge($data1,$data2);
    }
	
	static function autocomplet_stsel($query){
        $where =   [
            ['stel_type', '=', '2'],
            ['code', 'like', '%'.$query.'%'] 
        ];
        $data1 = DB::table('stels')
                ->select('code as autosuggest')
				->where($where) 
				->orderBy('code')
                ->take(3)
				->distinct()
                ->get();
		$data2 = DB::table('stels')
                ->select('name as autosuggest')
				->where($where) 
				->orderBy('name')
                ->take(3)
				->distinct()
                ->get(); 
        return array_merge($data1,$data2);
    }
	
	static function adm_stel_autocomplet($query){
        return DB::table('stels')
                ->select('name as autosuggest')
				->where('name', 'like','%'.$query.'%')
				->orderBy('name')
                ->take(5)
				->distinct()
                ->get(); 
    }
}
