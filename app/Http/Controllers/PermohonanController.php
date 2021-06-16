<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Auth;
use Mail;
use Input;
use File;
use Session;
//use Response;

use App\Device;
use App\Examination;
use App\ExaminationAttach;
use App\ExaminationType;
use App\Feedback;
use App\ExaminationHistory;
use App\Footer;
use App\Question;
use App\AdminRole;
use App\Company;
use App\GeneralSetting;
use App\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use App\Events\Notification;
use App\NotificationTable;
use App\Services\NotificationService;
use App\Services\FileService;

use Carbon\Carbon;

class PermohonanController extends Controller
{
	private const ATTRIBUTES = 'attributes';
	private const DATA_NOT_FOUND = 'Data Not Found';
	private const EMAIL = 'email';
	private const JENIS_PERUSAHAAN = 'jns_perusahaan';
	private const F1_PLG_ID_PERUSAHAAN = 'f1-plg_id-perusahaan';
	private const F1_NIB_PERUSAHAAN = 'f1-nib-perusahaan';
	private const NAMA_PERANGKAT = 'nama_perangkat';
	private const MODEL_PERANGKAT = 'model_perangkat';
	private const KAPASITAS_PERANGKAT = 'kapasitas_perangkat';
	private const FUPLOADSIUPP = 'fuploadsiupp';
	private const MEDIA_COMPANY_LOC = 'company/';
	private const HIDE_SIUPP_FILE = 'hide_siupp_file';
	private const FUPLOADLAMPIRAN = 'fuploadlampiran';
	private const HIDE_SERTIFIKAT_FILE = 'hide_sertifikat_file';
	private const UPLOADNPWP = 'fuploadnpwp';
	private const HIDE_NPWP_FILE = 'hide_npwp_file';
	private const DATE_FORMAT = 'Y-m-d H:i:s'; 
	private const FUPLOADUJI = 'fuploadrefuji';
	private const PABRIKAN = 'Pabrikan';
	private const FUPLOADPRINSIPAL = 'fuploadprinsipal';
	private const FUPLOADSP3 = 'fuploadsp3';
	private const FUPLOADDLL = 'fuploaddll';
	private const EXAMINATION_ATTACHMENTS = 'examination_attachments';
	private const ATTACHMENT = 'attachment';
	private const EXAMINATION_ID = 'examination_id';
	private const CREATED_AT = 'created_at';
	private const CREATED_BY = 'created_by';
	private const UPDATED_AT = 'updated_at';
	private const UPDATED_BY = 'updated_by';
	private const REFERENSI_UJI = 'Referensi Uji';
	private const TINJAUAN_KONTRAK = 'Tinjauan Kontrak';
	private const LAPORAN_UJI = 'Laporan Uji';
	private const FILE_PEMBAYARAN = 'File Pembayaran';
	private const FILE_LAINNYA = 'File Lainnya';
	private const DATE_FORMAT2 = 'Y-m-d';
	private const MESSAGE = 'message';
	private const IS_READ = 'is_read';
	private const USERS = 'users';
	private const CLIENT_PERMOHONAN_EMAIL = 'client.permohonan.email';
	private const HIDE_EXAM_ID = 'hide_exam_id';
	private const EXAMINATIONS = 'examinations';
	private const MEDIA_EXAMINATION_LOC = 'examination/';
	private const HIDE_PRINSIPAL_FILE = 'hide_prinsipal_file';
	private const HIDE_REF_UJI_FILE = 'hide_ref_uji_file';
	private const HIDE_SP3_FILE = 'hide_sp3_file';
	private const HIDE_DLL_FILE = 'hide_dll_file';
	private const TOKEN = '|token|';
	private const SUBMIT = 'submit';
	private const UPDATE = 'update';
	private const UPDATE_BY_EQUAL = "', updated_by = '";
	private const UPDATE_AT_EQUAL = "', updated_at = '";

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
	{
		return view('client.home');
	}
	
