<?php

namespace App\Services;

use Auth;
use App\Examination;
use App\User;
use App\Logs;
use App\LogsAdministrator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\DB;

class ExaminationService
{

    private const CREATED_AT = 'created_at';
    private const COMPANY = 'company';
	private const EXAMINATION_TYPE = 'examinationType';
	private const EXAMINATION_LAB = 'examinationLab';
	private const MEDIA = 'media';
    private const DEVICE = 'device';
    private const REGISTRATION_STATUS = 'registration_status';
	private const FUNCTION_STATUS = 'function_status';
	private const CONTRACT_STATUS = 'contract_status';
	private const SPB_STATUS = 'spb_status';
	private const PAYMENT_STATUS = 'payment_status';
	private const SPK_STATUS = 'spk_status';
	private const EXAMINATION_STATUS = 'examination_status';
	private const RESUME_STATUS = 'resume_status';
	private const QA_STATUS = 'qa_status';
	private const CERTIFICATE_STATUS = 'certificate_status';
    private const LOCATION = 'location';
    private const STATUS = 'status';
	private const BEFORE_DATE = 'before_date';
	private const SPK_DATE = 'spk_date';
	private const AFTER_DATE = 'after_date';
    
    public function requestQuery($request, $search, $type, $status, $before, $after){
		$query = Examination::whereNotNull(self::CREATED_AT)
							->with('user')
							->with(self::COMPANY)
							->with(self::EXAMINATION_TYPE)
							->with(self::EXAMINATION_LAB)
							->with(self::MEDIA)
							->with(self::DEVICE);
		$query->where(function($q){
			return $q->where(self::REGISTRATION_STATUS, '!=', '1')
				->orWhere(self::FUNCTION_STATUS, '!=', '1')
				->orWhere(self::CONTRACT_STATUS, '!=', '1')
				->orWhere(self::SPB_STATUS, '!=', '1')
				->orWhere(self::PAYMENT_STATUS, '!=', '1')
				->orWhere(self::SPK_STATUS, '!=', '1')
				->orWhere(self::EXAMINATION_STATUS, '!=', '1')
				->orWhere(self::RESUME_STATUS, '!=', '1')
				->orWhere(self::QA_STATUS, '!=', '1')
				->orWhere(self::CERTIFICATE_STATUS, '!=', '1')
				->orWhere(self::LOCATION, '!=', '1')
				;
			})
			;
		if ($search != null){
			$query->where(function($qry) use($search){
				$qry->whereHas(self::DEVICE, function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					})
				->orWhereHas(self::COMPANY, function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					})
				->orWhereHas(self::EXAMINATION_LAB, function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					})
				->orWhere('function_test_NO', 'like', '%'.strtolower($search).'%')
				->orWhere('spk_code', 'like', '%'.strtolower($search).'%');
			});
		}

		if ($request->has('type')){
			$type = $request->get('type');
			if($request->input('type') != 'all'){
				$query->where('examination_type_id', $request->get('type'));
			}
		}

		if ($request->has(self::COMPANY)){
			$query->whereHas(self::COMPANY, function ($q) use ($request){
				return $q->where('name', 'like', '%'.strtolower($request->get(self::COMPANY)).'%');
			});
		}

		if ($request->has(self::DEVICE)){
			$query->whereHas(self::DEVICE, function ($q) use ($request){
				return $q->where('name', 'like', '%'.strtolower($request->get(self::DEVICE)).'%');
			});
		}

		if ($request->has(self::STATUS)){
			switch ($request->get(self::STATUS)) {
				case 1:
					$query->where(self::REGISTRATION_STATUS, '!=', 1);
					$status = 1;
					break;
				case 2:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '!=', 1);
					$status = 2;
					break;
				case 3:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '!=', 1);
					$status = 3;
					break;
				case 4:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '!=', 1);
					$status = 4;
					break;
				case 5:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '=', 1);
					$query->where(self::PAYMENT_STATUS, '!=', 1);
					$status = 5;
					break;
				case 6:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '=', 1);
					$query->where(self::PAYMENT_STATUS, '=', 1);
					$query->where(self::SPK_STATUS, '!=', 1);
					$status = 6;
					break;
				case 7:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '=', 1);
					$query->where(self::PAYMENT_STATUS, '=', 1);
					$query->where(self::SPK_STATUS, '=', 1);
					$query->where(self::EXAMINATION_STATUS, '!=', 1);
					$status = 7;
					break;
				case 8:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '=', 1);
					$query->where(self::PAYMENT_STATUS, '=', 1);
					$query->where(self::SPK_STATUS, '=', 1);
					$query->where(self::EXAMINATION_STATUS, '=', 1);
					$query->where(self::RESUME_STATUS, '!=', 1);
					$status = 8;
					break;
				case 9:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '=', 1);
					$query->where(self::PAYMENT_STATUS, '=', 1);
					$query->where(self::SPK_STATUS, '=', 1);
					$query->where(self::EXAMINATION_STATUS, '=', 1);
					$query->where(self::RESUME_STATUS, '=', 1);
					$query->where(self::QA_STATUS, '!=', 1);
					$status = 9;
					break;
				case 10:
					$query->where(self::REGISTRATION_STATUS, '=', 1);
					$query->where(self::FUNCTION_STATUS, '=', 1);
					$query->where(self::CONTRACT_STATUS, '=', 1);
					$query->where(self::SPB_STATUS, '=', 1);
					$query->where(self::PAYMENT_STATUS, '=', 1);
					$query->where(self::SPK_STATUS, '=', 1);
					$query->where(self::EXAMINATION_STATUS, '=', 1);
					$query->where(self::RESUME_STATUS, '=', 1);
					$query->where(self::QA_STATUS, '=', 1);
					$query->where(self::CERTIFICATE_STATUS, '!=', 1);
					$status = 10;
					break;
				
				default:
					$status = 'all';
					break;
			}
		}
		
		if ($request->has(self::BEFORE_DATE)){
			$query->where(self::SPK_DATE, '<=', $request->get(self::BEFORE_DATE));
			$before = $request->get(self::BEFORE_DATE);
		}

		if ($request->has(self::AFTER_DATE)){
			$query->where(self::SPK_DATE, '>=', $request->get(self::AFTER_DATE));
			$after = $request->get(self::AFTER_DATE);
		}

		return array($query, $search, $type, $status, $before, $after);
	}

    public function getDetailDataFromOTR($client, $id = '', $spk_code = '')
    {
        $query_lab = "SELECT action_date FROM equipment_histories WHERE location = 3 AND examination_id = '".$id."' ORDER BY created_at DESC LIMIT 1";
		$data_lab = DB::select($query_lab);
		
		$query_gudang = "SELECT action_date FROM equipment_histories WHERE location = 2 AND examination_id = '".$id."' ORDER BY created_at DESC LIMIT 2";
		$data_gudang = DB::select($query_gudang);
		
		$res_exam_schedule = $client->get('spk/searchData?spkNumber='.$spk_code)->getBody();
		$exam_schedule = json_decode($res_exam_schedule);
		
		$res_exam_approve_date = $client->get('spk/searchHistoryData?spkNumber='.$spk_code)->getBody();
        $exam_approve_date = json_decode($res_exam_approve_date);
        
        return array($data_lab, $data_gudang, $exam_schedule, $exam_approve_date);
    }

}