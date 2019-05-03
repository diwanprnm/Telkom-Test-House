<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExaminationCharge extends Model
{
    protected $table = "examination_charges";
    public $incrementing = false;
	
	static function autocomplet($query){
        $auto_complete_result = DB::table('examination_charges')
                ->select('device_name as autosuggest')
                ->where('device_name', 'like','%'.$query.'%')
				->orderBy('device_name')
                ->take(5)
                ->distinct()
                ->get();
        return $auto_complete_result;
    }

    public function newExaminationChargeDetail()
    {
        return $this->hasMany('App\newExaminationChargeDetail');
    }
}
