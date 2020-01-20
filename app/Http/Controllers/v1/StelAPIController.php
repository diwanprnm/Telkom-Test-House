<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\STEL;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use FPDF;
 
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

    public function webHookTPN(Request $param){
    	switch ($param->event) {
    		case 'billing_paid':
    			if(empty($param->data['billing']['_id'])){
					return $this->sendError('BILLING_ID Not Found');
				}

		    	/*JIKA PERLU TAMBAH NOTIFIKASI KE ADMIN, BAHWA PEMBAYARAN SUDAH SELESAI*/
		    	$data_invoices = [
		            "billing_id" => $param->data['billing']['_id'],
		            "created" => [
		                "by" => "admin",
		                "reference_id" => 1
		            ]
		        ];

		        // $invoices = $this->api_invoice($data_invoices);
		        $invoices = null;
		        if($invoices && $invoices->status == true){
		        	return $this->sendResponse($invoices, "create_invoice SENT");
		        }else{
		        	return $this->sendError("create_invoice FAILED");	
		        }
    			break;

    		case 'faktur_created':
    			$url = $param->data['url_faktur'];
				$html = file_get_contents($url);
				// dd($html);

				$pdf = new FPDF();

				// dd($pdf);
				// return $pdf->load($html)->show();
				return $this->sendResponse($param, "faktur delivered");
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    }
	
    public function api_invoice($data_invoices){
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $param_invoices['json'] = $data_invoices;
            $res_invoices = $client->post("v1/invoices", $param_invoices)->getBody();
            $invoice = json_decode($res_invoices);

            /*get
                $invoice->status; //if true lanjut, else panggil lagi API ny
                $invoice->data->_id; //INVOICE_ID
            */

            return $invoice;
        } catch(Exception $e){
            return null;
        }
    }
	 
}
