<?php

namespace App\Http\Controllers\v1;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\Device;
use App\Equipment;
use App\EquipmentHistory;
use App\Examination;
use App\ExaminationType;
use App\ExaminationHistory;
use App\ExaminationAttach;
use App\AdminRole;
use App\TbMSPK;
use App\TbHSPK;

use App\User;
use Mail;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use App\Events\Notification;
use App\NotificationTable;
 
class ExaminationAPIController extends AppBaseController
{
    
    public function getExaminationData(Request $param)
    {
		$param = (object) $param->all();
		
		$whereID = array();
		 
		
		if(isset($param->id)){
			$whereID["examinations.id"] = $param->id;
		}  

		$select = array(
			"examinations.id",
			"examinations.is_loc_test",
			"examinations.device_id",
			"examinations.created_at as registered_date", "examination_types.name as testing_type",
			"examination_labs.name as lab",
			"examination_labs.lab_code",
			"users.name as applicant_name","users.address as applicant_address","users.phone_number as applicant_phone",
			"users.fax as applicant_facsimile,users.email as applicant_email",
			"companies.name as company_name","companies.address as company_address", "companies.phone_number as company_phone",
			"companies.fax as company_facsimile","companies.email as company_email",
			"devices.serial_number as device_serial_number","devices.name as device_name","devices.mark as device_brand_name","devices.model as device_model",
			"devices.capacity as device_capacity", "devices.manufactured_by","devices.test_reference",
			"CASE 
			  WHEN registration_status != 1 THEN 'Registrasi'
			  WHEN registration_status = 1 AND function_status != 1 THEN 'Uji Fungsi'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status != 1 THEN 'Tinjauan Kontrak'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status != 1 THEN 'SPB'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status != 1 THEN 'Pembayaran'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status != 1 THEN 'Pembuatan SPK'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status != 1 THEN 'Pelaksanaan Uji'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1 AND resume_status != 1 THEN 'Laporan Uji'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1 AND resume_status = 1 AND qa_status != 1 THEN 'Sidang QA'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1 AND qa_status = 1 AND certificate_status != 1 THEN 'Penerbitan Sertifikat'
			  ELSE NULL
			  END as 'step_progress'
			",
			"examinations.spb_number",
			"examinations.function_test_NO as no_form_uji",
			"examinations.spb_date",
			"examinations.spk_code",
			"examinations.spk_date",
			"(
				SELECT
					action_date AS tgl_masuk_gudang
				FROM
					equipment_histories
				WHERE
					examination_id = examinations.id
				AND
					location = 2
				ORDER BY
					created_at DESC
				LIMIT 1
			) AS tgl_masuk_barang",
			"examinations.is_spk_created"
		);
		$result = Examination::selectRaw(implode(",", $select))  
				->join("examination_types","examinations.examination_type_id","=","examination_types.id")
				->join("devices","devices.id","=","examinations.device_id")
				->join("users","users.id","=","examinations.created_by")
				->join("companies","companies.id","=","users.company_id")  
				->join("examination_labs","examinations.examination_lab_id","=","examination_labs.id")  
				->where($whereID);
				
