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
use App\ApiLogs;
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
use Storage;

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
use App\Services\FileService;

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
	private const MEDIA_EXAMINATION_LOC = 'examination/';
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
	private const MEDIA_DEVICE_LOC = 'device/';
	private const SPK_NUMBER_URI = '&spkNumber=';
	private const SPK_ADD_NOTIF_ID_URI = 'spk/addNotif?id=';
	private const EXAMINATIONS = 'examinations';
	private const ADMIN_EXAMINATION = '/admin/examination';  
	private const EXAMINATIONS_ID = 'examinations.id';
	private const EXAMINATION_ATTACHMENTS = 'examination_attachments';  
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
	private const EQUIPMENT = 'equipment'; 
	private const MANAGER_UREL = 'manager_urel'; 
	private const EXAM_ID = 'exam_id'; 

	private const TABLE_DEVICE = 'devices';
	private const EXAM_DEVICES_ID = 'examinations.device_id';
	private const DEVICES_ID = 'devices.id';
	private const TABLE_COMPANIES = 'companies';
	private const EXAM_COMPANY_ID = 'examinations.company_id';
	private const COMPANIES_ID = 'companies.id';
	private const COMPANY_AUTOSUGGEST = 'companies.name as autosuggest'; 
	private const COMPANIES_NAME = 'companies.name';
	private const DEVICE_NAME_AUTOSUGGEST = 'devices.name as autosuggest';
	private const DEVICE_NAME = 'devices.name';
	private const MINIO = 'minio';
	private const EXAMINATION_LOC = 'examination\\';

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

        if (!$currentUser){ return redirect('login');}
		$message = null;
		$paginate = 5;
		$search = trim($request->get(self::SEARCH,''));
		$afterDateExam = $request->get('after_date_exam','');
		$beforeDateExam = $request->get('before_date_exam','');
		$selectedExamLab = $request->get('selected_exam_lab','');
		$sortFromQuery = 'examinations.updated_at';
		$sortFrom = $request->get('sort_from','updated_at');
		$sortBy = $request->get('sort_by','dsc');
		$examinationLab = \App\ExaminationLab::select('id', 'name')->get();
		$examType = ExaminationType::all();

		$tempData = $examinationService->requestQuery($request, $search, $type = '', $status = '', $before = null, $after = null);

		$query = $tempData[0];
		$search = $tempData[1];
		$type = $tempData[2];
		$status = $tempData[3];
		$before = $tempData[4];
		$after = $tempData[5];
		
		$search != null ? $logService->createLog(self::SEARCH, $this::EXAMINATION, json_encode(array(self::SEARCH => $search)) ) : '';

		if($sortFrom == 'updated_at'){
			$sortFromQuery = 'examinations.updated_at';
		} if($sortFrom == 'created_at'){
			$sortFromQuery = 'examinations.created_at';
		} if ($sortFrom == 'device_name'){
			$sortFromQuery = 'devices.name';
		}
		
		$data = $query
			->orderBy($sortFromQuery, $sortBy)
			->paginate($paginate)
		;
		
		if (count(array($query)) == 0){ $message = 'Data not found'; }
		
		return view('admin.examination.index')
			->with(self::MESSAGE, $message)
			->with('data', $data)
			->with('type', $examType)
			->with(self::SEARCH, $search)
			->with('filterType', $type)
			->with(self::STATUS, $status)
			->with(self::BEFORE_DATE, $before)
			->with(self::AFTER_DATE, $after)
			->with('after_date_exam', $afterDateExam)
			->with('before_date_exam', $beforeDateExam)
			->with(self::EXAMINATION_LAB, $examinationLab)
			->with('selected_exam_lab', $selectedExamLab)
			->with('sort_from', $sortFrom)
			->with('sort_by', $sortBy)
		;
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
		$logService = new LogService();
		$notificationService = new NotificationService();
		$examinationService = new ExaminationService();

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
			    // event(new Notification($data));
				
				$examinationService->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.registrasi", "Acc Registrasi");
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
				// event(new Notification($data));
				
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::REGISTRASI,$request->input(self::KETERANGAN));
			}
        }
		if ($request->has(self::FUNCTION_STATUS)){
			$examinationService->insertAttachment($request,$exam->id,$currentUser->id,self::BARANG_FILE,'form_penerimaan_barang_','Bukti Penerimaan & Pengeluaran Perangkat Uji1');
			if($exam->is_loc_test){
				$examinationService->insertAttachment($request,$exam->id,$currentUser->id,self::FUNCTION_FILE,'function_','Laporan Hasil Technical Meeting');
			}else{
			$examinationService->insertAttachment($request,$exam->id,$currentUser->id,self::FUNCTION_FILE,'function_','Laporan Hasil Uji Fungsi');
			}
			
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
				"created_at"=>date(self::DATE_FORMAT_1),
				"updated_at"=>date(self::DATE_FORMAT_1)
			);

			$notification_id = $notificationService->make($data);
			$data['id'] = $notification_id;
			// event(new Notification($data));

			if($status == -1){
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Uji Fungsi",$request->input(self::KETERANGAN));
			}
        }
        $spk_created = 0;
        if ($request->has(self::CONTRACT_STATUS)){
			if ($request->hasFile(self::CONTRACT_FILE)) {

				$fileService = new FileService();
				$fileProperties = array(
					'path' => self::MEDIA_EXAMINATION_LOC.$exam->id.'/',
					'prefix' => "contract_"
				);
				$fileService->upload($request->file($this::CONTRACT_FILE), $fileProperties);

				if($fileService->isUploaded()){
					$name_file = $fileService->getFileName();
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
				$path_file = self::MEDIA_EXAMINATION_LOC.$id;
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
                    $spk_number_forOTR = $examinationService->generateSPKCode($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
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
					// event(new Notification($data));
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
					// event(new Notification($data));
				}
				
			}else if($status == -1){
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Tinjauan Pustaka",$request->input(self::KETERANGAN));
			}
        }
		if ($request->has(self::SPB_STATUS)){
			$examinationService->insertAttachment($request,$exam->id,$currentUser->id,self::SPB_FILE,'spb_','SPB');
			$status = $request->input(self::SPB_STATUS);
            $exam->spb_status = $status;
			if($status == 1){
				$exam->price = str_replace(".",'',$request->input('exam_price'));
				$path_file = self::MEDIA_EXAMINATION_LOC.$id;
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
			    // event(new Notification($data));
				$exam->PO_ID ? $examinationService->send_revision($exam, $request->input('spb_number')) : $examinationService->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.spb", "Penerbitan Surat Pemberitahuan Biaya (SPB) untuk ".$exam->function_test_NO,$path_file."/".$attach_name,$request->input('spb_number'),$exam->id);
			}else if($status == -1){
				$exam->price = str_replace(".",'',$request->input('exam_price'));
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"SPB",$request->input(self::KETERANGAN));
			}
        }
		
        if ($request->has(self::PAYMENT_STATUS)){
			if ($request->has(self::CUST_PRICE_PAYMENT)){
				$exam->cust_price_payment = str_replace(".",'',$request->input(self::CUST_PRICE_PAYMENT));
			}
			if ($request->hasFile(self::KUITANSI_FILE)) {
				$fileService = new FileService();
				$fileProperties = array(
					'path' => self::MEDIA_EXAMINATION_LOC.$exam->id."/",
					'prefix' => "kuitansi_"
				);
				$fileService->upload($request->file($this::KUITANSI_FILE), $fileProperties);
				$name_file = $fileService->getFileName();

				if($fileService->isUploaded()){
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
				$fileService = new FileService();
				$fileProperties = array(
					'path' => self::MEDIA_EXAMINATION_LOC.$exam->id."/",
					'prefix' => "faktur_"
				);
				$fileService->upload($request->file($this::FAKTUR_FILE), $fileProperties);
				$name_file = $fileService->getFileName();

				if($fileService->isUploaded()){
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
				$income = Income::where(self::REFERENCE_ID, $exam->id)->first();
				if(!$income){
					$income = new Income;
					$income->id = Uuid::uuid4();
					$income->company_id = $exam->company_id;
					$income->inc_type = 1; 
					$income->reference_id = $exam->id; 
					$income->reference_number = $exam->spb_number;
					$income->tgl = $exam->spb_date;
					$income->created_by = $currentUser->id;
				}
					// ($item->payment_method == 1)?'ATM':'Kartu Kredit'
					$income->price = ($request->input(self::CUST_PRICE_PAYMENT) != NULL) ? str_replace(".",'',$request->input(self::CUST_PRICE_PAYMENT)) : 0;
					$income->updated_by = $currentUser->id;
					$income->save();

				$examinationService->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran");
				
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
                    $spk_number_forOTR = $examinationService->generateSPKCode($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
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
				// event(new Notification($data));
				
			}else if($status == -1){
				Income::where(self::REFERENCE_ID, '=' ,''.$exam->id.'')->delete();
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::PEMBAYARAN,$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::SPK_STATUS)){
            $status = $request->input(self::SPK_STATUS);
            $exam->spk_status = $status;
			if($status == -1){
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::PEMBUATAN_SPK,$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::EXAMINATION_STATUS)){
            $status = $request->input(self::EXAMINATION_STATUS);
            $exam->examination_status = $status;
			if($status == -1){
			
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::PELAKSANAAN_UJI,$request->input(self::KETERANGAN));
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
				// event(new Notification($data));				
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
					$data_upload [] = 
						[
							'name' => "file",
							'contents' => fopen($request->input('hide_attachment_form-lap-uji'), 'r'),
							'filename' => 'Laporan Uji.pdf'
						]
					;

	                $data_upload [] = array(
	                    'name'=>"delivered",
	                    'contents'=>json_encode(['by'=>$currentUser->name, self::REFERENCE_ID => '1']),
	                );

	                $examinationService->api_upload($data_upload,$exam->BILLING_ID);
			}
			if ($request->hasFile(self::REV_LAP_UJI)) {
				$fileService = new FileService();
				$fileProperties = array(
					'path' => self::MEDIA_EXAMINATION_LOC.$exam->id."/",
					'prefix' => "rev_lap_uji_"
				);
				$fileService->upload($request->file($this::REV_LAP_UJI), $fileProperties);
				$name_file = $fileService->getFileName();

				if($fileService->isUploaded()){
                    /*TPN api_upload*/
		            if($exam->BILLING_ID != null){
						$data_upload [] = 
							[
								'name' => "file",
								'contents' => fopen(Storage::disk(self::MINIO)->url(self::MEDIA_EXAMINATION_LOC.$exam->id.'/'.$name_file), 'r'),
								'filename' => $request->file(self::REV_LAP_UJI)->getClientOriginalName()
							]
						;
		                $data_upload [] = array(
		                    'name'=>"delivered",
		                    'contents'=>json_encode(['by'=>$currentUser->name, self::REFERENCE_ID => '1']),
		                );

		                $examinationService->api_upload($data_upload,$exam->BILLING_ID);
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
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Laporan Uji",$request->input(self::KETERANGAN));
			
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
		$examinationService->insertAttachment($request,$exam->id,$currentUser->id,self::BARANG_FILE2,'form_penerimaan_barang2_','Bukti Penerimaan & Pengeluaran Perangkat Uji2');
		$examinationService->insertAttachment($request,$exam->id,$currentUser->id,self::TANDA_TERIMA,'form_tanda_terima_hasil_pengujian_','Tanda Terima Hasil Pengujian');
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
			    // event(new Notification($data));

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
			    // event(new Notification($data));

            }
           
			if($status == -1){
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,self::SIDANG_QA,$request->input(self::KETERANGAN));
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
				$examinationService->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sertifikat", "Penerbitan Sertfikat");
			}else if($status == -1){
				$examinationService->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAILS_FAIL, self::KONFORMASI_PEMBATALAN,"Pembuatan Sertifikat",$request->input(self::KETERANGAN));
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
                    	self::REFERENCE_ID => '1'
					]
				];
				$examinationService->api_cancel_billing($exam->BILLING_ID, $data_cancel_billing);
			}
			/*
			$data_billing = [
                "draft_id" => $request->input(self::PO_ID),
                "created" => [
                    "by" => $currentUser->name,
                    self::REFERENCE_ID => '1'
                ]
            ];
			$billing = $examinationService->api_billing($data_billing);
			*/
			$exam->PO_ID = $request->input(self::PO_ID);
			$exam->BILLING_ID = null;
			$exam->include_pph = 0;
			$exam->payment_method = 0;
			$exam->VA_name = null;
			$exam->VA_image_url = null;
			$exam->VA_number = null;
			$exam->VA_amount = null;
			$exam->VA_expired = null;

            // $exam->BILLING_ID = $billing && $billing->status ? $billing->data->_id : null;
        }

		if ($request->has(self::SPB_NUMBER)){
            $exam->spb_number = $request->input(self::SPB_NUMBER);
		}
		
        if ($request->hasFile(self::CERTIFICATE_DATE)) {
			$fileService = new FileService();
			$fileProperties = array(
				'path' => self::MEDIA_DEVICE_LOC.$exam->device_id."/",
				'prefix' => "sertifikat_"
			);
			$fileService->upload($request->file($this::CERTIFICATE_DATE), $fileProperties);
			$name_file = $fileService->getFileName();

            if($fileService->isUploaded()){
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
				if($exam_schedule && $exam_schedule->status){
					$api_logs = new ApiLogs;
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
					$api_logs = new ApiLogs;
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

				$logService->createLog("Update ".$request->input(self::STATUS), self::EXAMINATION, $exam);

            Session::flash(self::MESSAGE, 'Examination successfully updated');
            return redirect(self::ADMIN_EXAMINATION);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
        }
    }

    public function generateFromTPN(Request $request) {
		$examinationService = new ExaminationService();
		$id = $request->input('id');
		$type = $request->input('type');
		$filelink = $request->input('filelink');
		
		if($type == self::KUITANSI){
			$exam = Examination::where("id", $id)->first();
		}else{
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
			->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
			->join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
			->leftJoin(self::EXAMINATION_ATTACHMENTS, function($leftJoin){
				$leftJoin->on(self::EXAMINATIONS_ID, '=', 'examination_attachments.examination_id');
				$leftJoin->on(DB::raw('examination_attachments.name'), DB::raw('='),DB::raw("'File Pembayaran'"));
			})
			->first();
		}
		
		return $examinationService->generateFromTPN($exam, $type, $filelink);
    }

    public function downloadForm($id)
    {
        $exam = Examination::find($id);

        if ($exam){
			$fileName = $exam->attachment;
			$fileMinio = Storage::disk(self::MINIO)->get(self::MEDIA_EXAMINATION_LOC.$exam->id.'/'.$fileName);
			return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
        }
    }

    public function downloadMedia($id, $name)
    {
        if (strcmp($name, 'certificate') == 0){
            $device = Device::findOrFail($id);

            if ($device){
				$fileName = $device->certificate;
				$fileMinio = Storage::disk(self::MINIO)->get(self::MEDIA_DEVICE_LOC.$device->id.'/'.$fileName);
				return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
            }
        } else{
            $exam = ExaminationAttach::where(self::EXAMINATION_ID, $id)
                                ->where('name','like','%'.$name.'%')
                                ->first();

            if ($exam){
				$fileName = $exam->attachment;
				$fileMinio = Storage::disk(self::MINIO)->get(self::MEDIA_EXAMINATION_LOC.$exam->examination_id.'/'.$fileName);
				return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
            }
        }
    }

    public function downloadRefUjiFile($id)
    {
        $data = ExaminationAttach::find($id);

        if ($data){
			$fileName = $data->attachment;
			$fileMinio = Storage::disk(self::MINIO)->get(self::MEDIA_EXAMINATION_LOC.$data->examination_id.'/'.$fileName);
			return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
        }
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
		$sortFrom = $sortFromQuery = $request->get('sort_from', 'examinations.created_at');
		$sortBy = $request->get('sort_by', 'dsc');


        $tempData = $examinationService->requestQuery($request, $search, '', '', null, null);

		$query = $tempData[0];
		 
		if($sortFrom == 'created_at'){
			$sortFromQuery = 'examinations.created_at';
		} if ( $sortFrom == 'device_name'){
			$sortFromQuery = 'devices.name';
		} if ( $sortFrom == 'updated_at'){
			$sortFromQuery = 'examinations.updated_at';
		}

		$data = $query
			->orderBy($sortFromQuery, $sortBy)
			->get()
		;

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
			'Total Biaya',
			'Tanggal Registrasi'
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
			$tahap = self::REGISTRASI;
			if(	$row->registration_status != 1){ $tahap = self::REGISTRASI;
			}
			if(	$row->registration_status == 1 
				&& $row->function_status != 1){ $tahap = self::UJI_FUNGSI;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status != 1){ $tahap = self::TINJAUAN_KONTRAK;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status != 1){ $tahap = 'SPB';
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status == 1
				&& $row->payment_status != 1){ $tahap = self::PEMBAYARAN;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status == 1
				&& $row->payment_status == 1
				&& $row->spk_status != 1){ $tahap = self::PEMBUATAN_SPK;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status == 1
				&& $row->payment_status == 1
				&& $row->spk_status == 1
				&& $row->examination_status != 1){ $tahap = self::PELAKSANAAN_UJI;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status == 1
				&& $row->payment_status == 1
				&& $row->spk_status == 1
				&& $row->examination_status == 1
				&& $row->resume_status != 1){ $tahap = self::LAPORAN_UJI;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status == 1
				&& $row->payment_status == 1
				&& $row->spk_status == 1
				&& $row->examination_status == 1
				&& $row->resume_status == 1
				&& $row->qa_status != 1){ $tahap = self::SIDANG_QA;
			}
			if(	$row->registration_status == 1
				&& $row->function_status == 1
				&& $row->contract_status == 1
				&& $row->spb_status == 1
				&& $row->payment_status == 1
				&& $row->spk_status == 1
				&& $row->examination_status == 1
				&& $row->resume_status == 1
				&& $row->qa_status == 1
				&& $row->certificate_status != 1){ $tahap = 'Penerbitan Sertifikat';
			}

			/*End Tahap Pengujian*/

			/*ExaminationType*/
			if(!$row->examinationType->name){
				$examType_name = '';
			}else{
				$examType_name = $row->examinationType->name;
			}
			if(!$row->examinationType->description){
				$examType_desc = '';
			}else{
				$examType_desc = $row->examinationType->description;
			}
			/*EndExaminationType*/
			
			/*User*/
			if(!$row->user->name){
				$user_name = '';
			}else{
				$user_name = $row->user->name;
			}
			if(!$row->user->email){
				$user_email = '';
			}else{
				$user_email = $row->user->email;
			}
			if(!$row->user->address){
				$user_address = '';
			}else{
				$user_address = $row->user->address;
			}
			if(!$row->user->phone_number){
				$user_phone_number = '';
			}else{
				$user_phone_number = $row->user->phone_number;
			}
			if(!$row->user->fax){
				$user_fax = '';
			}else{
				$user_fax = $row->user->fax;
			}
			/*EndUser*/
			
			/*Company*/
			if(!$row->company->siup_date){
				$siup_date = '';
			}else{
				$siup_date = date(self::DATE_FORMAT_3, strtotime($row->company->siup_date));
			}
			if(!$row->company->qs_certificate_date){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date(self::DATE_FORMAT_3, strtotime($row->company->qs_certificate_date));
			}
			if(!$row->company->name){
				$company_name = '';
			}else{
				$company_name = $row->company->name;
			}
			if(!$row->company->address){
				$company_address = '';
			}else{
				$company_address = $row->company->address;
			}
			if(!$row->company->city){
				$company_city = '';
			}else{
				$company_city = $row->company->city;
			}
			if(!$row->company->postal_code){
				$company_postal_code = '';
			}else{
				$company_postal_code = $row->company->postal_code;
			}
			if(!$row->company->email){
				$company_email = '';
			}else{
				$company_email = $row->company->email;
			}
			if(!$row->company->phone_number){
				$company_phone_number = '';
			}else{
				$company_phone_number = $row->company->phone_number;
			}
			if(!$row->company->fax){
				$company_fax = '';
			}else{
				$company_fax = $row->company->fax;
			}
			if(!$row->company->npwp_number){
				$company_npwp_number = '';
			}else{
				$company_npwp_number = $row->company->npwp_number;
			}
			if(!$row->company->siup_number){
				$company_siup_number = '';
			}else{
				$company_siup_number = $row->company->siup_number;
			}
			if(!$row->company->qs_certificate_number){
				$company_qs_certificate_number = '';
			}else{
				$company_qs_certificate_number = $row->company->qs_certificate_number;
			}
			/*EndCompany*/
			
			/*Device*/
			if(!$row->device->valid_from){
				$valid_from = '';
			}else{
				$valid_from = date(self::DATE_FORMAT_3, strtotime($row->device->valid_from));
			}
			if(!$row->device->valid_thru){
				$valid_thru = '';
			}else{
				$valid_thru = date(self::DATE_FORMAT_3, strtotime($row->device->valid_thru));
			}
			if(!$row->device->name){
				$device_name = '';
			}else{
				$device_name = $row->device->name;
			}
			if(!$row->device->mark){
				$device_mark = '';
			}else{
				$device_mark = $row->device->mark;
			}
			if(!$row->device->capacity){
				$device_capacity = '';
			}else{
				$device_capacity = $row->device->capacity;
			}
			if(!$row->device->manufactured_by){
				$device_manufactured_by = '';
			}else{
				$device_manufactured_by = $row->device->manufactured_by;
			}
			if(!$row->device->serial_number){
				$device_serial_number = '';
			}else{
				$device_serial_number = $row->device->serial_number;
			}
			if(!$row->device->model){
				$device_model = '';
			}else{
				$device_model = $row->device->model;
			}
			if(!$row->device->test_reference){
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
				$row->price,
				date('d-m-Y', strtotime($row->created_at)),
			];
		}
		
		$logService->createLog("download_excel", $this::EXAMINATION, ""); 

		$excel = \App\Services\ExcelService::download($examsArray, 'Data Pengujian');
        return response($excel['file'], 200, $excel['headers']);
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
		$logService = new LogService();
		$notificationService = new NotificationService();
		$examinationService = new ExaminationService();
		
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

			$logService->createLog("update", "REVISI", $device);

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
			// event(new Notification($data));

            Session::flash(self::MESSAGE, 'Examination successfully updated');
			$examinationService->sendEmailRevisi(
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
        } catch(Exception $e){ return redirect('/admin/examination/revisi/'.$request->input(self::ID_EXAM).'')->with(self::ERROR, 'Save failed'); }
    }
	
	public function tanggalkontrak(Request $request)
    {
		$examinationService = new ExaminationService();
		try{
			$data = $examinationService->tanggalkontrak($request);
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
		$examinationService = new ExaminationService();
		try{
			$data = $examinationService->tandaterima($request);
			$request->session()->put('key_tanda_terima', $data);
			Session::flash(self::MESSAGE, 'Tanda Terima successfully created');
			echo 1;
		} catch(Exception $e){
			Session::flash(self::ERROR, 'Tanda Terima failed');
			echo 0;
		}
    }
	
	public function destroy($id,$page,$reason = null)
	{ 
		 
		$logs_a_device = NULL;
		$logService = new LogService();
		
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
				
				if (Storage::disk(self::MINIO)->exists(self::EXAMINATION_LOC.$id)){
					Storage::disk(self::MINIO)->deleteDirectory(self::EXAMINATION_LOC.$id);
				}

				$logService->createAdminLog("Hapus Data Pengujian", $page, $logs_a_exam.$logs_a_device, urldecode($reason));

				Session::flash(self::MESSAGE, 'Examination successfully deleted');
				return redirect(self::ADMIN_EXAMINATION);
			}catch (Exception $e){ return redirect(self::ADMIN_EXAMINATION)->with(self::ERROR, 'Delete failed'); }
		}
	}
	
	public function resetUjiFungsi($id,$reason = null)
	{
		$currentUser = Auth::user(); 
		$logService = new LogService();

        if ($currentUser){
			$exam = Examination::find($id);
			if ($exam){
				try{
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

					$logService->createLog("Reset Uji Fungsi", self::EXAMINATION, $exam);
					$logService->createAdminLog("Reset Uji Fungsi", "Pengujian -> Change Status", $exam, urldecode($reason));
					
					Session::flash(self::MESSAGE, 'Function Test successfully reset');
					return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC);
				}catch (Exception $e){ return redirect(self::ADMIN_EXAMINATION_LOC.$exam->id.self::EDIT_LOC)->with(self::ERROR, 'Reset failed'); }
			}
		}
	}
	
	public function autocomplete($query) {
		$data1 = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(3)
				->distinct()
                ->get();
		
		$data2 = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where(self::COMPANIES_NAME, 'like','%'.$query.'%')
				->orderBy(self::COMPANIES_NAME)
                ->take(3)
				->distinct()
                ->get();
		
		return array_merge((array)$data1,(array)$data2); 
    }
	
	public function generateSPKCodeManual(Request $request) {
		$examinationService = new ExaminationService();
		return $examinationService->generateSPKCode($request->input('lab_code'),$request->input(self::EXAM_TYPE),$request->input('year'));
    }
	
	public function generateSPB(Request $request) {
		$examinationService = new ExaminationService();
		$exam_id = $request->session()->get('key_exam_id_for_generate_spb');
		$spb_number = $request->session()->get('key_spb_number_for_generate_spb');
		if($spb_number == "" || $spb_number == null){
			$spb_number = $examinationService->generateSPBNumber();
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
	
	public function generateSPBData(Request $request) {
		$examinationService = new ExaminationService();
		$general_setting_poh = GeneralSetting::where('code', 'poh_manager_urel')->first();
		$is_poh = $general_setting_poh && $general_setting_poh->is_active ?  1 : 0;
		$manager_urels = $examinationService->manager_urels($general_setting_poh);
		
		$check_spb_number = Examination::where(self::SPB_NUMBER, $request->input(self::SPB_NUMBER))->first();
		if($check_spb_number){
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
			for($i=0;$i<count((array)$arr_biaya);$i++){
				$biaya = $biaya + $arr_biaya[$i];
			} 
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
	                "name" => "PT. TELKOM INDONESIA (PERSERO) Tbk",
	                "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
	                "phone" => "(+62) 812-2483-7500",
	                "email" => "urelddstelkom@gmail.com",
	                "npwp" => "01.000.013.1-093.000"
	            ],
	            "to" => [
	                "name" => $exam->company->name ? $exam->company->name : "-",
	                "address" => $exam->company->address ? $exam->company->address : "-",
	                "phone" => $exam->company->phone_number ? $exam->company->phone_number : "-",
	                "email" => $exam->user->email ? $exam->user->email : "-",
	                "npwp" => $exam->company->npwp_number ? $exam->company->npwp_number : "-"
	            ],
	            "product_id" => config("app.product_id_tth_2"), //product_id TTH untuk Pengujian
	            "details" => $details,
	            "created" => [
	                "by" => $exam->user->name,
	                self::REFERENCE_ID => '1'
	            ],
	            "include_tax_invoice" => true,
	            "bank" => [
	                "owner" => "Divisi RisTI TELKOM",
	                "account_number" => "131-0096022712",
	                "bank_name" => "BANK MANDIRI",
	                "branch_office" => "KCP KAMPUS TELKOM BANDUNG"         
	            ]
	        ];
	        $purchase = $examinationService->api_purchase($data_draft);
	        $total_price = $biaya;
            $PO_ID = $purchase && $purchase->status ? $purchase->data->_id : null;
            $tax = floor(0.1*$total_price);
            $final_price = $total_price + $tax;
			/* END Kirim Draft ke TPN */
			$data = []; 
			$data[] = [
				self::SPB_NUMBER => $spb_number,
				self::SPB_DATE => $spb_date,
				'arr_nama_perangkat' => $arr_nama_perangkat,
				'arr_biaya' => $arr_biaya,
				'exam' => $exam,
				self::MANAGER_UREL => $manager_urels,
				'is_poh' => $is_poh,
				'payment_method' => $examinationService->api_get_payment_methods()
			];

			$request->session()->put('key_exam_for_spb', $data);
			echo $PO_ID.'myToken'.$final_price;
		}
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
		if( strpos( $data[0]->company->phone_number, "/" ) !== false ) {$company_phone = urlencode(urlencode($data[0]->company->phone));}
			else{$company_phone = $data[0]->company->phone_number?: '-';}
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

		$PDFData = array(
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
			'pic' => $pic,
			'currentUser' => Auth::user()
		);

		$PDF = new \App\Services\PDF\PDFService();
		$PDF->cetakUjiFungsi($PDFData);
    }
	
	function cetakTechnicalMeeting($id)
    {
		$client = new Client([
			self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
			self::BASE_URI => config(self::APP_URI_API_BSP),
			self::TIMEOUT  => 60.0,
		]);
	
		$data = Examination::where('id','=',$id)
			->with(self::COMPANY)
			->with(self::DEVICE)
			->with(self::EQUIPMENT)
			->with(self::EXAMINATION_LAB)
			->first()
		;

		$user = User::where('id', '=', $data['created_by'])->first();

		$res_manager_lab = $client->get('user/getManagerLabInfo?labCode='.$data->examinationLab->lab_code)->getBody();
		$manager_lab = json_decode($res_manager_lab);
		
		$res_manager_urel = $client->get('user/getManagerLabInfo?groupId=MU')->getBody();
		$manager_urel = json_decode($res_manager_urel);

		$tgl_uji_fungsi = '-';
		if($data->function_test_date_approval == 1){
			if($data->function_date != null){
				if( strpos( $data->function_date, "/" ) !== false ) {$tgl_uji_fungsi = urlencode(urlencode(date(self::J_F_Y, strtotime($data->function_date))));}
					else{$tgl_uji_fungsi = date(self::J_F_Y, strtotime($data->function_date))?: '-';}
			}else{
				if( strpos( $data->deal_test_date, "/" ) !== false ) {$tgl_uji_fungsi = urlencode(urlencode(date(self::J_F_Y, strtotime($data->deal_test_date))));}
					else{$tgl_uji_fungsi = date(self::J_F_Y, strtotime($data->deal_test_date))?: '-';}
			}
		}

		$PDFData = array(
			'deviceName' => \App\Services\MyHelper::setDefault($data->device['name'], '-'),
			'deviceMark' => \App\Services\MyHelper::setDefault($data->device['mark'], '-'),
			'deviceModel' => \App\Services\MyHelper::setDefault($data->device['model'], '-'),
			'deviceCapacity' => \App\Services\MyHelper::setDefault($data->device['capacity'], '-'),
			'deviceTestReference' => \App\Services\MyHelper::setDefault($data->device['test_reference'], '-'),
			'examinationFunctionTestTE' => \App\Services\MyHelper::setDefault($data['function_test_TE'], '0'),
			'examinationFunctionTestPIC' => \App\Services\MyHelper::setDefault($data['function_test_PIC'], '-'),
			'companyAddress' => \App\Services\MyHelper::setDefault($data->company['address'], '-'),
			'companyCity' => \App\Services\MyHelper::setDefault($data->company['city'], '-'),
			'examinationFunctionDate' => \App\Services\MyHelper::setDefault($tgl_uji_fungsi, '-'),
			'userName' => \App\Services\MyHelper::setDefault($user['name'], '-'),
			'adminName' => Auth::user()->name,
			'managerLab' => \App\Services\MyHelper::setDefault($manager_lab->data[0]->name, '-'),
			'managerUrel' => \App\Services\MyHelper::setDefault($manager_urel->data[0]->name, '-')
		);

		$PDF = new \App\Services\PDF\PDFService();
		return $PDF->cetakTechnicalMeetingUjiLokasi($PDFData);
	}
	
	function cetakFormBarang($id, Request $request)
    {
		$examinationService = new ExaminationService();
		$data = Examination::where('id','=',$id)
			->with(self::EXAMINATION_TYPE)
			->with(self::EXAMINATION_LAB)
			->with(self::COMPANY)
			->with('user')
			->with(self::DEVICE)
			->with(self::EQUIPMENT)
			->get()
		;
		
		if(isset($data[0]->equipment[0]->location)){
			if ($data[0]->equipment[0]->no) {
				$kode_barang = $data[0]->equipment[0]->no;
			}else{
				$kode_barang = $examinationService->generateKodeBarang($data[0]->ExaminationLab->lab_init,$examinationService->romawi(date('n')),date('Y'));
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

		$PDFData = array(
			'request' => $request,
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
			self::CONTRACT_DATE => $contract_date,
			'equipment' => $data[0]->equipment,
			'currentUser' => Auth::user()
		);
		
		$PDF = new \App\Services\PDF\PDFService();
		$PDF->cetakBuktiPenerimaanPerangkat($PDFData);
    }
	
	public function generateEquip(Request $request)
    {
		$exam_id = $request->session()->get('key_exam_id_for_generate_equip_masuk');
		$equipment = Equipment::where(self::EXAMINATION_ID, $exam_id)->get();
        $location = Equipment::where(self::EXAMINATION_ID, $exam_id)->first();
        $examination = DB::table(self::EXAMINATIONS)
			->join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
			->select(
					self::EXAMINATIONS_ID,
					self::DEVICE_NAME,
					'devices.model'
					)
            ->where(self::EXAMINATIONS_ID, $exam_id)
			->orderBy(self::DEVICE_NAME)
			->first(); 
        return view('admin.equipment.edit')
            ->with('item', $examination)
            ->with(self::LOCATION, $location)
            ->with('data', $equipment);
    }
	
	public function deleteRevLapUji($id)
    {
        $examination_attachment = ExaminationAttach::find($id);
        if($examination_attachment){

            // unlink stels_sales_detail.attachment
            if (Storage::disk(self::MINIO)->exists(self::EXAMINATION_LOC.$examination_attachment->examination_id.'\\'.$examination_attachment->attachment)){
                Storage::disk(self::MINIO)->deleteDirectory(self::EXAMINATION_LOC.$examination_attachment->examination_id.'\\'.$examination_attachment->attachment);
            }

            // delete stels_sales_detail
            $examination_attachment->delete();

            Session::flash(self::MESSAGE, 'Successfully Delete Revision File');
        }else{
            Session::flash(self::ERROR, 'Undefined Data');
        }
            return redirect(self::ADMIN_EXAMINATION_LOC.$examination_attachment->examination_id.self::EDIT_LOC);

	}

}
