<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;
use Mail;
use Session;
use File;
use Response;
use Storage;

use App\Device;
use App\Examination;
use App\ExaminationAttach;
use App\ExaminationType;
use App\Testimonial;
use App\ExaminationHistory;
use App\Equipment;
use App\Questioner;
use App\QuestionerQuestion;
use App\QuestionerDynamic;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable; 
use App\Services\NotificationService;
use App\Services\ExaminationService;
use App\AdminRole;

use Carbon\Carbon;
use App\Services\FileService;

class PengujianController extends Controller
{

	private const ATTRIBUTES = 'attributes';
	private const COMPANY_ID = 'company_id';
	private const SEARCH = 'search';
	private const STATUS = 'status';
	private const PAYMENT_STATUS = 'payment_status';
	private const EXAMINATIONS = 'examinations';
	private const DEVICES = 'devices';
	private const DEVICES_DOT_ID = 'devices.id';
	private const EXAMINATIONS_DEVICES_DOT_ID = 'examinations.device_id';
	private const EXAMINATIONS_CREATED_BY = 'examinations.created_by';
	private const USERS_ID = 'users.id';
	private const USERS = 'users';
	private const COMPANIES = 'companies';
	private const COMPANIES_ID = 'companies.id';
	private const USER_COMPANY_ID = 'users.company_id';
	private const EXAMINATION_TYPES = 'examination_types';
	private const EXAMINATIONS_TYPE_ID = 'examinations.examination_type_id';
	private const EXAMINATION_TYPES_ID = 'examination_types.id';
	private const EXAMINATIONS_COMPANY_ID = 'examinations.company_id';
	private const DEVICES_NAME = 'devices.name';
	private const EXAMINATIONS_DOT = 'examinations.';
	private const EXAMINATIONS_UPDATED_AT = 'examinations.updated_at';
	private const DATA_NOT_FOUND = 'Data not found';
	private const IS_ACTIVE = 'is_active';
	private const MESSAGE = 'message';
	private const PENGUJIAN = 'pengujian';
	private const USER_ID = 'user_id';
	private const CONTENT_TYPE = 'Content-Type';
	private const TOKEN = '|token|';
	private const CREATED_AT = 'created_at';
	private const EXAMINATION_ID = 'examination_id';
	private const EXAMINATION_LAB = 'examinationLab';
	private const HEADERS = 'headers';
	private const APLLICATION_HEADER_FORM = 'application/x-www-form-urlencoded';
	private const APP_URL_API_BSP = 'app.url_api_bsp';
	private const BASE_URI = 'base_uri';
	private const TIMEOUT = 'timeout';
	private const APLLICATION_HEADER_OCTET = 'Content-Type: application/octet-stream';
	private const DOWNLOAD_FAILED = 'Download Failed';
	private const MEDIA_EXAMINATION_LOC = 'examination/';
	private const DATE_FORMAT1 = 'Y-m-d H:i:s';
	private const HIDE_ID_EXAM = 'hide_id_exam';
	private const FILE_PEMBAYARAN = 'filePembayaran';
	private const HIDE_FILE_PEMBAYARAN = 'hide_file_pembayaran';
	private const ERROR = 'error';
	private const DATE_FORMAT2 = 'Y-m-d';
	private const EXAMINATION_LOC = 'examination/';
	private const EDIT_LOC = '/edit';
	private const IS_READ = 'is_read';
	private const UPDATED_AT = 'updated_at';
	private const HIDE_DATE_TYPE = 'hide_date_type';
	private const UPDATE_FAILED = 'Update failed';
	private const HIDE_ID_EXAM2 = 'hide_id_exam2';
	private const ADMIN = 'admin';
	private const HIDE_ID_EXAM3 = 'hide_id_exam3';
	private const EXAM_ID = 'exam_id';
	private const MY_EXAM_ID = 'my_exam_id';
	private const LOGIN = 'login';
	private const MINIO = 'minio';
	private const DEVICE = 'device';
	private const PAYMENT_METHOD = 'payment_method';
	private const EMAIL = 'email';
	private const PAGE_PEMBAYARAN = '/pembayaran';
	private const REFERENCE_ID = 'reference_id';
	private const QUERY_UPDATED_AT = "',updated_at = '";
	private const APPLICATION_JSON = "application/json";
	private const TPN_2 = "app.gateway_tpn_2";
	private const URI_API_TPN = "app.url_api_tpn";
	private const HTTP_ERRORS = "http_errors";



	private const DEVICES_NAME_AUTOSUGGEST = 'devices.name as autosuggest';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	 
    public function index(Request $request)
    {
		$currentUser = Auth::user();
		$user_id = ''.$currentUser[self::ATTRIBUTES]['id'].'';
		$company_id = ''.$currentUser[self::ATTRIBUTES][self::COMPANY_ID].'';
        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input(self::SEARCH));
            $jns = trim($request->input('jns'));
            $status = trim($request->input(self::STATUS));
			
			$arr_status = [
				'registration_status',
				'function_status',
				'contract_status',
				'spb_status',
				self::PAYMENT_STATUS,
				'spk_status',
				'examination_status',
				'resume_status',
				'qa_status',
				'certificate_status'
			];

			$neededColumn = 'examinations.id,
				examinations.examination_type_id,
				examinations.device_id,
				devices.name AS nama_perangkat,
				devices.mark AS merk_perangkat,
				devices.serial_number AS serialNumber,
				devices.model AS model_perangkat,
				devices.certificate AS sistem_mutuPerangkat,
				devices.capacity AS kapasitas_perangkat,
				devices.cert_number,
				examinations.registration_status,
				examinations.function_status,
				examinations.contract_status,
				examinations.spb_status,
				examinations.payment_status,
				examinations.spk_status,
				examinations.examination_status,
				examinations.resume_status,
				examinations.qa_status,
				examinations.qa_passed,
				examinations.certificate_status,
				examinations.urel_test_date,
				examinations.cust_test_date,
				examinations.deal_test_date,
				examination_types.name AS jns_pengujian,
				examination_types.description AS desc_pengujian,
				users.name AS userName,
				examinations.function_test_reason,
				companies.name AS companiesName,
				examinations.function_date,
				examinations.function_test_PIC,
				examinations.function_test_NO,
				examinations.function_test_date_approval,
				examinations.resume_date,
				examinations.created_at,
				examinations.payment_method,
				examinations.VA_expired,
				(SELECT name FROM examination_labs WHERE examination_labs.id=examinations.examination_lab_id) AS labs_name';
			