		if(isset($param->find)){
			$result->where(function($q) use ($param){
				return $q->where("examination_types.name", "LIKE", '%'.$param->find .'%')
							->orWhere("users.name", "LIKE", '%'.$param->find .'%')
							->orWhere("users.address", "LIKE", '%'.$param->find .'%')
							->orWhere("users.phone_number", "LIKE", '%'.$param->find .'%')
							->orWhere("users.fax", "LIKE", '%'.$param->find .'%')
							->orWhere("users.email", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.name", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.address", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.phone_number", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.fax", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.email", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.serial_number", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.name", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.mark", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.model", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.capacity", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.manufactured_by", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.test_reference", "LIKE", '%'.$param->find .'%')
							->orWhere("examinations.spk_code", "LIKE", '%'.$param->find .'%')
							->orWhere("examination_labs.name", "LIKE", '%'.$param->find .'%')
					;
				});
				switch ($param->find) {
					case "Registrasi": 
						$result->orWhere("examinations.registration_status", "!=", '1')
							;
						break;
					case "Uji Fungsi": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "!=", '1')
							;
						});
						break;
					case "Tinjauan Kontrak":
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "!=", '1')
							;
						});
						break;
					case "SPB": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "!=", '1')
							;
						});
						break;
					case "Pembayaran": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "=", '1')
								->where("examinations.payment_status", "!=", '1')
							;
						});
						break;
					case "Pembuatan SPK": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "=", '1')
								->where("examinations.payment_status", "=", '1')
								->where("examinations.spk_status", "!=", '1')
							;
						});
						break;
					case "Pelaksanaan Uji": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "=", '1')
								->where("examinations.payment_status", "=", '1')
								->where("examinations.spk_status", "=", '1')
								->where("examinations.examination_status", "!=", '1')
							;
						});
						break;
					case "Laporan Uji": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "=", '1')
								->where("examinations.payment_status", "=", '1')
								->where("examinations.spk_status", "=", '1')
								->where("examinations.examination_status", "=", '1')
								->where("examinations.resume_status", "!=", '1')
							;
						});
						break;
					case "Sidang QA": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "=", '1')
								->where("examinations.payment_status", "=", '1')
								->where("examinations.spk_status", "=", '1')
								->where("examinations.examination_status", "=", '1')
								->where("examinations.resume_status", "=", '1')
								->where("examinations.qa_status", "!=", '1')
							;
						});
						break;
					case "Penerbitan Sertifikat": 
						$result->orWhere(function($q){
						return $q->where("examinations.registration_status", "=", '1')
								->where("examinations.function_status", "=", '1')
								->where("examinations.contract_status", "=", '1')
								->where("examinations.spb_status", "=", '1')
								->where("examinations.payment_status", "=", '1')
								->where("examinations.spk_status", "=", '1')
								->where("examinations.examination_status", "=", '1')
								->where("examinations.resume_status", "=", '1')
								->where("examinations.qa_status", "=", '1')
								->where("examinations.certificate_status", "!=", '1')
							;
						});
						break;
				} 
			// $rawRangeDate ="date_format(examinations.created_at,'%Y-%m-%d') = '".$param->find."'";
			// $result = $result->orWhere(\DB::raw($rawRangeDate), 1); 
			// $rawRangeSPKDate ="date_format(examinations.spk_date,'%Y-%m-%d') = '".$param->find."'";
			// $result = $result->orWhere(\DB::raw($rawRangeSPKDate), 1); 
		}else{
  
			if(isset($param->status)){
				switch ($param->status) {
					case 1: 
						$result = $result->where("examinations.registration_status", "!=", '1');
						break;
					case 2: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "!=", '1'); 
						break;
					case 3: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "!=", '1'); 
						break;
					case 4: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "!=", '1'); 
						break;
					case 5: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "!=", '1'); 
						break;
					case 6: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "!=", '1');
						break;
					case 7: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1'); 
						$result = $result->where("examinations.examination_status", "!=", '1');
						break;
					case 8: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "!=", '1');
						break;
					case 9: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "!=", '1');
						break;
					case 10: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "=", '1'); 
						$result = $result->where("examinations.certificate_status", "!=", '1'); 
						break;
					default:
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "=", '1'); 
						$result = $result->where("examinations.certificate_status", "=", '1');
						break;
				} 
			}

			if(isset($param->date)){
				$rawRangeDate ="date_format(examinations.created_at,'%Y-%m-%d') = '".$param->date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); 
			}

			if(isset($param->registered_date_from)){
				$rawRangeDate ="date_format(examinations.created_at,'%Y-%m-%d') >= '".$param->date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); 
			}

			if(isset($param->registered_date_thru)){
				$rawRangeDate ="date_format(examinations.created_at,'%Y-%m-%d') <= '".$param->date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); 
			}

			if(isset($param->applicant_name)){
				$result = $result->where("users.name", "LIKE", '%'.$param->applicant_name .'%');
			}

			 
			if(isset($param->company_name)){
				$result = $result->where("companies.name", "LIKE", '%'.$param->company_name.'%');
			}
	 
			if(isset($param->device_name)){
				$result = $result->where("devices.name", "LIKE", '%'.$param->device_name .'%');
			}

			if(isset($param->testing_type)){
				$result = $result->where("examinations.examination_type_id", "=", $param->testing_type);
			}
			
			if(isset($param->lab)){
				$result = $result->where("examination_labs.name", "LIKE", '%'.$param->lab .'%')
								->orWhere("examination_labs.lab_code", "LIKE", '%'.$param->lab .'%');
			}
			
			if(isset($param->spk_code)){
				if($param->spk_code == ''){
					$result = $result->where("examinations.payment_status", "=", 1);
				}else{
					$result = $result->where("examinations.spk_code", "LIKE", '%'.$param->spk_code .'%');
				}
			}
			
			if(isset($param->spk_date)){
				$rawRangeDate ="date_format(examinations.spk_date,'%Y-%m-%d') = '".$param->spk_date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); 
			}
			
			if(isset($param->is_spk_created)){
				$result = $result->where("examinations.is_spk_created", "=", $param->is_spk_created);
			}

			if(isset($param->is_loc_test)){
				$result = $result->where("examinations.is_loc_test", "=", $param->is_loc_test);
			}

		}
		
		if(isset($param->limit)){
			$result = $result->limit($param->limit);
			if(isset($param->offset)){
				$result = $result->offset($param->offset);
			}
		}
		// $result = $result->toSql();
		// print_r($result);exit;
		$result = $result->get()->toArray();
		
	 
		if(!is_array($result) || empty($result)){
			return $this->sendError('Examination Data Not Found');
		}
		return $this->sendResponse($result, 'Examination Data Found');
    }

    public function getExaminationByApplicants(Request $param)
    {
		$param = (object) $param->all();
		
		$whereID = array();  

		$select = array(
			"examinations.created_at as registered_date", "examination_types.name as testing_type",
			"users.name as applicant_name","users.address as applicant_address","users.phone_number as applicant_phone",
			"users.fax as applicant_facsimile,users.email as applicant_email",
			"companies.name as company_name","companies.address as company_address", "companies.phone_number as company_phone",
			"companies.fax as company_facsimile","companies.email as company_email",
			"devices.name as device_name","devices.mark as device_brand_name","devices.model as device_model",
			"devices.capacity as device_capacity", "devices.manufactured_by","devices.test_reference"
		);
		$result = Examination::selectRaw(implode(",", $select))  
				->join("examination_types","examinations.examination_type_id","=","examination_types.id")
				->join("devices","devices.id","=","examinations.device_id")
				->join("users","users.id","=","examinations.created_by")
				->join("companies","companies.id","=","users.company_id")  
				->where($whereID);

		if(isset($param->applicant_id) && isset($param->applicant_name)){
			$result = $result->where("examinations.id", "=", $param->applicant_id);
			$result = $result->where("users.name", "LIKE", '%'.$param->applicant_name .'%');
		}else{
			return $this->sendError('ID Applicant Or Applicant Name Required');
		}
		 
		$result = $result->get()->toArray();
		
	 
		if(!is_array($result) || empty($result)){
			return $this->sendError('Examination Data Not Found');
		}
		return $this->sendResponse($result, 'Examination Data Found');
    }


    public function getExaminationByCompany(Request $param)
    {
		$param = (object) $param->all();
		
		$whereID = array();  

		$select = array(
			"examinations.created_at as registered_date", "examination_types.name as testing_type",
			"users.name as applicant_name","users.address as applicant_address","users.phone_number as applicant_phone",
			"users.fax as applicant_facsimile,users.email as applicant_email",
			"companies.name as company_name","companies.address as company_address", "companies.phone_number as company_phone",
			"companies.fax as company_facsimile","companies.email as company_email",
			"devices.name as device_name","devices.mark as device_brand_name","devices.model as device_model",
			"devices.capacity as device_capacity", "devices.manufactured_by","devices.test_reference"
		);
		$result = Examination::selectRaw(implode(",", $select))  
				->join("examination_types","examinations.examination_type_id","=","examination_types.id")
				->join("devices","devices.id","=","examinations.device_id")
				->join("users","users.id","=","examinations.created_by")
				->join("companies","companies.id","=","users.company_id")  
				->where($whereID);

		if(isset($param->company_id) && isset($param->company_name)){
			$result = $result->where("examinations.company_id", "=", $param->company_id);
			$result = $result->where("companies.name", "LIKE", '%'.$param->company_name .'%');
		}else{
			return $this->sendError('ID Company Or Company Name Required');
		}
		 
		$result = $result->get()->toArray();
		
	 
		if(!is_array($result) || empty($result)){
			return $this->sendError('Examination Data Not Found');
		}
		return $this->sendResponse($result, 'Examination Data Found');
    }
	
	 public function getExaminationByDevice(Request $param)
    {
		$param = (object) $param->all();
		
		$whereID = array();  

		$select = array(
			"examinations.created_at as registered_date", "examination_types.name as testing_type",
			"users.name as applicant_name","users.address as applicant_address","users.phone_number as applicant_phone",
			"users.fax as applicant_facsimile,users.email as applicant_email",
			"companies.name as company_name","companies.address as company_address", "companies.phone_number as company_phone",
			"companies.fax as company_facsimile","companies.email as company_email",
			"devices.name as device_name","devices.mark as device_brand_name","devices.model as device_model",
			"devices.capacity as device_capacity", "devices.manufactured_by","devices.test_reference"
		);
		$result = Examination::selectRaw(implode(",", $select))  
				->join("examination_types","examinations.examination_type_id","=","examination_types.id")
				->join("devices","devices.id","=","examinations.device_id")
				->join("users","users.id","=","examinations.created_by")
				->join("companies","companies.id","=","users.company_id")  
				->where($whereID);

		if(isset($param->device_id) && isset($param->device_name)){
			$result = $result->where("examinations.device_id", "=", $param->device_id);
			$result = $result->where("devices.name", "LIKE", '%'.$param->device_name .'%');
		}else{
			return $this->sendError('ID Devices Or Devices Name Required');
		}
		 
		$result = $result->get()->toArray();
		
	 
		if(!is_array($result) || empty($result)){
			return $this->sendError('Examination Data Not Found');
		}
		return $this->sendResponse($result, 'Examination Data Found');
    }

	public function getSPk(Request $param)
    {
		$param = (object) $param->all();
		
		$condition = array();
		 
		
		if(isset($param->id)){
			$condition["examinations.id"] = $param->id;
		}  

		$select = array(
			"examinations.id",
			"spk_code", "spk_date",
			"examination_labs.name as lab",
			"examination_labs.lab_code",
			"CASE 
			  WHEN registration_status != 1 THEN 'Registrasi'
			  WHEN registration_status = 1 AND function_status != 1 THEN 'Uji Fungsi'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status != 1 THEN 'Tinjauan Kontrak'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status != 1 THEN 'SPB'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status != 1 THEN 'Pembayaran'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status != 1 THEN 'Pembuatan SPK'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status != 1 THEN 'Pelaksanaan Uji'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1 AND resume_status != 1 THEN 'Laporan Uji'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1 AND resume_status = 1 AND qa_status != 1 THEN 'Sidang QA'
			  WHEN registration_status = 1 AND function_status = 1 AND contract_status = 1 AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1 AND qa_status = 1 AND certificate_status != 1 THEN 'Penerbitan Sertifikat'
			  ELSE NULL
			  END as 'step_progress'
			",
			"devices.name as device_name"
		);
		$result = Examination::selectRaw(implode(",", $select)) 
				->join("devices","devices.id","=","examinations.device_id")
				->join("examination_labs","examinations.examination_lab_id","=","examination_labs.id")  
				->whereNotNull("spk_code")
				->where($condition); 

		if(isset($param->find)){
			$result->where(function($q) use ($param){
				return $q->where("examinations.spk_code", "LIKE", '%'.$param->find .'%')
							->orWhere("devices.name", "LIKE", '%'.$param->find .'%')
							->orWhere("examination_labs.name", "LIKE", '%'.$param->find .'%')
					;
				});
			// $rawRangeDate ="date_format(examinations.spk_date,'%Y-%m-%d') = '".$param->find."'";
			// $result = $result->orWhere(\DB::raw($rawRangeDate), 1); 
		}else{
			if(isset($param->code)){
				$result = $result->where("examinations.spk_code", "LIKE", '%'.$param->code .'%');
			}
			if(isset($param->date)){
				$rawRangeDate ="date_format(examinations.spk_date,'%Y-%m-%d') = '".$param->date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); 
			}
			if(isset($param->lab)){
				$result = $result->where("examination_labs.name", "LIKE", '%'.$param->lab .'%')
								->orWhere("examination_labs.lab_code", "LIKE", '%'.$param->lab .'%');
			}
			if(isset($param->status)){
				switch ($param->status) {
					case 1: 
						$result = $result->where("examinations.registration_status", "!=", '1');
						break;
					case 2: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "!=", '1'); 
						break;
					case 3: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "!=", '1'); 
						break;
					case 4: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "!=", '1'); 
						break;
					case 5: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "!=", '1'); 
						break;
					case 6: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "!=", '1');
						break;
					case 7: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1'); 
						$result = $result->where("examinations.examination_status", "!=", '1');
						break;
					case 8: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "!=", '1');
						break;
					case 9: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "!=", '1');
						break;
					case 10: 
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "=", '1'); 
						$result = $result->where("examinations.certificate_status", "!=", '1'); 
						break;
					default:
						$result = $result->where("examinations.registration_status", "=", '1');
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "=", '1'); 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "=", '1');
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "=", '1'); 
						$result = $result->where("examinations.certificate_status", "=", '1');
						break;
				} 
			}
			if(isset($param->device_name)){
				$result = $result->where("devices.name", "LIKE", '%'.$param->device_name .'%');
			}
		}
		if(isset($param->limit)){
			$result = $result->limit($param->limit);
			if(isset($param->offset)){
				$result = $result->offset($param->offset);
			}
		}

		$result = $result->get()->toArray();
		
	 
		if(!is_array($result) || empty($result)){
			return $this->sendError('SPK Data Not Found');
		}
		return $this->sendResponse($result, 'SPK Data Found');
    }
	
	public function getFunctionTest(Request $param)
    {
		$param = (object) $param->all();
		
		$condition = array();
		 
		
		if(isset($param->id)){
			$condition["examinations.id"] = $param->id;
		}  

		$select = array(
			"examinations.id","examinations.cust_test_date","examinations.deal_test_date",
			"examinations.urel_test_date as cust_test_date2","examinations.function_date as deal_test_date2",
			"examinations.function_test_TE as function_result","examinations.function_test_PIC","examinations.function_test_reason",
			"examinations.function_test_date_approval",
			"examinations.catatan",
			"examinations.location",
			"examinations.function_test_status_detail as status",
			"examination_labs.name as lab",
			"examination_labs.lab_code",
			"companies.name as company_name",
			"companies.address as company_address",
			"companies.phone_number as phone_number",
			"companies.fax as facsimile",
			"devices.name as device_name",
			"devices.mark as device_brand_name",
			"devices.model as device_model",
			"devices.capacity as device_capacity",
			"devices.serial_number as device_serial_number",
			"devices.manufactured_by",
			"devices.test_reference"
		);
		$result = Examination::selectRaw(implode(",", $select)) 
				->join("devices","devices.id","=","examinations.device_id")
				->join("companies","companies.id","=","examinations.company_id")  
				->join("examination_labs","examination_labs.id","=","examinations.examination_lab_id")  
				->where("examinations.registration_status", "!=", 0)
				->where("examinations.function_status", "!=" ,1)
				->whereNotNull("examinations.cust_test_date")
				->where($condition); 
		
		if(isset($param->find)){
			$result->where(function($q) use ($param){
				return $q->where("companies.name", "LIKE", '%'.$param->find .'%')
						->orWhere("companies.address", "LIKE", '%'.$param->find .'%')
						->orWhere("companies.phone_number", "LIKE", '%'.$param->find .'%')
						->orWhere("companies.fax", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.name", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.mark", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.model", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.capacity", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.serial_number", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.manufactured_by", "LIKE", '%'.$param->find .'%')
						->orWhere("devices.test_reference", "LIKE", '%'.$param->find .'%')
						->orWhere("examinations.function_test_status_detail", "LIKE", '%'.$param->find .'%')
					;
				});
		}else{
			if(isset($param->company_name)){
				$result = $result->where("companies.name", "LIKE", '%'.$param->company_name .'%');
			}
			if(isset($param->device_name)){
				$result = $result->where("devices.name", "LIKE", '%'.$param->device_name .'%');
			}
			if(isset($param->deal_test_date)){
				/*$rawRangeDate ="date_format(examinations.deal_test_date,'%Y-%m-%d') = '".$param->deal_test_date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); */
				$result = $result->where('function_test_date_approval', 1)
				->where(DB::Raw('IFNULL( function_date, deal_test_date )'), '=', $param->deal_test_date);
			}
			if(isset($param->function_result)){
				$result = $result->where("examinations.function_test_TE", "=", $param->function_result);
			}
			if(isset($param->location)){
				$result = $result->where("examinations.location", "=", $param->location);
			}
			if(isset($param->status)){
				$result = $result->where("examinations.function_test_status_detail", "LIKE", '%'.$param->status .'%');
			}
		}

		if(isset($param->date_from)){
			$result = $result->where('function_test_date_approval', 1)
			->where(DB::Raw('IFNULL( function_date, deal_test_date )'), '>=', $param->date_from);
		}

		if(isset($param->date_to)){
			$result = $result->where('function_test_date_approval', 1)
			->where(DB::Raw('IFNULL( function_date, deal_test_date )'), '<=', $param->date_to);
		}

		if(isset($param->lab_code)){
			$result = $result->where("examination_labs.lab_code", "LIKE", '%'.$param->lab_code .'%');
		}

		if(isset($param->limit)){
			$result = $result->limit($param->limit);
			if(isset($param->offset)){
				$result = $result->offset($param->offset);
			}
		}
		
		$result = $result->orderBy('examinations.updated_at', 'desc')->get()->toArray();
	
		if(!is_array($result) || empty($result)){
			return $this->sendError('Function Data Not Found');
		}
		return $this->sendResponse($result, 'Function Data Found');
    }
	
	public function getExaminationHistory(Request $param)
    {
		$param = (object) $param->all();
		
		$condition = array();
		 
		
		if(isset($param->id)){
			$condition["examination_histories.examination_id"] = $param->id;
		}  

		$select = array(
			"examination_histories.examination_id",
			"examination_histories.tahap as step",
			"examination_histories.status",
			"examination_histories.keterangan as note",
			"examination_histories.created_at as time_action",
			"users.name"
		);
		$result = ExaminationHistory::selectRaw(implode(",", $select)) 
				->join("users","users.id","=","examination_histories.created_by")
				->where("examination_histories.status", "!=", 0)
				->where($condition)
				->orderBy("examination_id"); 
		
		if(isset($param->limit)){
			$result = $result->limit($param->limit);
			if(isset($param->offset)){
				$result = $result->offset($param->offset);
			}
		}
		
		$result = $result->get()->toArray();
	
		if(!is_array($result) || empty($result)){
			return $this->sendError('History Not Found');
		}
		return $this->sendResponse($result, 'History Found');
    }
	
	public function updateFunctionDate(Request $param)
    {
    	$param = (object) $param->all();
		$currentUser = Auth::user();
		if($currentUser){
			$id_user = $currentUser->id;
		}else{
			$id_user = 1;
		}

    	if(!empty($param->id) && !empty($param->function_test_date)&& !empty($param->function_test_pic)&& !empty($param->reason)&& !empty($param->date_type)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				if($param->date_type == 1){
					$examinations->deal_test_date = $param->function_test_date;
				}else{
					$examinations->function_date = $param->function_test_date;
				}
				$examinations->function_test_date_approval = $param->is_agree;
				$examinations->function_test_PIC = $param->function_test_pic;
				$examinations->function_test_reason = $param->reason;
				if($param->is_agree == 1){
					$examinations->function_test_status_detail = 'Tanggal uji fungsi fix';
					if($examinations->save()){ 
					 
						$exam_hist = new ExaminationHistory;
						$exam_hist->examination_id = $param->id;
						$exam_hist->date_action = date('Y-m-d H:i:s');
						$exam_hist->tahap = 'Menyetujui Tanggal Uji';
						$exam_hist->status = 1;
						$exam_hist->keterangan = $param->function_test_date.' dari Test Engineer ('.$param->reason.')';
						$exam_hist->created_by = $examinations->created_by;
						$exam_hist->created_at = date('Y-m-d H:i:s');
						$exam_hist->save();

						$admins = AdminRole::where('function_status',1)->get()->toArray();
						foreach ($admins as $admin) {  
							$data= array( 
								"from"=>$id_user,
								"to"=>$admin['user_id'],
								"message"=>"Test Engineer menyetujui Tanggal Uji Fungsi",
								"url"=>"examination/".$param->id."/edit",
								"is_read"=>0,
								"created_at"=>date("Y-m-d H:i:s"),
								"updated_at"=>date("Y-m-d H:i:s")
							);
						  	$notification = new NotificationTable();
							$notification->id = Uuid::uuid4();
						  	$notification->from = $data['from'];
						  	$notification->to = $data['to'];
						  	$notification->message = $data['message'];
						  	$notification->url = $data['url'];
						  	$notification->is_read = $data['is_read'];
						  	$notification->created_at = $data['created_at'];
						  	$notification->updated_at = $data['updated_at'];
						  	$notification->save();

						  $data['id'] = $notification->id;
						  event(new Notification($data));  
						}
					  	$data= array( 
							"from"=>"admin",
							"to"=>$examinations->created_by,
							"message"=>"Test Engineer menyetujui Tanggal Uji Fungsi",
							"url"=>"pengujian",
							"is_read"=>0,
							"created_at"=>date("Y-m-d H:i:s"),
							"updated_at"=>date("Y-m-d H:i:s")
					 	);
						$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
						$notification->from = $data['from'];
						$notification->to = $data['to'];
						$notification->message = $data['message'];
						$notification->url = $data['url'];
						$notification->is_read = $data['is_read'];
						$notification->created_at = $data['created_at'];
						$notification->updated_at = $data['updated_at'];
						$notification->save();

						$data['id'] = $notification->id;
						event(new Notification($data));
						return $this->sendResponse($examinations, 'Function Date Found');
					}else{
						return $this->sendError('Failed to Update Function Date ');
					}
				}
				else if($param->is_agree == 0 || $param->date_type == 2){
					$examinations->function_test_status_detail = 'TE menjadwal ulang tanggal';
					if($examinations->save()){
						
						 $data= array( 
						"from"=>$id_user,
						"to"=>"admin",
						"message"=>"Test Engineer memberikan Tanggal Uji Fungsi",
						"url"=>"examination/".$param->id."/edit",
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					 );
					 
					$exam_hist = new ExaminationHistory;
					$exam_hist->examination_id = $param->id;
					$exam_hist->date_action = date('Y-m-d H:i:s');
					$exam_hist->tahap = 'Update Tanggal Uji';
					$exam_hist->status = 1;
					$exam_hist->keterangan = $param->function_test_date.' dari Test Engineer ('.$param->reason.')';
					$exam_hist->created_by = $examinations->created_by;
					$exam_hist->created_at = date('Y-m-d H:i:s');
					$exam_hist->save();
					 
					  $notification = new NotificationTable();
	$notification->id = Uuid::uuid4();
					  $notification->from = $data['from'];
					  $notification->to = $data['to'];
					  $notification->message = $data['message'];
					  $notification->url = $data['url'];
					  $notification->is_read = $data['is_read'];
					  $notification->created_at = $data['created_at'];
					  $notification->updated_at = $data['updated_at'];
					  $notification->save();

					  $data['id'] = $notification->id;
					  event(new Notification($data));  
					  
					  $data= array( 
						"from"=>"admin",
						"to"=>$examinations->created_by,
						"message"=>"Test Engineer mengajukan Tanggal Uji Fungsi",
						"url"=>"pengujian",
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					 );
					  $notification = new NotificationTable();
	$notification->id = Uuid::uuid4();
					  $notification->from = $data['from'];
					  $notification->to = $data['to'];
					  $notification->message = $data['message'];
					  $notification->url = $data['url'];
					  $notification->is_read = $data['is_read'];
					  $notification->created_at = $data['created_at'];
					  $notification->updated_at = $data['updated_at'];
					  $notification->save();

					 $data['id'] = $notification->id;
					  event(new Notification($data));

						return $this->sendResponse($examinations, 'Function Date Found');
					}else{
						return $this->sendError('Failed to Update Function Date ');
					}
				}
    		}else{
    			return $this->sendError('Failed to Update Function Date');
    		}
    	}else{
    		return $this->sendError('ID Examination or Date or PIC or Date Type or Approval Status Is Required');
    	}
    }
	
	public function updateEquipLoc(Request $param)
    {
    	$param = (object) $param->all();
    	$currentUser = Auth::user();
		if($currentUser){
			$id_user = $currentUser->id;
		}else{
			$id_user = 1;
		}
    	if(!empty($param->id) && !empty($param->date) && !empty($param->location)){
			$equip_hist = new EquipmentHistory;
			$equip_hist->id = Uuid::uuid4();
			$equip_hist->location = $param->location;
			$equip_hist->created_by = 1;
			$equip_hist->updated_by = 1;
			$equip_hist->created_at = date("Y-m-d H:i:s");
			$equip_hist->updated_at = date("Y-m-d H:i:s");
			$equip_hist->examination_id = $param->id;
			$equip_hist->action_date = $param->date;

			if($equip_hist->save()){
				if($param->location == 2){$examination_status = 1;}else{$examination_status = 0;}
				$examination = Examination::where('id', $param->id)->first();
				$examination->examination_status = $examination_status;
				$examination->location = $param->location;
				$examination->save();
				
				$equip = Equipment::where("examination_id",$param->id)->first();
				$equip->location = $param->location;
				$equip->save();
				if($param->location == 3){
					$admins = AdminRole::where('examination_status',1)->get()->toArray();
					foreach ($admins as $admin) {  
				      	$data= array( 
		                "from"=>$id_user,
		                "to"=>$admin['user_id'],
		                "message"=>"Test Engineer mengambil barang dari Gudang",
		                "url"=>"examination/".$param->id.'/edit',
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
		                );
					  	$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
				      	$notification->from = $data['from'];
				      	$notification->to = $data['to'];
				      	$notification->message = $data['message'];
				      	$notification->url = $data['url'];
				      	$notification->is_read = $data['is_read'];
				      	$notification->created_at = $data['created_at'];
				      	$notification->updated_at = $data['updated_at'];
				      	$notification->save();
					}
	                $data['id'] = $notification->id;
			      	event(new Notification($data));
				}else if($param->location == 2){
					$admins = AdminRole::where('examination_status',1)->get()->toArray();
					foreach ($admins as $admin) {  
						$data= array( 
			                "from"=>$id_user,
			                "to"=>$admin['user_id'],
			                "message"=>"Test Engineer mengembalikan barang ke Gudang",
			                "url"=>"examination/".$param->id.'/edit',
			                "is_read"=>0,
			                "created_at"=>date("Y-m-d H:i:s"),
			                "updated_at"=>date("Y-m-d H:i:s")
		                );
					  	$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
				      	$notification->from = $data['from'];
				      	$notification->to = $data['to'];
				      	$notification->message = $data['message'];
				      	$notification->url = $data['url'];
				      	$notification->is_read = $data['is_read'];
				      	$notification->created_at = $data['created_at'];
				      	$notification->updated_at = $data['updated_at'];
				      	$notification->save();
	 					$data['id'] = $notification->id; 
		                event(new Notification($data));
	            	}
				}
				
		        event(new Notification($data));
				return $this->sendResponse($equip_hist, 'History Found');
			}else{
				return $this->sendError('Failed to Input History');
			}
    	}else{
    		return $this->sendError('ID Examination or Date or Location Is Required');
    	}
    }
	
	public function updateDeviceTE(Request $param)
    {
    	$param = (object) $param->all();
    	$currentUser = Auth::user();
		if($currentUser){
			$id_user = $currentUser->id;
		}else{
			$id_user = 1;
		}
    	if(!empty($param->id) && (!empty($param->name) || !empty($param->mark) || !empty($param->capacity) || !empty($param->manufactured_by) || !empty($param->model) || !empty($param->serial_number) || !empty($param->test_reference))){
    		$examinations = Examination::where("id",$param->id)->with('examinationType')->first();
			if($examinations){
				$device = Device::find($examinations->device_id);
				$a = Device::find($examinations->device_id);
				if($device){
					if (!empty($param->name)){
						$device->name = $param->name;
					}
						
					if (!empty($param->mark)){
						$device->mark = $param->mark;
					}
						
					if (!empty($param->capacity)){
						$device->capacity = $param->capacity;
					}
						
					if (!empty($param->manufactured_by)){
						$device->manufactured_by = $param->manufactured_by;
					}
						
					if (!empty($param->model)){
						$device->model = $param->model;
					}
						
					if (!empty($param->serial_number)){
						$device->serial_number = $param->serial_number;
					}
						
					if (!empty($param->test_reference)){
						$device->test_reference = $param->test_reference;
					}
					
					$device->updated_by = 1;
					$device->updated_at = date("Y-m-d H:i:s");
					
					if($device->save()){ 
						$admins = AdminRole::where('function_status',1)->get()->toArray();
						foreach ($admins as $admin) {  
							$data= array( 
				                "from"=>$id_user,
				                "to"=>$admin['user_id'],
				                "message"=>"Test Engineer mengedit data Pengujian",
				                "url"=>"examination/".$param->id,
				                "is_read"=>0,
				                "created_at"=>date("Y-m-d H:i:s"),
				                "updated_at"=>date("Y-m-d H:i:s")
			             	);
						  	$notification = new NotificationTable();
							$notification->id = Uuid::uuid4();
					      	$notification->from = $data['from'];
					      	$notification->to = $data['to'];
					      	$notification->message = $data['message'];
					      	$notification->url = $data['url'];
					      	$notification->is_read = $data['is_read'];
					      	$notification->created_at = $data['created_at'];
					      	$notification->updated_at = $data['updated_at'];
					      	$notification->save();
					     	$data['id'] = $notification->id;
					     	event(new Notification($data));
					    }

				      	$data= array(
				        
			                "from"=>"admin",
			                "to"=>$id_user,
			                "message"=>"Test Engineer mengedit data Pengujian",
			                "url"=>"pengujian".$param->id,
			                "is_read"=>0,
			                "created_at"=>date("Y-m-d H:i:s"),
			                "updated_at"=>date("Y-m-d H:i:s")
			             );

					  	$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
				      	$notification->from = $data['from'];
				      	$notification->to = $data['to'];
				      	$notification->message = $data['message'];
				      	$notification->url = $data['url'];
				      	$notification->is_read = $data['is_read'];
				      	$notification->created_at = $data['created_at'];
				      	$notification->updated_at = $data['updated_at'];
				      	$notification->save(); 
				      	$data['id'] = $notification->id;
				       	event(new Notification($data));
						$this->updaterevisi($a,$param,$examinations);
						return $this->sendResponse($device, 'Device Found');
					}else{
						return $this->sendError('Failed to Update Device ');
					}
				}else{
					return $this->sendError('Device Not Found');
				}
    		}else{
    			return $this->sendError('Examination Not Found');
    		}
    	}else{
    		return $this->sendError('ID Examination Is Required Or Nothing to Update');
    	}
    }
	
	public function updaterevisi($a,$b,$c)
    {
		if (!empty($b->name)){
			$rev_name = $b->name;
		}else{
			$rev_name = $a->name;
		}
		
		if (!empty($b->mark)){
			$rev_mark = $b->mark;
		}else{
			$rev_mark = $a->mark;
		}
		
		if (!empty($b->capacity)){
			$rev_capacity = $b->capacity;
		}else{
			$rev_capacity = $a->capacity;
		}
		
		if (!empty($b->manufactured_by)){
			$rev_manufactured_by = $b->manufactured_by;
		}else{
			$rev_manufactured_by = $a->manufactured_by;
		}
		
		if (!empty($b->model)){
			$rev_model = $b->model;
		}else{
			$rev_model = $a->model;
		}
		
		if (!empty($b->serial_number)){
			$rev_serial_number = $b->serial_number;
		}else{
			$rev_serial_number = $a->serial_number;
		}
		
		if (!empty($b->test_reference)){
			$rev_test_reference = $b->test_reference;
		}else{
			$rev_test_reference = $a->test_reference;
		}
		
		$this->sendEmailRevisi(
			$c->created_by,
			$c->examinationType->name,
			$c->examinationType->description,
			$a->name,
			$rev_name,
			$a->mark,
			$rev_mark,
			$a->capacity,
			$rev_capacity,
			$a->manufactured_by,
			$rev_manufactured_by,
			$a->model,
			$rev_model,
			$a->test_reference,
			$rev_test_reference,
			$a->serial_number,
			$rev_serial_number,
			"emails.revisi", 
			"Revisi Data Permohonan Uji"
		);
    }
	
	public function sendEmailRevisi(
		$user, 
		$exam_type, 
		$exam_type_desc, 
		$perangkat1, 
		$perangkat2, 
		$merk_perangkat1, 
		$merk_perangkat2, 
		$kapasitas_perangkat1, 
		$kapasitas_perangkat2, 
		$pembuat_perangkat1, 
		$pembuat_perangkat2, 
		$model_perangkat1, 
		$model_perangkat2, 
		$ref_perangkat1, 
		$ref_perangkat2, 
		$sn_perangkat1, 
		$sn_perangkat2, 
		$message,
		$subject
	)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc,
			'perangkat1' => $perangkat1,
			'perangkat2' => $perangkat2,
			'merk_perangkat1' => $merk_perangkat1,
			'merk_perangkat2' => $merk_perangkat2,
			'kapasitas_perangkat1' => $kapasitas_perangkat1,
			'kapasitas_perangkat2' => $kapasitas_perangkat2,
			'pembuat_perangkat1' => $pembuat_perangkat1,
			'pembuat_perangkat2' => $pembuat_perangkat2,
			'model_perangkat1' => $model_perangkat1,
			'model_perangkat2' => $model_perangkat2,
			'ref_perangkat1' => $ref_perangkat1,
			'ref_perangkat2' => $ref_perangkat2,
			'sn_perangkat1' => $sn_perangkat1,
			'sn_perangkat2' => $sn_perangkat2
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }
	
	public function updateFunctionStat(Request $param)
    {
    	$param = (object) $param->all();
    	$currentUser = Auth::user();
		if($currentUser){
			$id_user = $currentUser->id;
		}else{
			$id_user = 1;
		}
    	if(!empty($param->id) && !empty($param->catatan) && !empty($param->function_result) && !empty($param->function_test_pic)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$examinations->catatan = $param->catatan;
				$examinations->function_test_TE = $param->function_result;
				$examinations->function_test_PIC = $param->function_test_pic;
				$examinations->function_test_status_detail = 'Perangkat selesai diuji fungsi';
    			if($examinations->save()){
    				$admins = AdminRole::where('function_status',1)->get()->toArray();
					foreach ($admins as $admin) {  
	    				$data= array( 
			                "from"=>$id_user,
			                "to"=>$admin['user_id'],
			                "message"=>"Test Engineer memberikan Hasil Uji Fungsi",
			                "url"=>"examination/".$param->id."/edit",
			                "is_read"=>0,
			                "created_at"=>date("Y-m-d H:i:s"),
			                "updated_at"=>date("Y-m-d H:i:s")
			            );
					  	$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
				      	$notification->from = $data['from'];
				      	$notification->to = $data['to'];
				      	$notification->message = $data['message'];
				      	$notification->url = $data['url'];
				      	$notification->is_read = $data['is_read'];
				      	$notification->created_at = $data['created_at'];
				      	$notification->updated_at = $data['updated_at'];
				      	$notification->save();
				     
				      	$data['id'] = $notification->id;
				      	event(new Notification($data));
					}

				       $data= array( 
		                "from"=>"admin",
		                "to"=>$id_user,
		                "message"=>"Test Engineer memberikan Hasil Uji Fungsi",
		                "url"=>"examination/".$param->id."/edit",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
		             );
					  $notification = new NotificationTable();
$notification->id = Uuid::uuid4();
				      $notification->from = $data['from'];
				      $notification->to = $data['to'];
				      $notification->message = $data['message'];
				      $notification->url = $data['url'];
				      $notification->is_read = $data['is_read'];
				      $notification->created_at = $data['created_at'];
				      $notification->updated_at = $data['updated_at'];
				      $notification->save();

				     
				      $data['id'] = $notification->id;
				      event(new Notification($data));
    				return $this->sendResponse($examinations, 'Examination Found');
    			}else{
    				return $this->sendError('Failed to Update ');
    			}
    		}else{
    			return $this->sendError('Success Update');
    		}
    	}else{
    		return $this->sendError('ID Examination or Catatan or Function Result or PIC Is Required');
    	}
    }
	
	public function updateSpkStat(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->status)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$examinations->is_spk_created = $param->status;
    			if($examinations->save()){
    				return $this->sendResponse($examinations, 'Examination Found');
    			}else{
    				return $this->sendError('Failed to Update ');
    			}
    		}else{
    			return $this->sendError('Success Update');
    		}
    	}else{
    		return $this->sendError('ID Examination or Status Is Required');
    	}
    }
	
	public function updateSpk(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->status)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$examinations->spk_status = $param->status;
    			if($examinations->save()){
    				return $this->sendResponse($examinations, 'Examination Found');
    			}else{
    				return $this->sendError('Failed to Update ');
    			}
    		}else{
    			return $this->sendError('Success Update');
    		}
    	}else{
    		return $this->sendError('ID Examination or Status Is Required');
    	}
    }
	
	public function sendLapUji(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->name) && !empty($param->link) && !empty($param->no)){
    		$attach = ExaminationAttach::where('name', 'Laporan Uji')->where('examination_id', ''.$param->id.'')->first();

			if ($attach){
				$attach->attachment = $param->link;
				$attach->no = $param->no;
				$attach->updated_by = 1;
				
				if($attach->save()){
					$data= array( 
						"from"=>1,
						"to"=>"admin",
						"message"=>"Test Engineer mengirimkan laporan uji",
						"url"=>"examination/".$param->id."/edit",
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					);
					  $notification = new NotificationTable();
					  $notification->id = Uuid::uuid4();
					  $notification->from = $data['from'];
					  $notification->to = $data['to'];
					  $notification->message = $data['message'];
					  $notification->url = $data['url'];
					  $notification->is_read = $data['is_read'];
					  $notification->created_at = $data['created_at'];
					  $notification->updated_at = $data['updated_at'];
					  $notification->save();

					  $data['id'] = $notification->id;
					  event(new Notification($data));

    				return $this->sendResponse($attach, 'Resume Found');
    			}else{
    				return $this->sendError('Failed to Input Resume');
    			}
			} else{
				$attach = new ExaminationAttach;
				$attach->id = Uuid::uuid4();
				$attach->examination_id = $param->id; 
				// $attach->name = 'Laporan Uji'. $param->name;
				$attach->name = 'Laporan Uji';
				$attach->attachment = $param->link;
				$attach->no = $param->no;
				$attach->created_by = 1;
				$attach->updated_by = 1;

				if($attach->save()){
					$data= array( 
						"from"=>1,
						"to"=>"admin",
						"message"=>"Test Engineer mengirimkan laporan uji",
						"url"=>"pengujian",
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					);
					  $notification = new NotificationTable();
					  $notification->id = Uuid::uuid4();
					  $notification->from = $data['from'];
					  $notification->to = $data['to'];
					  $notification->message = $data['message'];
					  $notification->url = $data['url'];
					  $notification->is_read = $data['is_read'];
					  $notification->created_at = $data['created_at'];
					  $notification->updated_at = $data['updated_at'];
					  $notification->save();

					  $data['id'] = $notification->id;
					  event(new Notification($data));
					  
    				return $this->sendResponse($attach, 'Resume Found');
    			}else{
    				return $this->sendError('Failed to Input Resume');
    			}
			}
    	}else{
    		return $this->sendError('ID Examination or name or attachment or Ref. No link Is Required');
    	}
    }
	
	public function updateSidangQa(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->status) && !empty($param->date)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$examinations->qa_passed = $param->status;
				$examinations->qa_date = $param->date;
    			if($examinations->save()){
    				return $this->sendResponse($examinations, 'Examination Found');
    			}else{
    				return $this->sendError('Failed to Update ');
    			}
    		}else{
    			return $this->sendError('Success Update');
    		}
    	}else{
    		return $this->sendError('ID Examination or Status or Date Is Required');
    	}
    }
	
	public function sendSertifikat(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->name) && !empty($param->link) && !empty($param->no) && !empty($param->cert_date) && !empty($param->cert_valid_from) && !empty($param->cert_valid_thru)){
			$attach = ExaminationAttach::where('name', 'Sertifikat')->where('examination_id', ''.$param->id.'')->first();

			if ($attach){
				$attach->attachment = $param->link;
				$attach->no = $param->no;
				$attach->updated_by = 1;
				
				if($attach->save()){
    				$examinations = Examination::find($param->id);
					if($examinations){
						$examinations->certificate_date = $param->cert_date;
						if($examinations->save()){
							$device = Device::find($examinations->device_id);
							if($device){
								if (!empty($param->link)){
									$device->certificate = $param->link;
								}
								
								if (!empty($param->cert_valid_from)){
									$device->valid_from = $param->cert_valid_from;
								}
								
								if (!empty($param->cert_valid_thru)){
									$device->valid_thru = $param->cert_valid_thru;
								}
								
								if (!empty($param->no)){
									$device->cert_number = $param->no;
								}
								
								$device->updated_by = 1;
								$device->updated_at = date("Y-m-d H:i:s");
								
								if($device->save()){
									return $this->sendResponse($device, 'Device Found');
								}else{
									return $this->sendError('Failed to Update Device ');
								}
							}else{
								return $this->sendError('Device Not Found');
							}
						}else{
							return $this->sendError('Failed to Update Certificate Date ');
						}
					}else{
						return $this->sendError('Examination Not Found');
					}
    			}else{
    				return $this->sendError('Failed to Input Certificate');
    			}
			} else{
					$attach = new ExaminationAttach;
				$attach->id = Uuid::uuid4();
				$attach->examination_id = $param->id; 
				// $attach->name = 'Sertifikat'. $param->name;
				$attach->name = 'Sertifikat';
				$attach->attachment = $param->link;
				$attach->no = $param->no;
				$attach->created_by = 1;
				$attach->updated_by = 1;

				if($attach->save()){
					$examinations = Examination::find($param->id);
					if($examinations){
						$examinations->certificate_date = $param->cert_date;
						if($examinations->save()){
							$device = Device::find($examinations->device_id);
							if($device){
								if (!empty($param->link)){
									$device->certificate = $param->link;
								}
								
								if (!empty($param->cert_valid_from)){
									$device->valid_from = $param->cert_valid_from;
								}
								
								if (!empty($param->cert_valid_thru)){
									$device->valid_thru = $param->cert_valid_thru;
								}
								
								if (!empty($param->no)){
									$device->cert_number = $param->no;
								}
								
								$device->updated_by = 1;
								$device->updated_at = date("Y-m-d H:i:s");
								
								if($device->save()){
									return $this->sendResponse($device, 'Device Found');
								}else{
									return $this->sendError('Failed to Update Device ');
								}
							}else{
								return $this->sendError('Device Not Found');
							}
						}else{
							return $this->sendError('Failed to Update Certificate Date ');
						}
					}else{
						return $this->sendError('Examination Not Found');
					}
				}else{
					return $this->sendError('Failed to Input Certificate');
				}
			}
    	}else{
    		return $this->sendError('ID Examination or name or attachment or Ref. No link or Date of Cert Is Required');
    	}
    }

    public function sendSPK(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->ID) && !empty($param->SPK_NUMBER) && !empty($param->DEVICE_NAME)){
			$spk = new TbMSPK;
	        $spk->ID = $param->ID;
	        $spk->SPK_NUMBER = $param->SPK_NUMBER;
	        $spk->LAB_CODE = $param->LAB_CODE;
	        $spk->TESTING_TYPE = $param->TESTING_TYPE;
	        $spk->DEVICE_NAME = $param->DEVICE_NAME;
	        $spk->COMPANY_NAME = $param->COMPANY_NAME;
	        $spk->FLOW_STATUS = $param->FLOW_STATUS;
	        $spk->CREATED_BY = $param->CREATED_BY;
	        $spk->CREATED_DT = $param->CREATED_DT;
	        $spk->UPDATED_DT = $param->UPDATED_DT;
	        $spk->UPDATED_BY = $param->UPDATED_BY;

			if ($spk->save()){
				return $this->sendResponse($spk, 'SPK Created');
			} else{
				return $this->sendError('Failed to Input SPK');
			}
    	}else{
    		return $this->sendError('ID or SPK_NUMBER or DEVICE_NAME Is Required');
    	}
    }

    public function sendSPKHistory(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->ID) && !empty($param->SPK_NUMBER) && !empty($param->ACTION) && !empty($param->REMARK) && !empty($param->CREATED_BY) && !empty($param->CREATED_DT) && !empty($param->UPDATED_BY) && !empty($param->UPDATED_DT)){
			$spk_hist = new TbHSPK;
	        $spk_hist->ID = $param->ID;
	        $spk_hist->SPK_NUMBER = $param->SPK_NUMBER;
	        $spk_hist->ACTION = $param->ACTION;
	        $spk_hist->REMARK = $param->REMARK;
	        $spk_hist->CREATED_BY = $param->CREATED_BY;
	        $spk_hist->CREATED_DT = $param->CREATED_DT;
	        $spk_hist->UPDATED_BY = $param->UPDATED_BY;
	        $spk_hist->UPDATED_DT = $param->UPDATED_DT;

			if ($spk_hist->save()){
				return $this->sendResponse($spk_hist, 'SPK History Created');
			} else{
				return $this->sendError('Failed to Input SPK History');
			}
    	}else{
    		return $this->sendError('ID or SPK_NUMBER or ACTION or REMARK or CREATED_BY or CREATED_DT or UPDATED_BY or UPDATED_DT Is Required');
    	}
    }
}
