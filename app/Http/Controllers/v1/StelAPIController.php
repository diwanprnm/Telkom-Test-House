<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;
use App\STEL;

use App\STELSales;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
 
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

    public function checkBillingTPN()
    {
        $stel = STELSales::where('payment_status', 0)->whereNotNull('BILLING_ID')->get();
        if(count($stel)>0){
            $updated_count = 0;
            foreach ($stel as $data) {
                $STELSales = STELSales::find($data->id);

                $data_invoices = [
                    "billing_id" => $data->BILLING_ID,
                    "created" => [
                        "by" => "SUPERADMIN UREL",
                        "reference_id" => "1"
                    ]
                ];

                $invoice = $this->api_invoice($data_invoices);
                $STELSales->INVOICE_ID = $invoice && $invoice->status == true ? $invoice->data->_id : null;
                $STELSales->payment_status = $invoice && $invoice->status == true ? 1 : 0;

                $updated_count = $invoice && $invoice->status == true ? $updated_count += 1 : $updated_count;
                $updated_count = $STELSales->save() ? $updated_count : $updated_count -= 1;
            }
            return 'checkBillingTPN Command Run successfully! '.$updated_count.'/'.count($stel).' updated.';
        }else{
            return 'checkBillingTPN Command Run successfully! Nothing to update.';
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
            $res_invoices = $client->post("v1/invoices", $param_invoices)->getBody()->getContents();
            $invoice = json_decode($res_invoices);

            return $invoice;
        } catch(Exception $e){
            return null;
        }
    }

    public function checkTaxInvoiceTPN()
    {
        $stel = STELSales::where('faktur_file', '')->whereNotNull('INVOICE_ID')->get();
        if(count($stel)>0){
            $client = new Client([
                'headers' => ['Authorization' => config("app.gateway_tpn")],
                'base_uri' => config("app.url_api_tpn"),
                'timeout'  => 60.0,
                'http_errors' => false
            ]);

            $updated_count = 0;
            foreach ($stel as $data) {
                $STELSales = STELSales::where("id", $data->id)->first();

                if($STELSales){
                    try {
                        $INVOICE_ID = $STELSales->INVOICE_ID;
                        $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
                        $invoice = json_decode($res_invoice->getBody());
                        
                        if($invoice && $invoice->status == true){
                            $status_invoice = $invoice->data->status_invoice;
                            $status_faktur = $invoice->data->status_faktur;
                            if($status_invoice == "approved" && $status_faktur == "received"){
                                /*SAVE FAKTUR PAJAK*/
                                $name_file = 'faktur_stel_'.$INVOICE_ID.'.pdf';

                                $path_file = public_path().'/media/stel/'.$data->id;
                                if (!file_exists($path_file)) {
                                    mkdir($path_file, 0775);
                                }

                                $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                                $stream = (String)$response->getBody();

                                if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                                    $STELSales->faktur_file = $name_file;
                                    $updated_count = $STELSales->save() ? $updated_count += 1 : $updated_count;
                                }
                            }
                        }
                    } catch(Exception $e){
                        return null;
                    }
                }
            }
            return 'checkTaxInvoiceTPN Command Run successfully! '.$updated_count.'/'.count($stel).' updated.';
        }else{
            return 'checkTaxInvoiceTPN Command Run successfully! Nothing to update.';
        }
    }
}