	public function createPermohonan()
	{
		$currentUser = Auth::user();
		$message = '';
		$message_slideshow = '';
		$message_about = '';
		$message_stels = '';
		$message_question = '';
		if(isset($currentUser)){

			$user_id = ''.$currentUser[self::ATTRIBUTES]['id'].'';
		}else{
			$user_id = 0;
		}
		$query = "SELECT * FROM examination_types";
		$page = "";
		$data = DB::select($query);

		$partners = Footer::where('is_active', true)->get();
            
		if (count($data) == 0){
			$message = self::DATA_NOT_FOUND;
		}
		
        $query_pop_up_information = "SELECT * FROM certifications WHERE type = 0 AND is_active = 1";
		$data_pop_up_information = DB::select($query_pop_up_information);
        
        $query_slideshow = "SELECT * FROM slideshows WHERE is_active = 1 ORDER BY position";
		$data_slideshow = DB::select($query_slideshow);
            
		if (count($data_slideshow) == 0){
			$message_slideshow = self::DATA_NOT_FOUND;
		}
		
        $query_about = "SELECT * FROM articles WHERE is_active = 1 AND type='About'";
		$data_about = DB::select($query_about);
            
		if (count($data_about) == 0){
			$message_about = self::DATA_NOT_FOUND;
		}
		
        $query_procedure = "SELECT * FROM articles WHERE is_active = 1 AND type='Procedure'";
		$data_procedure = DB::select($query_procedure);
            
		if (count($data_about) == 0){
			$message_about = self::DATA_NOT_FOUND;
		}
		
        $query_stels = "SELECT * FROM stels WHERE is_active = 1";
		$data_stels = DB::select($query_stels);
            
		if (count($data_stels) == 0){
			$message_stels = self::DATA_NOT_FOUND;
		}
		
        $query_question = "SELECT * FROM question_categories WHERE is_active = 1 ORDER BY name";
		$data_question = DB::select($query_question);
            
		if (count($data_question) == 0){
			$message_question = self::DATA_NOT_FOUND;
		}
		
        $query_playlist = "SELECT playlist_url FROM youtube WHERE id = 1";
		$data_playlist = DB::select($query_playlist);

		$playlist_id = "";
		$playlist_url = "";
		if (count($data_playlist)){
			$playlist_url = $data_playlist[0]->playlist_url;
			$url_components = parse_url($playlist_url); 
			if(isset($url_components['query'])){
				parse_str($url_components['query'], $params);	
				$playlist_id = $params['list'];
			}	
		}
		
		return view('client.home')
			->with('user_id', $user_id)
			->with('data', $data)
			->with('data_pop_up_information', $data_pop_up_information)
			->with('data_slideshow', $data_slideshow)
			->with('data_about', $data_about)
			->with('data_procedure', $data_procedure)
			->with('data_stels', $data_stels)
			->with('data_question', $data_question)
			->with('partners', $partners)
			->with('playlist_id', $playlist_id)
			->with('playlist_url', $playlist_url)
			->with('count_partners', sizeof($partners))
			->with('page', $page)
			->with(self::MESSAGE,$message)
			->with('message_slideshow',$message_slideshow)
			->with('message_about',$message_about)
			->with('message_stels',$message_stels)
			->with('message_question',$message_question)
		;
	}
	
	public function sendProgressEmail($message)
    {
		if(GeneralSetting::where('code', 'send_email')->first()['is_active']){
			$data = DB::table(self::USERS)
				->where('role_id', 1)
				->where('is_active', 1)
				->get();
		
			Mail::send(self::CLIENT_PERMOHONAN_EMAIL, array('data' => $message), function ($m) use ($data) {
				$m->to($data[0]->email)->subject("Permohonan Pengujian Baru");
			});
		}

        return true;
    }

	public function submit(Request $request)
	{
		$result = $this->submit_update($request, self::SUBMIT);
		return $result;
	}

