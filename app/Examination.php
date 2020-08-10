<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Examination extends Model
{
    
    private const APP_COMPANY = 'App\Company';
    private const APP_EXAMINATION_TYPE = 'App\ExaminationType';
    private const APP_EXAMINATION_LAB= 'App\ExaminationLab';
    private const APP_DEVICE = 'App\Device';
    private const APP_USER = 'App\User';
    private const APP_EQUIPMENT = 'App\Equipment';
    private const EXAM_REGISTRATION_STATUS = 'examinations.registration_status';
    private const EXAM_DEVICES_ID = 'examinations.device_id';
    private const EXAM_COMPANY_ID = 'examinations.company_id';
    private const COMPANY_AUTOSUGGEST = 'companies.name as autosuggest';
    private const DEVICES_ID = 'devices.id';
    private const COMPANIES_ID = 'companies.id';
    private const PAYMENT_STATUS = 'payment_status';
    private const TABLE_EXAM = 'examinations';
    private const TABLE_DEVICE = 'devices';
    private const TABLE_COMPANIES = 'companies';

    protected $table = self::TABLE_EXAM;
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

    public function questioner()
    {
        return $this->hasMany('App\Questioner');
    }

    public function questionerdynamic()
    {
        return $this->hasMany('App\QuestionerDynamic');
    }

    public function examinationHistory()
    {
        return $this->hasMany('App\ExaminationHistory');
    }

    public function media()
    {
        return $this->hasMany('App\ExaminationAttach', 'examination_id')->orderBy('created_at', 'DESC');
    }
	
	static function autocomplet($query){
		$datenow = date('Y-m-d');
		
        $data1 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('companies.name', 'like','%'.$query.'%')
				->orderBy('companies.name')
                ->take(2)
				->distinct()
                ->get();
		$data2 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.name as autosuggest')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(2)
				->distinct()
                ->get();
		$data3 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.mark as autosuggest')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
                ->where('devices.mark', 'like','%'.$query.'%')
				->orderBy('devices.mark')
                ->take(2)
				->distinct()
                ->get();
		$data4 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
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
		
        $auto_complete_result = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join('users', 'examinations.created_by', '=', 'users.id')
				->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
                ->select('devices.name as autosuggest')
                ->where(self::EXAM_COMPANY_ID,'=',''.$company_id.'')
                ->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(2)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
	
	static function adm_dashboard_autocomplet($query){
		$auto_complete_result = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
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
				->where(self::PAYMENT_STATUS, 0)
                ->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(5)
				->distinct()
                ->get();
		
		return $auto_complete_result;
	}
	
	static function adm_exam_autocomplet($query){
		$data1 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.name as autosuggest')
				->where('devices.name', 'like','%'.$query.'%')
				->orderBy('devices.name')
                ->take(3)
				->distinct()
                ->get();
		
		$data2 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where('companies.name', 'like','%'.$query.'%')
				->orderBy('companies.name')
                ->take(3)
				->distinct()
                ->get();
		 
        return array_merge($data1,$data2);
	}
	
	static function adm_exam_done_autocomplet($query){
		$queries = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.name as autosuggest')
				->where('devices.name', 'like','%'.$query.'%');
					$queries->where(function($qry){
						$qry->where(function($q){
							return $q->where('examination_type_id', '=', '1')
								->where('registration_status', '=', '1')
								->where('function_status', '=', '1')
								->where('contract_status', '=', '1')
								->where('spb_status', '=', '1')
								->where(self::PAYMENT_STATUS, '=', '1')
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
								->where(self::PAYMENT_STATUS, '=', '1')
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
		
		$queries = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
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
				
		 return array_merge($data1,$data2);
       
	}
}
