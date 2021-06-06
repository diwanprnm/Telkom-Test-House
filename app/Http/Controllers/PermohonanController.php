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
	private const FILE_PEMBAYARAN = 'File Pembayaran';
	private const UPDATED_AT = 'updated_at';
	private const UPDATED_BY = 'updated_by';
	private const REFERENSI_UJI = 'Referensi Uji';
	private const TINJAUAN_KONTRAK = 'Tinjauan Kontrak';
	private const LAPORAN_UJI = 'Laporan Uji';
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
				AND	e.examination_type_id = '".$request->input('jnsPelanggan')."'
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
					$qa_date = date(self::DATE_FORMAT2, strtotime("+6 months", strtotime($data[0]->qa_date)));
					echo '2qa_date'.$data[0]->qa_date.'qa_date'.$qa_date;
				}else{
					echo '-1qa_date'.$data[0]->qa_date.'qa_date'.$data[0]->id_exam;
				}
			}else{
				echo 1;
			}			
		}else{
			echo 0;
		}
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
		$fileService = new FileService();
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
		$currentUser = Auth::user();
		$user_id = ''.$currentUser[self::ATTRIBUTES]['id'].'';
		$company_id = ''.$currentUser[self::ATTRIBUTES]['company_id'].'';
		$nama_pemohon = 
			$request->input('f1-nama-pemohon');
		$alamat_pemohon = 
			$request->input('f1-alamat-pemohon');
		$telepon_pemohon = 
			$request->input('f1-telepon-pemohon');
		$faksimile_pemohon = 
			$request->input('f1-faksimile-pemohon');
		$email_pemohon = 
			$request->input('f1-email-pemohon');
		$jns_perusahaan = 
			$request->input(self::JENIS_PERUSAHAAN);
		$nama_perusahaan = 
			$request->input('f1-nama-perusahaan');
		$alamat_perusahaan = 
			$request->input('f1-alamat-perusahaan');
		$plg_id_perusahaan = 
			$request->input(self::F1_PLG_ID_PERUSAHAAN);
		$nib_perusahaan = 
			$request->input(self::F1_NIB_PERUSAHAAN);
		$telepon_perusahaan = 
			$request->input('f1-telepon-perusahaan');
		$faksimile_perusahaan = 
			$request->input('f1-faksimile-perusahaan');
		$email_perusahaan = 
			$request->input('f1-email-perusahaan');
		$npwp_perusahaan = 
			$request->input('hide_npwpPerusahaan');
		$jns_pengujian = 
			$request->input('hide_jns_pengujian');
				$exam_type = DB::table('examination_types')->where('id', ''.$jns_pengujian.'')->first();
			$jns_pengujian_name = ''.$exam_type->name.'';
			$jns_pengujian_desc = ''.$exam_type->description.'';
		$lokasi_pengujian = 
			$request->input('lokasi_pengujian');
		$nama_perangkat = 
			$request->input('f1-nama-perangkat');
		$merek_perangkat = 
			$request->input('f1-merek-perangkat');
		$kapasitas_perangkat = 
			$request->input('f1-kapasitas-perangkat');
		$pembuat_perangkat = 
			$request->input('f1-pembuat-perangkat');
		$serialNumber_perangkat = 
			$request->input('f1-serialNumber-perangkat');
		$model_perangkat = 
			$request->input('f1-model-perangkat');
		if($request->input('f1-jns-referensi-perangkat') == 1){
			$referensi_perangkat = 
				$request->input('f1-cmb-ref-perangkat');
		}else{
			$referensi_perangkat = 
				$request->input('f1-referensi-perangkat');
				$referensi_perangkat = implode(",", $referensi_perangkat);
		}
		$no_siupp = 
			$request->input('f1-no-siupp');
		$tgl_siupp = 
			$request->input('f1-tgl-siupp');
		$sertifikat_sistem_mutu = 
			$request->input('f1-sertifikat-sistem-mutu');
		$batas_waktu_sistem = 
			$request->input('f1-batas-waktu');
		$no_reg = $type == self::UPDATE ? $exam_no_reg->function_test_NO : $this->generateFunctionTestNumber($jns_pengujian_name);
		$kotaPerusahaan = Company::select('city')->where('id', $company_id)->first()->city;
		
		if($request->ajax()){
			$data = Array([
				'nama_pemohon' => $nama_pemohon,
				'alamat_pemohon' => $alamat_pemohon,
				'telepon_pemohon' => $telepon_pemohon,
				'faksimile_pemohon' => $faksimile_pemohon,
				'email_pemohon' => $email_pemohon,
				self::JENIS_PERUSAHAAN => $jns_perusahaan,
				'nama_perusahaan' => $nama_perusahaan,
				'alamat_perusahaan' => $alamat_perusahaan,
				'plg_id_perusahaan' => $plg_id_perusahaan,
				'nib_perusahaan' => $nib_perusahaan,
				'telepon_perusahaan' => $telepon_perusahaan,
				'faksimile_perusahaan' => $faksimile_perusahaan,
				'email_perusahaan' => $email_perusahaan,
				'npwp_perusahaan' => $npwp_perusahaan,
				self::NAMA_PERANGKAT => $nama_perangkat,
				'merek_perangkat' => $merek_perangkat,
				self::MODEL_PERANGKAT => $model_perangkat,
				self::KAPASITAS_PERANGKAT => $kapasitas_perangkat,
				'referensi_perangkat' => $referensi_perangkat,
				'pembuat_perangkat' => $pembuat_perangkat,
				'jnsPengujian' => $jns_pengujian,
				'initPengujian' => $jns_pengujian_name,
				'descPengujian' => $jns_pengujian_desc,
				'no_reg' => $no_reg,
				'kotaPerusahaan' => $kotaPerusahaan,
				'user_name' => Auth::user()->name,
				'date' => date('d-m-Y')
			]);
		}else{
			$data = array();
		}

		$request->session()->put('key', $data);
