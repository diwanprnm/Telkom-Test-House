<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Http\Requests;

use App\Examination;
use App\Device;
use App\Company;
use App\Logs;
use App\LogsAdministrator;

use App\Services\Logs\LogService;

use Auth;
use Session;
use Excel;
use Ramsey\Uuid\Uuid;

use Carbon\Carbon;

class DevicencController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
	private const SEARCH = 'search';
	private const SEARCH2 = "search";
	private const EXAMINATIONS = 'examinations';
	private const DEVICE = 'devices';
	private const EXAMINATIONS_DEVICE_ID = 'examinations.device_id';
	private const DEVICE_ID = 'devices.id';
	private const COMPANIES = 'companies';
	private const COMP_ID = 'companies.id';
	private const DEVICE_NAME = 'devices.name';
	private const DEVICE_MARK = 'devices.mark';
	private const DEVICE_MODEL = 'devices.model';
	private const QA_DATE = 'qa_date';
	private const ADMIN = '/admin/devicenc/';
	private const EXAMINATIONS_COMP = 'examinations.company_id';
	private const EXAMINATIONS_TYPE_ID = 'examinations.examination_type_id';
	private const EXAMINATIONS_RESUME_STATUS = 'examinations.resume_status';
	private const EXAMINATIONS_QA_STATUS = 'examinations.qa_status';
	private const EXAMINATIONS_QA_PASSED = 'examinations.qa_passed';
	private const EXAMINATIONS_CERTIFICATION_STATUTS = 'examinations.certificate_status';
	private const DEVICE_STATUS='devices.status';
	private const EXAMINATIONS_QA_DATE = 'examinations.qa_date';
	private const DEVICE_VALID_THRU = 'devices.valid_thru';
	private const COMPANIES_NAME = 'companies.name';
	
	public const DATA = ['examinations.price AS totalBiaya',
	'examinations.spk_code',
	'examinations.jns_perusahaan',
	self::EXAMINATIONS_QA_DATE,
	'companies.name AS namaPerusahaan',
	'devices.id as device_id',
	'devices.name AS namaPerangkat',
	'devices.mark AS merk',
	'devices.model AS tipe',
	'devices.capacity AS kapasitas',
	'devices.test_reference AS standarisasi',
	'devices.valid_from',
	self::DEVICE_VALID_THRU,
	'devices.serial_number',
	'devices.manufactured_by',
	self::COMPANIES_NAME,
	'companies.address',
	'companies.city',
	'companies.postal_code',
	'companies.email',
	'companies.phone_number',
	'companies.fax',
	'companies.npwp_number',
	'companies.siup_number',
	'companies.siup_date',
	'companies.qs_certificate_number',
	'companies.qs_certificate_date'];
	
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
		$messageAfter = null;
		$paginate = 1000;
		$search = trim($request->input($this::SEARCH));
		$search2 = trim($request->input('search2'));

		$tab = $request->input('tab');
		$expDate = Carbon::now()->subMonths(6);
		$where = array(
			self::EXAMINATIONS_TYPE_ID=>1,
			self::EXAMINATIONS_RESUME_STATUS=>1,
			self::EXAMINATIONS_QA_STATUS=>1,
			self::EXAMINATIONS_QA_PASSED=>-1,
			self::EXAMINATIONS_CERTIFICATION_STATUTS=>1,
		);
		$dev = DB::table($this::EXAMINATIONS)
			->join($this::DEVICE, $this::EXAMINATIONS_DEVICE_ID, '=', $this::DEVICE_ID)
			->join($this::COMPANIES, self::EXAMINATIONS_COMP, '=', $this::COMP_ID)
			->select(self::DATA)
			->where($where) 
			->whereDate(self::EXAMINATIONS_QA_DATE,'>=',$expDate)
			->where(self::DEVICE_STATUS, 1);

		$afterDev = 
		DB::table($this::EXAMINATIONS)
			->join($this::DEVICE, $this::EXAMINATIONS_DEVICE_ID, '=', $this::DEVICE_ID)
			->join($this::COMPANIES, self::EXAMINATIONS_COMP, '=', $this::COMP_ID)
			->select(self::DATA)  
			->where($where) 
			->where(function($q) use($expDate){
				$q->whereDate(self::EXAMINATIONS_QA_DATE,'<',$expDate)
					->orWhere(self::DEVICE_STATUS, '-1');
			});
		
		if ($search != null){
			$dev->where(function($q) use($search){
				$q->where($this::DEVICE_NAME,'like','%'.$search.'%')
					->orWhere(self::COMPANIES_NAME,'like','%'.$search.'%')
					->orWhere(self::DEVICE_MARK,'like','%'.$search.'%')
					->orWhere($this::DEVICE_MODEL,'like','%'.$search.'%');
			});
			
			$logService = new LogService();
			$logService->createLog("Search Device","DEVICE", json_encode( array(self::SEARCH2=>$search)) );
			
		}

		if ($search2 != null){
			$afterDev->where(function($q) use($search2){
				$q->where($this::DEVICE_NAME,'like','%'.$search2.'%')
					->orWhere(self::COMPANIES_NAME,'like','%'.$search2.'%')
					->orWhere(self::DEVICE_MARK,'like','%'.$search2.'%')
					->orWhere($this::DEVICE_MODEL,'like','%'.$search2.'%');
			});
			
			$logService = new LogService();
			$logService->createLog("Search Device","DEVICE", json_encode( array(self::SEARCH2=>$search)) );
		}

		$data = $dev->orderBy($this::QA_DATE, 'desc')->paginate($paginate);
		
		if (count($data) == 0){
			$message = 'Data not found';
		}
		
		$dataAfter = $afterDev->orderBy($this::QA_DATE, 'desc')->paginate($paginate);
		$dataAfter->setPageName('other_page');
		
		if (count($dataAfter) == 0){
			$messageAfter = 'Data not found';
		}
		
		return view('admin.devicenc.index')
			->with('tab', $tab)
			->with('message', $message)
			->with('messageAfter', $messageAfter)
			->with('data', $data)
			->with('dataAfter', $dataAfter)
			->with($this::SEARCH, $search)
			->with('search2', $search2)
		;
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.
        $search = trim($request->input($this::SEARCH));
        $tab = $request->input('tab');
        $expDate = Carbon::now()->subMonths(6);   
        $dev = DB::table($this::EXAMINATIONS)
			->join($this::DEVICE, $this::EXAMINATIONS_DEVICE_ID, '=', $this::DEVICE_ID)
			->join($this::COMPANIES, self::EXAMINATIONS_COMP, '=', $this::COMP_ID)
			->select(self::DATA)
			->where(self::EXAMINATIONS_TYPE_ID,'=','1')
			->where(self::EXAMINATIONS_RESUME_STATUS,'=','1')
			->where(self::EXAMINATIONS_QA_STATUS,'=','1')
			->where(self::EXAMINATIONS_QA_PASSED,'=','-1')
			->where(self::EXAMINATIONS_CERTIFICATION_STATUTS,'=','1')
			->whereDate(self::EXAMINATIONS_QA_DATE,'>=',$expDate)
			->where(self::DEVICE_STATUS, 1)
		;
		$afterDev = DB::table($this::EXAMINATIONS)
			->join($this::DEVICE, $this::EXAMINATIONS_DEVICE_ID, '=', $this::DEVICE_ID)
			->join($this::COMPANIES, self::EXAMINATIONS_COMP, '=', $this::COMP_ID)
			->select(self::DATA)
			->where(self::EXAMINATIONS_TYPE_ID,'=','1')
			->where(self::EXAMINATIONS_RESUME_STATUS,'=','1')
			->where(self::EXAMINATIONS_QA_STATUS,'=','1')
			->where(self::EXAMINATIONS_QA_PASSED,'=','-1')
			->where(self::EXAMINATIONS_CERTIFICATION_STATUTS,'=','1')
			->where(function($q) use($expDate){
			$q->whereDate(self::EXAMINATIONS_QA_DATE,'<',$expDate)
				->orWhere(self::DEVICE_STATUS, '-1');
		});

        if ($search != null){
        	if($tab == 'tab-2'){
				
        		$afterDev->where(function($q) use($search){
					$q->where($this::DEVICE_NAME,'like','%'.$search.'%')
						->orWhere(self::COMPANIES_NAME,'like','%'.$search.'%')
						->orWhere(self::DEVICE_MARK,'like','%'.$search.'%')
						->orWhere($this::DEVICE_MODEL,'like','%'.$search.'%');
				});

				$data = $afterDev->orderBy($this::QA_DATE, 'desc')->get();
        	}else{
				$dev->where(function($q) use($search){
					$q->where($this::DEVICE_NAME,'like','%'.$search.'%')
						->orWhere(self::COMPANIES_NAME,'like','%'.$search.'%')
						->orWhere(self::DEVICE_MARK,'like','%'.$search.'%')
						->orWhere($this::DEVICE_MODEL,'like','%'.$search.'%');
				});

				$data = $dev->orderBy($this::QA_DATE, 'desc')->get();
        	}			
        }else{
        	if($tab == 'tab-2'){$data = $afterDev->orderBy($this::QA_DATE, 'desc')->get();}else{$data = $dev->orderBy($this::QA_DATE, 'desc')->get();}
        }

        $examsArray = []; 
        // Define the Excel spreadsheet headers
        $examsArray[] = [
			'No',
			// 'Jenis Perusahaan',
			'Nama Perusahaan',
			/*'Alamat Perusahaan',
			'Email Perusahaan',
			'Telepon Perusahaan',
			'Faksimil Perusahaan',
			'NPWP Perusahaan',
			'SIUP Perusahaan',
			'Tgl. SIUP Perusahaan',
			'Sertifikat Perusahaan',
			'Tgl. Sertifikat Perusahaan',*/
			'Nama Perangkat',
			'Merk/Pabrik',
			'Tipe',
			'Kapasitas/Kecepatan',
			/*'Nomor Seri Perangkat',*/
			'Referensi Uji',
			'Dibuat di',
			/*'Nomor SPK',
			'Total Biaya'*/
			'Tanggal Sidang'
		]; 
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        $no = 1;
		foreach ($data as $row) {
			$examsArray[] = [
				$no,
				// $row->jns_perusahaan,
				$row->namaPerusahaan,
				/*$row->address.", kota".$row->city.", kode pos".$row->postal_code,
				$row->email,
				$row->phone_number,
				$row->fax,
				$row->npwp_number,
				$row->siup_number,
				$siup_date,
				$row->qs_certificate_number,
				$qs_certificate_date,*/
				$row->namaPerangkat,
				$row->merk,
				$row->tipe,
				$row->kapasitas,
				/*$row->serial_number,*/
				$row->standarisasi,
				$row->manufactured_by,
				// $row->spk_code,
				// $row->totalBiaya
				$row->qa_date
			];
			$no++;
		}
		
		$logService = new LogService();
		$logService->createLog("Download Data Perangkat Tidak Lulus Uji","Perangkat Tidak Lulus Uji", "" );
		
		$excel = \App\Services\ExcelService::download($examsArray, 'Data Perangkat Tidak Lulus Uji');
		return response($excel['file'], 200, $excel['headers']);		
    }

    public function moveData($id,$reason = null)
    { 
        $logs_devicenc = NULL;
        $devicenc = Device::find($id);

        if($devicenc){
        	try{
				$devicenc->status = '-1';
				$devicenc->save();
				
        		$logs_devicenc = clone $devicenc;
				$logService = new LogService();
				$logService->createLog("Memindahkan Data Perangkat Menjadi Layak Uji Ulang","Perangkat Tidak Lulus Uji",$logs_devicenc );
		
	            Session::flash('message', 'Successfully Move Data');
	            return redirect($this::ADMIN);
        	} catch(Exception $e){ return redirect($this::ADMIN)->with('error', 'Move failed');
		    }
        }else{
			return redirect($this::ADMIN)
				->with('error', 'Undefined Data')
			;
        }

    }
}