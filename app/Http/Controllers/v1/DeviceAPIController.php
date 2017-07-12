<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\Device;
use App\ExaminationCharge;
 
class DeviceAPIController extends AppBaseController
{
    
    public function getDeviceData(Request $param)
    {
		$param = (object) $param->all();
		
		$whereID = array();
		 
		
		if(isset($param->id)){
			// $whereID["devices.id"] = $param->id;
			$whereID["id"] = $param->id;
		}  

		// $select = array(
			// "devices.id", "devices.test_reference as code","devices.name",
			// "examination_charges.price as qa_price","examination_charges.ta_price","examination_charges.vt_price"
		// );
		$select = array(
			"id", "device_name", "stel as document_code", "category as lab", "duration", "price as qa_price", "vt_price", "ta_price"
		);
		$result = ExaminationCharge::selectRaw(implode(",", $select))  
				// ->join("stels","devices.test_reference","=",".stels.code")
				// ->join("examination_charges","examination_charges.stel","=",".stels.code")
				// ->where("devices.is_active","=",1)
				->where($whereID);
				
		if(isset($param->find)){
			$result = $result->where("device_name", "LIKE", '%'.$param->find .'%')
							->orWhere("stel", "LIKE", '%'.$param->find .'%')
							->orWhere("category", "LIKE", '%'.$param->find .'%')
							->orWhere("duration", "LIKE", '%'.$param->find .'%')
							->orWhere("price", "LIKE", '%'.$param->find .'%')
							->orWhere("vt_price", "LIKE", '%'.$param->find .'%')
							->orWhere("ta_price", "LIKE", '%'.$param->find .'%');
		}else{

			if(isset($param->device_name)){
				// $result = $result->where("devices.name", "LIKE", '%'.$param->device_name .'%');
				$result = $result->where("device_name", "LIKE", '%'.$param->device_name .'%');
			}
			if(isset($param->document_code)){
				// $result = $result->where("stels.code", "LIKE", '%'.$param->document_code .'%');
				$result = $result->where("stel", "LIKE", '%'.$param->document_code .'%');
			} 
			if(isset($param->lab)){
				// $result = $result->where("stels.type", "LIKE", '%'.$param->lab .'%');
				$result = $result->where("category", "LIKE", '%'.$param->lab .'%');
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
			return $this->sendError('Devices Data Not Found');
		}
		return $this->sendResponse($result, 'Devices Data Found');
    }
	 
}
