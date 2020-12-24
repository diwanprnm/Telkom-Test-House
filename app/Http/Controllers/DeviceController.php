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
use Ramsey\Uuid\Uuid;
use App\Services\Logs\LogService;
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
	private const SEARCH = 'search';
	private const YMD = 'Y-m-d';
	private const DEVICEVAL= 'devices.valid_thru';
	private const BEFORE = 'before_date';
	private const AFTER = 'after_date';
	private const CATEGORY = 'category';
	private const MANUFACTURE = 'manufactured_by';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$currentUser = Auth::user();
		
		$logService = new LogService();

		if (!$currentUser){ return redirect('login');}

		$message = null;
		$paginate = 10;
		$search = trim($request->input($this::SEARCH));
		$datenow = date($this::YMD);
		$dateMin1Year = date($this::YMD, strtotime('-1 year'));
		$before = null;
		$after = null;
		$category = null;
		
		$dev = DB::table('examinations')
		->join('devices', 'examinations.device_id', '=', 'devices.id')
		->join('companies', 'examinations.company_id', '=', 'companies.id')
		->select(
				'examinations.price AS totalBiaya','examinations.spk_code','examinations.jns_perusahaan','companies.name AS namaPerusahaan',
				'devices.id AS deviceId','devices.name AS namaPerangkat','devices.mark AS merk','devices.model AS tipe',
				'devices.capacity AS kapasitas','devices.test_reference AS standarisasi','devices.valid_from',$this::DEVICEVAL,
				'devices.manufactured_by','devices.serial_number','devices.cert_number',$this::DEVICEVAL,'companies.address',
				'companies.city','companies.postal_code','companies.email','companies.phone_number','companies.fax',
				'companies.npwp_number','companies.siup_number','companies.siup_date','companies.qs_certificate_number',
				'companies.qs_certificate_date'
				)
		->where('examinations.resume_status','=','1')
		->where('examinations.qa_status','=','1')
		->where('examinations.qa_passed','=','1')
		->where('examinations.certificate_status','=','1');

		if ($search){
			$dev->where(function($q) use($search){
				$q->where('devices.name','like','%'.$search.'%')
					->orWhere($this::DEVICEVAL,'like','%'.$search.'%')
					->orWhere('devices.mark','like','%'.$search.'%')
					->orWhere('devices.model','like','%'.$search.'%');
			});
			
			$logService->createLog('Search Device', 'DEVICE', json_encode(array("search"=>$search)) );
		}

		if ($request->has($this::BEFORE)){
			$dev->where($this::DEVICEVAL, '>=', $request->get($this::BEFORE));
			$before = $request->get($this::BEFORE);
		}
		if ($request->has($this::AFTER)){
			$dev->where($this::DEVICEVAL, '<=', $request->get($this::AFTER));
			$after = $request->get($this::AFTER);
		}
		if ($request->has($this::CATEGORY)){
			switch ($request->get($this::CATEGORY)) {
				case 'aktif':
					$dev->where($this::DEVICEVAL, '>=', $datenow);
					break;		
				case 'aktif1':
					$dev->where($this::DEVICEVAL, '>=', $dateMin1Year);
					$dev->where($this::DEVICEVAL, '<', $datenow);
					break;
				default:
					$dev->where(function($q) use($datenow,$dateMin1Year){
						$q->where($this::DEVICEVAL, '>=', $datenow)
							->orWhere($this::DEVICEVAL, '>=', $dateMin1Year);
					});
					break;
			}
			$category = $request->get($this::CATEGORY);
		}
		else{
			$dev->where($this::DEVICEVAL, '>=', $datenow);
		}
		
		$data_excel = $dev->orderBy($this::DEVICEVAL, 'desc')->get();
		$request->session()->put('excel_pengujian_sukses', $data_excel);
		
		$data = $dev->orderBy($this::DEVICEVAL, 'desc')->paginate($paginate);

		if (count($data) == 0){
			$message = 'Data not found';
		}
		
		return view('admin.devices.index')
			->with('message', $message)
			->with('data', $data)
			->with($this::SEARCH, $search)
			->with($this::BEFORE, $before)
			->with($this::CATEGORY, $category)
			->with($this::AFTER, $after);
    }
	
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		$logService = new LogService();
		$data = $request->session()->get('excel_pengujian_sukses');
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
			'Negara Pembuat',
			'Tipe',
			'Kapasitas/Kecepatan',
			// 'Nomor Seri Perangkat',
			'Referensi Uji',
			'No Sertifikat',
			'Berlaku Dari',
			'Berlaku Sampai',
			/*'Nomor SPK',
			'Total Biaya'*/
			'Kategori'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
			$no = 1;
		foreach ($data as $row) {
			$category = $row->valid_thru >= date($this::YMD) ? 'Aktif' : 'Aktif + 1';
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
				$row->manufactured_by,
				$row->tipe,
				$row->kapasitas,
				// $row->serial_number,
				$row->standarisasi,
				$row->cert_number,
				$row->valid_from,
				$row->valid_thru,
				// $row->spk_code,
				// $row->totalBiaya
				$category
			];
			$no++;
		}

		$logService->createLog('Download Devices', 'DEVICE', "" );

        $excel = \App\Services\ExcelService::download($examsArray, 'Data Perangkat Lulus Uji');
        return response($excel['file'], 200, $excel['headers']);
	}

	public function edit($id)
    {
        $device = Device::find($id);

        return view('admin.devices.edit')
            ->with('data', $device)
        ;
    }

	public function update(Request $request, $id)
	{
		$currentUser = Auth::user();
		$logService = new LogService();

        $device = Device::find($id);
        $oldDevice = $device;

        if ($request->has('name')){
            $device->name = $request->input('name');
        }
        if ($request->has('mark')){
            $device->mark = $request->input('mark');
        }
        if ($request->has('capacity')){
            $device->capacity = $request->input('capacity');
        }
        if ($request->has($this::MANUFACTURE)){
            $device->manufactured_by = $request->input($this::MANUFACTURE);
        }
        if ($request->has('serial_number')){
            $device->serial_number = $request->input('serial_number');
        }
        if ($request->has('model')){
            $device->model = $request->input('model');
        }
        if ($request->has('test_reference')){
            $device->test_reference = $request->input('test_reference');
        }
        if ($request->has('cert_number')){
            $device->cert_number = $request->input('cert_number');
        }
        if ($request->has('valid_from')){
            $device->valid_from = $request->input('valid_from');
        }
        if ($request->has('valid_thru')){
            $device->valid_thru = $request->input('valid_thru');
        }

        $device->updated_by = $currentUser->id;
       

        try{
			$device->save();
			
			$logService->createLog('Update Perangkat Lulus Uji', 'SPerangkat Lulus UjiEL', $oldDevice );
			
            Session::flash('message', 'Perangkat Lulus Uji successfully updated');
            return redirect('/admin/device');
        } catch(Exception $e){ return redirect('/admin/device/'.$device->id.'/edit')->with('error', 'Save failed');
        }
	}

	/**
	 * Display a resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
    {
        
    }
}