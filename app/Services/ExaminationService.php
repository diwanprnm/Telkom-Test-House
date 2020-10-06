<?php

namespace App\Services;

use Auth;
use App\Examination;
use App\User;
use App\Logs;
use App\LogsAdministrator;
use App\ExaminationAttach;
use App\GeneralSetting;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Mail;
use Session;
use App\Services\FileService;

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
	private const HEADERS = 'headers';
	private const CONTENT_TYPE = 'Content-type';
	private const JSON_HEADER = 'application/json';
	private const AUTHORIZATION = 'Authorization';
	private const APP_GATEWAY_TPN_2 = 'app.gateway_tpn_2';
	private const BASE_URI = 'base_uri';
	private const APP_URI_API_BSP = 'app.url_api_bsp';
	private const APP_URI_API_TPN = 'app.url_api_tpn';
	private const TIMEOUT = 'timeout';
	private const DATE_FORMAT_1 = 'Y-m-d H:i:s';
	private const HTTP_ERRORS = 'http_errors';
	private const FAKTUR_PAJAK = 'Faktur Pajak';
	private const V1_INVOICE = 'v1/invoices/';
	private const KUITANSI = 'Kuitansi';
	private const MEDIA_EXAMINATION_LOC = 'examination/';
	private const EXAMINATION_ID = 'examination_id';
	private const APPLICATION_HEADER = 'application/x-www-form-urlencoded';
	private const CONTRACT_DATE = 'contract_date';
	private const J_F_Y = 'j F Y';
	private const EQUIPMENT = 'Equipment';
	private const DOTS = '...............................';
	private const NAMA_PERANGKAT = 'nama_perangkat';
	private const MODEL_PERANGKAT = 'model_perangkat';
	private const KAPASITAS_PERANGKAT = 'kapasitas_perangkat';
	private const PEMBUAT_PERANGKAT = 'pembuat_perangkat';
	private const MANAGER_UREL = 'manager_urel';
	private const LAPORAN_UJI = 'Laporan Uji';
	private const TTH_02 = '/TTH-02/';
	private const DDS_73 = '/DDS-73/';
	private const TANDA_TERIMA = 'tanda_terima_file';
	private const MESSAGE = 'message';
	private const BARANG_FILE = 'barang_file';
	private const BARANG_FILE2 = 'barang_file2';
	private const ADMIN_EXAMINATION_LOC = '/admin/examination/';
	private const EDIT_LOC = '/edit';
	private const USER_NAME = 'user_name';
	private const DEV_NAME = 'dev_name';
	private const EXAM_TYPE = 'exam_type';
	private const EXAM_TYPE_DESC = 'exam_type_desc';
	private const KETERANGAN = 'keterangan';
    
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

    public function getDetailDataFromOTR($client, $id = '', $spk_code = ''){
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

	public function generateFromTPN($exam, $type, $filelink){
    	$client = new Client([
            self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_2)],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);

    	if($exam){
    		if($type == self::FAKTUR_PAJAK){
    			$payment_date = $exam->payment_date != '0000-00-00' ? $exam->payment_date : null;
			    /* GENERATE NAMA FILE FAKTUR */
			    $filename = $exam ? $payment_date.'_'.$exam->company_name.'_'.$exam->name.'_'.$exam->mark.'_'.$exam->capacity.'_'.$exam->model : $exam->INVOICE_ID;
			    /* END GENERATE NAMA FILE FAKTUR */
				$name_file = trim(preg_replace('/\s\s+/', ' ', 'faktur_spb_'.$filename.'.pdf'));
    		}
            try {
                $INVOICE_ID = $exam->INVOICE_ID;
                $res_invoice = $client->request('GET', self::V1_INVOICE.$INVOICE_ID);
                $invoice = json_decode($res_invoice->getBody());
                
                if($INVOICE_ID && $invoice && $invoice->status){
                    $status_invoice = $invoice->data->status_invoice;
                    if($status_invoice == "approved"){
                        $status_faktur = $invoice->data->status_faktur;
                        if($status_faktur == "received"){
							/* 
							 * SAVE FILE
							 * TODO-Chris
							 * Ket: Pengaplikasian upload ke minio dari stream API
							 * Tgs: Belum ditest
							 */

							$name_file = $type == self::KUITANSI ? "kuitansi_spb_$INVOICE_ID.pdf" : $name_file;
							$path_file = self::MEDIA_EXAMINATION_LOC.$exam->id;
							$response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.$filelink);
							$stream = (String)$response->getBody();

							$fileService = new FileService();
							$isUploaded = $fileService->uploadPDFfromStream($stream, $name_file, $path_file);

							if($isUploaded){
                                $attach = ExaminationAttach::where('name', $type)->where(self::EXAMINATION_ID, ''.$exam->id.'')->first();
                                $currentUser = Auth::user();

								if ($attach){
									$attach->attachment = $name_file;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								} else{
									$attach = new ExaminationAttach;
									$attach->id = Uuid::uuid4();
									$attach->examination_id = $exam->id; 
									$attach->name = $type;
									$attach->attachment = $name_file;
									$attach->created_by = $currentUser->id;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								}
                                return $type." Berhasil Disimpan.";
                            }else{
                                return "Gagal Menyimpan ".$type."!";
                            }
                        }else{
                            return $invoice->data->status_faktur;
                        }
                    }else{
                        switch ($status_invoice) {
                            case 'invoiced':
                                return "Invoice Baru Dibuat.";
                                break;
                            
                            case 'returned':
                                return $invoice->data->$status_invoice->message;
                                break;
                            
                            default:
                                return "Invoice sudah dikirim ke DJP.";
                                break;
                        }
                    }
                }else{
                    return "Data Invoice Tidak Ditemukan!";        
                }
            } catch(Exception $e){
                return null;
            }
        }else{
            return "Data Pembelian Tidak Ditemukan!";
        }
	}

    public function api_purchase($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::JSON_HEADER, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_2)
                        ],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false,
            'verify' => false
        ]);
        try {
            
            $params['json'] = $data;
            $res_purchase = $client->post("v1/draftbillings", $params)->getBody();
            return json_decode($res_purchase);
        } catch(Exception $e){
            return null;
        }
	}
	
	public function api_billing($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::JSON_HEADER, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_2)
                        ],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $params['json'] = $data;
            $res_billing = $client->post("v1/billings", $params)->getBody();
            return json_decode($res_billing);
        } catch(Exception $e){
            return null;
        }
	}
	
	public function api_cancel_billing($BILLING_ID,$data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::JSON_HEADER, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_2)
                        ],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $params['json'] = $data;
            $res_cancel_billing = $client->put("v1/billings/".$BILLING_ID."/cancel", $params)->getBody();
            return json_decode($res_cancel_billing);
        } catch(Exception $e){
            return null;
        }
	}
	
	public function api_upload($data, $BILLING_ID){
        $client = new Client([
            self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_2)],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $params['multipart'] = $data;
            $res_upload = $client->post("v1/billings/".$BILLING_ID."/deliver", $params)->getBody(); //BILLING_ID
            return json_decode($res_upload);

        } catch(Exception $e){
            return null;
        }
	}
	
    /**
     * Send an e-mail notification to the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function sendEmailNotification($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject){
        $data = User::findOrFail($user);
		return true;
        $send_email = Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        }); 

        return true;
    }
	
	public function sendEmailNotification_wAttach($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $attach, $spb_number = null, $exam_id = null){
        $data = User::findOrFail($user);
		return true;
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc,
			'spb_number' => $spb_number,
			'id' => $exam_id,
			'payment_method' => $this->api_get_payment_methods()

			), function ($m) use ($data,$subject,$attach) {
            $m->to($data->email)->subject($subject);
			$m->attach($attach);
        });

        return true;
    }
	
	public function api_get_payment_methods(){
        $client = new Client([
			self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_2)],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $res_payment_method = $client->get("v1/products/".config("app.product_id_tth_2")."/paymentmethods")->getBody();
            $payment_method = json_decode($res_payment_method);

            return $payment_method;
        } catch(Exception $e){
            return null;
        }
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
	){
        $data = User::findOrFail($user);
		return true;
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc,
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
	
	public function sendEmailFailure($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $tahap, $keterangan){
        $data = User::findOrFail($user);
		return true;
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc,
			'tahap' => $tahap,
			self::KETERANGAN => $keterangan
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }

	public function tanggalkontrak($request){
		$client = new Client([
			self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
			// Base URI is used with relative requests
			// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			self::BASE_URI => config(self::APP_URI_API_BSP),
			// You can set any number of default request options.
			self::TIMEOUT  => 60.0,
		]);
		
		$currentUser = Auth::user();
		
		$exam_id = $request->input('hide_id_exam');
		$contract_date = $request->input(self::CONTRACT_DATE);
			$contract_date_ina_temp = strtotime($contract_date);
			$contract_date_ina = date(self::J_F_Y, $contract_date_ina_temp);
		
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with(self::COMPANY)
				->with(self::DEVICE)
				->with(self::EXAMINATION_LAB)
				->with(self::EQUIPMENT)
				->first();

		$query_update = "UPDATE examinations
			SET 
				contract_date = '".$contract_date."',
				updated_by = '".$currentUser['attributes']['id']."',
				updated_at = '".date(self::DATE_FORMAT_1)."'
			WHERE id = '".$exam_id."'
		";
		DB::update($query_update);
		
		$res_manager_lab = $client->get('user/getManagerLabInfo?labCode='.$exam->examinationLab->lab_code)->getBody();
		$manager_lab = json_decode($res_manager_lab);
		
		$res_manager_urel = $client->get('user/getManagerLabInfo?groupId=MU')->getBody();
		$manager_urel = json_decode($res_manager_urel);
		
		if(count($manager_lab->data) == 1){
			if( strpos( $manager_lab->data[0]->name, "/" ) !== false ) {$manager_labs = urlencode(urlencode($manager_lab->data[0]->name));}
				else{$manager_labs = $manager_lab->data[0]->name?: '-';}
		}else{
			$manager_labs = self::DOTS;
		}
		
		if(count($manager_urel->data) == 1){
			if( strpos( $manager_urel->data[0]->name, "/" ) !== false ) {$manager_urels = urlencode(urlencode($manager_urel->data[0]->name));}
				else{$manager_urels = $manager_urel->data[0]->name?: '-';}
		}else{
			$manager_urels = self::DOTS;
		}

		$general_setting_poh = GeneralSetting::where('code', 'poh_manager_urel')->first();
		$is_poh = $general_setting_poh && $general_setting_poh->is_active ?  1 : 0;
		$manager_urels = $this->manager_urels($general_setting_poh);
		
		if(count($exam->equipment)>0){
			if( strpos( $exam->equipment[0]->pic, "/" ) !== false ) {$pic = urlencode(urlencode($exam->equipment[0]->pic));}
				else{$pic = $exam->equipment[0]->pic?: '-';}
		}else{
			$pic = self::DOTS;
		}
		
		$data = Array([
			'no_reg' => $exam->function_test_NO,
			'jns_pengujian' => $exam->examination_type_id,
			'nama_pemohon' => $exam->user->name,
			'alamat_pemohon' => $exam->user->address,
			'nama_perusahaan' => $exam->company->name,
			'alamat_perusahaan' => $exam->company->address,
			'plg_id' => $exam->company->plg_id,
			'nib' => $exam->company->nib,
			self::NAMA_PERANGKAT => $exam->device->name,
			'merek_perangkat' => $exam->device->mark,
			self::MODEL_PERANGKAT => $exam->device->model,
			self::KAPASITAS_PERANGKAT => $exam->device->capacity,
			'referensi_perangkat' => $exam->device->test_reference,
			self::PEMBUAT_PERANGKAT => $exam->device->manufactured_by,
			'is_loc_test' => $exam->is_loc_test,
			self::CONTRACT_DATE => $contract_date_ina,
			'manager_lab' => $manager_labs,
			self::MANAGER_UREL => $manager_urels,
			'pic' => $pic,
			'is_poh' => $is_poh
		]);

		return $data;
	}
	
	public function tandaterima($request){
		$exam_id = $request->input('hide_id_exam');
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with(self::DEVICE)
				->with(self::MEDIA)
				->first();
		$no_laporan = '-';
		foreach ($exam->media as $item) {
			if($item->name == self::LAPORAN_UJI && $item->no != ''){
				$no_laporan = $item->no;
			}
		}

		$data = Array([
			'nama_pemohon' => $exam->user->name,
			'alamat_pemohon' => $exam->user->address,
			self::NAMA_PERANGKAT => $exam->device->name,
			'merek_perangkat' => $exam->device->mark,
			self::MODEL_PERANGKAT => $exam->device->model,
			self::KAPASITAS_PERANGKAT => $exam->device->capacity,
			'referensi_perangkat' => $exam->device->test_reference,
			self::PEMBUAT_PERANGKAT => $exam->device->manufactured_by,
			'cert_number' => $exam->device->cert_number,
			'no_laporan' => $no_laporan
		]);

		return $data;
	}

	public function generateSPKCode($a,$b,$c) {
		$total_string_lab_code = strlen($a);
		$delimiter_year = 0;
		if($b == "CAL"){
			$delimiter_year = 10;
		}else{
			$delimiter_year = 9;
		}
		$delimiter = $total_string_lab_code + $delimiter_year;
		$query = "
			SELECT 
			SUBSTR(spk_code,".($total_string_lab_code+2).",3)+1 AS last_numb 
			FROM examinations WHERE 
			SUBSTR(spk_code,1,".$total_string_lab_code.") = '".$a."' AND
			SUBSTR(spk_code,".$delimiter.",4) = '".$c."' 
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (!count($data)){
			return ''.$a.'/001/'.$b.'/'.$c.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return ''.$a.'/00'.$last_numb.'/'.$b.'/'.$c.'';
			}
			else if($last_numb < 100){
				return ''.$a.'/0'.$last_numb.'/'.$b.'/'.$c.'';
			}
			else{
				return ''.$a.'/'.$last_numb.'/'.$b.'/'.$c.'';
			}
		}
	}
	
	public function generateSPBNumber() {
		$thisYear = date('Y');
		$query = "
			SELECT SUBSTR(spb_number,12,4) + 1 AS last_numb
			FROM examinations WHERE SUBSTR(spb_number,12,4)  = ".$thisYear." 
			AND spb_number LIKE '%TTH-02%'
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (!count($data)){
			return '001/TTH-02/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.self::TTH_02.$thisYear.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.self::TTH_02.$thisYear.'';
			}
			else{
				return ''.$last_numb.self::TTH_02.$thisYear.'';
			}
		}
    }
	
	public function generateKodeBarang($a,$b,$c) {
		$query = "
			SELECT
				SUBSTRING_INDEX(no, '/', 1) + 1 AS last_numb
			FROM
				equipments
			WHERE
			SUBSTRING_INDEX(no, '/', -1) = ".$c."
			ORDER BY
				last_numb DESC
			LIMIT 1
		";
		$data = DB::select($query);
		if (!count($data)){
			return '001/'.$a.'/'.$b.'/'.$c.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.'/'.$a.'/'.$b.'/'.$c.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.'/'.$a.'/'.$b.'/'.$c.'';
			}
			else{
				return ''.$last_numb.'/'.$a.'/'.$b.'/'.$c.'';
			}
		}
	}
	
	public function romawi($bln){
		$array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
		$bulan = $array_bulan[$bln];
		return $bulan;
	}

	public function insertAttachment($request,$exam_id,$currentUser_id,$file_type,$file_name,$attach_name){
		if ($request->hasFile($file_type)) {
			$fileService = new FileService();
			$name_file = $fileService->uploadFile($request->file($file_type), $file_name, self::MEDIA_EXAMINATION_LOC.$exam_id);

			if($name_file){
				$attach = ExaminationAttach::where('name', $attach_name)->where(self::EXAMINATION_ID, ''.$exam_id.'')->first();
				if ($attach){
					$attach->attachment = $name_file;
					$attach->updated_by = $currentUser_id;
		
					$attach->save();
				} else{
					$attach = new ExaminationAttach;
					$attach->id = Uuid::uuid4();
					$attach->examination_id = $exam_id; 
					$attach->name = $attach_name;
					$attach->attachment = $name_file;
					$attach->created_by = $currentUser_id;
					$attach->updated_by = $currentUser_id;
		
					$attach->save();
				}
				if($file_type == self::TANDA_TERIMA){
					Session::flash(self::MESSAGE, 'Success Save '.$attach.' to directory');
				}
				if($file_type == self::BARANG_FILE || $file_type == self::BARANG_FILE2 || $file_type == self::TANDA_TERIMA){
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam_id.self::EDIT_LOC);
				}
			}else{
				Session::flash(self::ERROR, 'Save '.$attach_name.' to directory failed');
				return redirect(self::ADMIN_EXAMINATION_LOC.$exam_id.self::EDIT_LOC);
			}
		}		
	}

	public function manager_urels($general_setting_poh){
		if($general_setting_poh){
			if($general_setting_poh->is_active){
				if( strpos( $general_setting_poh->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting_poh->value));}
					else{$manager_urels = $general_setting_poh->value?: '-';}
			}else{
				$general_setting = GeneralSetting::where('code', self::MANAGER_UREL)->first();
				if($general_setting){
					if( strpos( $general_setting->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting->value));}
						else{$manager_urels = $general_setting->value?: '-';}
				}else{
					$manager_urels = self::DOTS;
				}	
			}
		}else{
			$general_setting = GeneralSetting::where('code', self::MANAGER_UREL)->first();
			if($general_setting){
				if( strpos( $general_setting->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting->value));}
					else{$manager_urels = $general_setting->value?: '-';}
			}else{
				$manager_urels = self::DOTS;
			}
		}
		return $manager_urels;
	}
	
}