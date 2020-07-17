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
use App\Logs_administrator;

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
	private const EXAM = 'examinations';
	private const DEVICE = 'devices';
	private const EDI = 'examinations.device_id';
	private const DEV_ID = 'devices.id';
	private const COMPANIES = 'companies';
	private const COMP_ID = 'companies.id';
	private const DEVICE_NAME = 'devices.name';
	private const DEVICE_MARK = 'devices.mark';
	private const DEVICE_MOD = 'devices.model';
	private const QA_DATE = 'qa_date';
	private const ADMIN = '/admin/devicenc/';
	
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

        if ($currentUser){
            $message = null;
            $messageAfter = null;
            $paginate = 1000;
            $search = trim($request->input($this::SEARCH));
            $search2 = trim($request->input('search2'));
			$before = null;
            $after = null;

            $tab = $request->input('tab');
            $expDate = Carbon::now()->subMonths(6);
            
            $dev = DB::table($this::EXAM)
			->join($this::DEVICE, $this::EDI, '=', $this::DEV_ID)
			->join($this::COMPANIES, 'examinations.company_id', '=', $this::COMP_ID)
			->select(
					'examinations.price AS totalBiaya',
					'examinations.spk_code',
					'examinations.jns_perusahaan',
					'examinations.qa_date',
					'companies.name AS namaPerusahaan',
					'devices.id as device_id',
					'devices.name AS namaPerangkat',
					'devices.mark AS merk',
					'devices.model AS tipe',
					'devices.capacity AS kapasitas',
					'devices.test_reference AS standarisasi',
					'devices.valid_from',
					'devices.valid_thru',
					'devices.serial_number',
					'devices.manufactured_by',
					'companies.name',
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
					'companies.qs_certificate_date'
					)
			->where('examinations.examination_type_id','=','1')
			->where('examinations.resume_status','=','1')
			->where('examinations.qa_status','=','1')
			->where('examinations.qa_passed','=','-1')
			->where('examinations.certificate_status','=','1')
			->whereDate('examinations.qa_date','>=',$expDate)
			->where('devices.status', 1)
			;

			$afterDev = 
			DB::table($this::EXAM)
			->join($this::DEVICE, $this::EDI, '=', $this::DEV_ID)
			->join($this::COMPANIES, 'examinations.company_id', '=', $this::COMP_ID)
			->select(
					'examinations.price AS totalBiaya',
					'examinations.spk_code',
					'examinations.jns_perusahaan',
					'examinations.qa_date',
					'companies.name AS namaPerusahaan',
					'devices.id as device_id',
					'devices.name AS namaPerangkat',
					'devices.mark AS merk',
					'devices.model AS tipe',
					'devices.capacity AS kapasitas',
					'devices.test_reference AS standarisasi',
					'devices.valid_from',
					'devices.valid_thru',
					'devices.serial_number',
					'devices.manufactured_by',
					'companies.name',
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
					'companies.qs_certificate_date'
					)
			->where('examinations.examination_type_id','=','1')
			->where('examinations.resume_status','=','1')
			->where('examinations.qa_status','=','1')
			->where('examinations.qa_passed','=','-1')
			->where('examinations.certificate_status','=','1')
			->where(function($q) use($expDate){
					$q->whereDate('examinations.qa_date','<',$expDate)
						->orWhere('devices.status', '-1');
				});
			;

			if ($request->has('before_date')){
				$dev->where('devices.valid_thru', '<=', $request->get('before_date'));
				$before = $request->get('before_date');
			}

			if ($request->has('after_date')){
				$dev->where('devices.valid_thru', '>=', $request->get('after_date'));
				$after = $request->get('after_date');
			}

            if ($search != null){
        		$dev->where(function($q) use($search){
					$q->where($this::DEVICE_NAME,'like','%'.$search.'%')
						->orWhere('companies.name','like','%'.$search.'%')
						->orWhere('devices.mark','like','%'.$search.'%')
						->orWhere($this::DEVICE_MOD,'like','%'.$search.'%');
				});
				

				$logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search Device";  
                $dataSearch = array("search"=>$search); 
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "DEVICE";
                $logs->save();
				
            }

            if ($search2 != null){
        		$afterDev->where(function($q) use($search2){
					$q->where($this::DEVICE_NAME,'like','%'.$search2.'%')
						->orWhere('companies.name','like','%'.$search2.'%')
						->orWhere('devices.mark','like','%'.$search2.'%')
						->orWhere($this::DEVICE_MOD,'like','%'.$search2.'%');
				});
				

				$logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search Device";  
                $dataSearch = array("search"=>$search); 
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "DEVICE";
                $logs->save();
				
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
                ->with('before_date', $before)
                ->with('after_date', $after);
        }
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
            
        $dev = DB::table($this::EXAM)
		->join($this::DEVICE, $this::EDI, '=', $this::DEV_ID)
		->join($this::COMPANIES, 'examinations.company_id', '=', $this::COMP_ID)
		->select(
				'examinations.price AS totalBiaya',
				'examinations.spk_code',
				'examinations.jns_perusahaan',
				'examinations.qa_date',
				'companies.name AS namaPerusahaan',
				'devices.id as device_id',
				'devices.name AS namaPerangkat',
				'devices.mark AS merk',
				'devices.model AS tipe',
				'devices.capacity AS kapasitas',
				'devices.test_reference AS standarisasi',
				'devices.valid_from',
				'devices.valid_thru',
				'devices.serial_number',
				'devices.manufactured_by',
				'companies.name',
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
				'companies.qs_certificate_date'
				)
		->where('examinations.examination_type_id','=','1')
		->where('examinations.resume_status','=','1')
		->where('examinations.qa_status','=','1')
		->where('examinations.qa_passed','=','-1')
		->where('examinations.certificate_status','=','1')
		->whereDate('examinations.qa_date','>=',$expDate)
		->where('devices.status', 1)
		;

		$afterDev = DB::table($this::EXAM)
		->join($this::DEVICE, $this::EDI, '=', $this::DEV_ID)
		->join($this::COMPANIES, 'examinations.company_id', '=', $this::COMP_ID)
		->select(
				'examinations.price AS totalBiaya',
				'examinations.spk_code',
				'examinations.jns_perusahaan',
				'examinations.qa_date',
				'companies.name AS namaPerusahaan',
				'devices.id as device_id',
				'devices.name AS namaPerangkat',
				'devices.mark AS merk',
				'devices.model AS tipe',
				'devices.capacity AS kapasitas',
				'devices.test_reference AS standarisasi',
				'devices.valid_from',
				'devices.valid_thru',
				'devices.serial_number',
				'devices.manufactured_by',
				'companies.name',
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
				'companies.qs_certificate_date'
				)
		->where('examinations.examination_type_id','=','1')
		->where('examinations.resume_status','=','1')
		->where('examinations.qa_status','=','1')
		->where('examinations.qa_passed','=','-1')
		->where('examinations.certificate_status','=','1')
		->where(function($q) use($expDate){
					$q->whereDate('examinations.qa_date','<',$expDate)
						->orWhere('devices.status', '-1');
				});
		

		if ($request->has('before_date')){
			$dev->where('devices.valid_thru', '<=', $request->get('before_date'));
			$before = $request->get('before_date');
		}

		if ($request->has('after_date')){
			$dev->where('devices.valid_thru', '>=', $request->get('after_date'));
			$after = $request->get('after_date');
		}

        if ($search != null){
        	if($tab == 'tab-2'){
        		$afterDev->where(function($q) use($search){
					$q->where($this::DEVICE_NAME,'like','%'.$search.'%')
						->orWhere('companies.name','like','%'.$search.'%')
						->orWhere('devices.mark','like','%'.$search.'%')
						->orWhere($this::DEVICE_MOD,'like','%'.$search.'%');
				});

				$data = $afterDev->orderBy($this::QA_DATE, 'desc')->get();
        	}else{
				$dev->where(function($q) use($search){
					$q->where($this::DEVICE_NAME,'like','%'.$search.'%')
						->orWhere('companies.name','like','%'.$search.'%')
						->orWhere('devices.mark','like','%'.$search.'%')
						->orWhere($this::DEVICE_MOD,'like','%'.$search.'%');
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
			'SIUPP Perusahaan',
			'Tgl. SIUPP Perusahaan',
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
		$logs = new Logs;
		$currentUser = Auth::user();
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "Download Data Perangkat Tidak Lulus Uji";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Perangkat Tidak Lulus Uji";
        $logs->save();
        // Generate and return the spreadsheet
        Excel::create('Data Perangkat Tidak Lulus Uji', function($excel) use ($examsArray) {

            // Set the spreadsheet title, creator, and description
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }

    public function moveData($id,$reason = null)
    {
        $currentUser = Auth::user();
        $logs_devicenc = NULL;

        $devicenc = Device::find($id);

        if($devicenc){
        	$devicenc->status = '-1';

        	try{
        		$devicenc->save();
        		$logs_devicenc = $devicenc;
            
	            $logs = new Logs_administrator;
	            $logs->id = Uuid::uuid4();
	            $logs->user_id = $currentUser->id;
	            $logs->action = "Memindahkan Data Perangkat Menjadi Layak Uji Ulang";
	            $logs->page = "Perangkat Tidak Lulus Uji";
	            $logs->reason = urldecode($reason);
	            $logs->data = $logs_devicenc;
	            $logs->save();

	            Session::flash('message', 'Successfully Move Data');
	            return redirect($this::ADMIN);
        	} catch(Exception $e){
	            Session::flash('error', 'Move failed');
	            return redirect($this::ADMIN);
		    }
        }else{
            Session::flash('error', 'Undefined Data');
            return redirect($this::ADMIN);
        }

    }
}