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

use Auth;
use Session;
use Excel;

class DeviceController extends Controller
{
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

        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
			$datenow = date('Y-m-d');
            $before = null;
            $after = null;
            
            if ($search != null){
                $dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select(
						'examinations.price AS totalBiaya',
						'examinations.spk_code',
						'examinations.jns_perusahaan',
						'companies.name AS namaPerusahaan',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.valid_from',
						'devices.valid_thru',
						'devices.serial_number',
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
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
				->where(function($q) use($search){
					$q->where('devices.name','like','%'.$search.'%')
						->orWhere('companies.name','like','%'.$search.'%')
						->orWhere('devices.mark','like','%'.$search.'%')
						->orWhere('devices.model','like','%'.$search.'%');
				});
				
				if ($request->has('before_date')){
					$dev->where('devices.valid_thru', '<=', $request->get('before_date'));
					$before = $request->get('before_date');
				}

				if ($request->has('after_date')){
					$dev->where('devices.valid_thru', '>=', $request->get('after_date'));
					$after = $request->get('after_date');
				}

				$logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search Device";  
                $dataSearch = array("search"=>$search); 
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "DEVICE";
                $logs->save();
				
				$data_excel = $dev->orderBy('devices.valid_thru', 'desc')->get();
				$data = $dev->paginate($paginate);
            }else{
				$dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select(
						'examinations.price AS totalBiaya',
						'examinations.spk_code',
						'examinations.jns_perusahaan',
						'companies.name AS namaPerusahaan',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.valid_from',
						'devices.valid_thru',
						'devices.manufactured_by',
						'devices.serial_number',
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
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow);

				if ($request->has('before_date')){
					$dev->where('devices.valid_thru', '<=', $request->get('before_date'));
					$before = $request->get('before_date');
				}

				if ($request->has('after_date')){
					$dev->where('devices.valid_thru', '>=', $request->get('after_date'));
					$after = $request->get('after_date');
				}

				$data_excel = $dev->orderBy('devices.valid_thru', 'desc')->get();
				$data = $dev->paginate($paginate);
            }
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
			
            $request->session()->put('excel_pengujian_sukses', $data_excel);
			
            return view('admin.devices.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
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
		
		$data = $request->session()->get('excel_pengujian_sukses');
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'No',
			'Jenis Perusahaan',
			'Nama Perusahaan',
			'Alamat Perusahaan',
			'Email Perusahaan',
			'Telepon Perusahaan',
			'Faksimil Perusahaan',
			'NPWP Perusahaan',
			'SIUPP Perusahaan',
			'Tgl. SIUPP Perusahaan',
			'Sertifikat Perusahaan',
			'Tgl. Sertifikat Perusahaan',
			'Nama Perangkat',
			'Merk/Pabrik',
			'Tipe',
			'Kapasitas/Kecepatan',
			'Pembuat Perangkat',
			'Nomor Seri Perangkat',
			'Referensi Uji',
			'Berlaku Dari',
			'Berlaku Sampai',
			'Kode SPK',
			'Total Biaya'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
			$no = 1;
		foreach ($data as $row) {
			if($row->siup_date==''){
				$siup_date = '';
			}else{
				$siup_date = date("d-m-Y", strtotime($row->siup_date));
			}
			if($row->qs_certificate_date==''){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date("d-m-Y", strtotime($row->qs_certificate_date));
			}
			$examsArray[] = [
				$no,
				$row->jns_perusahaan,
				$row->namaPerusahaan,
				$row->address.", kota".$row->city.", kode pos".$row->postal_code,
				$row->email,
				$row->phone_number,
				$row->fax,
				$row->npwp_number,
				$row->siup_number,
				$siup_date,
				$row->qs_certificate_number,
				$qs_certificate_date,
				$row->namaPerangkat,
				$row->merk,
				$row->tipe,
				$row->kapasitas,
				$row->manufactured_by,
				$row->serial_number,
				$row->standarisasi,
				$row->valid_from,
				$row->valid_thru,
				$row->spk_code,
				$row->totalBiaya
			];
			$no++;
		}

		$logs = new Logs;
		$currentUser = Auth::user();
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "Download Devices";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "DEVICE";
        $logs->save();

		// Generate and return the spreadsheet
		Excel::create('Data Perangkat Lulus Uji', function($excel) use ($examsArray) {

			// Set the spreadsheet title, creator, and description
			// $excel->setTitle('Payments');
			// $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
			// $excel->setDescription('payments file');

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx');
	}
}