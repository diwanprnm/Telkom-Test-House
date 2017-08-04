<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;
use Mail;
use Session;
use File;
use Response;

use App\Device;
use App\Examination;
use App\ExaminationAttach;
use App\ExaminationType;
use App\Testimonial;
use App\ExaminationHistory;
use App\Equipment;
use App\Questioner;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable;

class PengujianController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	 
    public function index(Request $request)
    {
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$company_id = ''.$currentUser['attributes']['company_id'].'';
        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            $jns = trim($request->input('jns'));
            $status = trim($request->input('status'));
			
			$arr_status = [
				'registration_status',
				'function_status',
				'contract_status',
				'spb_status',
				'payment_status',
				'spk_status',
				'examination_status',
				'resume_status',
				'qa_status',
				'certificate_status'
			];
			
            if ($search != null){
				if($jns > 0){
					if($status > 0){
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('devices.name','like','%'.$search.'%')
						->where('examinations.'.$arr_status[$status-1].'','=','1')
						->where('examinations.'.$arr_status[$status].'','<','1')
						->where('examinations.examination_type_id','=',''.$request->input('jns').'')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}else{
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('devices.name','like','%'.$search.'%')
						->where('examinations.examination_type_id','=',''.$request->input('jns').'')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}
				}else{
					if($status > 0){
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('devices.name','like','%'.$search.'%')
						->where('examinations.'.$arr_status[$status-1].'','=','1')
						->where('examinations.'.$arr_status[$status].'','<','1')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}else{
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('devices.name','like','%'.$search.'%')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}
				}
            }else{
                if($jns > 0){
					if($status > 0){
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('examinations.'.$arr_status[$status-1].'','=','1')
						->where('examinations.'.$arr_status[$status].'','<','1')
						->where('examinations.examination_type_id','=',''.$request->input('jns').'')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}else{
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('examinations.examination_type_id','=',''.$request->input('jns').'')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}
				}else{
					if($status > 0){
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->where('examinations.'.$arr_status[$status-1].'','=','1')
						->where('examinations.'.$arr_status[$status].'','<','1')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}else{
						$data = DB::table('examinations')
						->join('devices', 'examinations.device_id', '=', 'devices.id')
						->join('users', 'examinations.created_by', '=', 'users.id')
						->join('companies', 'users.company_id', '=', 'companies.id')
						->join('examination_types', 'examinations.examination_type_id', '=', 'examination_types.id')
						->select(
								'examinations.id',
								'examinations.examination_type_id',
								'examinations.device_id',
								'devices.name AS nama_perangkat',
								'devices.mark AS merk_perangkat',
								'devices.serial_number AS serialNumber',
								'devices.model AS model_perangkat',
								'devices.certificate AS sistem_mutuPerangkat',
								'devices.capacity AS kapasitas_perangkat',
								'examinations.registration_status',
								'examinations.function_status',
								'examinations.contract_status',
								'examinations.spb_status',
								'examinations.payment_status',
								'examinations.spk_status',
								'examinations.examination_status',
								'examinations.resume_status',
								'examinations.qa_status',
								'examinations.certificate_status',
								'examinations.urel_test_date',
								'examinations.cust_test_date',
								'examinations.deal_test_date',
								'examination_types.name AS jns_pengujian',
								'examination_types.description AS desc_pengujian',
								'users.name AS userName',
								'examinations.function_test_reason',
								'companies.name AS companiesName',
								'examinations.function_date',
								'examinations.function_test_NO',
								'examinations.created_at'
								)
						// ->where('examinations.company_id','=',''.$company_id.'')
						->where('examinations.created_by','=',''.$user_id.'')
						->orderBy('examinations.updated_at', 'desc')
						->paginate($paginate);
					}
				}
            }
				$query_exam_type = "SELECT * FROM examination_types";
				$data_exam_type = DB::select($query_exam_type);
			
            if (count($data) == 0){
                $message = 'Data not found';
            }
			
			$query_stels = "SELECT * FROM stels WHERE is_active = 1";
			$data_stels = DB::select($query_stels);
				
			if (count($data_stels) == 0){
				$message_stels = "Data Not Found";
			}
            	
            return view('client.pengujian.index')
                ->with('message', $message)
                ->with('data_exam_type', $data_exam_type)
                ->with('data', $data)
                ->with('search', $search)
                ->with('jns', $jns)
                ->with('page', "pengujian")
                ->with('status', $status)
                ->with('data_stels', $data_stels);
        }else{
			return  redirect('login');
		}
    }
	 
    public function filter(Request $request)
    {
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$company_id = ''.$currentUser['attributes']['company_id'].'';
			$pengujian = $request->input('pengujian');
			$status = $request->input('status');
		$arr_status = [
			'registration_status',
			'spb_status',
			'payment_status',
			'spk_status',
			'examination_status',
			'resume_status',
			'qa_status',
			'certificate_status'
		];
		$message = null;
		$paginate = 2;
		if($pengujian > 0){
			if($status > 0){
				$data = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->select(
						'examinations.id',
						'examinations.examination_type_id',
						'devices.name AS nama_perangkat',
						'devices.mark AS merk_perangkat',
						'devices.serial_number AS serialNumber',
						'devices.model AS model_perangkat',
						'devices.certificate AS sistem_mutuPerangkat',
						'examinations.registration_status',
						'examinations.spb_status',
						'examinations.payment_status',
						'examinations.spk_status',
						'examinations.examination_status',
						'examinations.resume_status',
						'examinations.qa_status',
						'examinations.certificate_status'
						)
				->where('examinations.company_id','=',''.$company_id.'')
				->where('examinations.'.$arr_status[$status-1].'','=','1')
				->where('examinations.'.$arr_status[$status].'','<','1')
				->where('examinations.examination_type_id','=',''.$request->input('pengujian').'')
				->paginate($paginate);
			}else{
				$data = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->select(
						'examinations.id',
						'examinations.examination_type_id',
						'devices.name AS nama_perangkat',
						'devices.mark AS merk_perangkat',
						'devices.serial_number AS serialNumber',
						'devices.model AS model_perangkat',
						'devices.certificate AS sistem_mutuPerangkat',
						'examinations.registration_status',
						'examinations.spb_status',
						'examinations.payment_status',
						'examinations.spk_status',
						'examinations.examination_status',
						'examinations.resume_status',
						'examinations.qa_status',
						'examinations.certificate_status'
						)
				->where('examinations.company_id','=',''.$company_id.'')
				->where('examinations.examination_type_id','=',''.$request->input('pengujian').'')
				->paginate($paginate);
			}
		}else{
			if($status > 0){
				$data = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->select(
						'examinations.id',
						'examinations.examination_type_id',
						'devices.name AS nama_perangkat',
						'devices.mark AS merk_perangkat',
						'devices.serial_number AS serialNumber',
						'devices.model AS model_perangkat',
						'devices.certificate AS sistem_mutuPerangkat',
						'examinations.registration_status',
						'examinations.spb_status',
						'examinations.payment_status',
						'examinations.spk_status',
						'examinations.examination_status',
						'examinations.resume_status',
						'examinations.qa_status',
						'examinations.certificate_status'
						)
				->where('examinations.company_id','=',''.$company_id.'')
				->where('examinations.'.$arr_status[$status-1].'','=','1')
				->where('examinations.'.$arr_status[$status].'','<','1')
				->paginate($paginate);
			}else{
				$data = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->select(
						'examinations.id',
						'examinations.examination_type_id',
						'devices.name AS nama_perangkat',
						'devices.mark AS merk_perangkat',
						'devices.serial_number AS serialNumber',
						'devices.model AS model_perangkat',
						'devices.certificate AS sistem_mutuPerangkat',
						'examinations.registration_status',
						'examinations.spb_status',
						'examinations.payment_status',
						'examinations.spk_status',
						'examinations.examination_status',
						'examinations.resume_status',
						'examinations.qa_status',
						'examinations.certificate_status'
						)
				->where('examinations.company_id','=',''.$company_id.'')
				->paginate($paginate);
			}
		}
		
		return response()
            ->view('client.pengujian.filter', $data, 200)
            ->header('Content-Type', 'text/html');
    }
	
	public function edit(Request $request)
    {
		// print_r($request->all());exit;
		$query = "SELECT
			e.id,
			e.device_id,
			e.examination_type_id,
			e.attachment,
			u.id AS user_id, u.`name` AS namaPemohon, u.email AS emailPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, 
			u.company_id AS company_id,
			d.`name` AS nama_perangkat,
			d.mark AS merk_perangkat,
			d.capacity AS kapasitas_perangkat,
			d.manufactured_by AS pembuat_perangkat,
			d.model AS model_perangkat,
			d.test_reference AS referensi_perangkat,
			d.serial_number AS serialNumber,
			c.`name` AS namaPerusahaan,	e.jns_perusahaan AS jnsPerusahaan, c.address AS alamatPerusahaan, c.phone_number AS telpPerusahaan, c.fax AS faxPerusahaan, c.email AS emailPerusahaan,
			c.qs_certificate_number AS noSertifikat, c.qs_certificate_file AS fileSertifikat, c.qs_certificate_date AS tglSertifikat,
			c.siup_number AS noSIUPP, c.siup_file AS fileSIUPP, c.siup_date AS tglSIUPP, c.npwp_file AS fileNPWP,
			(SELECT attachment FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'Referensi Uji') AS fileref_uji,
			(SELECT `no` FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'Referensi Uji') AS noref_uji,
			(SELECT tgl FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'Referensi Uji') AS tglref_uji,
			(SELECT attachment FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'Surat Dukungan Prinsipal') AS filesrt_prinsipal,
			(SELECT `no` FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'Surat Dukungan Prinsipal') AS nosrt_prinsipal,
			(SELECT tgl FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'Surat Dukungan Prinsipal') AS tglsrt_prinsipal,
			(SELECT attachment FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'SP3') AS filesrt_sp3,
			(SELECT `no` FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'SP3') AS nosrt_sp3,
			(SELECT tgl FROM examination_attachments WHERE examination_id = '".$request->input('id')."' AND `name` = 'SP3') AS tglsrt_sp3
		FROM
			examinations e,
			devices d,
			companies c,
			users u
		WHERE
			u.id = e.created_by
		AND u.company_id = c.id
		AND	e.device_id = d.id
		AND e.id = '".$request->input('id')."'
		";
		$data = DB::select($query);
		if(count($data) > 0){			
			echo $data[0]->nama_perangkat."|token|"; #0
			echo $data[0]->merk_perangkat."|token|"; #1
			echo $data[0]->kapasitas_perangkat."|token|"; #2
			echo $data[0]->pembuat_perangkat."|token|"; #3
			echo $data[0]->serialNumber."|token|"; #4
			echo $data[0]->model_perangkat."|token|"; #5
			echo $data[0]->referensi_perangkat."|token|"; #6
			echo $data[0]->noSertifikat."|token|"; #7
			echo $data[0]->fileSertifikat."|token|"; #8
			echo date("d-m-Y", strtotime($data[0]->tglSertifikat))."|token|"; #9
			echo $data[0]->noSIUPP."|token|"; #10
			echo $data[0]->fileSIUPP."|token|"; #11
			echo date("d-m-Y", strtotime($data[0]->tglSIUPP))."|token|"; #12
			echo $data[0]->fileNPWP."|token|"; #13
			echo $data[0]->user_id."|token|"; #14
			echo $data[0]->namaPemohon."|token|"; #15
			echo $data[0]->emailPemohon."|token|"; #16
			echo $data[0]->namaPerusahaan."|token|"; #17
			echo $data[0]->alamatPerusahaan."|token|"; #18
			echo $data[0]->telpPerusahaan."|token|"; #19
			echo $data[0]->faxPerusahaan."|token|"; #20
			echo $data[0]->emailPerusahaan."|token|"; #21
			echo $data[0]->device_id."|token|"; #22
			echo $data[0]->examination_type_id."|token|"; #23
			echo $data[0]->jnsPerusahaan."|token|"; #24
			echo $data[0]->attachment."|token|"; #25
			echo $data[0]->fileref_uji."|token|"; #26
			echo $data[0]->noref_uji."|token|"; #27
			echo $data[0]->tglref_uji."|token|"; #28
			echo $data[0]->filesrt_prinsipal."|token|"; #29
			echo $data[0]->nosrt_prinsipal."|token|"; #30
			echo $data[0]->tglsrt_prinsipal."|token|"; #31
			echo $data[0]->filesrt_sp3."|token|"; #32
			echo $data[0]->nosrt_sp3."|token|"; #33
			echo $data[0]->tglsrt_sp3."|token|"; #34
			echo $data[0]->id."|token|"; #35
			echo $data[0]->company_id."|token|"; #36
			echo $data[0]->alamatPemohon."|token|"; #37
			echo $data[0]->telpPemohon."|token|"; #38
			echo $data[0]->faxPemohon."|token|"; #39
		}else{
			echo 0; //Tidak Ada Data
		}
	}
	
	public function detail($id, Request $request)
    {
        $currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
        if ($currentUser){
            $message = null;
            $paginate = 2;
            $search = trim($request->input('search'));
            
            if ($search != null){
				$query = "SELECT e.id,
					u.`name` AS namaPemohon, u.email AS emailPemohon,
					c.`name` AS namaPerusahaan,
					c.address AS alamatPerusahaan,
					c.phone_number AS telpPerusahaan,
					c.fax AS faxPerusahaan,
					c.email AS emailPerusahaan,
					d.`name` AS nama_perangkat,
					d.mark AS merk_perangkat,
					d.capacity AS kapasitas_perangkat,
					d.manufactured_by AS pembuat_perangkat,
					d.model AS model_perangkat,
					d.test_reference AS referensi_perangkat,
					d.serial_number AS serialNumber,
					e.jns_perusahaan AS jnsPerusahaan,
					et.id AS id_jns_pengujian,
					et.name AS jns_pengujian,
					et.description AS desc_pengujian,
					e.spk_code,
					e.resume_status,
					e.certificate_status
				FROM
					examinations e,
					devices d,
					companies c,
					users u,
					examination_types et
				WHERE
					u.id = e.created_by
				AND u.company_id = c.id
				AND	e.device_id = d.id
				AND	e.examination_type_id = et.id
				AND e.id = '".$id."'
				-- AND u.id = '".$user_id."'
				";
				$data = DB::select($query)->paginate($paginate);
            }else{
                $query = "SELECT
					e.id,
					u.`name` AS namaPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, u.email AS emailPemohon,
					c.`name` AS namaPerusahaan,
					c.address AS alamatPerusahaan,
					c.phone_number AS telpPerusahaan,
					c.fax AS faxPerusahaan,
					c.email AS emailPerusahaan,
					d.`name` AS nama_perangkat,
					d.mark AS merk_perangkat,
					d.capacity AS kapasitas_perangkat,
					d.manufactured_by AS pembuat_perangkat,
					d.model AS model_perangkat,
					d.test_reference AS referensi_perangkat,
					d.serial_number AS serialNumber,
					e.jns_perusahaan AS jnsPerusahaan,
					et.id AS id_jns_pengujian,
					et.name AS jns_pengujian,
					et.description AS desc_pengujian,
					e.spk_code,
					e.resume_status,
					e.certificate_status
				FROM
					examinations e,
					devices d,
					companies c,
					users u,
					examination_types et
				WHERE
					u.id = e.created_by
				AND u.company_id = c.id
				AND	e.device_id = d.id
				AND	e.examination_type_id = et.id
				AND e.id = '".$id."'
				-- AND u.id = '".$user_id."'
				";
				$data = DB::select($query);
            }
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
			
            $query_attach = "
				SELECT examination_id AS id_attach,`name`,attachment,'examination' AS jns FROM examination_attachments WHERE examination_id = '".$id."' AND attachment != ''
					UNION
				SELECT id AS id_attach,'Sertifikat',certificate,'device' AS jns FROM devices WHERE id = (SELECT device_id FROM examinations WHERE id = '".$id."'  AND certificate_status = 1)
			";
			$data_attach = DB::select($query_attach);
			
			$exam_history = ExaminationHistory::whereNotNull('created_at')
                    ->where('examination_id', $id)
                    ->orderBy('created_at', 'DESC')
                    ->get();
					
			$examfromOTR = Examination::where('id', $id)
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->first();
					
			$client = new Client([
				'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
				// Base URI is used with relative requests
				// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
				'base_uri' => config("app.url_api_bsp"),
				// You can set any number of default request options.
				'timeout'  => 60.0,
			]);
			
			$res_exam_schedule = $client->get('spk/searchData?spkNumber='.$examfromOTR->spk_code)->getBody();
			$exam_schedule = json_decode($res_exam_schedule);
					
            return view('client.pengujian.detail')
                // ->with('message', $message)
                ->with('data', $data)
                ->with('exam_history', $exam_history)
                ->with('exam_schedule', $exam_schedule)
                ->with('page', "pengujian")
                ->with('data_attach', $data_attach);
                // ->with('search', $search);
        }
    }
	
	public function download($id, $attach, $jns)
    {
		$file = public_path().'/media/'.$jns.'/'.$id.'/'.$attach;
		$headers = array(
		  'Content-Type: application/octet-stream',
		);

		return Response::download($file, $attach, $headers);
    }
	
	public function downloadSPB($id)
    {
    	$currentUser = Auth::user();
		$query_attach = "
			SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND name = 'spb' AND attachment != ''
		";
		$data_attach = DB::select($query_attach);
		if (count($data_attach) == 0){
			$message = 'Data not found';
			$attach = NULL;
			Session::flash('error_download_spb', 'Download Failed');
			return back();
		}
		else{
			$attach = $data_attach[0]->attachment;
			$file = public_path().'/media/examination/'.$id.'/'.$attach;
			$headers = array(
			  'Content-Type: application/octet-stream',
			);
			
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $id;
			$exam_hist->date_action = date('Y-m-d H:i:s');
			$exam_hist->tahap = 'Download SPB';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date('Y-m-d H:i:s');
			$exam_hist->save();

			return Response::download($file, $attach, $headers);
		}
    }
	
	public function downloadLaporanPengujian($id)
    {
    	$currentUser = Auth::user();
		$query_attach = "
			SELECT name,attachment FROM examination_attachments WHERE examination_id = '".$id."' AND name = 'Laporan Uji' AND attachment != ''
		";
		$data_attach = DB::select($query_attach);
		if (count($data_attach) == 0){
			$message = 'Data not found';
			$attach = NULL;
			Session::flash('error_download_resume', 'Download Failed');
			return back();
		}
		else{
			$attach = $data_attach[0]->name; //name
			$file = $data_attach[0]->attachment; //link here
			$headers = array(
			  'Content-Type: application/octet-stream',
			);
			
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $id;
			$exam_hist->date_action = date('Y-m-d H:i:s');
			$exam_hist->tahap = 'Download Laporan Uji';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date('Y-m-d H:i:s');
			$exam_hist->save();

			// return Response::download($file, $attach, $headers);
			return  redirect($file);
		}
    }
	
	public function downloadSertifikat($id)
    {
    	$currentUser = Auth::user();
		$query_attach = "
			SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND name LIKE '%Sertifikat%' AND attachment != ''
		";
		$data_attach = DB::select($query_attach);
		if (count($data_attach) == 0){
			$message = 'Data not found';
			$attach = NULL;
			Session::flash('error_download_certificate', 'Download Failed');
			return back();
		}
		else{
			$attach = $data_attach[0]->name; //name
			$file = $data_attach[0]->attachment; //link here
			$headers = array(
			  'Content-Type: application/octet-stream',
			);
			
			$examhist = ExaminationHistory::where("examination_id", "=", $id)->where("tahap", "=", "Download Sertifikat");
			$count_download = count($examhist->get());
			// if($count_download > 0){
				// return($examhist->get());
			// }else{				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $id;
				$exam_hist->date_action = date('Y-m-d H:i:s');
				$exam_hist->tahap = 'Download Sertifikat';
				$exam_hist->status = 1;
				$exam_hist->keterangan = 'Download ke-'.($count_download+1);
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date('Y-m-d H:i:s');
				$exam_hist->save();
			
			return Response::download($file, $attach, $headers);
		}
    }
	
	public function pembayaran($id, Request $request)
    {
		$examination = Examination::find($id);
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
        if ($currentUser){
            $message = null;
            $paginate = 2;
            $search = trim($request->input('search'));
            
			$data = DB::table('examination_attachments')
				->where('examination_id', '=', ''.$id.'')
				->where('created_by', '=', ''.$user_id.'')
				->where('name', '=', 'File Pembayaran')
				->first();


		 	$examinationsData = DB::table('examinations')
				->where('id', '=', ''.$id.'') 
				->first();
			
            // print_r($data);exit;
            if (count($data) == 0){
                $message = 'Data not found';
				$data = NULL;
            }
			
            return view('client.pengujian.pembayaran')
                ->with('message', $message)
                ->with('spb_number', $examination->spb_number)
                ->with('spb_date', $examination->spb_date)
                ->with('cust_price_payment', $examination->cust_price_payment)
                ->with('data', $data)
                ->with('examinationsData', $examinationsData);
                // ->with('search', $search);
        }
    }
	
	public function uploadPembayaran(Request $request)
    {
		$currentUser = Auth::user();
		$user_name = ''.$currentUser['attributes']['name'].'';
		$user_email = ''.$currentUser['attributes']['email'].'';
		$path_file = public_path().'/media/examination/'.$request->input('hide_id_exam').'';
		if ($request->hasFile('filePembayaran')) {
			// $ext_file = $request->file('filePembayaran')->getClientOriginalName();
			// $name_file = uniqid().'_user_'.$request->input('hide_id_exam').'.'.$ext_file;
			$name_file = 'spb_payment_'.$request->file('filePembayaran')->getClientOriginalName();
			if($request->file('filePembayaran')->move($path_file,$name_file)){
                $fPembayaran = $name_file;
				if (File::exists(public_path().'\media\examination\\'.$request->input('hide_id_exam').'\\'.$request->input('hide_file_pembayaran'))){
					File::delete(public_path().'\media\examination\\'.$request->input('hide_id_exam').'\\'.$request->input('hide_file_pembayaran'));
				}
            }else{
                Session::flash('error', 'Upload Payment Attachment to directory failed');
                return redirect('/pengujian/'.$request->input('hide_id_exam').'/pembayaran');
            }
		}else{
			$fPembayaran = $request->input('hide_file_pembayaran');
		}
			$timestamp = strtotime($request->input('tgl-pembayaran'));
			$jumlah = $request->input('jml-pembayaran');
			
			try{
				$query_update_attach = "UPDATE examination_attachments
					SET 
						attachment = '".$fPembayaran."',
						no = '".$request->input('no-pembayaran')."',
						tgl = '".date('Y-m-d', $timestamp)."',
						updated_by = '".$currentUser['attributes']['id']."',
						updated_at = '".date('Y-m-d h:i:s')."'
					WHERE id = '".$request->input('hide_id_attach')."'
				";
				$data_update_attach = DB::update($query_update_attach);
				
				$examination = Examination::find($request->input('hide_id_exam'));
				$examination->cust_price_payment = $jumlah;
				$examination->save();
				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input('hide_id_exam');
				$exam_hist->date_action = date('Y-m-d H:i:s');
				$exam_hist->tahap = 'Upload Bukti Pembayaran';
				$exam_hist->status = 1;
				$exam_hist->keterangan = '';
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date('Y-m-d H:i:s');
				$exam_hist->save();

				
				$data= array( 
                "from"=>$currentUser->id,
                "to"=>"admin",
                "message"=>$currentUser->name." membayar SPB nomor".$examination->spb_number,
                "url"=>"examination/".$request->input('hide_id_exam').'/edit',
                "is_read"=>0,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
                );
			  	$notification = new NotificationTable();
				$notification->id = Uuid::uuid4();
		      	$notification->from = $data['from'];
		      	$notification->to = $data['to'];
		      	$notification->message = $data['message'];
		      	$notification->url = $data['url'];
		      	$notification->is_read = $data['is_read'];
		      	$notification->created_at = $data['created_at'];
		      	$notification->updated_at = $data['updated_at'];
		      	$notification->save();
		      	$data['id'] = $notification->id; 
		        event(new Notification($data));

				Session::flash('message', 'Upload successfully');
				// $this->sendProgressEmail("Pengujian atas nama ".$user_name." dengan alamat email ".$user_email.", telah melakukan proses Upload Bukti Pembayaran");
				// return back();
			} catch(Exception $e){
				Session::flash('error', 'Upload failed');
				// return back();
			}
			
		// $query_update_attach = "UPDATE examination_attachments
			// SET 
				// attachment = '".$fPembayaran."',
				// no = '".$request->input('no-pembayaran')."',
				// tgl = '".date('Y-m-d', $timestamp)."',
				// updated_by = '".$currentUser['attributes']['id']."',
				// updated_at = '".date('Y-m-d h:i:s')."'
			// WHERE id = '".$request->input('hide_id_attach')."'
		// ";
		// $data_update_attach = DB::update($query_update_attach);
		
		return back();
    }
	
	public function updateTanggalUji(Request $request)
    {
		$exam = Examination::where('id', $request->input('hide_id_exam'))
		->with('examinationLab')
		->first()
		;
		$currentUser = Auth::user();
		$cust_test_date = strtotime($request->input('cust_test_date'));
		if($request->input('hide_date_type') == 1){
			try{
				$query_update = "UPDATE examinations
					SET 
						cust_test_date = '".date('Y-m-d', $cust_test_date)."',
						updated_by = '".$currentUser['attributes']['id']."',
						updated_at = '".date('Y-m-d h:i:s')."'
					WHERE id = '".$request->input('hide_id_exam')."'
				";
				$data_update = DB::update($query_update);
				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input('hide_id_exam');
				$exam_hist->date_action = date('Y-m-d H:i:s');
				$exam_hist->tahap = 'Update Tanggal Uji';
				$exam_hist->status = 1;
				$exam_hist->keterangan = '';
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date('Y-m-d H:i:s');
				$exam_hist->save();
				
				Session::flash('message', 'Update successfully');
				$client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				// $res_exam_schedule = $client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code)->getBody();
				$res_exam_schedule = $client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code);
				// $exam_schedule = json_decode($res_exam_schedule);
				
				// $this->sendProgressEmail("Pengujian atas nama ".$user_name." dengan alamat email ".$user_email.", telah melakukan proses Upload Bukti Pembayaran");
				// return back();
			} catch(Exception $e){
				Session::flash('error', 'Update failed');
				// return back();
			}
		}else{
			try{
				$query_update = "UPDATE examinations
					SET 
						urel_test_date = '".date('Y-m-d', $cust_test_date)."',
						updated_by = '".$currentUser['attributes']['id']."',
						updated_at = '".date('Y-m-d h:i:s')."'
					WHERE id = '".$request->input('hide_id_exam')."'
				";
				$data_update = DB::update($query_update);
				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input('hide_id_exam');
				$exam_hist->date_action = date('Y-m-d H:i:s');
				$exam_hist->tahap = 'Update Tanggal Uji';
				$exam_hist->status = 1;
				$exam_hist->keterangan = '';
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date('Y-m-d H:i:s');
				$exam_hist->save();
				
				Session::flash('message', 'Update successfully');
				$client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				// $res_exam_schedule = $client->post('notification/notifRescheduleToTE?lab='.$exam->examinationLab->lab_code)->getBody();
				$res_exam_schedule = $client->post('notification/notifRescheduleToTE?id='.$exam->id);
				// $exam_schedule = json_decode($res_exam_schedule);
				
				// $this->sendProgressEmail("Pengujian atas nama ".$user_name." dengan alamat email ".$user_email.", telah melakukan proses Upload Bukti Pembayaran");
				// return back();
			} catch(Exception $e){
				Session::flash('error', 'Update failed');
				// return back();
			}
		}
		/* push notif*/
			$data= array(
	        "from"=>$currentUser->id,
	        "to"=>"admin",
	        "message"=>$currentUser->name." Mengajukan Tanggal Uji Fungsi",
	        "url"=>"examination/".$request->input('hide_id_exam'),
	        "is_read"=>0,
	        "created_at"=>date("Y-m-d H:i:s"),
	        "updated_at"=>date("Y-m-d H:i:s")
	        );

		  $notification = new NotificationTable();
