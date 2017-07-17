<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\ChangeLocale;
use Auth;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth.admin');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
		$data = array();
    	$page = "home";
		return view('client.home')
			->with('data', $data)
			->with('page', $page);
    }

    public function about()
    {
    	// $data = array();
			$query = "SELECT * FROM articles WHERE type = 'About'";
			$data = DB::select($query);
    	$page = "about";
		return view('client.about')
			->with('data', $data)
			->with('page', $page);
    }

    public function sertifikasi()
    {
    	// $data = array();
			$query = "SELECT * FROM articles WHERE type = 'Sertifikasi'";
			$data = DB::select($query);
			
			$query_slideshow = "SELECT * FROM slideshows WHERE is_active = 1 ORDER BY created_at";
			$data_slideshow = DB::select($query_slideshow);
				
			if (count($data_slideshow) == 0){
				$message_slideshow = 'Data not found';
			}
			
    	$page = "sertifikasi";
		return view('client.sertifikasi')
			->with('data', $data)
			->with('data_slideshow', $data_slideshow)
			->with('page', $page);
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
	    	$data = array();
	    	$page = "process";
			return view('client.process')
				->with('data', $data)
				->with('page', $page);   
		}else{
			return redirect("/login");
		}
    }

    public function detail_process($category)
    {
    	$currentUser = Auth::user();
		
		if($currentUser){
			$query_stels = "SELECT * FROM examination_charges ORDER BY device_name";
			$data_stels = DB::select($query_stels);

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
				AND u.id = '".$currentUser->id."'
				";
			$userData = DB::select($query);

			// print_r($userData);
			$data =  array();
	    	$page = "process";
			return view('client.process.'.$category.'_process')
				->with('data', $data)
				->with('userData', $userData[0])
				->with('jns_pengujian', $category)
				->with('data_stels', $data_stels)
				->with('page', $page);   
		}else{ 
			return redirect("/login");
		}
		
    }
	
	public function language($lang, ChangeLocale $changeLocale){		
		$lang = in_array($lang, config('app.languages')) ? $lang : config('app.fallback_locale');
		$changeLocale->lang = $lang;
		$this->dispatch($changeLocale);
		// $setLocale->
		// \App::setLocale($lang);
		// return \App::getLocale();
		// print_r($a);exit();
		
		return redirect()->back();
	}
	
	public function search(Request $request)
    {
		// print_r($request->input('globalSearch'));exit;
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
			// $stels = DB::table('stels')
				// ->select(DB::raw('id,code AS title,name AS description,1 AS jns'))
				// ->where('code', 'like', '%$search%')
			// ;
			// $stels = DB::table('stels')
				// ->select(DB::raw('id,code AS title,name AS description,1 AS jns'))
				// ->where(function($query){
					// $query->where('code', 'like', '%$search%')
						// ->orWhere('name','like','%$search%');
				// });
				// ->where('code','like','%'.$search.'%')
				// ->orWhere('name', 'like','%'.$search.'%'));
			
			// $exam_charge = DB::table('examination_charges')
				// ->select(
					// 'id',
					// 'device_name AS title',
					// 'price AS description',
					// '2 AS jns'
				// )
				// ->where('device_name','like','%'.$search.'%')
				// ->union($stels);
			
			// $exam_success = DB::table('examinations')
				// ->join('devices', 'examinations.device_id', '=', 'devices.id')
				// ->join('companies', 'examinations.company_id', '=', 'companies.id')
				// ->select(DB::raw('examinations.id,devices.name AS title,"SUCCESS" AS description,"3" AS jns'))
				// ->where('examinations.certificate_status','=','1')
				// ->where('devices.device_name','like','%'.$search.'%');
			
			// $exam = DB::table('examinations')
				// ->join('devices', 'examinations.device_id', '=', 'devices.id')
				// ->join('companies', 'examinations.company_id', '=', 'companies.id')
				// ->select(DB::raw('examinations.id,devices.`name` AS title, 
					// CASE 
						// WHEN registration_status = 1 AND spb_status = 0 THEN "Registration" 
						// WHEN spb_status = 1 AND payment_status = 0 THEN "SPB" 
						// WHEN payment_status = 1 AND spk_status = 0 THEN "Payment" 
						// WHEN spk_status = 1 AND examination_status = 0 THEN "SPK" 
						// WHEN examination_status = 1 AND resume_status = 0 THEN "Pengujian" 
						// WHEN resume_status = 1 AND qa_status = 0 THEN "Referensi Uji" 
						// WHEN qa_status = 1 AND certificate_status = 0 THEN "QA" 
						// WHEN certificate_status = 1 THEN "SUCCESS" 
					// ELSE "N/A" END AS description,"4" AS jns'))
				// ->where('device_name','like','%'.$search.'%');
				
			// $data = $stels->unionAll($exam_charge)->unionAll($exam_success)->unionAll($exam)->get();
			
			// print_r(json($stels->to_array()));exit;
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
			
			// $stels = DB::table('stels')
				// ->select(DB::raw('id,code AS title,name AS description,1 AS jns'))
				// ->where('code', 'like', '%$search%')
			// ;
			// $stels = DB::table('stels')
				// ->select(DB::raw('id,code AS title,name AS description,1 AS jns'))
				// ->where(function($query){
					// $query->where('code', 'like', '%$search%')
						// ->orWhere('name','like','%$search%');
				// });
				// ->where('code','like','%'.$search.'%')
				// ->orWhere('name', 'like','%'.$search.'%'));
			
			// $exam_charge = DB::table('examination_charges')
				// ->select(
					// 'id',
					// 'device_name AS title',
					// 'price AS description',
					// '2 AS jns'
				// )
				// ->where('device_name','like','%'.$search.'%')
				// ->union($stels);
			
			// $exam_success = DB::table('examinations')
				// ->join('devices', 'examinations.device_id', '=', 'devices.id')
				// ->join('companies', 'examinations.company_id', '=', 'companies.id')
				// ->select(DB::raw('examinations.id,devices.name AS title,"SUCCESS" AS description,"3" AS jns'))
				// ->where('examinations.certificate_status','=','1')
				// ->where('devices.device_name','like','%'.$search.'%');
			
			// $exam = DB::table('examinations')
				// ->join('devices', 'examinations.device_id', '=', 'devices.id')
				// ->join('companies', 'examinations.company_id', '=', 'companies.id')
				// ->select(DB::raw('examinations.id,devices.`name` AS title, 
					// CASE 
						// WHEN registration_status = 1 AND spb_status = 0 THEN "Registration" 
						// WHEN spb_status = 1 AND payment_status = 0 THEN "SPB" 
						// WHEN payment_status = 1 AND spk_status = 0 THEN "Payment" 
						// WHEN spk_status = 1 AND examination_status = 0 THEN "SPK" 
						// WHEN examination_status = 1 AND resume_status = 0 THEN "Pengujian" 
						// WHEN resume_status = 1 AND qa_status = 0 THEN "Referensi Uji" 
						// WHEN qa_status = 1 AND certificate_status = 0 THEN "QA" 
						// WHEN certificate_status = 1 THEN "SUCCESS" 
					// ELSE "N/A" END AS description,"4" AS jns'))
				// ->where('device_name','like','%'.$search.'%');
				
			// $data = $stels->unionAll($exam_charge)->unionAll($exam_success)->unionAll($exam)->get();
			
			// print_r(json($stels->to_array()));exit;
		}
		return view('client.search.index')
			->with('data', $data)
		;
    }
	
	public function downloadUsman()
    {
		$file = public_path().'/media/usman/User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Customer].pdf';
		$headers = array(
		  'Content-Type: application/octet-stream',
		);

		return Response::download($file, 'User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Customer].pdf', $headers);
	}
}
