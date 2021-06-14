<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;
use Mail;
use Input;
use File;
use Session;
use Response;

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
	private const HIDE_REF_UJI_FILE = 'hide_ref_uji_file';
	private const MEDIA_EXAMINATION_LOC = 'examination/';
	private const HIDE_PRINSIPAL_FILE = 'hide_prinsipal_file';
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
	
	public function submit(Request $request)
	{
		//UNTUK TESTING AJAX call permohonan
		// return response()->json([
		// 	'status' => true,
		// 	'data' => [
		// 		'file_dll' => $request->file('fuploaddll')->getClientOriginalName(),
		// 		'examinationReference' => $request->input('f1-referensi-perangkat')
		// 	]
		// ]);
		$this->submit_update($request, self::SUBMIT);
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

	public function update(Request $request)
	{ 
		$this->submit_update($request, self::UPDATE);
	}

	public function upload(Request $request){ 
		$exam_id = $request->session()->get('exam_id');
		
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
			'prefix' => "form_uji_"
		);
		$fileService->upload($request->file('fuploaddetailpengujian'), $fileProperties);
		$fuploaddetailpengujian_name = $fileService->isUploaded() ? $fileService->getFileName() : '';

		
		DB::table(self::EXAMINATIONS)
            ->where('id', ''.$exam_id.'')
            ->update([self::ATTACHMENT => ''.$fuploaddetailpengujian_name.'']);
	}

	public function uploadEdit(Request $request){ 
		$fileService = new FileService();
		$exam_id = $request->session()->get(self::HIDE_EXAM_ID);
		if(is_array($exam_id)){
			$exam_id = $exam_id[0];
		}
		$exam = Examination::find($exam_id); 
		$fileProperties = array(
			'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
			'prefix' => "form_uji_",
			'oldFile'=>$exam->attachment
		);
		$fileService->upload($request->file('fuploaddetailpengujian_edit'), $fileProperties);
		$fuploaddetailpengujian_name = $fileService->isUploaded() ? $fileService->getFileName() : '';


		DB::table(self::EXAMINATIONS)
            ->where('id', ''.$exam_id.'')
			->update([self::ATTACHMENT => ''.$fuploaddetailpengujian_name.'']);
	}
	
	public function cekSNjnsPengujian(Request $request){
		$expDate = Carbon::now()->subMonths(6);
		$query = "SELECT
					*, e.id as id_exam, d.status as status_device
				FROM
					examinations e, devices d
				WHERE e.device_id = d.id
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
			$request->session()->put(self::HIDE_EXAM_ID, $exam_id);
			$exam_no_reg = DB::table(self::EXAMINATIONS)->where('id', ''.$exam_id.'')->first();
			$device_id = $request->input('hide_device_id');
		}else{
			$exam_id = Uuid::uuid4();
			$device_id = Uuid::uuid4();
			$request->session()->put('my_exam_id_for_testing', $exam_id);
		}
		//Populate request data
		$jns_perusahaan = $request->input(self::JENIS_PERUSAHAAN);
		$jns_pengujian = $request->input('kode_jenis_pengujian');
		$exam_type = DB::table('examination_types')->where('id', $jns_pengujian)->first();
		$jns_pengujian_name = $exam_type->name;
		$jns_pengujian_desc = $exam_type->description;
		$lokasi_pengujian = $request->input('examination_location');
		$nama_perangkat = $request->input('device_name');
		$merek_perangkat = $request->input('device_mark');
		$kapasitas_perangkat = $request->input('device_capacity');
		$pembuat_perangkat = $request->input('device_made_in');
		$serialNumber_perangkat = $request->input('device_serial_number');
		$model_perangkat = $request->input('device_model');
		$referensi_perangkat = $request->input('test_reference');
		$dll = $sp3 = $refUji = '-'; 
		$request->hasFile('dllFile') && $dll = $request->file('dllFile')->getClientOriginalName();
		$request->hasFile('sp3File') && $sp3 = $request->file('sp3File')->getClientOriginalName();
		$request->hasFile('refUjiFile') && $dll = $request->file('refUjiFile')->getClientOriginalName();
		//TODO 14 June 23:45;
		dd([
			$jns_perusahaan,
			$jns_pengujian,
			$jns_pengujian_name,
			$jns_pengujian_desc,
			$lokasi_pengujian,
			$nama_perangkat,
			$merek_perangkat,
			$kapasitas_perangkat,
			$pembuat_perangkat,
			$serialNumber_perangkat,
			$model_perangkat,
			$referensi_perangkat,
			$dll,
			$sp3,
			$refUji
		]);
		//get no reg
		$no_reg = $type == self::UPDATE ? $exam_no_reg->function_test_NO : $this->generateFunctionTestNumber($jns_pengujian_name);
		$kotaPerusahaan = Company::select('city')->where('id', $company_id)->first()->city;
	
		//UPLOAD File to Minio
		$this->uploadFile($request, 'ref_uji', $exam_id);
		$this->uploadFile($request, 'dll', $exam_id);
		$this->uploadFile($request, 'sp3', $exam_id);

		//Update Device table
		if ($type == self::SUBMIT) {
			$device = new Device;
			$device->id = $device_id;
			$device->name = ''.$nama_perangkat.'';
			$device->mark = ''.$merek_perangkat.'';
			$device->capacity = ''.$kapasitas_perangkat.'';
			$device->manufactured_by = ''.$pembuat_perangkat.'';
			$device->serial_number = ''.$serialNumber_perangkat.'';
			$device->model = ''.$model_perangkat.'';
			$device->test_reference = ''.$referensi_perangkat.'';
			$device->certificate = NULL;
			$device->status = 1;
			$device->valid_from = NULL; //
			$device->valid_thru = NULL; //
			$device->is_active = 1;
			$device->created_by = ''.$user_id.'';
			$device->updated_by = ''.$user_id.'';
			$device->created_at = ''.date(self::DATE_FORMAT).'';
			$device->updated_at = ''.date(self::DATE_FORMAT).'';
			$device->save();

			$exam = new Examination;
			$exam->id = $exam_id;
			$exam->examination_type_id = ''.$jns_pengujian.'';
			$exam->company_id = ''.$company_id.'';
			$exam->device_id = ''.$device_id.'';
				$ref_perangkat = explode(",", $referensi_perangkat);
				$examLab = DB::table('stels')->where('code', ''.$ref_perangkat[0].'')->first();
				if(count(array($examLab))==0){
					$exam->examination_lab_id = NULL;
				}
				else{
					$exam->examination_lab_id = $examLab->type;
				}
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
			$exam->created_by = ''.$user_id.'';
			$exam->updated_by = ''.$user_id.'';
			$exam->created_at = ''.date(self::DATE_FORMAT).'';
			$exam->updated_at = ''.date(self::DATE_FORMAT).'';
			$exam->jns_perusahaan = ''.$jns_perusahaan.'';
			$exam->is_loc_test = $lokasi_pengujian;
			$exam->keterangan = ''.$request->input('hide_cekSNjnsPengujian').'';
			$exam->function_test_NO = ''.$no_reg.'';
			$exam->save();
			$request->session()->put('exam_id', $exam_id);
			
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $exam_id;
			$exam_hist->date_action = date(self::DATE_FORMAT);
			$exam_hist->tahap = 'Pengisian Form Permohonan';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date(self::DATE_FORMAT);
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
					self::CREATED_AT=>date(self::DATE_FORMAT),
					self::UPDATED_AT=>date(self::DATE_FORMAT)
				);
				$notification_id = $notificationService->make($data);
				$data['id'] = $notification_id;
				//event(new Notification($data)); 
			}
		}

		else{
			$ref_perangkat = explode(",", $referensi_perangkat);
			$examLab = DB::table('stels')->where('code', ''.$ref_perangkat[0].'')->first();
			$idLab = count((array)$examLab)>0 ? $examLab->type : $exam_no_reg->examination_lab_id;
			$query_update_company = "UPDATE examinations
				SET 
					examination_lab_id = '".$idLab."',
					jns_perusahaan = '".$jns_perusahaan."',
					is_loc_test = '".$lokasi_pengujian.
					self::UPDATE_BY_EQUAL.$user_id.
					self::UPDATE_AT_EQUAL.date(self::DATE_FORMAT)."'
				WHERE id = '".$exam_id."'
			";
			DB::update($query_update_company);
			
			$query_update_device = "UPDATE devices
				SET 
					name = '".$nama_perangkat."',
					mark = '".$merek_perangkat."',
					capacity = '".$kapasitas_perangkat."',
					manufactured_by = '".$pembuat_perangkat."',
					serial_number = '".$serialNumber_perangkat."',
					model = '".$model_perangkat."',
					test_reference = '".$referensi_perangkat.
					self::UPDATE_BY_EQUAL.$user_id.
					self::UPDATE_AT_EQUAL.date(self::DATE_FORMAT)."'
				WHERE id = '".$device_id."'
			";
			DB::update($query_update_device);
			
			$query_update_ref_uji = "UPDATE examination_attachments
				SET 
					attachment = '".$fuploadrefuji_name."'
				WHERE examination_id = '".$exam_id."' AND `name` = '".self::REFERENSI_UJI."'
			";
			DB::update($query_update_ref_uji);
			if($jns_pengujian == 1 && $jns_perusahaan != self::PABRIKAN){
				$query_update_attach = "UPDATE examination_attachments
					SET 
						attachment = '".$fuploadprinsipal_name."'
					WHERE examination_id = '".$exam_id."' AND `name` = 'Surat Dukungan Prinsipal'
				";
				DB::update($query_update_attach);
			}else if($jns_pengujian == 2){
				$query_update_attach = "UPDATE examination_attachments
					SET 
						attachment = '".$fuploadsp3_name."'
					WHERE examination_id = '".$exam_id."' AND `name` = 'SP3'
				";
				DB::update($query_update_attach);
			}
			
			$query_update_dll = "UPDATE examination_attachments
				SET 
					attachment = '".$fuploaddll_name."'
				WHERE examination_id = '".$exam_id."' AND `name` = '".self::FILE_LAINNYA."'
			";
			DB::update($query_update_dll);
			
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
	}

	private function uploadFile($request, $type, $exam_id){
		$fileService = new FileService();
		$fileProperties = array(
			'ref_uji' => [
				'inputName' => 'referensiUji',
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "ref_uji_",
				'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_REF_UJI_FILE, "") : "",
				'attachName' => 'Referensi Uji'
			],
			'dll' => [
				'inputName' => 'dll',
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "dll_",
				'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_DLL_FILE, "") : "",
				'attachName' => 'File Lainnya'
			],
			'sp3' => [
				'inputName' => 'sp3',
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "sp3_",
				'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_SP3_FILE, "") : "",
				'attachName' => 'SP3'
			]
		);

		if(!$fileProperties.include($type)) return false;

		if ($request->hasFile(  $fileProperties[$type]['inputName']  )) {
			$fileService->upload( $fileProperties[$type]['inputName'], $fileProperties);
			$uploadedFileName = $fileService->isUploaded() ? $fileService->getFileName() : ($type == self::UPDATE ? $request->input(self::HIDE_DLL_FILE) : '');
			//TODO input examination attachment();
			DB::table('examination_attachments')->insert([
				'id' => Uuid::uuid4(),
				'examination_id' => $exam_id,
				'name' => $fileProperties[$type]['attachName'],
				'attachment' => $uploadedFileName,
				'no' => '',
				'tgl' => '',
				self::CREATED_BY => Auth::user()->id,
				self::UPDATED_BY => Auth::user()->id,
				self::CREATED_AT => date(self::DATE_FORMAT),
				self::UPDATED_AT => date(self::DATE_FORMAT)
			]);
		}
		return true;
	}
}
