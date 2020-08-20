<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Company;
use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\ExaminationHistory;
use App\User;
use App\Api_logs;
use App\Logs;
use App\LogsAdministrator;
use App\Income;
use App\Questioner;
use App\Equipment;
use App\EquipmentHistory;
use App\QuestionerDynamic;
use App\GeneralSetting;

use Auth;
use File;
use Mail;
use Session;
use Response;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Events\Notification;
use App\AdminRole;

use App\Services\Logs\LogService;
use App\Services\ExaminationService;
use App\Services\NotificationService;

class ExaminationController extends Controller
{

	private const SEARCH = 'search';
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
	private const EXAMINATION = 'EXAMINATION';
	private const STATUS = 'status';
	private const BEFORE_DATE = 'before_date';
	private const AFTER_DATE = 'after_date';
	private const UPDATED_AT = 'updated_at';
	private const MESSAGE = 'message';
	private const EXAMINATION_ID = 'examination_id';
	private const HEADERS = 'headers';
	private const CONTENT_TYPE = 'Content-type';
	private const APPLICATION_HEADER = 'application/x-www-form-urlencoded';
	private const BASE_URI = 'base_uri';
	private const APP_URI_API_BSP = 'app.url_api_bsp';
	private const TIMEOUT = 'timeout';
	private const ADMIN = 'admin';
	private const PENGUJIAN_LOC = 'pengujian/';
	private const DETAIL_LOC = '/detail';
	private const IS_READ = 'is_read';
	private const DATE_FORMAT_1 = 'Y-m-d H:i:s';
	private const KONFORMASI_PEMBATALAN = 'Konfirmasi Pembatalan Pengujian';
	private const EMAILS_FAIL = 'emails.fail';
	private const KETERANGAN = 'keterangan';
	private const REGISTRASI = 'Registrasi';
	private const BARANG_FILE = 'barang_file';
	private const MEDIA_EXAMINATION_LOC = '/media/examination/';
	private const EDIT_LOC = '/edit';
	private const ADMIN_EXAMINATION_LOC = '/admin/examination/';
	private const ERROR = 'error';
	private const FUNCTION_FILE = 'function_file';
	private const DATE_FORMAT_2 = 'Y-m-d';
	private const UJI_FUNGSI = 'Uji Fungsi';
	private const CONTRACT_FILE = 'contract_file';
	private const TINJAUAN_KONTRAK = 'Tinjauan Kontrak';
	private const HTTP_ERRORS = 'http_errors';
	private const SPB_FILE = 'spb_file';
	private const CUST_PRICE_PAYMENT = 'cust_price_payment';
	private const KUITANSI_FILE = 'kuitansi_file';
	private const KUITANSI = 'Kuitansi';
	private const FAKTUR_FILE = 'faktur_file';
	private const FAKTUR_PAJAK = 'Faktur Pajak';
	private const REFERENCE_ID = 'reference_id';
	private const PEMBAYARAN = 'Pembayaran';
	private const PEMBUATAN_SPK = 'Pembuatan SPK';
	private const PELAKSANAAN_UJI = 'Pelaksanaan Uji';
	private const REV_LAP_UJI = 'rev_lap_uji_file';
	private const LAPORAN_UJI = 'Laporan Uji';
	private const BARANG_FILE2 = 'barang_file2';
	private const TANDA_TERIMA = 'tanda_terima_file';
	private const QA_DATE = 'qa_date';
	private const SIDANG_QA = 'Sidang QA';
	private const CATATAN = 'catatan';
	private const CONTRACT_DATE = 'contract_date';
	private const SPB_NUMBER = 'spb_number';
	private const SPB_DATE = 'spb_date';
	private const PO_ID = 'PO_ID';
	private const CERTIFICATE_DATE = 'certificate_file';
	private const MEDIA_DEVICE_LOC = '/media/device/';
	private const SPK_NUMBER_URI = '&spkNumber=';
	private const SPK_ADD_NOTIF_ID_URI = 'spk/addNotif?id=';
	private const EXAMINATIONS = 'examinations';
	private const ADMIN_EXAMINATION = '/admin/examination';
	private const JSON_HEADER = 'application/json';
	private const AUTHORIZATION = 'Authorization';
	private const APP_GATEWAY_TPN_2 = 'app.gateway_tpn_2';
	private const APP_URI_API_TPN = 'app.url_api_tpn';
	private const V1_INVOICE = 'v1/invoices/';
	private const EXAMINATIONS_ID = 'examinations.id';
	private const EXAMINATION_ATTACHMENTS = 'examination_attachments';
	private const HEADER_CONTENT_TYPE = 'Content-Type: application/octet-stream';
	private const USER_NAME = 'user_name';
	private const DEV_NAME = 'dev_name';
	private const EXAM_TYPE = 'exam_type';
	private const EXAM_TYPE_DESC = 'exam_type_desc';
	private const COMPLETED = 'Completed';
	private const NOT_COMPLETED = 'Not Completed';
	private const ON_PROGRESS = 'On Progress';
	private const DATE_FORMAT_3 = 'd-m-Y';
	private const NAMA_PERANGKAT = 'nama_perangkat';
	private const MEREK_PERANGKAT = 'merk_perangkat';
	private const KAPASITAS_PERANGKAT = 'kapasitas_perangkat';
	private const PEMBUAT_PERANGKAT = 'pembuat_perangkat';
	private const MODEL_PERANGKAT = 'model_perangkat';
	private const CMB_REF_PERANGKAT = 'cmb-ref-perangkat';
	private const REF_PERANGAKAT = 'ref_perangkat';
	private const SN_PERANGKAT = 'sn_perangkat';
	private const ID_EXAM = 'id_exam';
	private const J_F_Y = 'j F Y';
	private const EQUIPMENT = 'Equipment';
	private const DOTS = '...............................';
	private const MANAGER_UREL = 'manager_urel';
	private const TTH_02 = '/TTH-02/';
	private const EXAM_ID = 'exam_id';
	private const DDS_73 = '/DDS-73/';


	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
    public function index(Request $request)
    {
		$currentUser = Auth::user();
		$logService = new LogService();
		$examinationService = new ExaminationService();

        if ($currentUser){
            $message = null;
            $paginate = 5;
			$search = trim($request->input(self::SEARCH));
			
            $examType = ExaminationType::all();

			$tempData = $examinationService->requestQuery($request, $search, $type = '', $status = '', $before = null, $after = null);

            $query = $tempData[0];
            $search = $tempData[1];
            $type = $tempData[2];
            $status = $tempData[3];
			$before = $tempData[4];
			$after = $tempData[5];
			
			$search != null ? $logService->createLog(self::SEARCH, $this::EXAMINATION, json_encode(array(self::SEARCH => $search)) ) : '';

			$data = $query->orderBy(self::UPDATED_AT, 'desc')
                        ->paginate($paginate);
			
            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.examination.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $data)
                ->with('type', $examType)
                ->with(self::SEARCH, $search)
                ->with('filterType', $type)
                ->with(self::STATUS, $status)
				->with(self::BEFORE_DATE, $before)
                ->with(self::AFTER_DATE, $after);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = Examination::where('id', $id)
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with(self::EXAMINATION_TYPE)
                            ->with(self::EXAMINATION_LAB)
                            ->with(self::DEVICE)
                            ->with(self::MEDIA)
                            ->first();
							
		$exam_history = ExaminationHistory::whereNotNull(self::CREATED_AT)
					->with('user')
                    ->where(self::EXAMINATION_ID, $id)
                    ->orderBy(self::CREATED_AT, 'DESC')
                    ->get();

        return view('admin.examination.show')
            ->with('exam_history', $exam_history)
            ->with('data', $exam);
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$currentUser = Auth::user();
		$admins = AdminRole::where('user_id', $currentUser->id)->get();
		$examinationService = new ExaminationService();
    	
        $exam = Examination::where('id', $id)
                            ->with(self::COMPANY)
                            ->with(self::EXAMINATION_TYPE)
                            ->with(self::EXAMINATION_LAB)
                            ->with(self::DEVICE)
                            ->with(self::MEDIA)
                            ->with(self::EQUIPMENT)
                            ->first();

        $labs = ExaminationLab::all();
		
		$client = new Client([
			self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
			// Base URI is used with relative requests
			// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			self::BASE_URI => config(self::APP_URI_API_BSP),
			// You can set any number of default request options.
			// self::HTTP_ERRORS => false,
			self::TIMEOUT  => 60.0,
		]);

		$tempData = $examinationService->getDetailDataFromOTR($client, $id, $exam->spk_code);

        return view('admin.examination.edit')
            ->with('data', $exam)
            ->with('labs', $labs)
            ->with('data_lab', $tempData[0])
            ->with('data_gudang', $tempData[1])
			->with('exam_approve_date', $tempData[2])
			->with('exam_schedule', $tempData[3])
			->with('admin_roles', $admins);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$currentUser = Auth::user();
		$notificationService = new NotificationService();

        $exam = Examination::find($id);
			$device = Device::findOrFail($exam->device_id);
			$exam_type = ExaminationType::findOrFail($exam->examination_type_id);

        if ($request->has('examination_lab_id')){
            $exam->examination_lab_id = $request->input('examination_lab_id');
        }
        if ($request->has(self::REGISTRATION_STATUS)){
			$status = $request->input(self::REGISTRATION_STATUS);
            $exam->registration_status = $status;
			$exam->is_loc_test = $request->input('is_loc_test');
			if($status == 1){
				/* push notif*/ 
				$data= array( 
	                "from"=>self::ADMIN,
	                "to"=>$exam->created_by,
	                self::MESSAGE=>"Registrasi Completed",
	                "url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
	                self::IS_READ=>0,
	                self::CREATED_AT=>date(self::DATE_FORMAT_1),
	                self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);
				$notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;
			    event(new Notification($data));
				
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.registrasi", "Acc Registrasi");
			}else if($status == -1){
				/* push notif*/
			     $data= array( 
					"from"=>self::ADMIN,
					"to"=>$exam->created_by,
					self::MESSAGE=>"Registrasi Not Completed",
					"url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
					self::IS_READ=>0,
					self::CREATED_AT=>date(self::DATE_FORMAT_1),
					self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);
				
				$notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;
				event(new Notification($data));
				
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::REGISTRASI,$request->input(self::KETERANGAN));
			}
        }
		if ($request->has(self::FUNCTION_STATUS)){
			$this->insertAttachment($request,$exam->id,$currentUser->id,self::BARANG_FILE,'form_penerimaan_barang_','Bukti Penerimaan & Pengeluaran Perangkat Uji1');
			$this->insertAttachment($request,$exam->id,$currentUser->id,self::FUNCTION_FILE,'function_','Laporan Hasil Uji Fungsi');
			$status = $request->input(self::FUNCTION_STATUS);
			if($exam->function_date != null){
				$exam->contract_date = $exam->function_date;
			}
			else if($exam->deal_test_date != null){
				$exam->contract_date = $exam->deal_test_date;
			}else{
				$exam->contract_date = date(self::DATE_FORMAT_2);				
			}
			$exam->function_status = $status;

            if($exam->function_test_TE == 2){
                Equipment::where(self::EXAMINATION_ID, '=' ,''.$exam->id.'')->delete();
                EquipmentHistory::where(self::EXAMINATION_ID, '=' ,''.$exam->id.'')->delete();
                
                $exam->cust_test_date = NULL;
                $exam->deal_test_date = NULL;
                $exam->urel_test_date = NULL;
                $exam->function_date = NULL;
                $exam->function_test_reason = NULL;
                $exam->function_test_date_approval = 0;
                $exam->location = 0;
            }
			/* push notif*/
			
			switch ($exam->function_test_TE) {
				case '1':
					$message = "Hasil Uji Fungsi Memenuhi";
					break;
				case '2':
					$message = "Hasil Uji Fungsi Tidak Memenuhi";
					break;
				case '3':
					$message = "Hasil Uji Fungsi lain-lain";
					break;
				
				default:
					$message = $status == 1 ? "Tahap Uji Fungsi Completed" : "Tahap Uji Fungsi Not Completed";
					break;
			}

			$data= array( 
				"from"=>"admin",
				"to"=>$exam->created_by,
				"message"=>$message,
				"url"=>"pengujian/".$exam->id."/detail",
				"is_read"=>0,
				"created_at"=>date("Y-m-d H:i:s"),
				"updated_at"=>date("Y-m-d H:i:s")
			);

			$notification_id = $notificationService->make($data);
			$data['id'] = $notification_id;
			event(new Notification($data));

			if($status == -1){
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Uji Fungsi",$request->input(self::KETERANGAN));
			}
        }
        $spk_created = 0;
        if ($request->has(self::CONTRACT_STATUS)){
			if ($request->hasFile(self::CONTRACT_FILE)) {
				$name_file = 'contract_'.$request->file(self::CONTRACT_FILE)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::CONTRACT_FILE)->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', self::TINJAUAN_KONTRAK)->where(self::EXAMINATION_ID, ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = self::TINJAUAN_KONTRAK;
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}
				}else{
					Session::flash(self::ERROR, 'Save contract review to directory failed');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}
			}
			$status = $request->input(self::CONTRACT_STATUS);
            $exam->contract_status = $status;
			if($status == 1){
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$id;
				$attach = ExaminationAttach::where('name', self::TINJAUAN_KONTRAK)->where(self::EXAMINATION_ID, ''.$id.'')->first();
					$attach_name = $attach->attachment;
				
				$client = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
					// Base URI is used with relative requests
					// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					self::BASE_URI => config(self::APP_URI_API_BSP),
					self::HTTP_ERRORS => false,
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);

				$exam_forOTR = Examination::where('id', $exam->id)
				->with(self::EXAMINATION_TYPE)
				->with(self::EXAMINATION_LAB)
				->first();

				if ($exam->spk_code == null && $exam->company_id == '0fbf131c-32e3-4c9a-b6e0-a0f217cb2830'){
                    $spk_number_forOTR = $this->generateSPKCode($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
                    $exam->spk_code = $spk_number_forOTR;
                    $exam->spk_date = date(self::DATE_FORMAT_2);
                    $spk_created = 1;
                    $exam->spb_status = $status;
                    $exam->payment_status = $status;
                }

				if($exam->contract_status){
					/* push notif*/
		            $data= array( 
						"from"=>self::ADMIN,
						"to"=>$exam->created_by,
						self::MESSAGE=>"Tinjauan Kontrak Completed",
						"url"=>self::PENGUJIAN_LOC.$id.self::DETAIL_LOC,
						self::IS_READ=>0,
						self::CREATED_AT=>date(self::DATE_FORMAT_1),
						self::UPDATED_AT=>date(self::DATE_FORMAT_1)
					);
					
					$notification_id = $notificationService->make($data);
					$data['id'] = $notification_id;
					event(new Notification($data));
				}else{
					/* push notif*/
		            $data= array( 
						"from"=>self::ADMIN,
						"to"=>$exam->created_by,
						self::MESSAGE=>"Tinjauan Kontrak Not Completed",
						"url"=>self::PENGUJIAN_LOC.$id.self::DETAIL_LOC,
						self::IS_READ=>0,
						self::CREATED_AT=>date(self::DATE_FORMAT_1),
						self::UPDATED_AT=>date(self::DATE_FORMAT_1)
					);

					$notification_id = $notificationService->make($data);
					$data['id'] = $notification_id;
					event(new Notification($data));
				}
				
			}else if($status == -1){
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Tinjauan Pustaka",$request->input(self::KETERANGAN));
			}
        }
		if ($request->has(self::SPB_STATUS)){
			$this->insertAttachment($request,$exam->id,$currentUser->id,self::SPB_FILE,'spb_','SPB');
			$status = $request->input(self::SPB_STATUS);
            $exam->spb_status = $status;
			if($status == 1){
				$exam->price = str_replace(".",'',$request->input('exam_price'));
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$id;
				$attach = ExaminationAttach::where('name', 'SPB')->where(self::EXAMINATION_ID, ''.$id.'')->first();
					$attach_name = $attach->attachment;

				/* push notif*/
	           	$data= array( 
					"from"=>self::ADMIN,
					"to"=>$exam->created_by,
					self::MESSAGE=>"URel mengirimkan SPB untuk dibayar",
					"url"=>self::PENGUJIAN_LOC.$exam->id."/pembayaran",
					self::IS_READ=>0,
					self::CREATED_AT=>date(self::DATE_FORMAT_1),
					self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);

	            $notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;
			    event(new Notification($data));

				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.spb", "Upload SPB",$path_file."/".$attach_name);
			}else if($status == -1){
				$exam->price = str_replace(".",'',$request->input('exam_price'));
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"SPB",$request->input(self::KETERANGAN));
			}
        }
		
        if ($request->has(self::PAYMENT_STATUS)){
			if ($request->has(self::CUST_PRICE_PAYMENT)){
				$exam->cust_price_payment = str_replace(".",'',$request->input(self::CUST_PRICE_PAYMENT));
			}
			if ($request->hasFile(self::KUITANSI_FILE)) {
				$name_file = 'kuitansi_'.$request->file(self::KUITANSI_FILE)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::KUITANSI_FILE)->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', self::KUITANSI)->where(self::EXAMINATION_ID, ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = self::KUITANSI;
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}					
				}else{
					Session::flash(self::ERROR, 'Save kuitansi to directory failed');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}
			}
			if ($request->hasFile(self::FAKTUR_FILE)) {
				$name_file = 'faktur_'.$request->file(self::FAKTUR_FILE)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::FAKTUR_FILE)->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', self::FAKTUR_PAJAK)->where(self::EXAMINATION_ID, ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = self::FAKTUR_PAJAK;
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}					
				}else{
					Session::flash(self::ERROR, 'Save Faktur Pajak to directory failed');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}
			}
            $status = $request->input(self::PAYMENT_STATUS);
            $exam->payment_status = $status;
			if($status == 1){
				if($this->cekRefID($exam->id) == 0){
					$income = new Income;
					$income->id = Uuid::uuid4();
					$income->company_id = $exam->company_id;
					$income->inc_type = 1; 
					$income->reference_id = $exam->id; 
					$income->reference_number = $exam->spb_number;
					$income->tgl = $exam->spb_date;
					$income->created_by = $currentUser->id;

				}else{
					$income = Income::where(self::REFERENCE_ID, $exam->id)->first();
				}
					// ($item->payment_method == 1)?'ATM':'Kartu Kredit'
					$income->price = ($request->input(self::CUST_PRICE_PAYMENT) != NULL) ? str_replace(".",'',$request->input(self::CUST_PRICE_PAYMENT)) : 0;
					$income->updated_by = $currentUser->id;
					$income->save();

				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran");
				
				$client = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
					// Base URI is used with relative requests
					// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					self::BASE_URI => config(self::APP_URI_API_BSP),
					self::HTTP_ERRORS => false,
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);
				
				$exam_forOTR = Examination::where('id', $exam->id)
				->with(self::EXAMINATION_TYPE)
				->with(self::EXAMINATION_LAB)
				->first();

                if ($exam->spk_code == null){
                    $spk_number_forOTR = $this->generateSPKCode($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
                    $exam->spk_code = $spk_number_forOTR;
                    $exam->spk_date = date(self::DATE_FORMAT_2);
                    $spk_created = 1;
                }

				$data= array( 
					"from"=>self::ADMIN,
					"to"=>$exam->created_by,
					self::MESSAGE=>$exam->payment_status ? "Pembayaran Completed" : "Pembayaran Not Completed",
					"url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
					self::IS_READ=>0,
					self::CREATED_AT=>date(self::DATE_FORMAT_1),
					self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);

				$notification_id = $notificationService->make($data);
				$data['id'] = $notification_id;
				event(new Notification($data));
				
			}else if($status == -1){
				Income::where(self::REFERENCE_ID, '=' ,''.$exam->id.'')->delete();
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::PEMBAYARAN,$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::SPK_STATUS)){
            $status = $request->input(self::SPK_STATUS);
            $exam->spk_status = $status;
			if($status == -1){
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::PEMBUATAN_SPK,$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::EXAMINATION_STATUS)){
            $status = $request->input(self::EXAMINATION_STATUS);
            $exam->examination_status = $status;
			if($status == -1){
			
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::PELAKSANAAN_UJI,$request->input(self::KETERANGAN));
			}else{
				$data= array( 
					"from"=>self::ADMIN,
					"to"=>$exam->created_by,
					self::MESSAGE=>$status ? "Pelaksanaan Uji Completed" : "Pelaksanaan Uji Not Completed",
					"url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
					self::IS_READ=>0,
					self::CREATED_AT=>date(self::DATE_FORMAT_1),
					self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);

				$notification_id = $notificationService->make($data);
				$data['id'] = $notification_id;
				event(new Notification($data));				
			}
			if ($request->has('lab_to_gudang_date')){
				$query_update = "UPDATE equipment_histories
					SET 
						action_date = '".$request->input('lab_to_gudang_date')."',
						updated_by = '".$currentUser->id."',
						updated_at = '".date(self::DATE_FORMAT_1)."'
					WHERE location = 2 AND examination_id = '".$id."'";
				DB::update($query_update);
			}
        }
        if ($request->has(self::RESUME_STATUS)){
			if(!$request->hasFile(self::REV_LAP_UJI) && $request->has('hide_attachment_form-lap-uji') && $exam->resume_status == 0 && $exam->BILLING_ID != null){
	                $data_upload [] = array(
	                    'name'=>"delivered",
	                    'contents'=>json_encode(['by'=>$currentUser->name, self::REFERENCE_ID => $currentUser->id]),
	                );

	                $this->api_upload($data_upload,$exam->BILLING_ID);
			}
			if ($request->hasFile(self::REV_LAP_UJI)) {
				$name_file = 'rev_lap_uji_'.$request->file(self::REV_LAP_UJI)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::REV_LAP_UJI)->move($path_file,$name_file)){
                    /*TPN api_upload*/
		            if($exam->BILLING_ID != null){
		                $data_upload [] = array(
		                    'name'=>"delivered",
		                    'contents'=>json_encode(['by'=>$currentUser->name, self::REFERENCE_ID => $currentUser->id]),
		                );

		                $this->api_upload($data_upload,$exam->BILLING_ID);
		            }
					
					$attach = new ExaminationAttach;
					$attach->id = Uuid::uuid4();
					$attach->examination_id = $exam->id; 
					$attach->name = 'Revisi Laporan Uji';
					$attach->attachment = $name_file;
					$attach->created_by = $currentUser->id;
					$attach->updated_by = $currentUser->id;

					$attach->save();
				}else{
					Session::flash(self::ERROR, 'Save Revisi Laporan Uji to directory failed');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}
			}
            $status = $request->input(self::RESUME_STATUS);
            $exam->resume_status = $status;
			
			if($status == -1){
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Laporan Uji",$request->input(self::KETERANGAN));
			
			}else{
				$data= array( 
					"from"=>self::ADMIN,
					"to"=>$exam->created_by,
					self::MESSAGE=>$status ? "Laporan Uji Completed" : "Laporan Uji Not Completed",
					"url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
					self::IS_READ=>0,
					self::CREATED_AT=>date(self::DATE_FORMAT_1),
					self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);
				
			}
		}
		$this->insertAttachment($request,$exam->id,$currentUser->id,self::BARANG_FILE2,'form_penerimaan_barang2_','Bukti Penerimaan & Pengeluaran Perangkat Uji2');
		$this->insertAttachment($request,$exam->id,$currentUser->id,self::TANDA_TERIMA,'form_tanda_terima_hasil_pengujian_','Tanda Terima Hasil Pengujian');
		if ($request->has(self::QA_STATUS)){
            $status = $request->input(self::QA_STATUS);
            $passed = $request->input('passed');
            $exam->qa_status = $status;
            $exam->qa_passed = $passed;
            if($exam->qa_passed == 1){  

            	if(strpos($exam->keterangan, self::QA_DATE) !== false){
            		$data_ket = explode("qa_date", $exam->keterangan);
            		$devnc_exam = Examination::find($data_ket[2]);

            		if($devnc_exam){
			        	$devnc_exam->qa_passed = 0;

			        	try{
			        		$devnc_exam->save();
			        	} catch(Exception $e){
							// DO NOTHING
					    }
			        }
            	}

            	$data= array( 
	                "from"=>self::ADMIN,
	                "to"=>$exam->created_by,
	                self::MESSAGE=>"Perangkat Lulus Sidang QA",
	                "url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
	                self::IS_READ=>0,
	                self::CREATED_AT=>date(self::DATE_FORMAT_1),
	                self::UPDATED_AT=>date(self::DATE_FORMAT_1)
	            );

				$notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;
			    event(new Notification($data));

            }else{ 

            	$exam->certificate_status = 1;

		      	$data= array( 
	                "from"=>self::ADMIN,
	                "to"=>$exam->created_by,
	                self::MESSAGE=>"Perangkat tidak lulus Sidang QA",
	                "url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
	                self::IS_READ=>0,
	                self::CREATED_AT=>date(self::DATE_FORMAT_1),
	                self::UPDATED_AT=>date(self::DATE_FORMAT_1)
				);
				
				$notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;
			    event(new Notification($data));

            }
           
			if($status == -1){
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::SIDANG_QA,$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::CERTIFICATE_STATUS)){
            $status = $request->input(self::CERTIFICATE_STATUS);
            $exam->certificate_status = $status;
            $data= array( 
				"from"=>self::ADMIN,
				"to"=>$exam->created_by,
				self::MESSAGE=>$exam->certificate_status ? "Sertifikat Completed" : "Sertifikat Not Completed",
				"url"=>self::PENGUJIAN_LOC.$exam->id.self::DETAIL_LOC,
				self::IS_READ=>0,
				self::CREATED_AT=>date(self::DATE_FORMAT_1),
				self::UPDATED_AT=>date(self::DATE_FORMAT_1)
			);
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sertifikat", "Penerbitan Sertfikat");
			}else if($status == -1){
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Pembuatan Sertifikat",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has('resume_date')){
            $exam->resume_date = $request->input('resume_date');
        }
        if ($request->has(self::QA_DATE)){
            $exam->qa_date = $request->input(self::QA_DATE);
        }
        if ($request->has('certificate_date')){
            $exam->certificate_date = $request->input('certificate_date');
        }
		if ($request->has(self::CATATAN)){
            $exam->catatan = $request->input(self::CATATAN);
        }
		if ($request->has(self::CONTRACT_DATE)){
            $exam->contract_date = $request->input(self::CONTRACT_DATE);
        }
		if ($request->has('testing_start')){
            $exam->testing_start = $request->input('testing_start');
        }
		if ($request->has('testing_end')){
            $exam->testing_end = $request->input('testing_end');
        }
		if ($request->has(self::SPB_NUMBER)){
            $exam->spb_number = $request->input(self::SPB_NUMBER);
        }
		if ($request->has(self::SPB_DATE)){
            $exam->spb_date = $request->input(self::SPB_DATE);
        }
		if ($request->has(self::PO_ID)){
			if($exam->payment_status == 1){
				Session::flash(self::ERROR, 'SPB Already Paid');
                return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
			}
			if($exam->BILLING_ID){
				$data_cancel_billing = [
	            	"canceled" => [
						self::MESSAGE => "-",
						"by" => $currentUser->name,
                    	self::REFERENCE_ID => $currentUser->id
					]
	            ];
				$this->api_cancel_billing($exam->BILLING_ID, $data_cancel_billing);
			}
			$data_billing = [
                "draft_id" => $request->input(self::PO_ID),
                "created" => [
                    "by" => $currentUser->name,
                    self::REFERENCE_ID => $currentUser->id
                ]
            ];

            $billing = $this->api_billing($data_billing);

            $exam->PO_ID = $request->input(self::PO_ID);
            $exam->BILLING_ID = $billing && $billing->status ? $billing->data->_id : null;
        }

        if ($request->hasFile(self::CERTIFICATE_DATE)) {
			$name_file = 'sertifikat_'.$request->file(self::CERTIFICATE_DATE)->getClientOriginalName();
			$path_file = public_path().self::MEDIA_DEVICE_LOC.$exam->device_id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::CERTIFICATE_DATE)->move($path_file,$name_file)){
                $device = Device::findOrFail($exam->device_id);
                if ($device){
                    $device->certificate = $name_file;
                    $device->status = $request->input(self::CERTIFICATE_STATUS);
                    $device->valid_from = $request->input('valid_from');
                    $device->valid_thru = $request->input('valid_thru');
                    $device->cert_number = $request->input('cert_number');

                    $device->save();
                }
            }else{
                Session::flash(self::ERROR, 'Save spb to directory failed');
                return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
            }
        }
		
			$device = Device::findOrFail($exam->device_id);
			$exam_type = ExaminationType::findOrFail($exam->examination_type_id);
		
        $exam->updated_by = $currentUser->id;

        try{
            $exam->save();
			if($spk_created == 1){
				$res_exam_schedule = $client->get(self::SPK_ADD_NOTIF_ID_URI.$exam->id.self::SPK_NUMBER_URI.$spk_number_forOTR);
				$exam_schedule = $res_exam_schedule->getStatusCode() == '200' ? json_decode($res_exam_schedule->getBody()) : null;
				if($exam_schedule && !$exam_schedule->status){
					$api_logs = new Api_logs;
					$api_logs->send_to = "OTR";
					$api_logs->route = self::SPK_ADD_NOTIF_ID_URI.$exam->id.self::SPK_NUMBER_URI.$spk_number_forOTR;
					$api_logs->status = $exam_schedule->status;
					$api_logs->data = json_encode($exam_schedule);
					$api_logs->reference_id = $exam->id;
					$api_logs->reference_table = self::EXAMINATIONS;
					$api_logs->created_by = $currentUser->id;
					$api_logs->updated_by = $currentUser->id;

					$api_logs->save();
				}elseif ($exam_schedule == null) {
					$api_logs = new Api_logs;
					$api_logs->send_to = "OTR";
					$api_logs->route = self::SPK_ADD_NOTIF_ID_URI.$exam->id.self::SPK_NUMBER_URI.$spk_number_forOTR;
					$api_logs->status = 0;
					$api_logs->data = "-";
					$api_logs->reference_id = $exam->id;
					$api_logs->reference_table = self::EXAMINATIONS;
					$api_logs->created_by = $currentUser->id;
					$api_logs->updated_by = $currentUser->id;

					$api_logs->save();
				}
			}
             
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $exam->id;
				$exam_hist->date_action = date(self::DATE_FORMAT_1);
				$exam_hist->tahap = $request->input(self::STATUS);
					if ($request->has(self::REGISTRATION_STATUS)){$exam_hist->status = $request->input(self::REGISTRATION_STATUS);}
					if ($request->has(self::FUNCTION_STATUS)){$exam_hist->status = $request->input(self::FUNCTION_STATUS);}
					if ($request->has(self::CONTRACT_STATUS)){$exam_hist->status = $request->input(self::CONTRACT_STATUS);}
					if ($request->has(self::SPB_STATUS)){$exam_hist->status = $request->input(self::SPB_STATUS);}
					if ($request->has(self::PAYMENT_STATUS)){$exam_hist->status = $request->input(self::PAYMENT_STATUS);}
					if ($request->has(self::SPK_STATUS)){$exam_hist->status = $request->input(self::SPK_STATUS);}
					if ($request->has(self::EXAMINATION_STATUS)){$exam_hist->status = $request->input(self::EXAMINATION_STATUS);}
					if ($request->has(self::RESUME_STATUS)){$exam_hist->status = $request->input(self::RESUME_STATUS);}
					if ($request->has(self::QA_STATUS)){$exam_hist->status = $request->input(self::QA_STATUS);}
					if ($request->has(self::CERTIFICATE_STATUS)){$exam_hist->status = $request->input(self::CERTIFICATE_STATUS);}
				$exam_hist->keterangan = $request->input(self::KETERANGAN);
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date(self::DATE_FORMAT_1);
				$exam_hist->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Update ".$request->input(self::STATUS);   
                $logs->data = $exam;
                $logs->created_by = $currentUser->id;
                $logs->page = self::EXAMINATION;
                $logs->save();

            Session::flash(self::MESSAGE, 'Examination successfully updated');
            return redirect(self::ADMIN_EXAMINATION);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
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

    public function generateKuitansi(Request $request) {
    	$id = $request->input('id');
        
		$exam = Examination::where("id", $id)->first();
		$this->generateFromTPN($exam, self::KUITANSI, '/exportpdf');
    }

    public function generateTaxInvoice(Request $request) {
        $id = $request->input('id');

        $exam = Examination::select(DB::raw('companies.name as company_name, examination_attachments.tgl as payment_date, examinations.*, devices.name, devices.mark, devices.capacity, devices.model'))
    	->where(self::EXAMINATIONS_ID, $id)
    	->whereNotExists(function ($query) {
           	$query->select(DB::raw(1))
                 ->from(self::EXAMINATION_ATTACHMENTS)
                 ->whereRaw('examination_attachments.examination_id = examinations.id')
                 ->whereRaw('examination_attachments.name = "Faktur Pajak"')
            ;
       	})->orWhereExists(function ($query) {
           	$query->select(DB::raw(1))
                 ->from(self::EXAMINATION_ATTACHMENTS)
                 ->whereRaw('examination_attachments.examination_id = examinations.id')
                 ->whereRaw('examination_attachments.name = "Faktur Pajak"')
                 ->whereRaw('examination_attachments.attachment = ""')
            ;
       	})
       	->join('companies', 'examinations.company_id', '=', 'companies.id')
        ->join('devices', 'examinations.device_id', '=', 'devices.id')
        ->leftJoin(self::EXAMINATION_ATTACHMENTS, function($leftJoin){
            $leftJoin->on(self::EXAMINATIONS_ID, '=', 'examination_attachments.examination_id');
            $leftJoin->on(DB::raw('examination_attachments.name'), DB::raw('='),DB::raw("'File Pembayaran'"));
        })
        ->first();

        $this->generateFromTPN($exam, self::FAKTUR_PAJAK, '/taxinvoice/pdf');
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

    public function downloadForm($id)
    {
        $exam = Examination::find($id);

        if ($exam){
            $file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id.'/'.$exam->attachment;
            $headers = array(
              self::HEADER_CONTENT_TYPE,
            );

            return Response::download($file, $exam->attachment, $headers);
        }
    }

    public function printForm($id)
    {
        $exam = Examination::find($id);

        if ($exam){
            $file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id.'/'.$exam->attachment;
            $headers = array(
              self::HEADER_CONTENT_TYPE,
            );

            return Response::file($file);
        }
    }

    public function downloadMedia($id, $name)
    {
        if (strcmp($name, 'certificate') == 0){
            $device = Device::findOrFail($id);

            if ($device){
                $file = public_path().self::MEDIA_DEVICE_LOC.$device->id.'/'.$device->certificate;
                $headers = array(
                  self::HEADER_CONTENT_TYPE,
                );

                return Response::download($file, $device->attachment, $headers);
            }
        } else{
            $exam = ExaminationAttach::where(self::EXAMINATION_ID, $id)
                                ->where('name','like','%'.$name.'%')
                                ->first();

            if ($exam){
                $file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->examination_id.'/'.$exam->attachment;
                $headers = array(
                  self::HEADER_CONTENT_TYPE,
                );

                return Response::download($file, $exam->attachment, $headers);
            }
        }
    }

    public function downloadRefUjiFile($id)
    {
        $data = ExaminationAttach::find($id);

        if ($data){
            $file = public_path().self::MEDIA_EXAMINATION_LOC.$data->examination_id.'/'.$data->attachment;
            $headers = array(
              self::HEADER_CONTENT_TYPE,
            );

            return Response::download($file, $data->attachment, $headers);
        }
    }

    public function printMedia($id, $name)
    {
        if (strcmp($name, 'certificate') == 0){
            $device = Device::findOrFail($id);

            if ($device){
                $file = public_path().self::MEDIA_DEVICE_LOC.$device->id.'/'.$device->certificate;
                $headers = array(
                  'Content-Type: application/pdf',
                );

                return Response::file($file);
            }
        } else{
            $exam = ExaminationAttach::where(self::EXAMINATION_ID, $id)
                                ->where('name','like','%'.$name.'%')
                                ->first();

            if ($exam){
                $file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->examination_id.'/'.$exam->attachment;
                $headers = array(
                  'Content-Type: application/pdf',
                );

                return Response::file($file);
            }
        }
    }

    /**
     * Send an e-mail notification to the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function sendEmailNotification($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }
	
	public function sendEmailNotification_wAttach($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $attach)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc
			), function ($m) use ($data,$subject,$attach) {
            $m->to($data->email)->subject($subject);
			$m->attach($attach);
        });

        return true;
    }
	
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.

		$logService = new LogService();
		$examinationService = new ExaminationService();

        $search = trim($request->input(self::SEARCH));

        $tempData = $examinationService->requestQuery($request, $search, $type = '', $status = '', $before = null, $after = null);

		$query = $tempData[0];
		$search = $tempData[1];
		$type = $tempData[2];
		$status = $tempData[3];
		$before = $tempData[4];
		$after = $tempData[5];

		$data = $query->orderBy(self::UPDATED_AT, 'desc')->get();

		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'Tipe Pengujian',
			'Tahap Pengujian',
			'Nama Pemohon',
			'Email Pemohon',
			'Alamat Pemohon',
			'Telepon Pemohon',
			'Faksimil Pemohon',
			'Jenis Perusahaan',
			'Nama Perusahaan',
			'Alamat Perusahaan',
			'Email Perusahaan',
			'Telepon Perusahaan',
			'Faksimil Perusahaan',
			'NPWP Perusahaan',
			'SIUPP Perusahaan',
			'Tgl. SIUPP Perusahaan',
			'Sertifikat Perusahaan',
			'Tgl. Sertifikat Perusahaan',
			'Nama Perangkat',
			'Merk/Pabrik Perangkat',
			'Kapasitas/Kecepatan Perangkat',
			'Pembuat Perangkat',
			'Nomor Seri Perangkat',
			'Model/Tipe Perangkat',
			'Referensi Uji Perangkat',
			'Tanggal Berlaku Perangkat',
			'Nomor SPK',
			'Tanggal SPK',
			self::REGISTRASI,
			self::UJI_FUNGSI,
			self::TINJAUAN_KONTRAK,
			'SPB',
			self::PEMBAYARAN,
			self::PEMBUATAN_SPK,
			self::PELAKSANAAN_UJI,
			self::LAPORAN_UJI,
			self::SIDANG_QA,
			'Penerbitan Sertifikat',
			'Total Biaya'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
		foreach ($data as $row) {
			if( $row->registration_status == 1) {
				$status_reg = self::COMPLETED;
			}else{
				if( $row->registration_status == -1) {
					$status_reg = self::NOT_COMPLETED;
				}else{
					$status_reg = self::ON_PROGRESS;
				}
			}
				
			if( $row->function_status == 1) {
				$status_func = self::COMPLETED;
			}else{
				if( $row->function_status == -1) {
					$status_func = self::NOT_COMPLETED;
				}else{
					$status_func = self::ON_PROGRESS;
				}
			}
				
			if( $row->contract_status == 1) {
				$status_cont = self::COMPLETED;
			}else{
				if( $row->contract_status == -1) {
					$status_cont = self::NOT_COMPLETED;
				}else{
					$status_cont = self::ON_PROGRESS;
				}
			}
				
			if( $row->spb_status == 1) {
				$status_spb = self::COMPLETED;
			}else{
				if( $row->spb_status == -1) {
					$status_spb = self::NOT_COMPLETED;
				}else{
					$status_spb = self::ON_PROGRESS;
				}
			}
			
			if( $row->payment_status == 1) {
				$status_pay = self::COMPLETED;
			}else{
				if( $row->payment_status == -1) {
					$status_pay = self::NOT_COMPLETED;
				}else{
					$status_pay = self::ON_PROGRESS;
				}
			}
				
			if( $row->spk_status == 1) {
				$status_spk = self::COMPLETED;
			}else{
				if( $row->spk_status == -1) {
					$status_spk = self::NOT_COMPLETED;
				}else{
					$status_spk = self::ON_PROGRESS;
				}
			}
				
			if( $row->examination_status == 1) {
				$status_exam = self::COMPLETED;
			}else{
				if( $row->examination_status == -1) {
					$status_exam = self::NOT_COMPLETED;
				}else{
					$status_exam = self::ON_PROGRESS;
				}
			}
				
			if( $row->resume_status == 1) {
				$status_resu = self::COMPLETED;
			}else{
				if( $row->resume_status == -1) {
					$status_resu = self::NOT_COMPLETED;
				}else{
					$status_resu = self::ON_PROGRESS;
				}
			}
				
			if( $row->qa_status == 1) {
				$status_qa = self::COMPLETED;
			}else{
				if( $row->qa_status == -1) {
					$status_qa = self::NOT_COMPLETED;
				}else{
					$status_qa = self::ON_PROGRESS;
				}
			}

			if( $row->certificate_status == 1) {
				$status_cert = self::COMPLETED;
			}else{
				if( $row->certificate_status == -1) {
					$status_cert = self::NOT_COMPLETED;
				}else{
					$status_cert = self::ON_PROGRESS;
				}
			}

			/*Tahap Pengujian*/

			if($row->registration_status != 1){
				$tahap = self::REGISTRASI;
			}
			if($row->registration_status == 1 && $row->function_status != 1){
				$tahap = self::UJI_FUNGSI;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status != 1){
				$tahap = self::TINJAUAN_KONTRAK;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status != 1){
				$tahap = 'SPB';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status != 1){
				$tahap = self::PEMBAYARAN;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status != 1){
				$tahap = self::PEMBUATAN_SPK;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status != 1){
				$tahap = self::PELAKSANAAN_UJI;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status == 1 && $row->resume_status != 1){
				$tahap = self::LAPORAN_UJI;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status == 1 && $row->resume_status == 1 && $row->qa_status != 1){
				$tahap = self::SIDANG_QA;
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status == 1 && $row->resume_status == 1 && $row->qa_status == 1 && $row->certificate_status != 1){
				$tahap = 'Penerbitan Sertifikat';
			}

			/*End Tahap Pengujian*/

			/*ExaminationType*/
			if(!isset($row->examinationType->name)){
				$examType_name = '';
			}else{
				$examType_name = $row->examinationType->name;
			}
			if(!isset($row->examinationType->description)){
				$examType_desc = '';
			}else{
				$examType_desc = $row->examinationType->description;
			}
			/*EndExaminationType*/
			
			/*User*/
			if(!isset($row->user->name)){
				$user_name = '';
			}else{
				$user_name = $row->user->name;
			}
			if(!isset($row->user->email)){
				$user_email = '';
			}else{
				$user_email = $row->user->email;
			}
			if(!isset($row->user->address)){
				$user_address = '';
			}else{
				$user_address = $row->user->address;
			}
			if(!isset($row->user->phone_number)){
				$user_phone_number = '';
			}else{
				$user_phone_number = $row->user->phone_number;
			}
			if(!isset($row->user->fax)){
				$user_fax = '';
			}else{
				$user_fax = $row->user->fax;
			}
			/*EndUser*/
			
			/*Company*/
			if(!isset($row->company->siup_date)){
				$siup_date = '';
			}else{
				$siup_date = date(self::DATE_FORMAT_3, strtotime($row->company->siup_date));
			}
			if(!isset($row->company->qs_certificate_date)){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date(self::DATE_FORMAT_3, strtotime($row->company->qs_certificate_date));
			}
			if(!isset($row->company->name)){
				$company_name = '';
			}else{
				$company_name = $row->company->name;
			}
			if(!isset($row->company->address)){
				$company_address = '';
			}else{
				$company_address = $row->company->address;
			}
			if(!isset($row->company->city)){
				$company_city = '';
			}else{
				$company_city = $row->company->city;
			}
			if(!isset($row->company->postal_code)){
				$company_postal_code = '';
			}else{
				$company_postal_code = $row->company->postal_code;
			}
			if(!isset($row->company->email)){
				$company_email = '';
			}else{
				$company_email = $row->company->email;
			}
			if(!isset($row->company->phone_number)){
				$company_phone_number = '';
			}else{
				$company_phone_number = $row->company->phone_number;
			}
			if(!isset($row->company->fax)){
				$company_fax = '';
			}else{
				$company_fax = $row->company->fax;
			}
			if(!isset($row->company->npwp_number)){
				$company_npwp_number = '';
			}else{
				$company_npwp_number = $row->company->npwp_number;
			}
			if(!isset($row->company->siup_number)){
				$company_siup_number = '';
			}else{
				$company_siup_number = $row->company->siup_number;
			}
			if(!isset($row->company->qs_certificate_number)){
				$company_qs_certificate_number = '';
			}else{
				$company_qs_certificate_number = $row->company->qs_certificate_number;
			}
			/*EndCompany*/
			
			/*Device*/
			if(!isset($row->device->valid_from)){
				$valid_from = '';
			}else{
				$valid_from = date(self::DATE_FORMAT_3, strtotime($row->device->valid_from));
			}
			if(!isset($row->device->valid_thru)){
				$valid_thru = '';
			}else{
				$valid_thru = date(self::DATE_FORMAT_3, strtotime($row->device->valid_thru));
			}
			if(!isset($row->device->name)){
				$device_name = '';
			}else{
				$device_name = $row->device->name;
			}
			if(!isset($row->device->mark)){
				$device_mark = '';
			}else{
				$device_mark = $row->device->mark;
			}
			if(!isset($row->device->capacity)){
				$device_capacity = '';
			}else{
				$device_capacity = $row->device->capacity;
			}
			if(!isset($row->device->manufactured_by)){
				$device_manufactured_by = '';
			}else{
				$device_manufactured_by = $row->device->manufactured_by;
			}
			if(!isset($row->device->serial_number)){
				$device_serial_number = '';
			}else{
				$device_serial_number = $row->device->serial_number;
			}
			if(!isset($row->device->model)){
				$device_model = '';
			}else{
				$device_model = $row->device->model;
			}
			if(!isset($row->device->test_reference)){
				$device_test_reference = '';
			}else{
				$device_test_reference = $row->device->test_reference;
			}
			/*EndDevice*/
			
			if($row->spk_date==''){
				$spk_date = '';
			}else{
				$spk_date = date(self::DATE_FORMAT_3, strtotime($row->spk_date));
			}
			$examsArray[] = [
				"".$examType_name." (".$examType_desc.")",
				$tahap,
				$user_name,
				$user_email,
				$user_address,
				$user_phone_number,
				$user_fax,
				$row->jns_perusahaan,
				$company_name,
				$company_address.", kota".$company_city.", kode pos".$company_postal_code,
				$company_email,
				$company_phone_number,
				$company_fax,
				$company_npwp_number,
				$company_siup_number,
				$siup_date,
				$company_qs_certificate_number,
				$qs_certificate_date,
				$device_name,
				$device_mark,
				$device_capacity,
				$device_manufactured_by,
				$device_serial_number,
				$device_model,
				$device_test_reference,
				$valid_from." s.d. ".$valid_thru,
				$row->spk_code,
				$spk_date,
				$status_reg,
				$status_func,
				$status_cont,
				$status_spb,
				$status_pay,
				$status_spk,
				$status_exam,
				$status_resu,
				$status_qa,
				$status_cert,
				$row->price
			];
		}
		
		$logService->createLog("download_excel", $this::EXAMINATION, "");

		// Generate and return the spreadsheet
		Excel::create('Data Pengujian', function($excel) use ($examsArray) {
			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx');
	}
	
	public function revisi($id)
    {
        $exam = Examination::where('id', $id)
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with(self::EXAMINATION_TYPE)
                            ->with(self::EXAMINATION_LAB)
                            ->with(self::DEVICE)
                            ->with(self::MEDIA)
                            ->first();
		$query_stels = "SELECT * FROM stels WHERE is_active = 1";
		$data_stels = DB::select($query_stels);
		
        return view('admin.examination.revisi')
            ->with('data', $exam)
			->with('data_stels', $data_stels);
    }
	
	public function updaterevisi(Request $request)
    {
		$currentUser = Auth::user();
		$notificationService = new NotificationService();
		
		$device = Device::findOrFail($request->input('id_perangkat'));

        if ($request->has(self::NAMA_PERANGKAT)){
            $device->name = $request->input(self::NAMA_PERANGKAT);
        }
			
        if ($request->has(self::MEREK_PERANGKAT)){
            $device->mark = $request->input(self::MEREK_PERANGKAT);
        }
			
        if ($request->has(self::KAPASITAS_PERANGKAT)){
            $device->capacity = $request->input(self::KAPASITAS_PERANGKAT);
        }
			
        if ($request->has(self::PEMBUAT_PERANGKAT)){
            $device->manufactured_by = $request->input(self::PEMBUAT_PERANGKAT);
        }
			
        if ($request->has(self::MODEL_PERANGKAT)){
            $device->model = $request->input(self::MODEL_PERANGKAT);
        }
			
        if ($request->has(self::CMB_REF_PERANGKAT)){
            $device->test_reference = $request->input(self::CMB_REF_PERANGKAT);
			$ref_perangkat = $request->input(self::CMB_REF_PERANGKAT);
        }
			
        if ($request->has(self::REF_PERANGAKAT)){
            $device->test_reference = $request->input(self::REF_PERANGAKAT);
			$ref_perangkat = $request->input(self::REF_PERANGAKAT);
        }
			
        if ($request->has(self::SN_PERANGKAT)){
            $device->serial_number = $request->input(self::SN_PERANGKAT);
        }
			
		$device->updated_by = '".$currentUser->id."';
		$device->updated_at = date(self::DATE_FORMAT_1);
        
        try{
            $device->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "update";   
            $logs->data = $device;
            $logs->created_by = $currentUser->id;
            $logs->page = "REVISI";
            $logs->save();

            /* push notif*/
			$data= array( 
            	"from"=>$currentUser->id,
            	"to"=>$device->created_by,
            	self::MESSAGE=>"Urel mengedit data pengujian",
            	"url"=>self::PENGUJIAN_LOC.$request->input(self::ID_EXAM).self::DETAIL_LOC,
            	self::IS_READ=>0,
            	self::CREATED_AT=>date(self::DATE_FORMAT_1),
            	self::UPDATED_AT=>date(self::DATE_FORMAT_1)
            );

			$notification_id = $notificationService->make($data);
			$data['id'] = $notification_id;
			event(new Notification($data));

            Session::flash(self::MESSAGE, 'Examination successfully updated');
			$this->sendEmailRevisi(
				$request->input('exam_created'),
				$request->input(self::EXAM_TYPE),
				$request->input('exam_desc'),
				$request->input('hidden_nama_perangkat'),
				$request->input(self::NAMA_PERANGKAT),
				$request->input('hidden_merk_perangkat'),
				$request->input(self::MEREK_PERANGKAT),
				$request->input('hidden_kapasitas_perangkat'),
				$request->input(self::KAPASITAS_PERANGKAT),
				$request->input('hidden_pembuat_perangkat'),
				$request->input(self::PEMBUAT_PERANGKAT),
				$request->input('hidden_model_perangkat'),
				$request->input(self::MODEL_PERANGKAT),
				$request->input('hidden_ref_perangkat'),
				$ref_perangkat,
				$request->input('hidden_sn_perangkat'),
				$request->input(self::SN_PERANGKAT),
				"emails.revisi", 
				"Revisi Data Permohonan Uji"
			);
            return redirect(self::ADMIN_EXAMINATION_LOC.$request->input(self::ID_EXAM).'');
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect('/admin/examination/revisi/'.$request->input(self::ID_EXAM).'');
        }
    }
	
	public function tanggalkontrak(Request $request)
    {
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
			
			try{
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

				$manager_urels = $this->manager_urels();
				
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
				
				$request->session()->put('key_contract', $data);
				Session::flash(self::MESSAGE, 'Contract successfully created');
				echo 1;
			} catch(Exception $e){
				Session::flash(self::ERROR, 'Contract failed');
				echo 0;
			}
    }
	
	public function tandaterima(Request $request)
    {
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
			try{
				
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
				
				$request->session()->put('key_tanda_terima', $data);
				
				Session::flash(self::MESSAGE, 'Tanda Terima successfully created');
				echo 1;
			} catch(Exception $e){
				Session::flash(self::ERROR, 'Tanda Terima failed');
				echo 0;
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
	)
    {
        $data = User::findOrFail($user);
		
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
	
	public function sendEmailFailure($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $tahap, $keterangan)
    {
        $data = User::findOrFail($user);
		
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
	
	public function destroy($id,$page,$reason = null)
	{
		$currentUser = Auth::user();
		$logs_a_exam = NULL;
		$logs_a_device = NULL;
		
		$exam_attach = ExaminationAttach::where(self::EXAMINATION_ID, '=' ,''.$id.'')->get();
		$exam = Examination::find($id);
			$device_id = $exam['device_id'];
		$device = Device::find($device_id);
		if ($exam_attach && $exam && $device){
			/* DELETE SPK FROM OTR */
			if($exam->spk_code){
				$client = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
					// Base URI is used with relative requests
					// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					self::BASE_URI => config(self::APP_URI_API_BSP),
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);
				
				$res_delete_spk = $client->get('spk/delete?examId='.$exam->id.self::SPK_NUMBER_URI.$exam->spk_code)->getBody();
				$delete_spk = json_decode($res_delete_spk);
				if(!$delete_spk->status){
					Session::flash(self::ERROR, $delete_spk->message.' (message from OTR)');
					return redirect(self::ADMIN_EXAMINATION);
				}
			}
			/* END DELETE SPK FROM OTR */
			try{
				$logs_a_exam = $exam;
				$logs_a_device = $device;
				Income::where(self::REFERENCE_ID, '=' ,''.$id.'')->delete();
				Questioner::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				Equipment::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				EquipmentHistory::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				ExaminationHistory::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				ExaminationAttach::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				QuestionerDynamic::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				$exam->delete();
				$device->delete();
				
				if (File::exists(public_path().'\media\\examination\\'.$id)){
					File::deleteDirectory(public_path().'\media\\examination\\'.$id);
				}

				$logs = new LogsAdministrator;
				$logs->id = Uuid::uuid4();
				$logs->user_id = $currentUser->id;
				$logs->action = "Hapus Data Pengujian";
				$logs->page = $page;
				$logs->reason = urldecode($reason);
				$logs->data = $logs_a_exam.$logs_a_device;
				$logs->save();

				Session::flash(self::MESSAGE, 'Examination successfully deleted');
				return redirect(self::ADMIN_EXAMINATION);
			}catch (Exception $e){
				Session::flash(self::ERROR, 'Delete failed');
				return redirect(self::ADMIN_EXAMINATION);
			}
		}
	}
	
	public function resetUjiFungsi($id,$reason = null)
	{
		$currentUser = Auth::user();
		$logs_a_exam = NULL;

        if ($currentUser){
			$exam = Examination::find($id);
			if ($exam){
				try{
					$logs_a_exam = $exam;
					Equipment::where(self::EXAMINATION_ID, '=' ,''.$exam->id.'')->delete();
					EquipmentHistory::where(self::EXAMINATION_ID, '=' ,''.$exam->id.'')->delete();
					
					$exam->cust_test_date = NULL;
					$exam->deal_test_date = NULL;
					$exam->urel_test_date = NULL;
					$exam->function_date = NULL;
					$exam->function_test_reason = NULL;
					$exam->function_test_TE = 0;
					$exam->function_test_date_approval = 0;
					$exam->function_test_status_detail = NULL;
					$exam->updated_by = $currentUser->id;
					$exam->updated_at = date(self::DATE_FORMAT_1);
					$exam->location = 0;
					
					$exam->save();
					
					$exam_hist = new ExaminationHistory;
					$exam_hist->examination_id = $exam->id;
					$exam_hist->date_action = date(self::DATE_FORMAT_1);
					$exam_hist->tahap = self::UJI_FUNGSI;
					$exam_hist->status = self::NOT_COMPLETED;
					$exam_hist->keterangan = 'Data Uji Fungsi direset oleh Super Admin URel';
					$exam_hist->created_by = $currentUser->id;
					$exam_hist->created_at = date(self::DATE_FORMAT_1);
					$exam_hist->save();

					$logs = new Logs;
					$logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
					$logs->action = "Reset Uji Fungsi";
					$logs->data = $exam;
					$logs->created_by = $currentUser->id;
					$logs->page = self::EXAMINATION;
					$logs->save();

					$logs_a = new LogsAdministrator;
					$logs_a->id = Uuid::uuid4();
					$logs_a->user_id = $currentUser->id;
					$logs_a->action = "Reset Uji Fungsi";
					$logs_a->page = "Pengujian -> Change Status";
					$logs_a->reason = urldecode($reason);
					$logs_a->data = $logs_a_exam;
					$logs_a->save();
					
					Session::flash(self::MESSAGE, 'Function Test successfully reset');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}catch (Exception $e){
					Session::flash(self::ERROR, 'Reset failed');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}
			}
		}
	}
	
	public function autocomplete($query) {
        $respons_result = Examination::adm_exam_autocomplet($query);
        return response($respons_result);
    }
	
	public function checkSPKCode($a) {
        $query_exam = "SELECT * FROM examinations WHERE spk_code = '".$a."'";
		$data_exam = DB::select($query_exam);
		return count($data_exam);
    }
	
	public function generateSPKCodeManual(Request $request) {
		return $this->generateSPKCode($request->input('lab_code'),$request->input(self::EXAM_TYPE),$request->input('year'));
    }
	
	public function generateSPKCode($a,$b,$c) {

		$query = "
			SELECT 
			SUBSTRING_INDEX(SUBSTRING_INDEX(spk_code,'/',2),'/',-1) + 1 AS last_numb
			FROM examinations WHERE 
			SUBSTRING_INDEX(spk_code,'/',1) = '".$a."' AND
			SUBSTRING_INDEX(spk_code,'/',-1) = '".$c."'
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
			SELECT SUBSTRING_INDEX(spb_number,'/',1) + 1 AS last_numb
			FROM examinations WHERE SUBSTRING_INDEX(spb_number,'/',-1) = ".$thisYear." AND spb_number LIKE '%TTH-02%'
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
	
	public function generateSPBParam(Request $request) {
		$request->session()->put('key_exam_id_for_generate_spb', $request->input(self::EXAM_ID));
		$request->session()->put('key_spb_number_for_generate_spb', $request->input(self::SPB_NUMBER));
		$request->session()->put('key_spb_date_for_generate_spb', $request->input(self::SPB_DATE));
		echo 1;
    }
	
	public function generateEquipParam(Request $request) {
		$request->session()->put('key_exam_id_for_generate_equip_masuk', $request->input(self::EXAM_ID));
		$request->session()->put('key_in_equip_date_for_generate_equip_masuk', $request->input('in_equip_date'));
		echo 1;
    }
	
	public function generateKuitansiParam(Request $request) {
		$kode = $this->generateKuitansiManual();
		$exam_id = $request->input(self::EXAM_ID);
		$exam = Examination::where('id', $exam_id)
					->with(self::DEVICE)
					->with(self::COMPANY)
					->first();
		$request->session()->put('key_jenis_for_kuitansi', 1);
		$request->session()->put('key_id_for_kuitansi', $exam_id);
		$request->session()->put('key_kode_for_kuitansi', $kode);
		$request->session()->put('key_from_for_kuitansi', $exam->company->name);
		$request->session()->put('key_price_for_kuitansi', $exam->cust_price_payment?: '0');
		$request->session()->put('key_for_for_kuitansi', "Pengujian Perangkat ".$exam->device->name." (".$kode.")");
		echo 1;
    }
	
	public function generateKuitansiParamSTEL(Request $request) {
		$kode = $this->generateKuitansiManual();
		$exam_id = $request->input(self::EXAM_ID);
		$query_stels = "SELECT DISTINCT
				s. code AS stel,
				ss. cust_price_payment,
				c. name
			FROM
				stels s,
				stels_sales ss,
				stels_sales_detail ssd,
				companies c,
				users u
			WHERE
				s.id = ssd.stels_id
			AND ss.id = ssd.stels_sales_id
			AND ss.user_id = u.id
			AND u.company_id = c.id
			AND ss.id = '".$exam_id."'
			ORDER BY s.code";
		$data_stels = DB::select($query_stels);
			$stel = $data_stels[0]->stel;
		for ($i=1;$i<count($data_stels);$i++) {
			$stel = $stel.", ".$data_stels[$i]->stel;
		}
			$stel = $stel.".";
		$request->session()->put('key_jenis_for_kuitansi', 2);
		$request->session()->put('key_id_for_kuitansi', $exam_id);
		$request->session()->put('key_kode_for_kuitansi', $kode);
		$request->session()->put('key_from_for_kuitansi', $data_stels[0]->name);
		$request->session()->put('key_price_for_kuitansi', $data_stels[0]->cust_price_payment?: '0');
		$request->session()->put('key_for_for_kuitansi', $stel);
		echo 1;
    }
	
	public function generateSPB(Request $request) {
		$exam_id = $request->session()->get('key_exam_id_for_generate_spb');
		$spb_number = $request->session()->get('key_spb_number_for_generate_spb');
		if($spb_number == "" || $spb_number == null){
			$spb_number = $this->generateSPBNumber();
		}
		$spb_date = $request->session()->get('key_spb_date_for_generate_spb');
		$exam = Examination::where('id', $exam_id)
					->with(self::DEVICE)
					->first()
		;
		if($exam->examination_type_id == 2){
			$query_price = "SELECT ta_price as price FROM examination_charges WHERE stel LIKE '%".$exam->device->test_reference."%'";
		}
		else if($exam->examination_type_id == 3){
			$query_price = "SELECT vt_price as price FROM examination_charges WHERE stel LIKE '%".$exam->device->test_reference."%'";
		}else{
			$query_price = "SELECT price FROM examination_charges WHERE stel LIKE '%".$exam->device->test_reference."%'";
		}
		$price = DB::select($query_price);
		if(!count($price)){
			$price = 0;
		}else{
			$price = $price[0]->price;
		}
		
		$query_stels = "SELECT * FROM examination_charges ORDER BY device_name";
		$data_stels = DB::select($query_stels);

		return view('admin.examination.spb')
					->with(self::EXAM_ID, $exam_id)
					->with(self::SPB_NUMBER, $spb_number)
					->with(self::SPB_DATE, $spb_date)
					->with('data', $exam)
					->with('price', $price)
					->with('data_stels', $data_stels)
		;
    }
	
	public function generateSPBData(Request $request) {
		$manager_urels = $this->manager_urels();
		
		if($this->cekSPBNumber($request->input(self::SPB_NUMBER)) > 0){
			echo 2; //SPB Number Exists
		}else{
			$exam_id = $request->input(self::EXAM_ID);
			$spb_number = $request->input(self::SPB_NUMBER);
			$spb_date = $request->input(self::SPB_DATE);
			$arr_nama_perangkat = $request->input('arr_nama_perangkat');
			$arr_biaya = $request->input('arr_biaya');
			$exam = Examination::where('id', $exam_id)
						->with('user')
						->with(self::COMPANY)
						->with(self::DEVICE)
						->with(self::EXAMINATION_TYPE)
						->first()
			;
/* Kirim Draft ke TPN */
			$biaya = 0;
			for($i=0;$i<count($arr_biaya);$i++){
				$biaya = $biaya + $arr_biaya[$i];
			}
			$ppn = 0.1*$biaya;
			$total_biaya = $biaya + $ppn;
			$details [] = 
	            [
	                "item" => 'Biaya Uji '.$exam->examinationType->name.' ('.$exam->examinationType->description.')',
	                "description" => $exam->device->name.', '.$exam->device->mark.', '.$exam->device->capacity.', '.$exam->device->model,
	                "quantity" => 1,
	                "price" => $biaya,
	                "total" => $biaya
	            ]
	        ;

			$data_draft = [
	            "from" => [
	                "name" => "PT TELEKOMUNIKASI INDONESIA, TBK.",
	                "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
	                "phone" => "(+62) 812-2483-7500",
	                "email" => "urelddstelkom@gmail.com",
	                "npwp" => "01.000.013.1-093.000"
	            ],
	            "to" => [
	                "name" => $exam->company->name ? $exam->company->name : "-",
	                "address" => $exam->company->address ? $exam->company->address : "-",
	                "phone" => $exam->company->phone_number ? $exam->company->phone_number : "-",
	                "email" => $exam->company->email ? $exam->company->email : "-",
	                "npwp" => $exam->company->npwp_number ? $exam->company->npwp_number : "-"
	            ],
	            "product_id" => config("app.product_id_tth_2"), //product_id TTH untuk Pengujian
	            "details" => $details,
	            "created" => [
	                "by" => $exam->user->name,
	                self::REFERENCE_ID => $exam->user->id
	            ],
	            "config" => [
	                "kode_wapu" => "01",
	                "afiliasi" => "non-telkom",
	                "tax_invoice_text" => $details[0]['description']
	            ],
	            "include_tax_invoice" => true,
	            "bank" => [
	                "owner" => "Divisi RisTI TELKOM",
	                "account_number" => "131-0096022712",
	                "bank_name" => "BANK MANDIRI",
	                "branch_office" => "KCP KAMPUS TELKOM BANDUNG"         
	            ]
	        ];
	        $purchase = $this->api_purchase($data_draft);
	        $total_price = $biaya;
            $PO_ID = $purchase && $purchase->status ? $purchase->data->_id : null;
            $unique_code = $purchase && $purchase->status ? $purchase->data->unique_code : '0';
            $tax = floor(0.1*($total_price + $unique_code));
            $final_price = $total_price + $unique_code + $tax;
            array_push($arr_nama_perangkat, 'Unique Code');
            array_push($arr_biaya, $unique_code);
/* END Kirim Draft ke TPN */
			$data = []; 
			$data[] = [
				self::SPB_NUMBER => $spb_number,
				self::SPB_DATE => $spb_date,
				'arr_nama_perangkat' => $arr_nama_perangkat,
				'arr_biaya' => $arr_biaya,
				'unique_code' => $unique_code,
				'exam' => $exam,
				self::MANAGER_UREL => $manager_urels,
				'is_poh' => $is_poh
			];

			$request->session()->put('key_exam_for_spb', $data);
			echo $PO_ID.'myToken'.$final_price;
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
	
	function cekSPBNumber($spb_number)
    {
		$exam = Examination::where(self::SPB_NUMBER,'=',''.$spb_number.'')->get();
		return count($exam);
    }
	
	function cekRefID($exam_id)
    {
		$income = Income::where(self::REFERENCE_ID,'=',''.$exam_id.'')->get();
		return count($income);
    }
	
	function cetakUjiFungsi($id)
    {
		$client = new Client([
			self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
			// Base URI is used with relative requests
			// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			self::BASE_URI => config(self::APP_URI_API_BSP),
			// You can set any number of default request options.
			self::TIMEOUT  => 60.0,
		]);
		$res_function_test = $client->get('functionTest/getFunctionTestInfo?id='.$id)->getBody();
		$function_test = json_decode($res_function_test);
		
		$data = Examination::where('id','=',$id)
		->with(self::COMPANY)
		->with(self::DEVICE)
		->with(self::EQUIPMENT)
		->get();
		if( strpos( $data[0]->function_test_NO, "/" ) !== false ) {$no_reg = urlencode(urlencode($data[0]->function_test_NO));}
			else{$no_reg = $data[0]->function_test_NO?: '-';}
		if( strpos( $data[0]->company->name, "/" ) !== false ) {$company_name = urlencode(urlencode($data[0]->company->name));}
			else{$company_name = $data[0]->company->name?: '-';}
		if( strpos( $data[0]->company->address, "/" ) !== false ) {$company_address = urlencode(urlencode($data[0]->company->address));}
			else{$company_address = $data[0]->company->address?: '-';}
		if( strpos( $data[0]->company->phone, "/" ) !== false ) {$company_phone = urlencode(urlencode($data[0]->company->phone));}
			else{$company_phone = $data[0]->company->phone?: '-';}
		if( strpos( $data[0]->company->fax, "/" ) !== false ) {$company_fax = urlencode(urlencode($data[0]->company->fax));}
			else{$company_fax = $data[0]->company->fax?: '-';}
		if( strpos( $data[0]->device->name, "/" ) !== false ) {$device_name = urlencode(urlencode($data[0]->device->name));}
			else{$device_name = $data[0]->device->name?: '-';}
		if( strpos( $data[0]->device->mark, "/" ) !== false ) {$device_mark = urlencode(urlencode($data[0]->device->mark));}
			else{$device_mark = $data[0]->device->mark?: '-';}
		if( strpos( $data[0]->device->manufactured_by, "/" ) !== false ) {$device_manufactured_by = urlencode(urlencode($data[0]->device->manufactured_by));}
			else{$device_manufactured_by = $data[0]->device->manufactured_by?: '-';}
		if( strpos( $data[0]->device->model, "/" ) !== false ) {$device_model = urlencode(urlencode($data[0]->device->model));}
			else{$device_model = $data[0]->device->model?: '-';}
		if( strpos( $data[0]->device->serial_number, "/" ) !== false ) {$device_serial_number = urlencode(urlencode($data[0]->device->serial_number));}
			else{$device_serial_number = $data[0]->device->serial_number?: '-';}
		if( strpos( $data[0]->function_test_TE, "/" ) !== false ) {$function_test_TE = urlencode(urlencode($data[0]->function_test_TE));}
			else{$function_test_TE = $data[0]->function_test_TE?: '-';}
		if( strpos( $data[0]->catatan, "/" ) !== false ) {$catatan = urlencode(urlencode($data[0]->catatan));}
			else{$catatan = $data[0]->catatan?: '-';}
			if($function_test->code != 'MSTD0059AERR' && $function_test->code != 'MSTD0000AERR'){
				if( strpos( $function_test->data[0]->nik, "/" ) !== false ) {$nik_te = urlencode(urlencode($function_test->data[0]->nik));}
					else{$nik_te = $function_test->data[0]->nik?: '-';}
				if( strpos( $function_test->data[0]->name, "/" ) !== false ) {$name_te = urlencode(urlencode($function_test->data[0]->name));}
					else{$name_te = $function_test->data[0]->name?: '-';}
			}else{
				$nik_te = "-";
				$name_te = "-";
			}
		if(count($data[0]->equipment)>0){
			if( strpos( $data[0]->equipment[0]->pic, "/" ) !== false ) {$pic = urlencode(urlencode($data[0]->equipment[0]->pic));}
				else{$pic = $data[0]->equipment[0]->pic?: '-';}
		}else{
			$pic = '-';
		}
		if($data[0]->function_test_date_approval == 1){
			if($data[0]->function_date != null){
				if( strpos( $data[0]->function_date, "/" ) !== false ) {$tgl_uji_fungsi = urlencode(urlencode(date(self::J_F_Y, strtotime($data[0]->function_date))));}
					else{$tgl_uji_fungsi = date(self::J_F_Y, strtotime($data[0]->function_date))?: '-';}
			}else{
				if( strpos( $data[0]->deal_test_date, "/" ) !== false ) {$tgl_uji_fungsi = urlencode(urlencode(date(self::J_F_Y, strtotime($data[0]->deal_test_date))));}
					else{$tgl_uji_fungsi = date(self::J_F_Y, strtotime($data[0]->deal_test_date))?: '-';}
			}
		}else{
			$tgl_uji_fungsi = '-';
		}
		return \Redirect::route('cetakHasilUjiFungsi', [
			'no_reg' => $no_reg,
			'company_name' => $company_name,
			'company_address' => $company_address,
			'company_phone' => $company_phone,
			'company_fax' => $company_fax,
			'device_name' => $device_name,
			'device_mark' => $device_mark,
			'device_manufactured_by' => $device_manufactured_by,
			'device_model' => $device_model,
			'device_serial_number' => $device_serial_number,
			self::STATUS => $function_test_TE,
			self::CATATAN => $catatan,
			'tgl_uji_fungsi' => $tgl_uji_fungsi,
			'nik_te' => $nik_te,
			'name_te' => $name_te,
			'pic' => $pic
		]);
    }
	
	function cetakFormBarang($id, Request $request)
    {
		$data = Examination::where('id','=',$id)
		->with(self::EXAMINATION_TYPE)
		->with(self::EXAMINATION_LAB)
		->with(self::COMPANY)
		->with('User')
		->with(self::DEVICE)
		->with(self::EQUIPMENT)
		->get();
		if(isset($data[0]->equipment[0]->no)){
			if ($data[0]->equipment[0]->no) {
				$kode_barang = $data[0]->equipment[0]->no;
			}else{
				$kode_barang = $this->generateKodeBarang($data[0]->ExaminationLab->lab_init,$this->romawi(date('n')),date('Y'));
				$query_update = "UPDATE equipments
					SET no = '".$kode_barang."'
					WHERE examination_id = '".$id."'
				";
				DB::update($query_update);
			}
		}else{
			$kode_barang = '';
			Session::flash(self::ERROR, 'Undefined Equipment(s), please put equipment(s) first');
            return redirect(self::ADMIN_EXAMINATION_LOC.$id.self::EDIT_LOC);
		}
		$kode_barang = urlencode(urlencode($kode_barang));
		if( strpos( $data[0]->company->name, "/" ) !== false ) {$company_name = urlencode(urlencode($data[0]->company->name));}
			else{$company_name = $data[0]->company->name?: '-';}
		if( strpos( $data[0]->device->name, "/" ) !== false ) {$device_name = urlencode(urlencode($data[0]->device->name));}
			else{$device_name = $data[0]->device->name?: '-';}
		if( strpos( $data[0]->company->address, "/" ) !== false ) {$company_address = urlencode(urlencode($data[0]->company->address));}
			else{$company_address = $data[0]->company->address?: '-';}
		if( strpos( $data[0]->device->mark, "/" ) !== false ) {$device_mark = urlencode(urlencode($data[0]->device->mark));}
			else{$device_mark = $data[0]->device->mark?: '-';}
		if( strpos( $data[0]->company->phone, "/" ) !== false ) {$company_phone = urlencode(urlencode($data[0]->company->phone));}
			else{$company_phone = $data[0]->company->phone?: '-';}
		if( strpos( $data[0]->device->manufactured_by, "/" ) !== false ) {$device_manufactured_by = urlencode(urlencode($data[0]->device->manufactured_by));}
			else{$device_manufactured_by = $data[0]->device->manufactured_by?: '-';}
		if( strpos( $data[0]->company->fax, "/" ) !== false ) {$company_fax = urlencode(urlencode($data[0]->company->fax));}
			else{$company_fax = $data[0]->company->fax?: '-';}
		if( strpos( $data[0]->device->model, "/" ) !== false ) {$device_model = urlencode(urlencode($data[0]->device->model));}
			else{$device_model = $data[0]->device->model?: '-';}
		if( strpos( $data[0]->user->phone_number, "/" ) !== false ) {$user_phone = urlencode(urlencode($data[0]->user->phone_number));}
			else{$user_phone = $data[0]->user->phone_number?: '-';}
		if( strpos( $data[0]->user->fax, "/" ) !== false ) {$user_fax = urlencode(urlencode($data[0]->user->fax));}
			else{$user_fax = $data[0]->user->fax?: '-';}
		if( strpos( $data[0]->device->serial_number, "/" ) !== false ) {$device_serial_number = urlencode(urlencode($data[0]->device->serial_number));}
			else{$device_serial_number = $data[0]->device->serial_number?: '-';}
		if( strpos( $data[0]->ExaminationType->name, "/" ) !== false ) {$exam_type = urlencode(urlencode($data[0]->ExaminationType->name));}
			else{$exam_type = $data[0]->ExaminationType->name?: '-';}
		if( strpos( $data[0]->ExaminationType->description, "/" ) !== false ) {$exam_type_desc = urlencode(urlencode($data[0]->ExaminationType->description));}
			else{$exam_type_desc = $data[0]->ExaminationType->description?: '-';}
		if( strpos( $data[0]->contract_date, "/" ) !== false ) {$timestamp = strtotime($data[0]->contract_date);$contract_date = urlencode(urlencode(date(self::DATE_FORMAT_3, $timestamp)));}
			else{$timestamp = strtotime($data[0]->contract_date);$contract_date = date(self::DATE_FORMAT_3, $timestamp)?: '-';}
		
		$request->session()->put('key_exam_for_equipment', $data[0]->equipment);
		return \Redirect::route('cetakBuktiPenerimaanPerangkat', [
			'kode_barang' => $kode_barang,
			'company_name' => $company_name,
			'company_address' => $company_address,
			'company_phone' => $company_phone,
			'company_fax' => $company_fax,
			'user_phone' => $user_phone,
			'user_fax' => $user_fax,
			'device_name' => $device_name,
			'device_mark' => $device_mark,
			'device_manufactured_by' => $device_manufactured_by,
			'device_model' => $device_model,
			'device_serial_number' => $device_serial_number,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc,
			self::CONTRACT_DATE => $contract_date
		]);
    }
	
	public function generateEquip(Request $request)
    {
		$exam_id = $request->session()->get('key_exam_id_for_generate_equip_masuk');
		$equipment = Equipment::where(self::EXAMINATION_ID, $exam_id)->get();
        $location = Equipment::where(self::EXAMINATION_ID, $exam_id)->first();
        $examination = DB::table(self::EXAMINATIONS)
			->join('devices', 'examinations.device_id', '=', 'devices.id')
			->select(
					self::EXAMINATIONS_ID,
					'devices.name',
					'devices.model'
					)
            ->where(self::EXAMINATIONS_ID, $exam_id)
			->orderBy('devices.name')
			->first();

        return view('admin.equipment.edit')
            ->with('item', $examination)
            ->with(self::LOCATION, $location)
            ->with('data', $equipment);
    }
	
	public function generateKuitansiManual() {
		$thisYear = date('Y');
		$query = "
			SELECT SUBSTRING_INDEX(number,'/',1) + 1 AS last_numb
			FROM kuitansi WHERE SUBSTRING_INDEX(number,'/',-1) = ".$thisYear."
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (!count($data)){
			return '001/DDS-73/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.self::DDS_73.$thisYear.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.self::DDS_73.$thisYear.'';
			}
			else{
				return ''.$last_numb.self::DDS_73.$thisYear.'';
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
	
	public function deleteRevLapUji($id)
    {
        $examination_attachment = ExaminationAttach::find($id);
        if($examination_attachment){
            
            // unlink stels_sales_detail.attachment
            if (File::exists(public_path().'\media\examination\\'.$examination_attachment->examination_id.'\\'.$examination_attachment->attachment)){
                File::delete(public_path().'\media\examination\\'.$examination_attachment->examination_id.'\\'.$examination_attachment->attachment);
            }

            // delete stels_sales_detail
            $examination_attachment->delete();

            Session::flash(self::MESSAGE, 'Successfully Delete Revision File');
        }else{
            Session::flash(self::ERROR, 'Undefined Data');
        }
            return redirect(self::ADMIN_EXAMINATION_LOC.$examination_attachment->examination_id.self::EDIT_LOC);

	}
	
	public function insertAttachment($request,$exam_id,$currentUser_id,$file_type,$file_name,$attach_name){
		if ($request->hasFile($file_type)) {
			$name_file = $file_name.$request->file($file_type)->getClientOriginalName();
			$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam_id;
			if (!file_exists($path_file)) {
				mkdir($path_file, 0775);
			}
			if($request->file($file_type)->move($path_file,$name_file)){
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
			    $name_file = 'faktur_spb_'.$filename.'.pdf';
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
                            /*SAVE FILE*/
                            $name_file = $type == self::KUITANSI ? 'kuitansi_spb_'.$INVOICE_ID.'.pdf' : $name_file;
							$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$id;
							if (!file_exists($path_file)) {
								mkdir($path_file, 0775);
							}
							$response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.$filelink);
                            $stream = (String)$response->getBody();

                            if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                                $attach = ExaminationAttach::where('name', $type)->where(self::EXAMINATION_ID, ''.$id.'')->first();
                                $currentUser = Auth::user();

								if ($attach){
									$attach->attachment = $name_file;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								} else{
									$attach = new ExaminationAttach;
									$attach->id = Uuid::uuid4();
									$attach->examination_id = $id; 
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
	
	public function manager_urels(){
		$is_poh = 0;
		$general_setting_poh = GeneralSetting::where('code', 'poh_manager_urel')->first();
		if($general_setting_poh){
			if($general_setting_poh->is_active){
				$is_poh = 1;
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
	}
}
