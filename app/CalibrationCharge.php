<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalibrationCharge extends Model
{
    protected $table = "calibration_charges";
    public $incrementing = false;
	
	static function autocomplet($query){

		
		return DB::table('calibration_charges')
		->select('device_name as autosuggest')
		->where('device_name', 'like','%'.$query.'%')
		->orderBy('device_name')
		->take(5)
		->distinct()
		->get();;
	}
}
