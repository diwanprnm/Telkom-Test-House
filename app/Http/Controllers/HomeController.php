<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\ChangeLocale;
use Auth;
use Response;
use Storage;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Events\Notification;
use App\NotificationTable;
use App\Footer;
use App\AdminRole;
use App\ExaminationLab;
use App\Company;
use App\Services\NotificationService;

class HomeController extends Controller
{
	private const IS_ACTIVE = 'is_active';
	private const YOUTUBE_LINK = 'https://www.youtube.com/embed/cew5AE7Kwwk';
	private const PROCESS = 'process';
	private const DATA_LAYANAN_NOT_ACTIVE = 'data_layanan_not_active';
	private const DATA_SALES = 'data_stels';
	private const TO_LOGIN = '/login';
	private const MESSAGE = 'message';
	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
		$data = array();
		$partners = Footer::where(self::IS_ACTIVE, true)->get();
    	$page = "home";
		return view('client.home')
			->with('data', $data)
			->with('partners', $partners)
			->with('page', $page);
    }

    public function about()
    { 
		$query = "SELECT * FROM articles WHERE type = 'About'";
		$data = DB::select($query);
    	$page = "about";
		return view('client.about')
			->with('data', $data)
			->with('page', $page);
    }

    public function sertifikasi()
    { 
		$query = "SELECT * FROM articles WHERE type = 'Sertifikasi'";
		$data = DB::select($query);
		
		$query_certification = "SELECT * FROM certifications WHERE is_active = 1 AND type = 1 ORDER BY created_at";
		$data_certification = DB::select($query_certification);
		$message_slideshow = "";
		if (count($data_certification) == 0){
			$message_slideshow = 'Data not found';
		}
			
    	$page = "sertifikasi";
		return view('client.sertifikasi')
			->with('data', $data)
			->with('data_certification', $data_certification)
			->with('page', $page)
			->with(self::MESSAGE, $message_slideshow);
    }
    
    public function faq()
    {
    	$data = array();
    	$page = "faq";
		return view('client.faq')
			->with('data', $data)
			->with('page', $page);
    }

    public function contact()
    {
		$query_question = "SELECT * FROM question_categories WHERE is_active = 1 ORDER BY name";
		$data_question = DB::select($query_question);
		
    	$data = array();
    	$page = "contact";
		return view('client.contact')
			->with('data', $data)
			->with('data_question', $data_question)
			->with('page', $page);  
    }

    public function procedure()
    {
    	$data = array();
    	$page = "procedure";
		return view('client.procedure')
			->with('data', $data)
			->with('page', $page);   
    }

    public function process()
    {
    	$currentUser = Auth::user();
		
		if($currentUser){
			$date = strtotime($currentUser->company->qs_certificate_date);
			$now = strtotime('now');
			$qs_certificate_date = $date < $now ? 1 : 0;

			$query_url = "SELECT * FROM youtube WHERE id = 1";
            $data_url = DB::select($query_url);

            $qa_video_url = self::YOUTUBE_LINK;
            $ta_video_url = self::YOUTUBE_LINK;
            $vt_video_url = self::YOUTUBE_LINK;
            if (count($data_url)){
                $qa_video_url = $data_url[0]->qa_url ? $data_url[0]->qa_url : $qa_video_url;
                $ta_video_url = $data_url[0]->ta_url ? $data_url[0]->ta_url : $ta_video_url;
                $vt_video_url = $data_url[0]->vt_url ? $data_url[0]->vt_url : $vt_video_url;
            }
		// untuk QA
				$query_stels_qa = $this->getInitialQuery($currentUser);
				$data_stels_qa = DB::select($query_stels_qa);
		// untuk selain QA
			$query_stels = "SELECT code as stel, name as device_name , type as lab FROM stels WHERE is_active = 1 ORDER BY name";
				$data_stels = DB::select($query_stels);

            $query_layanan = ExaminationLab::where(self::IS_ACTIVE, 0);
            $data_layanan = $query_layanan->orderBy('lab_code')->get();

            $data_layanan_not_active = array();
			foreach ($data_layanan as $data) {
				array_push($data_layanan_not_active, $data->id);
			}

            $query_layanan_active = ExaminationLab::where(self::IS_ACTIVE, 1);
            $data_layanan_active = $query_layanan_active->get();

	    	$data = array();
	    	$page = self::PROCESS;
			return view('client.process')
				->with('qs_certificate_date', $qs_certificate_date)
				->with('qa_video_url', $qa_video_url)
				->with('ta_video_url', $ta_video_url)
				->with('vt_video_url', $vt_video_url)
				->with('data_layanan', $data_layanan)
				->with('data_layanan_active', $data_layanan_active)
				->with(self::DATA_LAYANAN_NOT_ACTIVE, $data_layanan_not_active)
				->with('data_stels_qa', $data_stels_qa)
				->with(self::DATA_SALES, $data_stels)
				->with('data', $data)
				->with('page', $page);   
		}else{
			return redirect(self::TO_LOGIN);
		}
    }

    public function detail_process($category)
    {
    	$currentUser = Auth::user();
		
		if($currentUser){
			$date = strtotime($currentUser->company->qs_certificate_date);
			$now = strtotime('now');
			if($date < $now){
				return view("errors.401_qs_certificate_date");
			}

			$query_layanan_active = ExaminationLab::where(self::IS_ACTIVE, 1);
            $data_layanan_active = $query_layanan_active->get();
            if(count($data_layanan_active) == 0){
				return view("errors.401_available_lab");
			}
			if($category == 'qa'){
				$query_stels = $this->getInitialQuery($currentUser);
				$data_stels = DB::select($query_stels);				
			}else{
				$query_stels = "SELECT code as stel, name as device_name , type as lab FROM stels WHERE is_active = 1 ORDER BY name";
				$data_stels = DB::select($query_stels);
			}
			$query_layanan = ExaminationLab::where(self::IS_ACTIVE, 0);
            $data_layanan = $query_layanan->get();

			$query = "SELECT
					u.id AS user_id, u.`name` AS namaPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, u.email AS emailPemohon, u.email2 AS emailPemohon2, u.email3 AS emailPemohon3, u.company_id AS company_id,
					c.`name` AS namaPerusahaan, c.address AS alamatPerusahaan, c.plg_id AS plg_idPerusahaan, c.nib AS nibPerusahaan, c.phone_number AS telpPerusahaan, c.fax AS faxPerusahaan, c.email AS emailPerusahaan, c.npwp_number AS npwpPerusahaan,
					c.qs_certificate_number AS noSertifikat, c.qs_certificate_file AS fileSertifikat, c.qs_certificate_date AS tglSertifikat,
					c.siup_number AS noSIUPP, c.siup_file AS fileSIUPP, c.siup_date AS tglSIUPP, c.npwp_file AS fileNPWP
				FROM
					users u,
					companies c
				WHERE
					u.company_id = c.id
				AND u.id = '".$currentUser->id."'
				";
			$userData = DB::select($query);

			$data_layanan_not_active = array();
			foreach ($data_layanan as $data) {
				array_push($data_layanan_not_active, $data->id);
			}
			
			$data =  array();
	    	$page = self::PROCESS;
			return view('client.process.v2')
				->with('data', $data)
				->with('userData', $userData[0])
				->with('jns_pengujian', $category)
				->with(self::DATA_SALES, $data_stels)
				->with(self::DATA_LAYANAN_NOT_ACTIVE, $data_layanan_not_active)
				->with('page', $page);   
		}else{ 
			return redirect(self::TO_LOGIN);
		}
		
    }

    public function edit_process($category,$id)
    {
    	$category = strtolower($category);
    	$currentUser = Auth::user();
		
		if($currentUser){
			if($category == 'qa'){
				$query_stels = $this->getInitialQuery($currentUser);
				$data_stels = DB::select($query_stels);				
			}else{
				$query_stels = "SELECT code as stel, name as device_name, type as lab FROM stels WHERE is_active = 1 ORDER BY name";
				$data_stels = DB::select($query_stels);
			}
			$query_layanan = ExaminationLab::where(self::IS_ACTIVE, 0);
            $data_layanan = $query_layanan->get();
			
			$query = "SELECT
				e.id,
				e.device_id,
				e.examination_type_id,
				e.attachment,
				e.is_loc_test,
				u.id AS user_id, u.`name` AS namaPemohon, u.email AS emailPemohon,u.email2 AS emailPemohon2,u.email3 AS emailPemohon3, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, 
				u.company_id AS company_id,
				d.`name` AS nama_perangkat,
				d.mark AS merk_perangkat,
				d.capacity AS kapasitas_perangkat,
				d.manufactured_by AS pembuat_perangkat,
				d.model AS model_perangkat,
				d.test_reference AS referensi_perangkat,
				d.serial_number AS serialNumber,
				c.`name` AS namaPerusahaan,	e.jns_perusahaan AS jnsPerusahaan, c.address AS alamatPerusahaan, c.plg_id AS plg_idPerusahaan, c.nib AS nibPerusahaan, c.phone_number AS telpPerusahaan, c.fax AS faxPerusahaan, c.email AS emailPerusahaan,
				c.qs_certificate_number AS noSertifikat, c.qs_certificate_file AS fileSertifikat, c.qs_certificate_date AS tglSertifikat,
				c.siup_number AS noSIUPP, c.siup_file AS fileSIUPP, c.siup_date AS tglSIUPP, c.npwp_file AS fileNPWP, c.npwp_number AS npwpPerusahaan,
				(SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'Referensi Uji') AS fileref_uji,
				(SELECT `no` FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'Referensi Uji') AS noref_uji,
				(SELECT tgl FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'Referensi Uji') AS tglref_uji,
				(SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'Surat Dukungan Prinsipal') AS filesrt_prinsipal,
				(SELECT `no` FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'Surat Dukungan Prinsipal') AS nosrt_prinsipal,
				(SELECT tgl FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'Surat Dukungan Prinsipal') AS tglsrt_prinsipal,
				(SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'SP3') AS filesrt_sp3,
				(SELECT `no` FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'SP3') AS nosrt_sp3,
				(SELECT tgl FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'SP3') AS tglsrt_sp3,
				(SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND `name` = 'File Lainnya') AS filedll
			FROM
				examinations e,
				devices d,
				companies c,
				users u
			WHERE
				u.id = e.created_by
			AND u.company_id = c.id
			AND	e.device_id = d.id
			AND e.id = '".$id."'
			";
			$userData = DB::select($query);
			
			$data_layanan_not_active = array();
			foreach ($data_layanan as $data) {
				array_push($data_layanan_not_active, $data->id);
			}

			$admins = AdminRole::where('registration_status',1)->get()->toArray();
			foreach ($admins as $admin) { 
				$data= array( 
					"from"=>$currentUser->id,
					"to"=>$admin['user_id'],
					self::MESSAGE=>$currentUser->company->name." Mengedit data Pengujian",
					"url"=>"examination/".$id."/edit",
					"is_read"=>0,
					"created_at"=>date("Y-m-d H:i:s"),
					"updated_at"=>date("Y-m-d H:i:s")
				);
				$notificationService = new NotificationService();
				$data['id'] = $notificationService->make($data);

				// event(new Notification($data));
		  	}
			$data =  array();

			if(!count($userData)){	
				return redirect("/process");
			}
	    	$page = self::PROCESS;
			//return view('client.process.'.$category.'_edit_process')
			return view('client.process.v2_edit')
				->with('data', $data)
				->with('userData', $userData[0])
				->with('jns_pengujian', $category)
				->with(self::DATA_SALES, $data_stels)
				->with(self::DATA_LAYANAN_NOT_ACTIVE, $data_layanan_not_active)
				->with('page', $page);   
		}else{ 
			return redirect(self::TO_LOGIN);
		}
		
    }
	
	public function language($lang, ChangeLocale $changeLocale){		
		$lang = in_array($lang, config('app.languages')) ? $lang : config('app.fallback_locale');
		$changeLocale->lang = $lang;
		$this->dispatch($changeLocale); 
		
		return redirect()->back();
	}
	
	public function search(Request $request)
    { 
		$currentUser = Auth::user();

        if ($currentUser){
			$search = '%'.$request->input('globalSearch').'%';
			$data = DB::select( DB::raw("
				SELECT id,`code` AS title,`name` AS description,'1' AS jns FROM stels WHERE `code` LIKE ? OR `name` LIKE ?
					UNION
				SELECT id,device_name AS title,price AS description,'2' AS jns FROM examination_charges WHERE device_name LIKE ?
					UNION
				SELECT examinations.id,devices.`name` AS title,'SUCCESS' AS description,'3' AS jns
				FROM examinations JOIN devices JOIN companies ON examinations.device_id = devices.id AND examinations.company_id = companies.id AND examinations.certificate_status = 1 AND devices.`name` LIKE ?
					UNION
				SELECT examinations.id,devices.`name` AS title, 
					CASE 
						WHEN registration_status = 1 AND function_status = 0 THEN 'Registration' 
						WHEN function_status = 1 AND contract_status = 0 THEN 'Uji Fungsi' 
						WHEN contract_status = 1 AND spb_status = 0 THEN 'Tinjauan Kontrak' 
						WHEN spb_status = 1 AND payment_status = 0 THEN 'SPB' 
						WHEN payment_status = 1 AND spk_status = 0 THEN 'Payment' 
						WHEN spk_status = 1 AND examination_status = 0 THEN 'SPK' 
						WHEN examination_status = 1 AND resume_status = 0 THEN 'Pengujian' 
						WHEN resume_status = 1 AND qa_status = 0 THEN 'Referensi Uji' 
						WHEN qa_status = 1 AND certificate_status = 0 THEN 'QA' 
						WHEN certificate_status = 1 THEN 'SUCCESS' 
					ELSE 'N/A' END AS description,'4'
				FROM examinations JOIN devices JOIN companies ON examinations.device_id = devices.id AND examinations.company_id = companies.id AND devices.`name` LIKE ?
			"), [$search,$search,$search,$search,$search]
			); 
		}
		else{
			$search = '%'.$request->input('globalSearch').'%';
			$data = DB::select( DB::raw("
				SELECT id,`code` AS title,`name` AS description,'1' AS jns FROM stels WHERE `code` LIKE ? OR `name` LIKE ?
					UNION
				SELECT id,device_name AS title,price AS description,'2' AS jns FROM examination_charges WHERE device_name LIKE ?
					UNION
				SELECT examinations.id,devices.`name` AS title,'SUCCESS' AS description,'3' AS jns
				FROM examinations JOIN devices JOIN companies ON examinations.device_id = devices.id AND examinations.company_id = companies.id AND examinations.certificate_status = 1 AND devices.`name` LIKE ?
			"), [$search,$search,$search,$search]
			); 
		}
		return view('client.search.index')
			->with('data', $data)
		;
    }
	
	public function downloadUsman()
    {
		$fileName = "User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Customer].pdf";
        $fileMinio = Storage::disk('minio')->get("usman/$fileName");
        return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
	}

	private function getInitialQuery($currentUser)
	{
		return "SELECT DISTINCT s.code as stel ,s.name as device_name, s.type as lab, ssd.attachment as file, ssd.id as id_folder
				FROM stels s,stels_sales ss,stels_sales_detail ssd, companies c , users u
				WHERE s.id = ssd.stels_id AND s.is_active = 1 AND ss.id = ssd.stels_sales_id AND ss.user_id = u.id AND u.company_id = c.id
				AND (ss.payment_status = 1 or ss.payment_status = 3) AND c.id = '".$currentUser->company->id."'";
	}
}
