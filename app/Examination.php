<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Examination extends Model
{
    protected $table = "examinations";

    public $incrementing = false;

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function examinationType()
    {
        return $this->belongsTo('App\ExaminationType');
    }

    public function examinationLab()
    {
        return $this->belongsTo('App\ExaminationLab');
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function equipment()
    {
        return $this->hasMany('App\Equipment');
    }

    public function examinationHistory()
    {
        return $this->hasMany('App\ExaminationHistory');
    }

    public function media()
    {
        return $this->hasMany('App\ExaminationAttach');
    }
	
	static function autocomplet($query){
		$datenow = date('Y-m-d');
		
        $data1 = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('companies.name as autosuggest')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('companies.name', 'like','%'.$query.'%')
				->orderBy('companies.name')
                ->take(2)
				->distinct()
                ->get();
		$data2 = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('devices.name as autosuggest')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(2)
				->distinct()
                ->get();
		$data3 = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('devices.mark as autosuggest')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('devices.mark', 'like','%'.$query.'%')
				->orderBy('devices.mark')
                ->take(2)
				->distinct()
                ->get();
		$data4 = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('devices.model as autosuggest')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('devices.model', 'like','%'.$query.'%')
				->orderBy('devices.model')
                ->take(2)
				->distinct()
                ->get();
		
		$auto_complete_result = array_merge($data1,$data2,$data3,$data4);
        return $auto_complete_result;
    }
	
	static function autocomplet_pengujian($query,$company_id){
		$datenow = date('Y-m-d');
		
        $auto_complete_result = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('users', 'examinations.created_by', '=', 'users.id')
				->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
                ->select('devices.name as autosuggest')
                ->where('examinations.company_id','=',''.$company_id.'')
                ->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(2)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
	
	static function adm_dashboard_autocomplet($query){
		$auto_complete_result = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
                ->select('devices.name as autosuggest')
				->where(function($q){
					$q->where('examinations.registration_status', 0)
						->orWhere('examinations.registration_status', 1)
						->orWhere('examinations.registration_status', -1)
						->orWhere('examinations.spb_status', 0)
						->orWhere('examinations.spb_status', -1)
						->orWhere('examinations.spb_status', 1)
						->orWhere('examinations.payment_status', -1);
				})
				->where('payment_status', 0)
                ->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(5)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
	
	static function adm_exam_autocomplet($query){
		$data1 = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('devices.name as autosuggest')
				->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(3)
				->distinct()
                ->get();
		
		$data2 = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('companies.name as autosuggest')
				->where('companies.name', 'like','%'.$query.'%')
				->orderBy('companies.name')
                ->take(3)
				->distinct()
                ->get();
		
		$auto_complete_result = array_merge($data1,$data2);
        return $auto_complete_result;
	}
	
	static function adm_exam_done_autocomplet($query){
		$queries = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('devices.name as autosuggest')
				->where('devices.name', 'like','%'.$query.'%');
					$queries->where(function($qry){
						$qry->where(function($q){
							return $q->where('examination_type_id', '=', '1')
								->where('registration_status', '=', '1')
								->where('function_status', '=', '1')
								->where('contract_status', '=', '1')
								->where('spb_status', '=', '1')
								->where('payment_status', '=', '1')
								->where('spk_status', '=', '1')
								->where('examination_status', '=', '1')
								->where('resume_status', '=', '1')
								->where('qa_status', '=', '1')
								->where('certificate_status', '=', '1')
								;
							})
						->orWhere(function($q){
							return $q->where('examination_type_id', '!=', '1')
								->where('registration_status', '=', '1')
								->where('function_status', '=', '1')
								->where('contract_status', '=', '1')
								->where('spb_status', '=', '1')
								->where('payment_status', '=', '1')
								->where('spk_status', '=', '1')
								->where('examination_status', '=', '1')
								->where('resume_status', '=', '1')
								;
							});
					});
				$data1 = $queries->orderBy('devices.name')
                ->take(3)
				->distinct()
                ->get();
		
		$queries = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->select('companies.name as autosuggest')
				->where('companies.name', 'like','%'.$query.'%');
					$queries->where(function($qry){
						$qry->where(function($q){
							return $q->where('examinations.examination_type_id', '=', '1')
								->where('examinations.registration_status', '=', '1')
								->where('examinations.function_status', '=', '1')
								->where('examinations.contract_status', '=', '1')
								->where('examinations.spb_status', '=', '1')
								->where('examinations.payment_status', '=', '1')
								->where('examinations.spk_status', '=', '1')
								->where('examinations.examination_status', '=', '1')
								->where('examinations.resume_status', '=', '1')
								->where('examinations.qa_status', '=', '1')
								->where('examinations.certificate_status', '=', '1')
								;
							})
						->orWhere(function($q){
							return $q->where('examinations.examination_type_id', '!=', '1')
								->where('examinations.registration_status', '=', '1')
								->where('examinations.function_status', '=', '1')
								->where('examinations.contract_status', '=', '1')
								->where('examinations.spb_status', '=', '1')
								->where('examinations.payment_status', '=', '1')
								->where('examinations.spk_status', '=', '1')
								->where('examinations.examination_status', '=', '1')
								->where('examinations.resume_status', '=', '1')
								;
							});
					});
				$data2 = $queries->orderBy('companies.name')
                ->take(3)
				->distinct()
                ->get();
				
		$auto_complete_result = array_merge($data1,$data2);
        return $auto_complete_result;
	}
}
