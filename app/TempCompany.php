<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TempCompany extends Model
{
    protected $table = "temp_company";
    public $incrementing = false;
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
	
	static function autocomplet($query){
		$auto_complete_result = DB::table('temp_company')
				->join('companies', 'temp_company.company_id', '=', 'companies.id')
                ->select('companies.name as autosuggest')
				->where('companies.name', 'like','%'.$query.'%')
				->orderBy('companies.name')
                ->take(5)
				->distinct()
                ->get();
				
		return $auto_complete_result;
	}
}
