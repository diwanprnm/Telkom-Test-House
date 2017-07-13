<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\Company;
/**
 * @resource Content
 *
 * Class CompanyAPIController,
 * list of all api related to company
 * - GET companies
 */
class CompanyAPIController extends AppBaseController
{
    
    public function getCompanies(Request $param)
    {
		$param = (object) $param->all();
		
		$condition = array();
		 
		
		if(isset($param->id)){
			$condition["id"] = $param->id;
		}  

		$select = array(
			"id", "name","address","email","phone_number","fax as facsimile","npwp_number"
		);
		$result = Company::selectRaw(implode(",", $select)) 
				->where("is_active","=",1)
				->where("id","!=",1)
				->where($condition); 

		if(isset($param->find)){
			$result = $result->where("name", "LIKE", '%'.$param->find .'%')
							->orWhere("address", "LIKE", '%'.$param->find .'%')
							->orWhere("email", "LIKE", '%'.$param->find .'%')
							->orWhere("phone_number", "LIKE", '%'.$param->find .'%')
							->orWhere("fax", "LIKE", '%'.$param->find .'%')
							->orWhere("npwp_number", "LIKE", '%'.$param->find .'%');
		}else{
			if(isset($param->name)){
				$result = $result->where("name", "LIKE", '%'.$param->name .'%');
			}
			if(isset($param->address)){
				$result = $result->where("address", "LIKE", '%'.$param->address .'%');
			}
			if(isset($param->email)){
				$result = $result->where("email", "LIKE", '%'.$param->email .'%');
			}
			if(isset($param->phone_number)){
				$result = $result->where("phone_number", "LIKE", '%'.$param->phone_number .'%');
			}
			if(isset($param->fax)){
				$result = $result->where("fax", "LIKE", '%'.$param->fax .'%');
			}
			if(isset($param->npwp_number)){
				$result = $result->where("npwp_number", "LIKE", '%'.$param->npwp_number .'%');
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
			return $this->sendError('Company Data Not Found');
		}
		return $this->sendResponse($result, 'Company Data Found');
    }
	 
}
