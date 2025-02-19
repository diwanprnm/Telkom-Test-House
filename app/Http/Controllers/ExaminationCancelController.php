<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\ExaminationHistory;
use App\User;
use App\Income;

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

use App\Services\ExaminationService;
use App\Services\Logs\LogService;

class ExaminationCancelController extends Controller
{
	private const SEARCH = 'search';
	private const CREATED_AT = 'created_at';
	private const COMPANY = 'company';
	private const EXAMINATION_TYPE = 'examinationType';
	private const MEDIA = 'media';
	private const DEVICE = 'device';
	private const DATE_FORMAT = 'd-m-Y';
	private const EXAM_LAB = 'examinationLab';

	private const TABLE_DEVICE = 'devices';
	private const EXAM_DEVICES_ID = 'examinations.device_id';
	private const DEVICES_ID = 'devices.id';
	private const TABLE_COMPANIES = 'companies';
	private const EXAM_COMPANY_ID = 'examinations.company_id';
	private const COMPANIES_ID = 'companies.id';
	private const DEVICE_NAME_AUTOSUGGEST = 'devices.name as autosuggest';
	private const DEVICE_NAME = 'devices.name';
	private const PAYMENT_STATUS = 'payment_status';
	private const COMPANY_AUTOSUGGEST = 'companies.name as autosuggest';
	private const COMPANIES_NAME = 'companies.name';
	private const EXAM_TYPE_ID = 'examinations.examination_type_id';
	private const EXAM_REGISTRATION_STATUS = 'examinations.registration_status';
	private const EXAM_SPB_STATUS = 'examinations.spb_status';
	private const EXAM_PAYMENT_STATUS = 'examinations.payment_status';
	private const EXAM_CERTIFICATE_STATUS = 'examinations.certificate_status';
	private const EXAM_FUNCTION_STATUS = 'examinations.function_status';
	private const EXAM_CONTRACT_STATUS = 'examinations.contract_status';
	private const EXAM_SPK_STATUS = 'examinations.spk_status';
	private const EXAM_EXAMINATION_STATUS = 'examinations.examination_status';
	private const EXAM_RESUME_STATUS = 'examinations.resume_status';
	private const EXAMINATION_TYPE_ID = 'examination_type_id';
	private const REGISTRATION_STATUS = 'registration_status';
	private const FUNCTION_STATUS = 'function_status';
	private const CONTRACT_STATUS = 'contract_status';
	private const SPB_STATUS = 'spb_status';
	private const SPK_STATUS = 'spk_status';
	private const EXAMINATION_STATUS = 'examination_status';
	private const RESUME_STATUS = 'resume_status';

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

        if (!$currentUser){ return redirect('login');}

		$message = null;
		$paginate = 5;
		$search = trim($request->input(self::SEARCH));
		$type = '';
		$before = null;
		$after = null;

		$examType = ExaminationType::all();

		$query = Examination::where('is_cancel', 1)->whereNotNull(self::CREATED_AT)
			->with('user')
			->with(self::COMPANY)
			->with(self::EXAMINATION_TYPE)
			->with(self::EXAM_LAB)
			->with(self::MEDIA)
			->with(self::DEVICE)
		;
		
		$query->where(function($qry) use($search){
			$qry->whereHas(self::DEVICE, function ($q) use ($search){
					return $q->where('name', 'like', '%'.strtolower($search).'%');
				})
			->orWhereHas(self::COMPANY, function ($q) use ($search){
					return $q->where('name', 'like', '%'.strtolower($search).'%');
				});
		});
		