	public function update(Request $request)
	{ 
		$result = $this->submit_update($request, self::UPDATE);
		return $result;
	}

	public function uploadEdit(Request $request){ 
		$fileService = new FileService();
		$exam_id = $request->input('hide_exam_id');
		$exam = Examination::find($exam_id);
		$exam_attachment = $exam->attachment;
		$exam->registration_status = 1;
		$exam->save();

		$fileProperties = array(
			'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
			'prefix' => "form_uji_",
			'oldFile'=> $exam_attachment
		);
		$fileService->upload($request->file('fuploaddetailpengujian_edit'), $fileProperties);
		if ($fileService->isUploaded()){
			DB::table(self::EXAMINATIONS)
				->where('id', $exam_id)
				->update([self::ATTACHMENT => $fileService->getFileName()])
			;
		}
	}
	
	public function cekSNjnsPengujian(Request $request){
		$expDate = Carbon::now()->subMonths(6);
		$query = "SELECT
					*, e.id as id_exam, d.status as status_device
				FROM
					examinations e, devices d
				WHERE e.device_id = d.id
				AND	e.id <> '".$request->input('exam_id', '')."'
				AND	e.examination_type_id = '".$request->input('examinationType')."'
				AND	TRIM(d.name) = '".trim($request->input(self::NAMA_PERANGKAT), " ")."'
				AND	TRIM(d.model) = '".trim($request->input(self::MODEL_PERANGKAT), " ")."'
				AND	TRIM(d.mark) = '".trim($request->input('merk_perangkat'), " ")."'
				AND	TRIM(d.capacity) = '".trim($request->input(self::KAPASITAS_PERANGKAT)," ")."'
				ORDER BY qa_date DESC;
				";
		$data = DB::select($query);
		if(count($data)){
			if($data[0]->qa_passed == "-1"){
				if($data[0]->qa_date >= $expDate && $data[0]->status_device == 1){
					$code = 2;
					$status = false;
					$remarks = 'QA passed = -1 and not yet 6 months';
				}else{
					$code = -1;
					$status = true;
					$remarks = 'QA passed = -1 but already passed than 6 months';
				}
			}else{
				$code = 1;
				$status = false;
				$remarks = 'device already recorded () in database';
			}			
		}else{
			$code = 0;
			$status = true;
			$remarks = 'device is eligible';
		}

		return response()->json([
			'code' => $code,
			'status' => $status,
			'remarks' => $remarks,
		]);
	}
		
	public function getInfo(Request $request){
		$currentUser = Auth::user();
		$user_id = ''.$currentUser[self::ATTRIBUTES]['id'].'';
        if ($currentUser){
			$query = "SELECT
						u.id AS user_id, u.`name` AS namaPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, u.email AS emailPemohon, u.email2 AS emailPemohon2, u.email3 AS emailPemohon3, u.company_id AS company_id,
						c.`name` AS namaPerusahaan, c.address AS alamatPerusahaan, c.phone_number AS telpPerusahaan, c.fax AS faxPerusahaan, c.email AS emailPerusahaan,
						c.qs_certificate_number AS noSertifikat, c.qs_certificate_file AS fileSertifikat, c.qs_certificate_date AS tglSertifikat,
						c.siup_number AS noSIUPP, c.siup_file AS fileSIUPP, c.siup_date AS tglSIUPP, c.npwp_file AS fileNPWP
					FROM
						users u,
						companies c
					WHERE
						u.company_id = c.id
					AND u.id = '".$user_id."'
					";
			$data = DB::select($query);
			if(count($data)){
				echo $data[0]->namaPemohon.self::TOKEN; #0
				echo $data[0]->emailPemohon.self::TOKEN; #1
				echo $data[0]->namaPerusahaan.self::TOKEN; #2
				echo $data[0]->alamatPerusahaan.self::TOKEN; #3
				echo $data[0]->telpPerusahaan.self::TOKEN; #4
				echo $data[0]->faxPerusahaan.self::TOKEN; #5
				echo $data[0]->emailPerusahaan.self::TOKEN; #6
				echo $data[0]->noSertifikat.self::TOKEN; #7
				echo $data[0]->fileSertifikat.self::TOKEN; #8
				echo date("d-m-Y", strtotime($data[0]->tglSertifikat)).self::TOKEN; #9
				echo $data[0]->noSIUPP.self::TOKEN; #10
				echo $data[0]->fileSIUPP.self::TOKEN; #11
				echo date("d-m-Y", strtotime($data[0]->tglSIUPP)).self::TOKEN; #12
				echo $data[0]->fileNPWP.self::TOKEN; #13
				echo $data[0]->user_id.self::TOKEN; #14
				echo $data[0]->user_id.self::TOKEN; #15
				echo $data[0]->company_id.self::TOKEN; #16
				echo $data[0]->alamatPemohon.self::TOKEN; #17
				echo $data[0]->telpPemohon.self::TOKEN; #18
				echo $data[0]->faxPemohon.self::TOKEN; #19
				echo $data[0]->emailPemohon2.self::TOKEN; #20
				echo $data[0]->emailPemohon3.self::TOKEN; #21
			}else{
				echo 0; //Tidak Ada Data
			}
		}else{
			return 0;
		}
	}
	
