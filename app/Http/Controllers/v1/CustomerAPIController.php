<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\User;
use App\Role;
/**
 * @resource Content
 *
 * Class CompanyAPIController,
 * list of all api related to company
 * - GET companies
 */
class CustomerAPIController extends AppBaseController
{
    
    public function getCustomer(Request $param)
    {
		$param = (object) $param->all();
		
		$whereID = array();
		 
		
		if(isset($param->id)){
			$whereID["users.id"] = $param->id;
		}  

		$select = array(
			"users.id", "users.name","users.address","users.email","users.phone_number","companies.fax as facsimile","companies.npwp_number","companies.name as company_name"
		);
		$result = User::selectRaw(implode(",", $select)) 
				->join("roles","users.role_id","=","roles.id")
				->join("companies","users.company_id","=","companies.id")
				->where("users.is_active","=",1)
				->where("users.id","!=",1)
				->where("users.role_id","=",2)
				->where($whereID); 
				
		if(isset($param->find)){
			$result->where(function($q) use ($param){
				return $q->where("users.name", "LIKE", '%'.$param->find .'%')
							->orWhere("users.address", "LIKE", '%'.$param->find .'%')
							->orWhere("users.email", "LIKE", '%'.$param->find .'%')
							->orWhere("users.phone_number", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.fax", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.npwp_number", "LIKE", '%'.$param->find .'%')
							->orWhere("companies.name", "LIKE", '%'.$param->find .'%')
					;
				});
		}else{

			if(isset($param->name)){
				$result = $result->where("users.name", "LIKE", '%'.$param->name .'%');
			}

			if(isset($param->address)){
				$result = $result->where("users.address", "LIKE", '%'.$param->address .'%');
			}

			if(isset($param->email)){
				$result = $result->where("users.email", "LIKE", '%'.$param->email .'%');
			}

			if(isset($param->phone_number)){
				$result = $result->where("users.phone_number", "LIKE", '%'.$param->phone_number .'%');
			}

			if(isset($param->fax)){
				$result = $result->where("companies.fax", "LIKE", '%'.$param->fax .'%');
			}

			if(isset($param->npwp_number)){
				$result = $result->where("companies.npwp_number", "LIKE", '%'.$param->npwp_number .'%');
			}

			if(isset($param->company_name)){
				$result = $result->where("companies.name", "LIKE", '%'.$param->company_name .'%');
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
			return $this->sendError('Customer Data Not Found');
		}
		return $this->sendResponse($result, 'Customer Data Found');
    }
	 
}
