<?php

namespace App\Http\Controllers\v1;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\Device;
use App\EquipmentHistory;
use App\Examination;
use App\ExaminationType;
use App\ExaminationHistory;
use App\ExaminationAttach;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
 
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
			  WHEN function_status = 1 AND contract_status != 1 THEN 'Tinjauan Kontrak'
			  WHEN contract_status = 1 AND spb_status != 1 THEN 'SPB'
			  WHEN spb_status = 1 AND payment_status != 1 THEN 'Pembayaran'
			  WHEN payment_status = 1 AND spk_status != 1 THEN 'Pembuatan SPK'
			  WHEN spk_status = 1 AND examination_status != 1 THEN 'Pelaksanaan Uji'
			  WHEN examination_status = 1 AND resume_status != 1 THEN 'Laporan Uji'
			  WHEN resume_status = 1 AND qa_status != 1 THEN 'Sidang QA'
			  WHEN qa_status = 1 AND certificate_status != 1 THEN 'Penerbitan Sertifikat'
			  ELSE NULL
			  END as 'step_progress'
			",
			"examinations.spk_code",
			"examinations.spk_date",
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
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "!=", '1'); 
						break;
					case 4: 
						$result = $result->where("examinations.contract_status ", "=", '1'); 
						$result = $result->where("examinations.spb_status", "!=", '1'); 
						break;
					case 5: 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "!=", '1'); 
						break;
					case 6: 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "!=", '1');
						break;
					case 7: 
						$result = $result->where("examinations.spk_status", "=", '1'); 
						$result = $result->where("examinations.examination_status", "!=", '1');
						break;
					case 8: 
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "!=", '1');
						break;
					case 9: 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "!=", '1');
						break;
					case 10: 
						$result = $result->where("examinations.qa_status", "==", '1'); 
						$result = $result->where("examinations.certificate_status", "!=", '1'); 
						break;
					default:
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
				if($param->spk_code){
					// $result = $result->where("examinations.payment_status", "=", 1);
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

		}
		
		if(isset($param->limit)){
			$result = $result->limit($param->limit);
			if(isset($param->offset)){
				$result = $result->offset($param->offset);
			}
		}
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
			  WHEN function_status = 1 AND contract_status != 1 THEN 'Tinjauan Kontrak'
			  WHEN contract_status = 1 AND spb_status != 1 THEN 'SPB'
			  WHEN spb_status = 1 AND payment_status != 1 THEN 'Pembayaran'
			  WHEN payment_status = 1 AND spk_status != 1 THEN 'Pembuatan SPK'
			  WHEN spk_status = 1 AND examination_status != 1 THEN 'Pelaksanaan Uji'
			  WHEN examination_status = 1 AND resume_status != 1 THEN 'Laporan Uji'
			  WHEN resume_status = 1 AND qa_status != 1 THEN 'Sidang QA'
			  WHEN qa_status = 1 AND certificate_status != 1 THEN 'Penerbitan Sertifikat'
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
						$result = $result->where("examinations.function_status", "=", '1'); 
						$result = $result->where("examinations.contract_status", "!=", '1'); 
						break;
					case 4: 
						$result = $result->where("examinations.contract_status ", "=", '1'); 
						$result = $result->where("examinations.spb_status", "!=", '1'); 
						break;
					case 5: 
						$result = $result->where("examinations.spb_status", "=", '1'); 
						$result = $result->where("examinations.payment_status", "!=", '1'); 
						break;
					case 6: 
						$result = $result->where("examinations.payment_status", "=", '1'); 
						$result = $result->where("examinations.spk_status", "!=", '1');
						break;
					case 7: 
						$result = $result->where("examinations.spk_status", "=", '1'); 
						$result = $result->where("examinations.examination_status", "!=", '1');
						break;
					case 8: 
						$result = $result->where("examinations.examination_status", "=", '1'); 
						$result = $result->where("examinations.resume_status", "!=", '1');
						break;
					case 9: 
						$result = $result->where("examinations.resume_status", "=", '1'); 
						$result = $result->where("examinations.qa_status", "!=", '1');
						break;
					case 10: 
						$result = $result->where("examinations.qa_status", "==", '1'); 
						$result = $result->where("examinations.certificate_status", "!=", '1'); 
						break;
					default:
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
			"examinations.function_test_TE as function_result","examinations.function_test_PIC",
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
				->where("examinations.registration_status", 1)
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
				$rawRangeDate ="date_format(examinations.deal_test_date,'%Y-%m-%d') = '".$param->deal_test_date."'";
				$result = $result->where(\DB::raw($rawRangeDate), 1); 
			}
			if(isset($param->function_result)){
				$result = $result->where("examinations.function_test_TE", "=", $param->function_result);
			}
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
		
		$result = $result->get()->toArray();
	
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

    	if(!empty($param->id) && !empty($param->function_test_date)&& !empty($param->function_test_pic)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$examinations->deal_test_date = $param->function_test_date;
				$examinations->function_test_PIC = $param->function_test_pic;
    			if($examinations->save()){
    				return $this->sendResponse($examinations, 'Function Date Found');
    			}else{
    				return $this->sendError('Failed to Update Function Date ');
    			}
    		}else{
    			return $this->sendError('Success Update Function Date');
    		}
    	}else{
    		return $this->sendError('ID Examination or Date or PIC Is Required');
    	}
    }
	
	public function updateEquipLoc(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->date)){
			$equip_hist = new EquipmentHistory;
			$equip_hist->id = Uuid::uuid4();
			$equip_hist->location = 2;
			$equip_hist->created_by = 1;
			$equip_hist->updated_by = 1;
			$equip_hist->created_at = $param->date;
			$equip_hist->updated_at = $param->date;
			$equip_hist->examination_id = $param->id;

			if($equip_hist->save()){
				return $this->sendResponse($equip_hist, 'History Found');
			}else{
				return $this->sendError('Failed to Input History');
			}
    	}else{
    		return $this->sendError('ID Examination or Date Is Required');
    	}
    }
	
	public function updateDeviceTE(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && (!empty($param->name) || !empty($param->mark) || !empty($param->capacity) || !empty($param->manufactured_by) || !empty($param->model) || !empty($param->serial_number) || !empty($param->test_reference))){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$device = Device::find($examinations->device_id);
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
					$device->updated_at = date("Y-m-d h:i:s");
					
					if($device->save()){
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
	
	public function generateFunctionTestNumber($a) {
		$thisYear = date('Y');
		$types = ExaminationType::find($a);
		$query = "
			SELECT 
			SUBSTRING_INDEX(function_test_NO,'/',1) + 1 AS last_numb
			FROM examinations 
			WHERE 
			SUBSTRING_INDEX(SUBSTRING_INDEX(function_test_NO,'/',2),'/',-1) = '".$types->name."' AND
			SUBSTRING_INDEX(function_test_NO,'/',-1) = '".$thisYear."'
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			return '001/'.$types->name.'/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.'/'.$types->name.'/'.$thisYear.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.'/'.$types->name.'/'.$thisYear.'';
			}
			else{
				return ''.$last_numb.'/'.$types->name.'/'.$thisYear.'';
			}
		}
    }
	
	public function updateFunctionStat(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->catatan) && !empty($param->function_result) && !empty($param->function_test_pic)){
    		$examinations = Examination::find($param->id);
    		if($examinations){
				$examinations->catatan = $param->catatan;
				$examinations->function_test_TE = $param->function_result;
				$examinations->function_test_PIC = $param->function_test_pic;
				$examinations->function_test_NO = $this->generateFunctionTestNumber($examinations->examination_type_id);
    			if($examinations->save()){
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
				$examinations->is_created_spk = $param->status;
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
    		// $attach = ExaminationAttach::where('name', 'Laporan Uji')->where('examination_id', ''.$param->id.'')->first();

			// if ($attach){
				// $attach->attachment = $param->link;
				// $attach->no = $param->no;
				// $attach->updated_by = 1;
				
				// if($attach->save()){
    				// return $this->sendResponse($attach, 'Resume Found');
    			// }else{
    				// return $this->sendError('Failed to Input Resume');
    			// }
			// } else{
				$attach = new ExaminationAttach;
				$attach->id = Uuid::uuid4();
				$attach->examination_id = $param->id; 
				$attach->name = 'Laporan Uji'. $param->name;
				$attach->attachment = $param->link;
				$attach->no = $param->no;
				$attach->created_by = 1;
				$attach->updated_by = 1;

				if($attach->save()){
    				return $this->sendResponse($attach, 'Resume Found');
    			}else{
    				return $this->sendError('Failed to Input Resume');
    			}
			// }
    	}else{
    		return $this->sendError('ID Examination or name or attachment or Ref. No link Is Required');
    	}
    }
	
	public function sendSertifikat(Request $param)
    {
    	$param = (object) $param->all();

    	if(!empty($param->id) && !empty($param->name) && !empty($param->link) && !empty($param->no) && !empty($param->cert_date) && !empty($param->cert_valid_from) && !empty($param->cert_valid_thru)){
			$attach = new ExaminationAttach;
			$attach->id = Uuid::uuid4();
			$attach->examination_id = $param->id; 
			$attach->name = 'Sertifikat'. $param->name;
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
							
							$device->updated_by = 1;
							$device->updated_at = date("Y-m-d h:i:s");
							
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
    	}else{
    		return $this->sendError('ID Examination or name or attachment or Ref. No link or Date of Cert Is Required');
    	}
    }
}
