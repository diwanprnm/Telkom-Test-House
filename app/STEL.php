<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class STEL extends Model
{   
    private const TABLE_STELS = 'stels'; 
    private const CODE_AUTOSUGGEST = 'code as autosuggest';
    private const NAME_AUTOSUGGEST = 'name as autosuggest';
    protected $table = self::TABLE_STELS;

    public function examinationLab()
    {
        return $this->belongsTo('App\ExaminationLab', 'type');
    }
	
	static function autocomplet_stel($query){
        $where =   [
            ['stel_type', '=', '1'],
            ['code', 'like', '%'.$query.'%'] 
        ];
        $data1 = DB::table(self::TABLE_STELS)
                ->select(self::CODE_AUTOSUGGEST)
				->where($where) 
				->orderBy('code')
                ->take(3)
                ->distinct()
                ->get();
		$data2 = DB::table(self::TABLE_STELS)
                ->select(self::NAME_AUTOSUGGEST)
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
        $data1 = DB::table(self::TABLE_STELS)
                ->select(self::CODE_AUTOSUGGEST)
				->where($where) 
				->orderBy('code')
                ->take(3)
				->distinct()
                ->get();
		$data2 = DB::table(self::TABLE_STELS)
                ->select(self::NAME_AUTOSUGGEST)
				->where($where) 
				->orderBy('name')
                ->take(3)
				->distinct()
                ->get(); 
        return array_merge($data1,$data2);
    }
	
	static function adm_stel_autocomplet($query){
        return DB::table(self::TABLE_STELS)
                ->select(self::NAME_AUTOSUGGEST)
				->where('name', 'like','%'.$query.'%')
				->orderBy('name')
                ->take(5)
				->distinct()
                ->get(); 
    }
}