		if ($search != null){
			$query->where(function($qry) use($search){
				$qry->whereHas(self::DEVICE, function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					})
				->orWhereHas(self::COMPANY, function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					});
			});
			
			$logService = new LogService();
			$logService->createLog(self::SEARCH, 'Pengujian Batal', json_encode(array(self::SEARCH=>$search)) );
		}

		if ($request->has('type')){
			$type = $request->get('type');
			if($request->input('type') != 'all'){
				$query->where(self::EXAMINATION_TYPE_ID, $request->get('type'));
			}
		}

		if ($request->has(self::COMPANY)){
			$query->whereHas(self::COMPANY, function ($q) use ($request){
				return $q->where('name', 'like', '%'.strtolower($request->get(self::COMPANY)).'%');
			});
		}

		if ($request->has(self::DEVICE)){
			$query->whereHas(self::DEVICE, function ($q) use ($request){
				return $q->where('name', 'like', '%'.strtolower($request->get(self::DEVICE)).'%');
			});
		}
		
		$data_excel = $query->orderBy('updated_at', 'desc')->get();
		$request->session()->put('excel_pengujian_batal', $data_excel);
		
		$data = $query->orderBy('updated_at', 'desc')->paginate($paginate);

		if (count($query) == 0){ $message = 'Data not found'; }
		
		return view('admin.examinationcancel.index')
			->with('message', $message)
			->with('data', $data)
			->with('type', $examType)
			->with(self::SEARCH, $search)
			->with('filterType', $type)
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
			->with(self::EXAM_LAB)
			->with(self::DEVICE)
			->with(self::MEDIA)
			->first()
		;
							
		$exam_history = ExaminationHistory::whereNotNull(self::CREATED_AT)
			->with('user')
			->where('examination_id', $id)
			->orderBy(self::CREATED_AT, 'DESC')
			->get()
		;

        return view('admin.examinationcancel.show')
			->with('exam_history', $exam_history)
			->with('data', $exam)
		;
    }
	

    public function edit($id)
    {
		$examinationService = new ExaminationService();
		$query_exam_hist = ExaminationHistory::where('examination_id', $id)->where(function($q){
			return $q->where('tahap', 'Download Laporan Uji')
				->orWhere('tahap', 'Download Sertifikat');
			})->with('user');
		$exam_hist = $query_exam_hist->orderBy('created_at', 'desc')->get();
		
        $exam = Examination::where('id', $id)
			->with(self::COMPANY)
			->with(self::EXAMINATION_TYPE)
			->with(self::EXAM_LAB)
			->with(self::DEVICE)
			->with(self::MEDIA)
			->with('examinationHistory')
			->with('questioner')
			->first()
		;

        $labs = ExaminationLab::all();
		
		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		
		$tempData = $examinationService->getDetailDataFromOTR($client, $id, $exam->spk_code);

        return view('admin.examinationcancel.edit')
            ->with('data', $exam)
            ->with('labs', $labs)
			->with('data_lab', $tempData[0])
            ->with('data_gudang', $tempData[1])
			->with('exam_approve_date', $tempData[2])
			->with('exam_schedule', $tempData[3])
			->with('exam_hist', $exam_hist)
		;
    }
	
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		
		$data = $request->session()->get('excel_pengujian_batal');
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'Tipe Pengujian',
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
			'SIUP Perusahaan',
			'Tgl. SIUP Perusahaan',
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
			'Total Biaya'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
		foreach ($data as $row) {
			if($row->company->siup_date==''){ $siup_date = '';
			}else{ $siup_date = date(self::DATE_FORMAT, strtotime($row->company->siup_date));
			}

			if($row->company->qs_certificate_date==''){ $qs_certificate_date = '';
			}else{ $qs_certificate_date = date(self::DATE_FORMAT, strtotime($row->company->qs_certificate_date));
			}

			if($row->device->valid_from==''){ $valid_from = '';
			}else{ $valid_from = date(self::DATE_FORMAT, strtotime($row->device->valid_from));
			}
			
			if($row->device->valid_thru==''){ $valid_thru = '';
			}else{ $valid_thru = date(self::DATE_FORMAT, strtotime($row->device->valid_thru));
			}

			if($row->spk_date==''){ $spk_date = '';
			}else{ $spk_date = date(self::DATE_FORMAT, strtotime($row->spk_date));
			}

			$examsArray[] = [
				"".$row->examinationType->name." (".$row->examinationType->description.")",
				$row->user->name,
				$row->user->email,
				$row->user->address,
				$row->user->phone_number,
				$row->user->fax,
				$row->jns_perusahaan,
				$row->company->name,
				$row->company->address.", kota".$row->company->city.", kode pos".$row->company->postal_code,
				$row->company->email,
				$row->company->phone_number,
				$row->company->fax,
				$row->company->npwp_number,
				$row->company->siup_number,
				$siup_date,
				$row->company->qs_certificate_number,
				$qs_certificate_date,
				$row->device->name,
				$row->device->mark,
				$row->device->capacity,
				$row->device->manufactured_by,
				$row->device->serial_number,
				$row->device->model,
				$row->device->test_reference,
				$valid_from." s.d. ".$valid_thru,
				$row->spk_code,
				$spk_date,
				$row->price
			];
		} 
        $logService = new LogService();  
        $logService->createLog('download_excel','Pengujian Batal');

		$excel = \App\Services\ExcelService::download($examsArray, 'Data Pengujian Batal');
		return response($excel['file'], 200, $excel['headers']);	 
	}
	
	public function autocomplete($query) {
		$queries = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(self::DEVICE_NAME, 'like','%'.$query.'%');
					$queries->where(function($qry){
						$qry->where(function($q){
							return $q->where(self::EXAMINATION_TYPE_ID, '=', '1')
								->where(self::REGISTRATION_STATUS, '=', '1')
								->where(self::FUNCTION_STATUS, '=', '1')
								->where(self::CONTRACT_STATUS, '=', '1')
								->where(self::SPB_STATUS, '=', '1')
								->where(self::PAYMENT_STATUS, '=', '1')
								->where(self::SPK_STATUS, '=', '1')
								->where(self::EXAMINATION_STATUS, '=', '1')
								->where(self::RESUME_STATUS, '=', '1')
								->where('qa_status', '=', '1')
								->where('certificate_status', '=', '1')
								;
							})
						->orWhere(function($q){
							return $q->where(self::EXAMINATION_TYPE_ID, '!=', '1')
								->where(self::REGISTRATION_STATUS, '=', '1')
								->where(self::FUNCTION_STATUS, '=', '1')
								->where(self::CONTRACT_STATUS, '=', '1')
								->where(self::SPB_STATUS, '=', '1')
								->where(self::PAYMENT_STATUS, '=', '1')
								->where(self::SPK_STATUS, '=', '1')
								->where(self::EXAMINATION_STATUS, '=', '1')
								->where(self::RESUME_STATUS, '=', '1')
								;
							});
					});
				$data1 = $queries->orderBy(self::DEVICE_NAME)
                ->take(3)
				->distinct()
                ->get();
		
		$queries = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where(self::COMPANIES_NAME, 'like','%'.$query.'%');
					$queries->where(function($qry){
						$qry->where(function($q){
							return $q->where(self::EXAM_TYPE_ID, '=', '1')
								->where(self::EXAM_REGISTRATION_STATUS, '=', '1')
								->where(self::EXAM_FUNCTION_STATUS, '=', '1')
								->where(self::EXAM_CONTRACT_STATUS, '=', '1')
								->where(self::EXAM_SPB_STATUS, '=', '1')
								->where(self::EXAM_PAYMENT_STATUS, '=', '1')
								->where(self::EXAM_SPK_STATUS, '=', '1')
								->where(self::EXAM_EXAMINATION_STATUS, '=', '1')
								->where(self::EXAM_RESUME_STATUS, '=', '1')
								->where('examinations.qa_status', '=', '1')
								->where(self::EXAM_CERTIFICATE_STATUS, '=', '1')
								;
							})
						->orWhere(function($q){
							return $q->where(self::EXAM_TYPE_ID, '!=', '1')
								->where(self::EXAM_REGISTRATION_STATUS, '=', '1')
								->where(self::EXAM_FUNCTION_STATUS, '=', '1')
								->where(self::EXAM_CONTRACT_STATUS, '=', '1')
								->where(self::EXAM_SPB_STATUS, '=', '1')
								->where(self::EXAM_PAYMENT_STATUS, '=', '1')
								->where(self::EXAM_SPK_STATUS, '=', '1')
								->where(self::EXAM_EXAMINATION_STATUS, '=', '1')
								->where(self::EXAM_RESUME_STATUS, '=', '1')
								;
							});
					});
				$data2 = $queries->orderBy(self::COMPANIES_NAME)
                ->take(3)
				->distinct()
                ->get();
				
		if( is_array($data1) && is_array($data2) ) { return array_merge($data1,$data2);} 
    }
	
	function cetakKepuasanKonsumen($id, Request $request)
    {
		$data = Examination::where('id','=',$id)
		->with('User')
		->with(self::EXAMINATION_TYPE)
		->with('ExaminationLab')
		->with(self::COMPANY)
		->with(self::DEVICE)
		->with('Questioner')
		->with('QuestionerDynamic')
		->get();
		
		$PDF = new \App\Services\PDF\PDFService();
		return $PDF->cetakKepuasanKonsumen($data);
    }
	
	function cetakComplaint($id, Request $request)
    {
		$data = Examination::where('id','=',$id)
			->with('User')
			->with(self::EXAMINATION_TYPE)
			->with('ExaminationLab')
			->with(self::COMPANY)
			->with(self::DEVICE)
			->with('Questioner')
			->get()
		;
		
		$PDF = new \App\Services\PDF\PDFService();
		return $PDF->cetakComplaint($data);
    }
}