	public function feedback(Request $request)
	{
		$notificationService = new NotificationService();
		$quest = Question::find($request->input('question')); 
		if(count((array)$quest)>0){
			$category = $quest->name;
		}else{
			$category = '-';
		}
		$currentUser = Auth::user();
		$feedback = new Feedback;
        $feedback->id = Uuid::uuid4();
        $feedback->category = $category;
        $feedback->email = ''.$request->input(self::EMAIL).'';
        $feedback->subject = ''.$request->input('subject').'';
        $feedback->message = ''.$request->input(self::MESSAGE).'';
        $feedback->created_at = ''.date(self::DATE_FORMAT).'';
        $feedback->created_by = $currentUser->id;
        $feedback->updated_at = ''.date(self::DATE_FORMAT).'';
        $feedback->updated_by = $currentUser->id;

        try{
            $feedback->save();

            
			if($currentUser){
				$id_user = $currentUser->id;
			}else{
				$id_user = $feedback->email;
			}
			
			$data= array( 
				"from"=>$id_user,
				"to"=>"admin",
				self::MESSAGE=>$feedback->email." mengirim feedback ",
				"url"=>"feedback/".$feedback->id.'/reply',
				self::IS_READ=>0,
				self::CREATED_AT=>date(self::DATE_FORMAT),
				self::UPDATED_AT=>date(self::DATE_FORMAT)
	        );
		  	$notification_id = $notificationService->make($data);
			$data['id'] = $notification_id; 
	        
	        //event(new Notification($data)); 

			$this->sendFeedbackEmail($request->input(self::EMAIL),$request->input('subject'),$request->input(self::MESSAGE),$request->input('question'));
            Session::flash('message_feedback', 'Feedback successfully send');
        } catch(Exception $e){
            Session::flash('error_feedback', 'Send failed');
        }
	}
	
	public function sendFeedbackEmail($email,$subject,$message,$question)
    {
		if(GeneralSetting::where('code', 'send_email')->first()['is_active']){
			$data = DB::table(self::USERS)
				->join('question_privileges', 'users.id', '=', 'question_privileges.user_id')
				->select('users.email')
				->where('question_privileges.question_id', '=', $question)
				->where('users.is_active', 1)
				->get();
			if(count($data)){
				foreach($data as $row){
					$emails = $row->email;
					Mail::send(self::CLIENT_PERMOHONAN_EMAIL, array('data' => $message. ". This message from ".$email.""), function ($m) use ($emails,$subject) {
						$m->to($emails)->subject($subject);
					});
				}
			}else{
				$data = DB::table(self::USERS)
					->where('id', 1)
					->select(self::EMAIL)
					->get();
				$emails = $data[0]->email;
				Mail::send(self::CLIENT_PERMOHONAN_EMAIL, array('data' => $message. ". This message from ".$email.""), function ($m) use ($emails,$subject) {
					$m->to($emails)->subject($subject);
				});
			} 
		}

        return true;
    }
	