/*
		UPLOAD COMPANY FILE
*/
		//Upload SIUP
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_COMPANY_LOC.$company_id."/",
			'prefix' => "siupp_",
			'oldFile' => $request->input(self::HIDE_SIUPP_FILE, "")
		);
		if ($request->hasFile(self::FUPLOADSIUPP)) {
			$fileService->upload($request->file($this::FUPLOADSIUPP), $fileProperties);
			$fuploadsiupp_name = $fileService->isUploaded() ? $fileService->getFileName() : $request->input(self::HIDE_SIUPP_FILE);
		}else{
			$fuploadsiupp_name = $request->input(self::HIDE_SIUPP_FILE);
		}

		//Upload Sertifikat Uji Mutu
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_COMPANY_LOC.$company_id."/",
			'prefix' => "serti_uji_mutu_",
			'oldFile' => $request->input(self::HIDE_SERTIFIKAT_FILE, "")
		);
		if ($request->hasFile(self::FUPLOADLAMPIRAN)) {
			$fileService->upload($request->file($this::FUPLOADLAMPIRAN), $fileProperties);
			$fuploadlampiran_name = $fileService->isUploaded() ? $fileService->getFileName() : $request->input(self::HIDE_SERTIFIKAT_FILE);
		}else{
			$fuploadlampiran_name = $request->input(self::HIDE_SERTIFIKAT_FILE);
		}

		//Upload NPWP
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_COMPANY_LOC.$company_id."/",
			'prefix' => "npwp_",
			'oldFile' => $request->input(self::HIDE_NPWP_FILE, "")
		);
		if ($request->hasFile(self::UPLOADNPWP)) {
			$fileService->upload($request->file($this::UPLOADNPWP), $fileProperties);
			$fuploadnpwp_name = $fileService->isUploaded() ? $fileService->getFileName() : $request->input(self::HIDE_NPWP_FILE);
		}else{
			$fuploadnpwp_name = $request->input(self::HIDE_NPWP_FILE);
		}
