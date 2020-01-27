<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\STEL;
 
class StelAPIController extends AppBaseController
{    
    public function getStelData(Request $param)
    {    	
    	$param = (object) $param->all();
		
		$whereID = array();
		 
		
		if(isset($param->id)){
			$whereID["stels.id"] = $param->id;
		}  

		$select = array(
			"stels.id","stels.name","stels.code","stels.price","stels.version","examination_labs.name as category","stels.stel_type as type"
		);
		
		$result = STEL::selectRaw(implode(",", $select))   
				->join("examination_labs","stels.type","=","examination_labs.id")
				->where("stels.is_active","=",1)
				->where($whereID);
				
		if(isset($param->find)){
			$result = $result->where("stels.code", "LIKE", '%'.$param->find .'%')
							->orWhere("stels.name", "LIKE", '%'.$param->find .'%')
							->orWhere("stels.price", "LIKE", '%'.$param->find .'%')
							->orWhere("examination_labs.name", "LIKE", '%'.$param->find .'%')
							->orWhere("stels.version", "LIKE", '%'.$param->find .'%');
			// $param->find = (strtoupper($param->find) == "S-TSEL")?2:1;   
			// $result = $result->orWhere("stels.stel_type", "=", $param->find);
		}else{

			if(isset($param->document_code)){
				$result = $result->where("stels.code", "LIKE", '%'.$param->document_code .'%');
			}

			if(isset($param->document_name)){
				$result = $result->where("stels.name", "LIKE", '%'.$param->document_name .'%');
			}

			if(isset($param->version)){
				$result = $result->where("stels.version", "LIKE", '%'.$param->version .'%');
			}
		
			if(isset($param->type)){ 
				$result = $result->where("stels.stel_type", "=", $param->type);
			}
		
			if(isset($param->category)){ 
				$result = $result->where("examination_labs.name", "LIKE", '%'.$param->category .'%');
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
			return $this->sendError('STELS Data Not Found');
		}
		return $this->sendResponse($result, 'STELS Data Found');
    }
}
