<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Examination extends Model
{      
	private const EXAM_REGISTRATION_STATUS = 'examinations.registration_status';
	private const EXAM_CERTIFICATE_STATUS = 'examinations.certificate_status';
	private const EXAM_SPB_STATUS = 'examinations.spb_status';
	private const EXAM_PAYMENT_STATUS = 'examinations.payment_status';
    private const EXAM_DEVICES_ID = 'examinations.device_id';
	private const EXAM_COMPANY_ID = 'examinations.company_id';
	private const EXAM_TYPE_ID = 'examinations.examination_type_id';
    private const DEVICES_ID = 'devices.id';
	private const DEVICES_VALID_THRU = 'devices.valid_thru';
	private const DEVICE_NAME = 'devices.name';
	private const DEVICE_NAME_AUTOSUGGEST = 'devices.name as autosuggest';
	private const COMPANIES_ID = 'companies.id';
	private const COMPANIES_NAME = 'companies.name';
	private const COMPANY_AUTOSUGGEST = 'companies.name as autosuggest';
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
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where(self::COMPANIES_NAME, 'like','%'.$query.'%')
				->orderBy(self::COMPANIES_NAME)
                ->take(2)
				->distinct()
                ->get();
		$data2 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(2)
				->distinct()
                ->get();
		$data3 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.mark as autosuggest')
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where('devices.mark', 'like','%'.$query.'%')
				->orderBy('devices.mark')
                ->take(2)
				->distinct()
                ->get();
		$data4 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.model as autosuggest')
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where('devices.model', 'like','%'.$query.'%')
				->orderBy('devices.model')
                ->take(2)
				->distinct()
                ->get();
		
		return array_merge($data1,$data2,$data3,$data4);
         
    }
	
	static function autocomplet_pengujian($query,$company_id){ 
		
        return DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join('users', 'examinations.created_by', '=', 'users.id')
				->join('examination_types', self::EXAM_TYPE_ID, '=', 'examination_types.id')
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
                ->where(self::EXAM_COMPANY_ID,'=',''.$company_id.'')
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(2)
				->distinct()
                ->get(); 
	}
	
	static function adm_dashboard_autocomplet($query){
		return DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(function($q){
					$q->where(self::EXAM_REGISTRATION_STATUS, 0)
						->orWhere(self::EXAM_REGISTRATION_STATUS, 1)
						->orWhere(self::EXAM_REGISTRATION_STATUS, -1)
						->orWhere(self::EXAM_SPB_STATUS, 0)
						->orWhere(self::EXAM_SPB_STATUS, -1)
						->orWhere(self::EXAM_SPB_STATUS, 1)
						->orWhere(self::EXAM_PAYMENT_STATUS, -1);
				})
				->where(self::PAYMENT_STATUS, 0)
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(5)
				->distinct()
                ->get(); 
	}
	
	static function adm_exam_autocomplet($query){
		$data1 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(3)
				->distinct()
                ->get();
		
		$data2 = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where(self::COMPANIES_NAME, 'like','%'.$query.'%')
				->orderBy(self::COMPANIES_NAME)
                ->take(3)
				->distinct()
                ->get();
		 
        return array_merge($data1,$data2);
	}
	
	static function adm_exam_done_autocomplet($query){
		$queries = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(self::DEVICE_NAME, 'like','%'.$query.'%');
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
				$data1 = $queries->orderBy(self::DEVICE_NAME)
                ->take(3)
				->distinct()
                ->get();
		
		$queries = DB::table(self::TABLE_EXAM)
				->join(self::TABLE_DEVICE, self::DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where(self::COMPANIES_NAME, 'like','%'.$query.'%');
					$queries->where(function($qry){
						$qry->where(function($q){
							return $q->where(self::EXAM_TYPE_ID, '=', '1')
								->where(self::EXAM_REGISTRATION_STATUS, '=', '1')
								->where('examinations.function_status', '=', '1')
								->where('examinations.contract_status', '=', '1')
								->where(self::EXAM_SPB_STATUS, '=', '1')
								->where(self::EXAM_PAYMENT_STATUS, '=', '1')
								->where('examinations.spk_status', '=', '1')
								->where('examinations.examination_status', '=', '1')
								->where('examinations.resume_status', '=', '1')
								->where('examinations.qa_status', '=', '1')
								->where(self::EXAM_CERTIFICATE_STATUS, '=', '1')
								;
							})
						->orWhere(function($q){
							return $q->where(self::EXAM_TYPE_ID, '!=', '1')
								->where(self::EXAM_REGISTRATION_STATUS, '=', '1')
								->where('examinations.function_status', '=', '1')
								->where('examinations.contract_status', '=', '1')
								->where(self::EXAM_SPB_STATUS, '=', '1')
								->where(self::EXAM_PAYMENT_STATUS, '=', '1')
								->where('examinations.spk_status', '=', '1')
								->where('examinations.examination_status', '=', '1')
								->where('examinations.resume_status', '=', '1')
								;
							});
					});
				$data2 = $queries->orderBy(self::COMPANIES_NAME)
                ->take(3)
				->distinct()
                ->get();
				
		 return array_merge($data1,$data2);
       
	}
}