	public function generateFunctionTestNumber($a) { 
		$thisYear = date('Y');
		if($a == "KAL"){
			$where = "SUBSTR(function_test_NO,'5',3) = '".$a."' AND SUBSTR(function_test_NO,'9',4) = '".$thisYear."'";
		}else{
			$where = "SUBSTR(function_test_NO,'5',2) = '".$a."' AND SUBSTR(function_test_NO,'8',4) = '".$thisYear."'";
		}
		$query = "
			SELECT 
			SUBSTR(function_test_NO,'1',3) + 1 AS last_numb
			FROM examinations 
			WHERE 
			".$where."
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query); 
		if (!count($data)){
			$test_number =  '001/'.$a.'/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				$test_number =  '00'.$last_numb.'/'.$a.'/'.$thisYear.'';
			}
			else if($last_numb < 100){
				$test_number =  '0'.$last_numb.'/'.$a.'/'.$thisYear.'';
			}
			else{
				$test_number =  ''.$last_numb.'/'.$a.'/'.$thisYear.'';
			}
		}

		return $test_number;
	}
	
	public function submit_update($request, $type)
	{
		//initialize var by function
		$fileService = new FileService();
		$currentUser = Auth::user();
		$user_id = $currentUser->id;
		$company_id = $currentUser->company_id;
		//initialize var by request type (submit/update)
		if($type == self::UPDATE){
			$exam_id = $request->input(self::HIDE_EXAM_ID);
			$device_id = $request->input('hide_device_id');
			$request->session()->put(self::HIDE_EXAM_ID, $exam_id);
			$exam_no_reg = DB::table(self::EXAMINATIONS)->where('id', ''.$exam_id.'')->first();
		}else{
			$exam_id = Uuid::uuid4()->toString();
			$device_id = Uuid::uuid4()->toString();
			$request->session()->put('my_exam_id_for_testing', $exam_id);
		}
		//Populate request data
		$company_type = $request->input(self::JENIS_PERUSAHAAN);
		$examintaion_type_code = $request->input('kode_jenis_pengujian');
		$examination_type = DB::table('examination_types')->where('id', $examintaion_type_code)->first();
		$examination_type_name = $examination_type->name;
		$examination_type_description = $examination_type->description;
		$examination_location = $request->input('examination_location');
		$device_name = $request->input('device_name');
		$device_mark = $request->input('device_mark');
		$device_capacity = $request->input('device_capacity');
		$device_made_in = $request->input('device_made_in');
		$device_serial_number = $request->input('device_serial_number');
		$device_model = $request->input('device_model');
		$test_reference = $request->input('test_reference');
		$no_reg = $type == self::UPDATE ? $exam_no_reg->function_test_NO : $this->generateFunctionTestNumber($examination_type_name);

		if ($type == self::SUBMIT) {
			$device = new Device;
			$device->id = $device_id;
			$device->name = $device_name;
			$device->mark = $device_mark;
			$device->capacity = $device_capacity;
			$device->manufactured_by = $device_made_in;
			$device->serial_number = $device_serial_number;
			$device->model = $device_model;
			$device->test_reference = $test_reference;
			$device->certificate = NULL;
			$device->status = 1;
			$device->valid_from = NULL; //
			$device->valid_thru = NULL; //
			$device->is_active = 1;
			$device->created_by = $user_id;
			$device->updated_by = $user_id;
			$device->created_at = Carbon::now();
			$device->updated_at = Carbon::now();
			$device->save();

			$exam = new Examination;
			$exam->id = $exam_id;
			$exam->examination_type_id = $examintaion_type_code;
			$exam->company_id = $company_id;
			$exam->device_id = $device_id;
			//$ref_perangkat = explode(",", $test_reference);
			$examLab = DB::table('stels')->where('code', ''.explode(",", $test_reference)[0].'')->first();
			$exam->examination_lab_id = (count(array($examLab))==0 ? NULL : $examLab->type);
			$exam->spk_code = NULL;
			$exam->registration_status = 0;
			$exam->function_status = 0;
			$exam->contract_status = 0;
			$exam->spb_status = 0;
			$exam->payment_status = 0;
			$exam->spk_status = 0;
			$exam->examination_status = 0;
			$exam->resume_status = 0;
			$exam->qa_status = 0;
			$exam->certificate_status = 0;
			$exam->created_by = $user_id;
			$exam->updated_by = $user_id;
			$exam->created_at = Carbon::now();
			$exam->updated_at = Carbon::now();
			$exam->jns_perusahaan = $company_type;
			$exam->is_loc_test = $examination_location;
			//$exam->keterangan = $request->input('hide_cekSNjnsPengujian');
			$exam->function_test_NO = $no_reg;
			$exam->save();
			$request->session()->put('exam_id', $exam_id);
			
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $exam_id;
			$exam_hist->date_action = Carbon::now();
			$exam_hist->tahap = 'Pengisian Form Permohonan';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = Carbon::now();
			$exam_hist->save();

			/* push notif*/
			$notificationService = new NotificationService();
			$admins = AdminRole::where('registration_status',1)->get()->toArray();
			foreach ($admins as $admin) { 
				$data= array( 
					"from"=>$currentUser->id,
					"to"=>$admin['user_id'],
					self::MESSAGE=>"Permohonan Baru",
					"url"=>"examination/".$exam_id."/edit",
					self::IS_READ=>0,
					self::CREATED_AT=>Carbon::now(),
					self::UPDATED_AT=>Carbon::now()
				);
				$notification_id = $notificationService->make($data);
				$data['id'] = $notification_id;
				//event(new Notification($data)); 
			}
		}

		else{
			$ref_perangkat = explode(",", $test_reference);
			$examLab = DB::table('stels')->where('code', $ref_perangkat[0])->first();
			$idLab = count((array)$examLab)>0 ? $examLab->type : $exam_no_reg->examination_lab_id;
			$query_update_company = "UPDATE examinations
				SET 
					examination_lab_id = '".$idLab."',
					jns_perusahaan = '".$company_type."',
					is_loc_test = '".$examination_location.
					self::UPDATE_BY_EQUAL.$user_id.
					self::UPDATE_AT_EQUAL.date(self::DATE_FORMAT)."'
				WHERE id = '".$exam_id."'
			";
			DB::update($query_update_company);
			
			$query_update_device = "UPDATE devices
				SET 
					name = '".$device_name."',
					mark = '".$device_mark."',
					capacity = '".$device_capacity."',
					manufactured_by = '".$device_made_in."',
					serial_number = '".$device_serial_number."',
					model = '".$device_model."',
					test_reference = '".$test_reference.
					self::UPDATE_BY_EQUAL.$user_id.
					self::UPDATE_AT_EQUAL.date(self::DATE_FORMAT)."'
				WHERE id = '".$device_id."'
			";
			
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $exam_id;
			$exam_hist->date_action = date(self::DATE_FORMAT);
			$exam_hist->tahap = 'Edit Form Permohonan';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date(self::DATE_FORMAT);
			$exam_hist->save();
		}

		//UPLOAD File to Minio
		$this->uploadFile($request, 'refUji', $exam_id);
		$this->uploadFile($request, 'dll', $exam_id);
		$this->uploadFile($request, 'sp3', $exam_id);


		return json_encode([
			'success' => true,
			'messages' => 'data has been recorded'
		]);
	}

	private function uploadFile($request, $type, $exam_id){
		$fileService = new FileService();
		$fileDetail = array(
			'refUji' => [
				'inputName' => 'refUjiFile',
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "ref_uji_",
				'oldFile' => $request->input(self::HIDE_REF_UJI_FILE, ""),
				'attachName' => 'Referensi Uji'
			],
			'dll' => [
				'inputName' => 'dllFile',
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "dll_",
				'oldFile' => $request->input(self::HIDE_DLL_FILE, ""),
				'attachName' => 'File Lainnya'
			],
			'sp3' => [
				'inputName' => 'sp3File',
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "sp3_",
				'oldFile' => $request->input(self::HIDE_SP3_FILE, ""),
				'attachName' => 'SP3'
			]
		);

		if( !array_key_exists($type, $fileDetail) ) return false;

		$fileProperties = [
			'path' => $fileDetail[$type]['path'],
			'prefix' => $fileDetail[$type]['prefix'],
			'oldFile' => $fileDetail[$type]['oldFile']
		];

		if ($request->hasFile(  $fileDetail[$type]['inputName']  )) {
			$fileService->upload( $request[$fileDetail[$type]['inputName']], $fileProperties);
			$uploadedFileName = $fileService->isUploaded() ? $fileService->getFileName() : $fileDetail[$type]['oldFile'];
			//TODO input examination attachment();
			$examinationAttachment = DB::table('examination_attachments')
				->where('examination_id', '=', $exam_id)
				->where('name', '=', $fileDetail[$type]['attachName'])
				->get()
			;
			if (count($examinationAttachment)){
				$examinationAttachment->attachment = $uploadedFileName;
				$examinationAttachment->updated_by = Auth::user()->id;
				$examinationAttachment->updated_at = Carbon::now();
				$examinationAttachment->save();
			}else {
				DB::table('examination_attachments')->insert([
					'id' => Uuid::uuid4(),
					'examination_id' => $exam_id,
					'name' => $fileDetail[$type]['attachName'],
					'attachment' => $uploadedFileName,
					'no' => '',
					'tgl' => '',
					self::CREATED_BY => Auth::user()->id,
					self::UPDATED_BY => Auth::user()->id,
					self::CREATED_AT => Carbon::now(),
					self::UPDATED_AT => Carbon::now()
				]);
			}
		}
		return true;
	}

	public function cetak($exam_id){
		// get data
		$exam = Examination::find($exam_id);
		$device = Device::find($exam->device_id);
		$company = Company::find($exam->company_id);
		$exam_type = ExaminationType::find($exam->examination_type_id);
		$user = User::find($exam->created_by);
		//setup pdf data
		$PDFData = array([
			'initPengujian' => $exam_type->name,
			'kotaPerusahaan' => $company->city,
			'date' => Carbon::parse($exam->created_at)->format('d-m-Y'),
			'nama_pemohon' => $user->name,
			'no_reg' => $exam->function_test_NO,
			'alamat_pemohon' => $user->address,
			'telepon_pemohon' => $user->phone_number,
			'email_pemohon' => $user->email,
			'nama_perusahaan' => $company->name,
			'alamat_perusahaan' => $company->address,
			'jns_perusahaan' => $exam->jns_perusahaan,
			'plg_id_perusahaan' => $company->plg_id,
			'nib_perusahaan' => $company->nib,
			'telepon_perusahaan' => $company->phone_number,
			'email_perusahaan' => $company->email,
			'npwp_perusahaan' => $company->npwp_number,
			'nama_perangkat' => $device->name,
			'merek_perangkat' => $device->mark,
			'model_perangkat' => $device->model,
			'kapasitas_perangkat' => $device->capacity,
			'referensi_perangkat' => $device->test_reference,
			'pembuat_perangkat' => $device->manufactured_by,
			'jnsPengujian' => $exam->examination_type_id,
		]);
		$PDF = new \App\Services\PDF\PDFService();
		return $PDF->cetakPermohonan($PDFData);	
	}
}