/*
		UPLOAD EXAMINATION FILE
*/

		//Upload Referensi Uji
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
			'prefix' => "ref_uji_",
			'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_REF_UJI_FILE, "") : ""
		);
		if ($request->hasFile(self::FUPLOADUJI)) {
			$fileService->upload($request->file($this::FUPLOADUJI), $fileProperties);
			$fuploadrefuji_name = $fileService->isUploaded() ? $fileService->getFileName() : ($type == self::UPDATE ? $request->input(self::HIDE_REF_UJI_FILE) : '');
		}else{
			if($type == self::SUBMIT){
				// case QA
				$res = explode('/',$request->input('path_ref'));   
				$fuploadrefuji_name = $res[count($res)-1];
				$url = str_replace(" ", "%20", $request->input('path_ref'));
				$fileService = new FileService();
				$fileProperties = array(
					'fileName' => $fuploadrefuji_name,
					'path' => self::MEDIA_EXAMINATION_LOC.$exam_id.'/'
				);
				$stream = file_get_contents($url);
				$fileService->uploadFromStream($stream, $fileProperties);
			}else{
				$fuploadrefuji_name = $request->input(self::HIDE_REF_UJI_FILE);
			}
		}

		//Upload Prinsipal
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
			'prefix' => "prinsipal_",
			'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_PRINSIPAL_FILE, "") : ""
		);
		
		if($jns_pengujian == 1 && $jns_perusahaan !=self::PABRIKAN){
			if ($request->hasFile(self::FUPLOADPRINSIPAL)) {
				$fileService->upload($request->file($this::FUPLOADPRINSIPAL), $fileProperties);
				$fuploadprinsipal_name = $fileService->isUploaded() ? $fileService->getFileName() : ($type == self::UPDATE ? $request->input(self::HIDE_PRINSIPAL_FILE) : '');
			}else{
				$fuploadprinsipal_name = $type == self::UPDATE ? $request->input(self::HIDE_PRINSIPAL_FILE) : '';
			}
		}else if($jns_pengujian == 2){
			//Upload SP3
			$fileService = new FileService();
			$fileProperties = array(
				'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
				'prefix' => "sp3_",
				'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_SP3_FILE, "") : ""
			);
			if ($request->hasFile(self::FUPLOADSP3)) {
				$fileService->upload($request->file($this::FUPLOADSP3), $fileProperties);
				$fuploadsp3_name = $fileService->isUploaded() ? $fileService->getFileName() : ($type == self::UPDATE ? $request->input(self::HIDE_SP3_FILE) : '');
			}else{
				$fuploadsp3_name = $type == self::UPDATE ? $request->input(self::HIDE_SP3_FILE) : '';
			}
		}
		
		//Upload DLL
		$fileService = new FileService();
		$fileProperties = array(
			'path' => self::MEDIA_EXAMINATION_LOC.$exam_id."/",
			'prefix' => "dll_",
			'oldFile' => ($type == self::UPDATE) ? $request->input(self::HIDE_DLL_FILE, "") : ""
		);

		if ($request->hasFile(self::FUPLOADDLL)) {
			$fileService->upload($request->file($this::FUPLOADDLL), $fileProperties);
			$fuploaddll_name = $fileService->isUploaded() ? $fileService->getFileName() : ($type == self::UPDATE ? $request->input(self::HIDE_DLL_FILE) : '');
		}else{
			$fuploaddll_name = $type == self::UPDATE ? $request->input(self::HIDE_DLL_FILE) : '';
		}
		
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

			try{
				$device->save();
			} catch(Exception $e){
				// do exception
			}

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

			try{
				$exam->save();
				$request->session()->put('exam_id', $exam_id);
			} catch(Exception $e){
				// Do Exception
			}

			if($jns_pengujian == 1){
				if($jns_perusahaan != self::PABRIKAN){
					DB::table(self::EXAMINATION_ATTACHMENTS)->insert([
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_PEMBAYARAN, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::REFERENSI_UJI, self::ATTACHMENT => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => 'Surat Dukungan Prinsipal', self::ATTACHMENT => ''.$fuploadprinsipal_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::TINJAUAN_KONTRAK, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::LAPORAN_UJI, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_LAINNYA, self::ATTACHMENT => ''.$fuploaddll_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => 'Laporan Hasil Uji Fungsi', self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).'']
					]);
				}else{
					DB::table(self::EXAMINATION_ATTACHMENTS)->insert([
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_PEMBAYARAN, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::REFERENSI_UJI, self::ATTACHMENT => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::TINJAUAN_KONTRAK, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::LAPORAN_UJI, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_LAINNYA, self::ATTACHMENT => ''.$fuploaddll_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
						['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => 'Laporan Hasil Uji Fungsi', self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).'']
					]);
				}
			}else if($jns_pengujian == 2){
				DB::table(self::EXAMINATION_ATTACHMENTS)->insert([
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_PEMBAYARAN, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::REFERENSI_UJI, self::ATTACHMENT => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => 'SP3', self::ATTACHMENT => ''.$fuploadsp3_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::TINJAUAN_KONTRAK, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_LAINNYA, self::ATTACHMENT => ''.$fuploaddll_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::LAPORAN_UJI, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).'']
				]);
			}else{
				DB::table(self::EXAMINATION_ATTACHMENTS)->insert([
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_PEMBAYARAN, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::REFERENSI_UJI, self::ATTACHMENT => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::TINJAUAN_KONTRAK, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::FILE_LAINNYA, self::ATTACHMENT => ''.$fuploaddll_name.'', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).''],
					['id' => Uuid::uuid4(), self::EXAMINATION_ID => ''.$exam_id.'', 'name' => self::LAPORAN_UJI, self::ATTACHMENT => '', 'no' => '', 'tgl' => '', self::CREATED_BY => ''.$user_id.'', self::UPDATED_BY => ''.$user_id.'', self::CREATED_AT => ''.date(self::DATE_FORMAT).'', self::UPDATED_AT => ''.date(self::DATE_FORMAT).'']
				]);
			}
			
			$plg_id = $request->input(self::F1_PLG_ID_PERUSAHAAN) ? $request->input(self::F1_PLG_ID_PERUSAHAAN) : '-' ;
			$nib = $request->input(self::F1_NIB_PERUSAHAAN) ? $request->input(self::F1_NIB_PERUSAHAAN) : '-' ;

			$query_update = "UPDATE companies
				SET 
					npwp_file = '".$fuploadnpwp_name."',
					plg_id = '".$plg_id."',
					nib = '".$nib."',
					siup_number = '".$no_siupp."',
					siup_file = '".$fuploadsiupp_name."',
					siup_date = '".date(self::DATE_FORMAT2, strtotime($tgl_siupp))."',
					qs_certificate_number = '".$sertifikat_sistem_mutu."',
					qs_certificate_file = '".$fuploadlampiran_name."',
					qs_certificate_date = '".date(self::DATE_FORMAT2, strtotime($batas_waktu_sistem)).
					self::UPDATE_BY_EQUAL.$user_id.
					self::UPDATE_AT_EQUAL.date(self::DATE_FORMAT)."'
				WHERE id = (SELECT company_id FROM users WHERE id = '".$user_id."')
			";

			DB::update($query_update);
			
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
			
			$plg_id = $request->input(self::F1_PLG_ID_PERUSAHAAN) ? $request->input(self::F1_PLG_ID_PERUSAHAAN) : '-' ;
			$nib = $request->input(self::F1_NIB_PERUSAHAAN) ? $request->input(self::F1_NIB_PERUSAHAAN) : '-' ;

			$query_update_companie = "UPDATE companies
				SET 
					npwp_file = '".$fuploadnpwp_name."',
					plg_id = '".$plg_id."',
					nib = '".$nib."',
					siup_number = '".$no_siupp."',
					siup_file = '".$fuploadsiupp_name."',
					siup_date = '".date(self::DATE_FORMAT2, strtotime($tgl_siupp))."',
					qs_certificate_number = '".$sertifikat_sistem_mutu."',
					qs_certificate_file = '".$fuploadlampiran_name."',
					qs_certificate_date = '".date(self::DATE_FORMAT2, strtotime($batas_waktu_sistem)).
					self::UPDATE_BY_EQUAL.$user_id.
					self::UPDATE_AT_EQUAL.date(self::DATE_FORMAT)."'
				WHERE id = (SELECT company_id FROM users WHERE id = '".$user_id."')
			";
			DB::update($query_update_companie);
			
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
}