$notification->id = Uuid::uuid4();
	      $notification->from = $data['from'];
	      $notification->to = $data['to'];
	      $notification->message = $data['message'];
	      $notification->url = $data['url'];
	      $notification->is_read = $data['is_read'];
	      $notification->created_at = $data['created_at'];
	      $notification->updated_at = $data['updated_at'];
	      $notification->save();
	      $data['id'] = $notification->id;
	      event(new Notification($data));
		return back();
    }
	
	public function sendProgressEmail($message)
    {
		$data = DB::table('users')
				->where('role_id', 1)
				->where('is_active', 1)
				->get();
		
		Mail::send('client.pengujian.email', array('data' => $message), function ($m) use ($data) {
            $m->to($data[0]->email)->subject("Upload Bukti Pembayaran");
        });

        return true;		
		// return redirect()->back()->with('status', '');
    }
	
	public function details($id, Request $request)
    {
		$query = "SELECT
			e.id,
			e.examination_type_id AS jnsPengujian,
			(SELECT `name` FROM examination_types WHERE id = examination_type_id) AS initPengujian,
			(SELECT description FROM examination_types WHERE id = examination_type_id) AS descPengujian,
			u.`name` AS namaPemohon, u.email AS emailPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, 
			c.`name` AS namaPerusahaan,
			c.address AS alamatPerusahaan,
			c.phone_number AS telpPerusahaan,
			c.fax AS faxPerusahaan,
			c.email AS emailPerusahaan,
			d.`name` AS nama_perangkat,
			d.mark AS merk_perangkat,
			d.capacity AS kapasitas_perangkat,
			d.manufactured_by AS pembuat_perangkat,
			d.model AS model_perangkat,
			d.test_reference AS referensi_perangkat,
			d.serial_number AS serialNumber,
			e.jns_perusahaan AS jnsPerusahaan,
			e.function_test_NO
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
		$data = DB::select($query);
            
		if (count($data) == 0){
			$message = 'Data not found';
		}
		// redirect()->route('cetak',array('param1' => 'as','param2' => 'asd'));
		// return redirect()->route('cetak', array('nick' => $data));
		// return redirect()->back()->with('data', ['some kind of data']);
		// return Redirect::route('cetak', array('nick' => $data));
		// return \Redirect::route('cetak')->with('message', 'State saved correctly!!!');
		return \Redirect::route('cetak', [
			'namaPemohon' => urlencode(urlencode($data[0]->namaPemohon)) ?: '-',
			'alamatPemohon' => urlencode(urlencode($data[0]->alamatPemohon)) ?: '-',
			'telpPemohon' => urlencode(urlencode($data[0]->telpPemohon)) ?: '-',
			'faxPemohon' => urlencode(urlencode($data[0]->faxPemohon)) ?: '-',
			'emailPemohon' => urlencode(urlencode($data[0]->emailPemohon)) ?: '-',
			'jnsPerusahaan' => urlencode(urlencode($data[0]->jnsPerusahaan)) ?: '-',
			'namaPerusahaan' => urlencode(urlencode($data[0]->namaPerusahaan)) ?: '-',
			'alamatPerusahaan' => urlencode(urlencode($data[0]->alamatPerusahaan)) ?: '-',
			'telpPerusahaan' => urlencode(urlencode($data[0]->telpPerusahaan)) ?: '-',
			'faxPerusahaan' => urlencode(urlencode($data[0]->faxPerusahaan)) ?: '-',
			'emailPerusahaan' => urlencode(urlencode($data[0]->emailPerusahaan)) ?: '-',
			'nama_perangkat' => urlencode(urlencode($data[0]->nama_perangkat)) ?: '-',
			'merk_perangkat' => urlencode(urlencode($data[0]->merk_perangkat)) ?: '-',
			'kapasitas_perangkat' => urlencode(urlencode($data[0]->kapasitas_perangkat)) ?: '-',
			'pembuat_perangkat' => urlencode(urlencode($data[0]->pembuat_perangkat)) ?: '-',
			'model_perangkat' => urlencode(urlencode($data[0]->model_perangkat)) ?: '-',
			'referensi_perangkat' => urlencode(urlencode($data[0]->referensi_perangkat)) ?: '-',
			'serialNumber' => urlencode(urlencode($data[0]->serialNumber)) ?: '-',
			'jnsPengujian' => urlencode(urlencode($data[0]->jnsPengujian)) ?: '-',
			'initPengujian' => urlencode(urlencode($data[0]->initPengujian)) ?: '-',
			'descPengujian' => urlencode(urlencode($data[0]->descPengujian)) ?: '-',
			'namaFile' => 'Pengujian '.urlencode(urlencode($data[0]->descPengujian)) ?: '-',
			'no_reg' => urlencode(urlencode($data[0]->function_test_NO)) ?: '-'
		]);
    }
	
	public function testimonial(Request $request)
    {
		$currentUser = Auth::user();
		$datenow = date('Y-m-d h:i:s');
		
		$testimonial = new Testimonial;
        $testimonial->id = Uuid::uuid4();
        $testimonial->examination_id = $request->input('exam_id');
        $testimonial->message = $request->input('message');
        $testimonial->is_active = 0;
		$testimonial->created_by = $currentUser->id;
        $testimonial->updated_by = $currentUser->id;
		
		$testimonial->created_at = $datenow;
		$testimonial->updated_at = $datenow;
		
		if($testimonial->save()){
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $request->input('exam_id');
			$exam_hist->date_action = date('Y-m-d H:i:s');
			$exam_hist->tahap = 'Download Sertifikat dan Mengisi Testimoni';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date('Y-m-d H:i:s');
			$exam_hist->save();
			
			echo 1;			
		}else{
			echo 0;
		}
	}
	
	public function cekAmbilBarang(Request $request)
    {
		$currentUser = Auth::user();
		$equip = Equipment::where("examination_id", "=", $request->input('my_exam_id'))->where("location", "=", "1");
		$is_location = count($equip->get());
		// return(count($equip->get()));
		//if count 1, masukan ke history download
		if($is_location > 0){
			$examhist = ExaminationHistory::where("examination_id", "=", $request->input('my_exam_id'))->where("tahap", "=", "Download Sertifikat");
			$count_download = count($examhist->get());
			// if($count_download > 0){
				// return($examhist->get());
			// }else{				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input('my_exam_id');
				$exam_hist->date_action = date('Y-m-d H:i:s');
				$exam_hist->tahap = 'Download Sertifikat';
				$exam_hist->status = 1;
				$exam_hist->keterangan = 'Download ke-'.($count_download+1);
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date('Y-m-d H:i:s');
				$exam_hist->save();
				
				return 1;
			// }
		}else{
			return 0;
		}
	}
	
	public function autocomplete($query) {
		$currentUser = Auth::user();
		$company_id = ''.$currentUser['attributes']['company_id'].'';
        $respons_result = Examination::autocomplet_pengujian($query,$company_id);
        return response($respons_result);
    }
	
	public function checkKuisioner(Request $request) {
		$quest = Questioner::where("examination_id", "=", $request->input('id'))->select('complaint')->get();
		$is_exists = count($quest);
		if($is_exists > 0){
			echo $quest[0]->complaint;
		}else{
			echo 0;
		}
    }
	
	public function insertKuisioner(Request $request){
		$currentUser = Auth::user();
		$tanggal = strtotime($request->input('tanggal'));
		
		$quest = new Questioner;
		$quest->id = Uuid::uuid4();
		$quest->examination_id = $request->input('exam_id');
		$quest->questioner_date = date('Y-m-d', $tanggal);
		$quest->quest1_eks = $request->input('quest1_eks');$quest->quest1_perf = $request->input('quest1_perf');
		$quest->quest2_eks = $request->input('quest2_eks');$quest->quest2_perf = $request->input('quest2_perf');
		$quest->quest3_eks = $request->input('quest3_eks');$quest->quest3_perf = $request->input('quest3_perf');
		$quest->quest4_eks = $request->input('quest4_eks');$quest->quest4_perf = $request->input('quest4_perf');
		$quest->quest5_eks = $request->input('quest5_eks');$quest->quest5_perf = $request->input('quest5_perf');
		$quest->quest6 = $request->input('quest6');
		$quest->quest7_eks = $request->input('quest7_eks');$quest->quest7_perf = $request->input('quest7_perf');
		$quest->quest8_eks = $request->input('quest8_eks');$quest->quest8_perf = $request->input('quest8_perf');
		$quest->quest9_eks = $request->input('quest9_eks');$quest->quest9_perf = $request->input('quest9_perf');
		$quest->quest10_eks = $request->input('quest10_eks');$quest->quest10_perf = $request->input('quest10_perf');
		$quest->quest11_eks = $request->input('quest11_eks');$quest->quest11_perf = $request->input('quest11_perf');
		$quest->quest12_eks = $request->input('quest12_eks');$quest->quest12_perf = $request->input('quest12_perf');
		$quest->quest13_eks = $request->input('quest13_eks');$quest->quest13_perf = $request->input('quest13_perf');
		$quest->quest14_eks = $request->input('quest14_eks');$quest->quest14_perf = $request->input('quest14_perf');
		$quest->quest15_eks = $request->input('quest15_eks');$quest->quest15_perf = $request->input('quest15_perf');
		$quest->quest16_eks = $request->input('quest16_eks');$quest->quest16_perf = $request->input('quest16_perf');
		$quest->quest17_eks = $request->input('quest17_eks');$quest->quest17_perf = $request->input('quest17_perf');
		$quest->quest18_eks = $request->input('quest18_eks');$quest->quest18_perf = $request->input('quest18_perf');
		$quest->quest19_eks = $request->input('quest19_eks');$quest->quest19_perf = $request->input('quest19_perf');
		$quest->quest20_eks = $request->input('quest20_eks');$quest->quest20_perf = $request->input('quest20_perf');
		// $quest->quest21_eks = $request->input('quest21_eks');$quest->quest21_perf = $request->input('quest21_perf');
		// $quest->quest22_eks = $request->input('quest22_eks');$quest->quest22_perf = $request->input('quest22_perf');
		// $quest->quest23_eks = $request->input('quest23_eks');$quest->quest23_perf = $request->input('quest23_perf');
		// $quest->quest24_eks = $request->input('quest24_eks');$quest->quest24_perf = $request->input('quest24_perf');
		// $quest->quest25_eks = $request->input('quest25_eks');$quest->quest25_perf = $request->input('quest25_perf');
		$quest->quest21_eks = 0;$quest->quest21_perf = 0;
		$quest->quest22_eks = 0;$quest->quest22_perf = 0;
		$quest->quest23_eks = 0;$quest->quest23_perf = 0;
		$quest->quest24_eks = 0;$quest->quest24_perf = 0;
		$quest->quest25_eks = 0;$quest->quest25_perf = 0;
		
		$quest->created_by = $currentUser->id;
		$quest->created_at = date('Y-m-d H:i:s');
		
		try{
			$quest->save();

			$data= array( 
	        "from"=>$currentUser->id,
	        "to"=>"admin",
	        "message"=>$currentUser->name." Mengisi Kuisioner",
	        "url"=>"examinationdone/".$request->input('exam_id').'/edit',
	        "is_read"=>0,
	        "created_at"=>date("Y-m-d H:i:s"),
	        "updated_at"=>date("Y-m-d H:i:s")
	        );
		  	$notification = new NotificationTable();
			$notification->id = Uuid::uuid4();
	      	$notification->from = $data['from'];
	      	$notification->to = $data['to'];
	      	$notification->message = $data['message'];
	      	$notification->url = $data['url'];
	      	$notification->is_read = $data['is_read'];
	      	$notification->created_at = $data['created_at'];
	      	$notification->updated_at = $data['updated_at'];
	      	$notification->save();
	      	$data['id'] = $notification->id; 
	        event(new Notification($data));

			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}
	
	public function insertComplaint(Request $request){
		$currentUser = Auth::user();
		$tanggal = strtotime($request->input('tanggal_complaint'));
		
		$quest = Questioner::where('examination_id','=',$request->input('my_exam_id'))->first();
		
		$quest->complaint_date = date('Y-m-d', $tanggal);
		$quest->complaint = $request->input('complaint');
		$quest->updated_by = $currentUser->id;
		$quest->updated_at = date('Y-m-d H:i:s');
		
		try{
			$quest->save();


			$data= array( 
	        "from"=>$currentUser->id,
	        "to"=>"admin",
	        "message"=>$currentUser->name." Mengajukan Complaint",
	        "url"=>"examinationdone/".$request->input('my_exam_id').'/edit',
	        "is_read"=>0,
	        "created_at"=>date("Y-m-d H:i:s"),
	        "updated_at"=>date("Y-m-d H:i:s")
	        );
		  	$notification = new NotificationTable();
			$notification->id = Uuid::uuid4();
	      	$notification->from = $data['from'];
	      	$notification->to = $data['to'];
	      	$notification->message = $data['message'];
	      	$notification->url = $data['url'];
	      	$notification->is_read = $data['is_read'];
	      	$notification->created_at = $data['created_at'];
	      	$notification->updated_at = $data['updated_at'];
	      	$notification->save();
	      	$data['id'] = $notification->id; 
	        event(new Notification($data));


			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}
}