<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Income extends Model
{
    protected const INCOMES = 'incomes';
    protected $table = self::INCOMES;
    
    public $incrementing = false;
	
	public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	public function examination()
    {
        return $this->belongsTo('App\Examination', 'reference_id');
    }
	
	static function autocomplet($query){
		$queries = DB::table(self::INCOMES)
				->join('companies', 'incomes.company_id', '=', 'companies.id')
                ->select('companies.name as autosuggest')
				->where('companies.name', 'like','%'.$query.'%');
				
		$data1 = $queries->orderBy('companies.name')
                ->take(3)
				->distinct()
                ->get();
		
		$data2 = DB::table(self::INCOMES)
				->select('reference_number as autosuggest')
				->where('reference_number', 'like','%'.$query.'%')
                ->orderBy('reference_number')
                ->take(3)
				->distinct()
                ->get();
		
        return array_merge($data1,$data2);
	}
}