				$query = DB::table(self::EXAMINATIONS)
							->join(self::DEVICES, self::EXAMINATIONS_DEVICES_DOT_ID, '=', self::DEVICES_DOT_ID)
							->join(self::USERS, self::EXAMINATIONS_CREATED_BY, '=', self::USERS_ID)
							->join(self::COMPANIES, self::USER_COMPANY_ID, '=', self::COMPANIES_ID)
							->join(self::EXAMINATION_TYPES, self::EXAMINATIONS_TYPE_ID, '=', self::EXAMINATION_TYPES_ID)
							->select(DB::raw( $neededColumn ))
							->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'');

				if ($search != null){
					if($jns > 0){
						if($status > 0){
							$query->where(self::DEVICES_NAME,'like','%'.$search.'%')
							->where(self::EXAMINATIONS_DOT.$arr_status[$status-1].'','=','1')
							->where(self::EXAMINATIONS_DOT.$arr_status[$status].'','<','1')
							->where(self::EXAMINATIONS_TYPE_ID,'=',''.$request->input('jns').'');
						}else{
							$query->where(self::DEVICES_NAME,'like','%'.$search.'%')
							->where(self::EXAMINATIONS_TYPE_ID,'=',''.$request->input('jns').'');
						}
					}else{
						if($status > 0){
							$query->where(self::DEVICES_NAME,'like','%'.$search.'%')
							->where(self::EXAMINATIONS_DOT.$arr_status[$status-1].'','=','1')
							->where(self::EXAMINATIONS_DOT.$arr_status[$status].'','<','1');
						}else{
							$query->where(self::DEVICES_NAME,'like','%'.$search.'%');
						}
					}
				}else{
					if($jns > 0){
						if($status > 0){
							$query->where(self::EXAMINATIONS_DOT.$arr_status[$status-1].'','=','1')
							->where(self::EXAMINATIONS_DOT.$arr_status[$status].'','<','1')
							->where(self::EXAMINATIONS_TYPE_ID,'=',''.$request->input('jns').'');
						}else{
							$query->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'')
							->where(self::EXAMINATIONS_TYPE_ID,'=',''.$request->input('jns').'');
						}
					}else{
						if($status > 0){
							$query->where(self::EXAMINATIONS_DOT.$arr_status[$status-1].'','=','1')
							->where(self::EXAMINATIONS_DOT.$arr_status[$status].'','<','1');
						}
					}
				}
				$query_exam_type = "SELECT * FROM examination_types";
				$data_exam_type = DB::select($query_exam_type);
			
			$data = $query->orderBy(self::EXAMINATIONS_UPDATED_AT, 'desc')->paginate($paginate);

            if (count($data) == 0){
                $message = self::DATA_NOT_FOUND;
            }
			
			$query_stels = "SELECT * FROM stels WHERE is_active = 1";
			$data_stels = DB::select($query_stels);
			 
			$data_kuisioner = QuestionerQuestion::where(self::IS_ACTIVE,1)->orderBy('order_question')->get();
            	
            return view('client.pengujian.index')
                ->with(self::MESSAGE, $message)
                ->with('data_exam_type', $data_exam_type)
                ->with('data', $data)
                ->with(self::SEARCH, $search)
                ->with('jns', $jns)
                ->with('page', self::PENGUJIAN)
                ->with(self::STATUS, $status)
                ->with('data_stels', $data_stels)
				->with('data_kuisioner', $data_kuisioner)
				->with(self::USER_ID, $user_id);
        }else{
			return  redirect(self::LOGIN);
		}
    }
	 
    public function filter(Request $request)
    {
		$currentUser = Auth::user();
		 
		$company_id = ''.$currentUser[self::ATTRIBUTES][self::COMPANY_ID].'';
		$pengujian = $request->input(self::PENGUJIAN);
		$status = $request->input(self::STATUS);
		$arr_status = [
			'registration_status',
			'spb_status',
			self::PAYMENT_STATUS,
			'spk_status',
			'examination_status',
			'resume_status',
			'qa_status',
			'certificate_status'
		];
		$neededColumn = array(
			'examinations.id',
			self::EXAMINATIONS_TYPE_ID,
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
		);
		$paginate = 2;
		if( $pengujian > 0){
			if($status > 0){
				$data = DB::table(self::EXAMINATIONS)
				->join(self::DEVICES, self::EXAMINATIONS_DEVICES_DOT_ID, '=', self::DEVICES_DOT_ID)
				->select( $neededColumn )
				->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'')
				->where(self::EXAMINATIONS_DOT.$arr_status[$status-1].'','=','1')
				->where(self::EXAMINATIONS_DOT.$arr_status[$status].'','<','1')
				->where(self::EXAMINATIONS_TYPE_ID,'=',''.$request->input(self::PENGUJIAN).'')
				->paginate($paginate);
			}else{
				$data = DB::table(self::EXAMINATIONS)
				->join(self::DEVICES, self::EXAMINATIONS_DEVICES_DOT_ID, '=', self::DEVICES_DOT_ID)
				->select( $neededColumn )
				->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'')
				->where(self::EXAMINATIONS_TYPE_ID,'=',''.$request->input(self::PENGUJIAN).'')
				->paginate($paginate);
			}
		}else{
			if($status > 0){
				$data = DB::table(self::EXAMINATIONS)
				->join(self::DEVICES, self::EXAMINATIONS_DEVICES_DOT_ID, '=', self::DEVICES_DOT_ID)
				->select( $neededColumn )
				->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'')
				->where(self::EXAMINATIONS_DOT.$arr_status[$status-1].'','=','1')
				->where(self::EXAMINATIONS_DOT.$arr_status[$status].'','<','1')
				->paginate($paginate);
			}else{
				$data = DB::table(self::EXAMINATIONS)
				->join(self::DEVICES, self::EXAMINATIONS_DEVICES_DOT_ID, '=', self::DEVICES_DOT_ID)
				->select( $neededColumn )
				->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'')
				->paginate($paginate);
			}
		}
		
		return response()
			->view('client.pengujian.filter', $data, 200) 
            ->header(self::CONTENT_TYPE, 'text/html');
    }
	
	public function edit(Request $request)
    {
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
			c.`name` AS namaPerusahaan,	e.jns_perusahaan AS jnsPerusahaan, c.address AS alamatPerusahaan, 
			c.plg_id AS plg_idPerusahaan, c.nib AS nibPerusahaan, c.phone_number AS telpPerusahaan, c.fax AS faxPerusahaan, c.email AS emailPerusahaan,
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
		if(count($data)){			
			echo $data[0]->nama_perangkat.self::TOKEN; #0
			echo $data[0]->merk_perangkat.self::TOKEN; #1
			echo $data[0]->kapasitas_perangkat.self::TOKEN; #2
			echo $data[0]->pembuat_perangkat.self::TOKEN; #3
			echo $data[0]->serialNumber.self::TOKEN; #4
			echo $data[0]->model_perangkat.self::TOKEN; #5
			echo $data[0]->referensi_perangkat.self::TOKEN; #6
			echo $data[0]->noSertifikat.self::TOKEN; #7
			echo $data[0]->fileSertifikat.self::TOKEN; #8
			echo date("d-m-Y", strtotime($data[0]->tglSertifikat)).self::TOKEN; #9
			echo $data[0]->noSIUPP.self::TOKEN; #10
			echo $data[0]->fileSIUPP.self::TOKEN; #11
			echo date("d-m-Y", strtotime($data[0]->tglSIUPP)).self::TOKEN; #12
			echo $data[0]->fileNPWP.self::TOKEN; #13
			echo $data[0]->user_id.self::TOKEN; #14
			echo $data[0]->namaPemohon.self::TOKEN; #15
			echo $data[0]->emailPemohon.self::TOKEN; #16
			echo $data[0]->namaPerusahaan.self::TOKEN; #17
			echo $data[0]->alamatPerusahaan.self::TOKEN; #18
			echo $data[0]->telpPerusahaan.self::TOKEN; #19
			echo $data[0]->faxPerusahaan.self::TOKEN; #20
			echo $data[0]->emailPerusahaan.self::TOKEN; #21
			echo $data[0]->device_id.self::TOKEN; #22
			echo $data[0]->examination_type_id.self::TOKEN; #23
			echo $data[0]->jnsPerusahaan.self::TOKEN; #24
			echo $data[0]->attachment.self::TOKEN; #25
			echo $data[0]->fileref_uji.self::TOKEN; #26
			echo $data[0]->noref_uji.self::TOKEN; #27
			echo $data[0]->tglref_uji.self::TOKEN; #28
			echo $data[0]->filesrt_prinsipal.self::TOKEN; #29
			echo $data[0]->nosrt_prinsipal.self::TOKEN; #30
			echo $data[0]->tglsrt_prinsipal.self::TOKEN; #31
			echo $data[0]->filesrt_sp3.self::TOKEN; #32
			echo $data[0]->nosrt_sp3.self::TOKEN; #33
			echo $data[0]->tglsrt_sp3.self::TOKEN; #34
			echo $data[0]->id.self::TOKEN; #35
			echo $data[0]->company_id.self::TOKEN; #36
			echo $data[0]->alamatPemohon.self::TOKEN; #37
			echo $data[0]->telpPemohon.self::TOKEN; #38
			echo $data[0]->faxPemohon.self::TOKEN; #39
			echo $data[0]->plg_idPerusahaan.self::TOKEN; #40
			echo $data[0]->nibPerusahaan.self::TOKEN; #41
		}else{
			echo 0; //Tidak Ada Data
		}
	}
	
	public function detail($id, Request $request)
    {
        $currentUser = Auth::user();
		$user_id = ''.$currentUser[self::ATTRIBUTES]['id'].'';
		$message = '';
        if ($currentUser){
            $paginate = 2;
            $search = trim($request->input(self::SEARCH));
            
            if ($search != null){
				$query = "SELECT e.id,
					u.`name` AS namaPemohon, u.email AS emailPemohon,
					c.`name` AS namaPerusahaan,
					c.address AS alamatPerusahaan,
					c.plg_id AS plg_idPerusahaan, 
					c.nib AS nibPerusahaan,
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
					e.examination_type_id,
					e.spk_code,
					e.function_test_PIC,
					e.resume_date,
					e.registration_status,
					e.function_status,
					e.contract_status,
					e.spb_status,
					e.payment_status,
					e.spk_status,
					e.examination_status,
					e.resume_status,
					e.qa_status,
					e.qa_passed,
					e.certificate_status,
					(SELECT name FROM examination_labs WHERE examination_labs.id=e.examination_lab_id) AS labs_name
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
				-- AND u.id = '".$user_id."'";
				$data = DB::select($query)->paginate($paginate);
            }else{
                $query = "SELECT
					e.id,
					u.`name` AS namaPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, u.email AS emailPemohon,
					c.`name` AS namaPerusahaan,
					c.address AS alamatPerusahaan,
					c.plg_id AS plg_idPerusahaan, 
					c.nib AS nibPerusahaan,
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
					e.examination_type_id,
					e.spk_code,
					e.function_test_PIC,
					e.resume_date,
					e.resume_date,
					e.registration_status,
					e.function_status,
					e.contract_status,
					e.spb_status,
					e.payment_status,
					e.spk_status,
					e.examination_status,
					e.resume_status,
					e.qa_status,
					e.qa_passed,
					e.certificate_status,
					(SELECT name FROM examination_labs WHERE examination_labs.id=e.examination_lab_id) AS labs_name
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
				-- AND u.id = '".$user_id."'";
				$data = DB::select($query);
            }
            
            if (count($data) == 0){
                $message = self::DATA_NOT_FOUND;
            }
			
            $query_attach = "
				SELECT examination_id AS id_attach,`name`,attachment,'examination' AS jns, created_at FROM examination_attachments WHERE examination_id = '".$id."' AND attachment != ''
					UNION
				SELECT id AS id_attach,'Sertifikat',certificate,'device' AS jns, created_at FROM devices WHERE id = (SELECT device_id FROM examinations WHERE id = '".$id."'  AND certificate_status = 1)
				ORDER BY created_at DESC
			";
			$data_attach = DB::select($query_attach);
			
			$exam_history = ExaminationHistory::whereNotNull(self::CREATED_AT)
					->with('user')
                    ->where(self::EXAMINATION_ID, $id)
                    ->orderBy(self::CREATED_AT, 'DESC')
                    ->get();
					
			$examfromOTR = Examination::where('id', $id)
                            ->with('examinationType')
                            ->with(self::EXAMINATION_LAB)
                            ->first();
					
			$client = new Client([
				self::HEADERS => [self::CONTENT_TYPE => self::APLLICATION_HEADER_FORM],
				// Base URI is used with relative requests
				// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
				self::BASE_URI => config(self::APP_URL_API_BSP),
				// You can set any number of default request options.
				self::TIMEOUT  => 60.0,
			]);
			
			$res_exam_schedule = $client->get('spk/searchData?spkNumber='.$examfromOTR->spk_code)->getBody();
			$exam_schedule = json_decode($res_exam_schedule);
			
			$data_kuisioner = QuestionerQuestion::where(self::IS_ACTIVE,1)->orderBy('order_question')->get();
			
            return view('client.pengujian.detail')
                // ->with(self::MESSAGE, $message)
                ->with('data', $data)
                ->with('exam_history', $exam_history)
                ->with('exam_schedule', $exam_schedule)
                ->with('page', self::PENGUJIAN)
                ->with('data_attach', $data_attach)
                ->with('data_kuisioner', $data_kuisioner)
                ->with(self::MESSAGE, $message);
		}else{
			return  redirect(self::LOGIN);
		}
    }
	
	public function download($id, $attach, $jns)
    {
		/* 
		 * DOWNLOAD FILE DARI MINIO
		 * TODO-Chris
		 * Ket: Download dari minio ke TEMP lalu download ke user via response
		 * Tgs: Belum ditest
		 */
		$file = Storage::disk(self::MINIO)->url($jns.'/'.$id.'/'.$attach);
                     
		$filename = $attach;
		$tempFile = tempnam(sys_get_temp_dir(), $filename);
		copy($file, $tempFile);
		$response =  response()->download($tempFile, $filename);
    }
	
	public function downloadSPB($id)
    {
		$currentUser = Auth::user();
		$message = '';
		$query_attach = "
			SELECT attachment FROM examination_attachments WHERE examination_id = '".$id."' AND name = 'spb' AND attachment != ''
		";
		$data_attach = DB::select($query_attach);
		if (!count($data_attach)){
			$message = self::DATA_NOT_FOUND;
			$attach = NULL;
			Session::flash('error_download_spb', self::DOWNLOAD_FAILED);
			return back()->with(self::MESSAGE, $message);
		}
		else{
			$attach = $data_attach[0]->attachment; 
			$fileName = $attach;
			$fileMinio = Storage::disk(self::MINIO)->get(self::MEDIA_EXAMINATION_LOC.$id.'/'.$attach);
				

			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $id;
			$exam_hist->date_action = date(self::DATE_FORMAT1);
			$exam_hist->tahap = 'Download SPB';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date(self::DATE_FORMAT1);
			$exam_hist->save();

			return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
		}
    }
	
	public function downloadLaporanPengujian($id)
    {
    	$currentUser = Auth::user();
		$query_attach = "
			SELECT name, attachment FROM examination_attachments WHERE examination_id = '".$id."' AND (name = 'Laporan Uji' OR name = 'Revisi Laporan Uji') AND attachment != '' ORDER BY created_at DESC
		";
		$data_attach = DB::select($query_attach);
		 
		$attach = NULL;
		if (count($data_attach) == 0){
			$message = self::DATA_NOT_FOUND;
			Session::flash('error_download_resume', self::DOWNLOAD_FAILED);
			return back()->with(self::MESSAGE, $message);
		}
		else{
			$rev_uji = 0;
			foreach ($data_attach as $item) {
				if($item->name == 'Laporan Uji' && $rev_uji == 0){
					 
					$attach = $item->attachment;
				}
				if($item->name == 'Revisi Laporan Uji' && $rev_uji == 0){
					$rev_uji = 1; 
					$attach = $item->attachment;
				}
			}
			  
			
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $id;
			$exam_hist->date_action = date(self::DATE_FORMAT1);
			$exam_hist->tahap = 'Download Laporan Uji';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date(self::DATE_FORMAT1);
			$exam_hist->save();

			 
			$fileName = $attach;
			$fileMinio = Storage::disk(self::MINIO)->get(self::MEDIA_EXAMINATION_LOC.$id.'/'.$attach);
			return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
			 
		}
    }
	
	public function downloadSertifikat($id)
    {
    	$currentUser = Auth::user();
		$examination = Examination::where('id', $id)->with(self::DEVICE)->get();
		$data_attach = $examination[0]->device;
		if (count((array)$data_attach) == 0){
			$message = self::DATA_NOT_FOUND;
			$attach = NULL;
			Session::flash('error_download_certificate', self::DOWNLOAD_FAILED);
			return back()->with(self::MESSAGE, $message);
		}
		else{
			
			$examhist = ExaminationHistory::where(self::EXAMINATION_ID, "=", $id)->where("tahap", "=", "Download Sertifikat");
			$count_download = count($examhist->get());		
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $id;
			$exam_hist->date_action = date(self::DATE_FORMAT1);
			$exam_hist->tahap = 'Download Sertifikat';
			$exam_hist->status = 1;
			$exam_hist->keterangan = 'Download ke-'.($count_download+1);
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date(self::DATE_FORMAT1);
			$exam_hist->save();
			
			$jns = 'device/';
			$id = $data_attach->id;
			$attach = $data_attach->certificate;  


			$fileName = $attach;
			$fileMinio = Storage::disk(self::MINIO)->get($jns.$id.'/'.$attach);
			return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName)); 
		}
    }
	
	public function pembayaran($id, Request $request)
    {
		$examinationService = new ExaminationService();
		$currentUser = Auth::user();
		$user_id = ''.$currentUser[self::ATTRIBUTES]['id'].'';
		$company_id = ''.$currentUser[self::ATTRIBUTES][self::COMPANY_ID].'';
        if ($currentUser){
			$examination = Examination::find($id);
			if($examination->payment_method != 0){
				return redirect('payment_confirmation_spb/'.$examination->id);
			}
            $message = null;
            $paginate = 2;
            $search = trim($request->input(self::SEARCH,''));
            
			$data = DB::table('examination_attachments')
				->select('examination_attachments.*')
				->join(self::EXAMINATIONS, 'examination_attachments.examination_id', '=', 'examinations.id')
				->where(self::EXAMINATION_ID, '=', ''.$id.'')
				->where(self::EXAMINATIONS_COMPANY_ID, '=', ''.$company_id.'')
				->where('name', '=', 'File Pembayaran')
				->first();

				$examinationsData = Examination::where('id', $id)->with(self::DEVICE)->get();
			
            if (!count((array)$data)){
                $message = self::DATA_NOT_FOUND;
				$data = NULL;
            }
			
            return view('client.pengujian.pembayaran')
                ->with(self::MESSAGE, $message)
                ->with('spb_number', $examination->spb_number)
                ->with('spb_date', $examination->spb_date)
                ->with('price', $examination->price)
                ->with('data', $data)
				->with('examinationsData', $examinationsData)
				->with(self::PAYMENT_METHOD, $examinationService->api_get_payment_methods())
                ->with(self::USER_ID, $user_id)
                ->with('paginate', $paginate)
                ->with(self::SEARCH, $search);
        }else{
			return redirect(self::LOGIN);
		}
    }
	
	public function uploadPembayaran(Request $request)
    {
		$currentUser = Auth::user();
		$user_name = ''.$currentUser[self::ATTRIBUTES]['name'].'';
		$user_email = ''.$currentUser[self::ATTRIBUTES][self::EMAIL].'';
		$path_file = self::MEDIA_EXAMINATION_LOC.$request->input(self::HIDE_ID_EXAM).'';
		if ($request->hasFile(self::FILE_PEMBAYARAN)) {
			$name_file = 'spb_payment_'.$request->file(self::FILE_PEMBAYARAN)->getClientOriginalName();
			/*
			 * UPLOAD PEMBAYARAN KE MINIO
			 * TODO-Chris
			 * Ket: Upload pembayran ke minio
			 * Tgs: Belum ditest				 
			 */
			$fileService = new FileService();
			$fileUploaded = $fileService->uploadFile($request->file(self::FILE_PEMBAYARAN), $path_file, $name_file);

			if($fileUploaded){
                $fPembayaran = $fileUploaded;
				$fileService->deleteFile('examination\\'.$request->input(self::HIDE_ID_EXAM).'\\'.$request->input(self::HIDE_FILE_PEMBAYARAN));
            }else{
                Session::flash(self::ERROR, 'Upload Payment Attachment to directory failed');
                return redirect('/pengujian/'.$request->input(self::HIDE_ID_EXAM).self::PAGE_PEMBAYARAN);
            }
		}else{
			$fPembayaran = $request->input(self::HIDE_FILE_PEMBAYARAN);
		}
			$timestamp = strtotime($request->input('tgl-pembayaran'));
			$jumlah = str_replace(".",'',$request->input('jml-pembayaran'));
        	$jumlah = str_replace("Rp",'',$jumlah);
			
			try{
				$query_update_attach = "UPDATE examination_attachments
					SET 
						attachment = '".$fPembayaran."',
						no = '".$request->input('no-pembayaran')."',
						tgl = '".date(self::DATE_FORMAT2, $timestamp)."',
						updated_by = '".$currentUser[self::ATTRIBUTES]['id'].self::QUERY_UPDATED_AT.date(self::DATE_FORMAT1)."'
					WHERE id = '".$request->input('hide_id_attach')."'";
				DB::update($query_update_attach);
				
				$examination = Examination::find($request->input(self::HIDE_ID_EXAM));
				$examination->cust_price_payment = $jumlah;
				$examination->save();
				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input(self::HIDE_ID_EXAM);
				$exam_hist->date_action = date(self::DATE_FORMAT1);
				$exam_hist->tahap = 'Upload Bukti Pembayaran';
				$exam_hist->status = 1;
				$exam_hist->keterangan = '';
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date(self::DATE_FORMAT1);
				$exam_hist->save();

				$admins = AdminRole::where(self::PAYMENT_STATUS,1)->get()->toArray();
				foreach ($admins as $admin) {  
					$data= array( 
		                "from"=>$currentUser->id,
		                "to"=>$admin[self::USER_ID],
		                self::MESSAGE=>$currentUser->company->name." membayar SPB nomor".$examination->spb_number,
		                "url"=>self::EXAMINATION_LOC.$request->input(self::HIDE_ID_EXAM).self::EDIT_LOC
	                );
 					
					$notificationService = new NotificationService();
	                $notification_id = $notificationService->make($data);
					$data['id'] = $notification_id;
					// event(new Notification($data));
		    	}
				Session::flash(self::MESSAGE, 'Upload successfully');
			} catch(Exception $e){
				Session::flash(self::ERROR, 'Upload failed');
			}
		
		return back()
			->with('user_name', $user_name)
			->with('user_email', $user_email);
	}
	
	public function doCheckout(Request $request){
    	$currentUser = Auth::user();
        $exam = Examination::where('id', $request->input('hide_id_exam'))
					->with('user')
					->with('company')
					->with(self::DEVICE)
					->with('examinationType')
					->first()
		;
        if($currentUser){ 
			$mps_info = explode('||', $request->input(self::PAYMENT_METHOD));
			$exam->include_pph = $request->has('is_pph') ? 1 : 0;
			$exam->payment_method = $mps_info[2] == "atm" ? 1 : 2;

            if($exam){
                $data = [
                    "draft_id" => $exam->PO_ID,
                    "include_pph" => $request->has('is_pph'),
                    "created" => [
                        "by" => $currentUser->name,
                        self::REFERENCE_ID => $currentUser->id
                    ],
                    "config" => [
                        "kode_wapu" => "01",
                        "afiliasi" => "non-telkom",
                        "tax_invoice_text" => $exam->device->name.', '.$exam->device->mark.', '.$exam->device->capacity.', '.$exam->device->model,
                        self::PAYMENT_METHOD => $mps_info[2] == "atm" ? "internal" : "mps",
                    ],
                    "mps" => [
                        "gateway" => $mps_info[0],
                        "product_code" => $mps_info[1],
                        "product_type" => $mps_info[2],
                        "manual_expired" => 20160
                    ]
                ];

				$billing = $this->api_billing($data);
				
                $exam->BILLING_ID = $billing && $billing->status? $billing->data->_id : null;
				if($mps_info[2] != "atm"){
                	$exam->VA_name = $mps_info ? $mps_info[3] : null;
                    $exam->VA_image_url = $mps_info ? $mps_info[4] : null;
					$exam->VA_number = $billing && $billing->status? $billing->data->mps->va->number : null;
					$exam->VA_amount = $billing && $billing->status? $billing->data->mps->va->amount : null;
                    $exam->VA_expired = $billing && $billing->status? $billing->data->mps->va->expired : null;
				}
				if(!$exam->VA_number){
                    Session::flash(self::ERROR, 'Failed to generate '.$mps_info[3].', please choose another bank!');
                    $exam->PO_ID = $this->regeneratePO($exam);
                    $exam->BILLING_ID = null;
					$exam->include_pph = 0;
					$exam->payment_method = 0;
					$exam->VA_name = null;
					$exam->VA_image_url = null;
					$exam->VA_number = null;
					$exam->VA_amount = null;
					$exam->VA_expired = null;
                    $exam->save();
                    return back();
                }
            }

            try{
                $exam->save();
                return redirect('payment_confirmation_spb/'.$exam->id);
            } catch(\Illuminate\Database\QueryException $e){
                dd($e);
                Session::flash(self::ERROR, 'Failed To Checkout');
                return back();
            }
        }else{
           return back();
        } 
        
	}
	
	public function regeneratePO($exam){
		$details [] = 
            [
                "item" => 'Biaya Uji '.$exam->examinationType->name.' ('.$exam->examinationType->description.')',
                "description" => $exam->device->name.', '.$exam->device->mark.', '.$exam->device->capacity.', '.$exam->device->model,
                "quantity" => 1,
                "price" => $exam->price/1.1,
                "total" => $exam->price/1.1
            ]
        ;

		$data_draft = [
            "from" => [
                "name" => "PT TELEKOMUNIKASI INDONESIA, TBK.",
                "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
                "phone" => "(+62) 812-2483-7500",
                self::EMAIL => "urelddstelkom@gmail.com",
                "npwp" => "01.000.013.1-093.000"
            ],
            "to" => [
                "name" => $exam->company->name ? $exam->company->name : "-",
                "address" => $exam->company->address ? $exam->company->address : "-",
                "phone" => $exam->company->phone_number ? $exam->company->phone_number : "-",
                self::EMAIL => $exam->user->email ? $exam->user->email : "-",
                "npwp" => $exam->company->npwp_number ? $exam->company->npwp_number : "-"
            ],
            "product_id" => config("app.product_id_tth_2"), //product_id TTH untuk Pengujian
            "details" => $details,
            "created" => [
                "by" => $exam->user->name,
                self::REFERENCE_ID => $exam->user->id
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

        return $purchase && $purchase->status ? $purchase->data->_id : null;
    }

    public function api_purchase($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            'Authorization' => config(self::TPN_2)
                        ],
            self::BASE_URI => config(self::URI_API_TPN),
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

    public function payment_confirmation($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $exam = Examination::where('id', $id)->with(self::DEVICE)->get();
            if($exam[0]->payment_method == 0){
				return redirect('pengujian/'.$id.self::PAGE_PEMBAYARAN);
			}else{
				return view('client.pengujian.payment_confirmation') 
            	->with('data', $exam);	
			} 
        }else{
           return redirect(self::LOGIN);
        }
        
    } 

    public function api_billing($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            'Authorization' => config(self::TPN_2)
                        ],
            self::BASE_URI => config(self::URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $params['json'] = $data;
            $res_billing = $client->post("v1/billings", $params)->getBody();
            return  json_decode($res_billing); 
        } catch(Exception $e){
            return null;
        }
    }

    public function api_resend_va($id){
        $exam = Examination::find($id);
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            'Authorization' => config(self::TPN_2)
                        ],
            self::BASE_URI => config(self::URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $res_resend = $client->post("v1/billings/mps/resend/".$exam->BILLING_ID)->getBody();
            $resend = json_decode($res_resend);
            if($resend){
				$exam->VA_number = $resend && $resend->status? $resend->data->mps->va->number : null;
				$exam->VA_amount = $resend && $resend->status? $resend->data->mps->va->amount : null;
                $exam->VA_expired = $resend && $resend->status? $resend->data->mps->va->expired : null;
                
                $exam->save();
            }
                        
            return redirect('/payment_confirmation_spb/'.$id);
        } catch(Exception $e){
            return null;
        }
	}
	
	public function api_cancel_va($id){
    	$currentUser = Auth::user();

        if($currentUser){
	        $exam = Examination::find($id);
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

			$exam->PO_ID = $this->regeneratePO($exam);
	        $exam->BILLING_ID = null;
			$exam->include_pph = 0;
			$exam->payment_method = 0;
			$exam->VA_name = null;
			$exam->VA_image_url = null;
			$exam->VA_number = null;
			$exam->VA_amount = null;
			$exam->VA_expired = null;

			$exam->save();

	        Session::flash(self::MESSAGE, "Please choose another bank. If you leave or move to another page, your process will not be saved!");
	        return redirect('pengujian/'.$id.self::PAGE_PEMBAYARAN);
		}
	}
	
    public function api_cancel_billing($BILLING_ID,$data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            'Authorization' => config(self::TPN_2)
                        ],
            self::BASE_URI => config(self::URI_API_TPN),
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
	
	public function updateTanggalUji(Request $request)
    {
		$currentUser = Auth::user();
		if($request->input(self::HIDE_DATE_TYPE) == 1){
			$exam = Examination::where('id', $request->input(self::HIDE_ID_EXAM))
			->with(self::EXAMINATION_LAB)
			->first()
			;
			$cust_test_date = strtotime($request->input('cust_test_date'));
			try{
				$query_update = "UPDATE examinations
					SET 
						cust_test_date = '".date(self::DATE_FORMAT2, $cust_test_date)."',
						updated_by = '".$currentUser[self::ATTRIBUTES]['id'].self::QUERY_UPDATED_AT.date(self::DATE_FORMAT1)."',
						function_test_status_detail = 'Pengajuan uji fungsi baru'
					WHERE id = '".$request->input(self::HIDE_ID_EXAM)."'
				";
				DB::update($query_update);
				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input(self::HIDE_ID_EXAM);
				$exam_hist->date_action = date(self::DATE_FORMAT1);
				$exam_hist->tahap = 'Update Tanggal Uji';
				$exam_hist->status = 1;
				$exam_hist->keterangan = date(self::DATE_FORMAT2, $cust_test_date).' dari Kastamer';
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date(self::DATE_FORMAT1);
				$exam_hist->save();
				
				Session::flash(self::MESSAGE, 'Update successfully');
				$client = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APLLICATION_HEADER_FORM],
					// Base URI is used with relative requests 
					self::BASE_URI => config(self::APP_URL_API_BSP),
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);
				
				$client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code.'&id='.$exam->id);
				/* push notif*/
				$admins = AdminRole::where('function_status',1)->get()->toArray();
				foreach ($admins as $admin) { 
					$dataNotif= array(
						"from"=>$currentUser->id,
						"to"=>$admin[self::USER_ID],
						self::IS_READ=>0,
						self::MESSAGE=>$currentUser->company->name." Update Tanggal Uji Fungsi",
						"url"=>self::EXAMINATION_LOC.$request->input(self::HIDE_ID_EXAM).self::EDIT_LOC
					);
					
					$notificationService = new NotificationService();
	                $notification_id = $notificationService->make($dataNotif);
					$dataNotif['id'] = $notification_id;
					// event(new Notification($dataNotif));
				}
			 	return back();

			} catch(Exception $e){
				Session::flash(self::ERROR, self::UPDATE_FAILED);
			}
		}else if($request->input(self::HIDE_DATE_TYPE) == 2){
			$exam = Examination::where('id', $request->input(self::HIDE_ID_EXAM2))
			->with(self::EXAMINATION_LAB)
			->first()
			;
			$urel_test_date = strtotime($request->input('urel_test_date'));
			if($request->input('urel_test_date') == $request->input('deal_test_date2')){
				try{
				$query_update = "UPDATE examinations
					SET 
						function_test_date_approval = '1',
						function_test_reason = '',
						updated_by = '".$currentUser[self::ATTRIBUTES]['id'].self::QUERY_UPDATED_AT.date(self::DATE_FORMAT1)."',
						function_test_status_detail = 'Tanggal uji fungsi fix'
					WHERE id = '".$request->input(self::HIDE_ID_EXAM2)."'";
				DB::update($query_update);
				
				$deal_test_date = strtotime($request->input('deal_test_date2'));
				
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $request->input(self::HIDE_ID_EXAM2);
				$exam_hist->date_action = date(self::DATE_FORMAT1);
				$exam_hist->tahap = 'Menyetujui Tanggal Uji';
				$exam_hist->status = 1;
				$exam_hist->keterangan = date(self::DATE_FORMAT2, $deal_test_date).' dari Kastamer (DISETUJUI)';
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date(self::DATE_FORMAT1);
				$exam_hist->save();

				$client = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APLLICATION_HEADER_FORM],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					self::BASE_URI => config(self::APP_URL_API_BSP),
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);
				
				 $client->get('notification/notifApproveToTE?id='.$exam->id.'&lab='.$exam->examinationLab->lab_code);

				/* push notif*/
					$data= array(
					"from"=>$currentUser->id,
					"to"=>self::ADMIN, 
					self::IS_READ=>0,
					self::MESSAGE=>$currentUser->company->name." Menyetujui Tanggal Uji Fungsi",
					"url"=>self::EXAMINATION_LOC.$request->input(self::HIDE_ID_EXAM2).self::EDIT_LOC
					);
					
					$notificationService = new NotificationService();
	                $notification_id = $notificationService->make($data);
					$data['id'] = $notification_id;
					// event(new Notification($data));

					return back();
				
				} catch(Exception $e){
					Session::flash(self::ERROR, self::UPDATE_FAILED);
				}
			}else{
				try{
					$query_update = "UPDATE examinations
						SET 
							urel_test_date = '".date(self::DATE_FORMAT2, $urel_test_date)."',
							function_test_reason = '".$request->input('alasan')."',
							updated_by = '".$currentUser[self::ATTRIBUTES]['id'].self::QUERY_UPDATED_AT.date(self::DATE_FORMAT1)."',
							function_test_status_detail = 'Pengajuan ulang uji fungsi'
						WHERE id = '".$request->input(self::HIDE_ID_EXAM2)."'
					";
					DB::update($query_update);
					
					$exam_hist = new ExaminationHistory;
					$exam_hist->examination_id = $request->input(self::HIDE_ID_EXAM2);
					$exam_hist->date_action = date(self::DATE_FORMAT1);
					$exam_hist->tahap = 'Update Tanggal Uji';
					$exam_hist->status = 1;
					$exam_hist->keterangan = date(self::DATE_FORMAT2, $urel_test_date).' dari Kastamer ('.$request->input('alasan').')';
					$exam_hist->created_by = $currentUser->id;
					$exam_hist->created_at = date(self::DATE_FORMAT1);
					$exam_hist->save();
					
					Session::flash(self::MESSAGE, 'Update successfully');
					$client = new Client([
						self::HEADERS => [self::CONTENT_TYPE => self::APLLICATION_HEADER_FORM],
						// Base URI is used with relative requests
						// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
						self::BASE_URI => config(self::APP_URL_API_BSP),
						// You can set any number of default request options.
						self::TIMEOUT  => 60.0,
					]);
					
					$client->post('notification/notifRescheduleToTE?id='.$exam->id);
					
					/* push notif*/
					$dataNotif2 = array(
						"from"=>$currentUser->id,
						"to"=>self::ADMIN,
						self::IS_READ=>0,
						self::MESSAGE=>$currentUser->company->name." Update Tanggal Uji Fungsi",
						"url"=>self::EXAMINATION_LOC.$request->input(self::HIDE_ID_EXAM2).self::EDIT_LOC
					);
					
				 	$notificationService = new NotificationService();
	                $notification_id = $notificationService->make($dataNotif2);
					$dataNotif2['id'] = $notification_id;
					// event(new Notification($dataNotif2));

					return back();
						
				} catch(Exception $e){
					Session::flash(self::ERROR, self::UPDATE_FAILED);
				}
			}
		}else if($request->input(self::HIDE_DATE_TYPE) == 3){
			$exam = Examination::where('id', $request->input(self::HIDE_ID_EXAM3))
			->with(self::EXAMINATION_LAB)
			->first()
			;
			try{
				$query_update = "UPDATE examinations
					SET 
						function_test_date_approval = '1',
						function_test_reason = '',
						updated_by = '".$currentUser[self::ATTRIBUTES]['id'].self::QUERY_UPDATED_AT.date(self::DATE_FORMAT1)."',
						function_test_status_detail = 'Tanggal uji fungsi fix'
					WHERE id = '".$request->input(self::HIDE_ID_EXAM3)."'
				";
				DB::update($query_update);
				
				$deal_test_date = strtotime($request->input('deal_test_date3'));
				
				$exam_hist2 = new ExaminationHistory;
				$exam_hist2->examination_id = $request->input(self::HIDE_ID_EXAM3);
				$exam_hist2->date_action = date(self::DATE_FORMAT1);
				$exam_hist2->tahap = 'Menyetujui Tanggal Uji';
				$exam_hist2->status = 1;
				$exam_hist2->keterangan = date(self::DATE_FORMAT2, $deal_test_date).' dari Kastamer (DISETUJUI)';
				$exam_hist2->created_by = $currentUser->id;
				$exam_hist2->created_at = date(self::DATE_FORMAT1);
				$exam_hist2->save();

				$client2 = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APLLICATION_HEADER_FORM],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					self::BASE_URI => config(self::APP_URL_API_BSP),
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);
				
				$client2->get('notification/notifApproveToTE?id='.$exam->id.'&lab='.$exam->examinationLab->lab_code);

				/* push notif*/
				$data= array(
					"from"=>$currentUser->id,
					"to"=>self::ADMIN,
					self::IS_READ=>0,
					self::MESSAGE=>$currentUser->company->name." Menyetujui Tanggal Uji Fungsi",
					"url"=>self::EXAMINATION_LOC.$request->input(self::HIDE_ID_EXAM3).self::EDIT_LOC 
				);
				$notificationService = new NotificationService();
                $notification_id = $notificationService->make($data);
				$data['id'] = $notification_id;
				// event(new Notification($data));
		 		return back();
				
			} catch(Exception $e){
				Session::flash(self::ERROR, self::UPDATE_FAILED);
			}
		}
    }
	
	public function sendProgressEmail($message)
    {
		$data = DB::table(self::USERS)
				->where('role_id', 1)
				->where(self::IS_ACTIVE, 1)
				->get();
		
		Mail::send('client.pengujian.email', array('data' => $message), function ($m) use ($data) {
            $m->to($data[0]->email)->subject("Upload Bukti Pembayaran");
        });

        return true;
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
			c.plg_id AS plg_idPerusahaan, 
			c.nib AS nibPerusahaan,
			c.phone_number AS telpPerusahaan,
			c.fax AS faxPerusahaan,
			c.email AS emailPerusahaan,
			c.npwp_number AS npwpPerusahaan,
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
		
		$PDFData = array(
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
			'no_reg' => urlencode(urlencode($data[0]->function_test_NO)) ?: '-',
			'plg_idPerusahaan' => urlencode(urlencode($data[0]->plg_idPerusahaan)) ?: '-',
			'nibPerusahaan' => urlencode(urlencode($data[0]->nibPerusahaan)) ?: '-',
			'npwpPerusahaan' => urlencode(urlencode($data[0]->npwpPerusahaan)) ?: '-'
		);
		
		$PDF = new \App\Services\PDF\PDFService();
		return $PDF->cetakPengujian($PDFData);
    }
	
	public function testimonial(Request $request)
    {
		$currentUser = Auth::user();
		$datenow = date(self::DATE_FORMAT1);
		
		$testimonial = new Testimonial;
        $testimonial->id = Uuid::uuid4();
        $testimonial->examination_id = $request->input(self::EXAM_ID);
        $testimonial->message = $request->input(self::MESSAGE);
        $testimonial->is_active = 0;
		$testimonial->created_by = $currentUser->id;
        $testimonial->updated_by = $currentUser->id;
		
		$testimonial->created_at = $datenow;
		$testimonial->updated_at = $datenow;
		
		if($testimonial->save()){
			$exam_hist = new ExaminationHistory;
			$exam_hist->examination_id = $request->input(self::EXAM_ID);
			$exam_hist->date_action = date(self::DATE_FORMAT1);
			$exam_hist->tahap = 'Download Sertifikat dan Mengisi Testimoni';
			$exam_hist->status = 1;
			$exam_hist->keterangan = '';
			$exam_hist->created_by = $currentUser->id;
			$exam_hist->created_at = date(self::DATE_FORMAT1);
			$exam_hist->save();
			
			echo 1;			
		}else{
			echo 0;
		}
	}
	
	public function cekAmbilBarang(Request $request)
    { 
		$equip = Equipment::where(self::EXAMINATION_ID, "=", $request->input(self::MY_EXAM_ID))->where("location", "=", "1");
		$is_location = count($equip->get());
		//if count 1, masukan ke history download
		if($is_location > 0){				
			return 1;
		}
		return 0;
		
	}
	
	public function autocomplete($query) {
		$currentUser = Auth::user();
		$company_id = ''.$currentUser[self::ATTRIBUTES][self::COMPANY_ID].'';
		return Examination::join(self::DEVICES, self::EXAMINATIONS_DEVICES_DOT_ID, '=', self::DEVICES_DOT_ID)
				->join('users', 'examinations.created_by', '=', 'users.id')
				->join('examination_types', self::EXAMINATIONS_TYPE_ID, '=', 'examination_types.id')
                ->select(self::DEVICES_NAME_AUTOSUGGEST)
                ->where(self::EXAMINATIONS_COMPANY_ID,'=',''.$company_id.'')
                ->where(self::DEVICES_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICES_NAME)
                ->take(2)
				->distinct()
                ->get();
    }
	
	public function checkKuisioner(Request $request) {
		$currentUser = Auth::user();
		$expDate = Carbon::now()->subMonths(3);
		$company_id = $currentUser->company_id;
		$exam_id = $request->input('id');
		// $quest = Questioner::where(self::EXAMINATION_ID, "=", $request->input('id'))
		$query = Questioner::with('user')
				->whereDate('questioner_date', '>=', $expDate)
	            ->orWhere(self::EXAMINATION_ID, $exam_id);
		$query->whereHas('user', function ($query) use ($company_id) {
            $query->where(self::COMPANY_ID, $company_id);
        });
		// ->where("created_by", "=", $currentUser->id)
		$quest = $query->select('complaint')->get();
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
		$quest->examination_id = $request->input(self::EXAM_ID);
		$quest->questioner_date = date(self::DATE_FORMAT2, $tanggal);
		
		$quest->created_by = $currentUser->id;
		$quest->created_at = date(self::DATE_FORMAT1);
		
		try{
			$quest->save();
			
			/* ====== */
			for($i=0;$i<count($request->input('question_id'));$i++){
				$questioner_dyn = new QuestionerDynamic;
				$questioner_dyn->question_id = $request->input('question_id')[$i];
				$questioner_dyn->examination_id = $request->input(self::EXAM_ID);
				$questioner_dyn->order_question = ($i+1);
				$questioner_dyn->is_essay = $request->input('is_essay')[$i];
				$questioner_dyn->questioner_date = date(self::DATE_FORMAT2, $tanggal);
				$questioner_dyn->eks_answer = $request->input('eks'.$i);
				$questioner_dyn->perf_answer = $request->input('is_essay')[$i] == 1 ? 0 : $request->input('pref'.$i);
				
				$questioner_dyn->created_by = $currentUser->id;
				$questioner_dyn->created_at = date(self::DATE_FORMAT1);

				try{
					$questioner_dyn->save();
				} catch(\Exception $e){
					// do nothing
				}
			}
			/* ====== */

			$data= array( 
				"from"=>$currentUser->id,
				"to"=>self::ADMIN,
				self::MESSAGE=>$currentUser->company->name." Mengisi Kuisioner",
				"url"=>"examinationdone/".$request->input(self::EXAM_ID).self::EDIT_LOC,
				self::IS_READ=>0,
				self::CREATED_AT=>date(self::DATE_FORMAT1),
				self::UPDATED_AT=>date(self::DATE_FORMAT1)
			);
			
			$notificationService = new NotificationService();
			$data['id'] = $notificationService->make($data);
			  
	        // event(new Notification($data))

			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}
	
	public function insertComplaint(Request $request){
		$currentUser = Auth::user();
		$tanggal = strtotime($request->input('tanggal_complaint'));
		
		$quest = Questioner::where(self::EXAMINATION_ID,'=',$request->input(self::MY_EXAM_ID))->first();
		
		$quest->complaint_date = date(self::DATE_FORMAT2, $tanggal);
		$quest->complaint = $request->input('complaint');
		$quest->updated_by = $currentUser->id;
		$quest->updated_at = date(self::DATE_FORMAT1);
		
		try{
			$quest->save();


			

			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	} 
}