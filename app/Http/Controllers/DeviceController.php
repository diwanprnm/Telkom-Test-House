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
			$dateMin1Year = date('Y-m-d', strtotime('-1 year'));
			$before = null;
            $after = null;
            $category = null;
            
            if ($search != null){
                $dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select(
						'examinations.price AS totalBiaya',
						'examinations.spk_code',
						'examinations.jns_perusahaan',
						'companies.name AS namaPerusahaan',
						'devices.id AS deviceId',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.valid_from',
						'devices.valid_thru',
						'devices.manufactured_by',
						'devices.serial_number',
						'devices.cert_number',
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
				/*->where(function($q) use($datenow,$dateMin1Year){
					$q->where('devices.valid_thru', '>=', $datenow)
						->orWhere('devices.valid_thru', '>=', $dateMin1Year);
				})*/
				->where(function($q) use($search){
					$q->where('devices.name','like','%'.$search.'%')
						->orWhere('companies.name','like','%'.$search.'%')
						->orWhere('devices.mark','like','%'.$search.'%')
						->orWhere('devices.model','like','%'.$search.'%');
				});
				
				if ($request->has('before_date')){
					$dev->where('devices.valid_thru', '>=', $request->get('before_date'));
					$before = $request->get('before_date');
				}

				if ($request->has('after_date')){
					$dev->where('devices.valid_thru', '<=', $request->get('after_date'));
					$after = $request->get('after_date');
				}

				if ($request->has('category')){
					switch ($request->get('category')) {
						case 'aktif':
							$dev->where('devices.valid_thru', '>=', $datenow);
							break;
						
						case 'aktif1':
							$dev->where('devices.valid_thru', '>=', $dateMin1Year);
							$dev->where('devices.valid_thru', '<', $datenow);
							break;
						
						default:
							$dev->where(function($q) use($datenow,$dateMin1Year){
								$q->where('devices.valid_thru', '>=', $datenow)
									->orWhere('devices.valid_thru', '>=', $dateMin1Year);
							});
							break;
					}
					$category = $request->get('category');
				}
				else{
					$dev->where('devices.valid_thru', '>=', $datenow);
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
				$data = $dev->orderBy('devices.valid_thru', 'desc')->paginate($paginate);
            }else{
				$dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select(
						'examinations.price AS totalBiaya',
						'examinations.spk_code',
						'examinations.jns_perusahaan',
						'companies.name AS namaPerusahaan',
						'devices.id AS deviceId',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.valid_from',
						'devices.valid_thru',
						'devices.manufactured_by',
						'devices.serial_number',
						'devices.cert_number',
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
				->where('examinations.certificate_status','=','1');
				/*->where(function($q) use($datenow,$dateMin1Year){
					$q->where('devices.valid_thru', '>=', $datenow)
						->orWhere('devices.valid_thru', '>=', $dateMin1Year);
				});*/

				if ($request->has('before_date')){
					$dev->where('devices.valid_thru', '>=', $request->get('before_date'));
					$before = $request->get('before_date');
				}

				if ($request->has('after_date')){
					$dev->where('devices.valid_thru', '<=', $request->get('after_date'));
					$after = $request->get('after_date');
				}

				if ($request->has('category')){
					switch ($request->get('category')) {
						case 'aktif':
							$dev->where('devices.valid_thru', '>=', $datenow);
							break;
						
						case 'aktif1':
							$dev->where('devices.valid_thru', '>=', $dateMin1Year);
							$dev->where('devices.valid_thru', '<', $datenow);
							break;
						
						default:
							$dev->where(function($q) use($datenow,$dateMin1Year){
								$q->where('devices.valid_thru', '>=', $datenow)
									->orWhere('devices.valid_thru', '>=', $dateMin1Year);
							});
							break;
					}
					$category = $request->get('category');
				}else{
					$dev->where('devices.valid_thru', '>=', $datenow);
				}

				$data_excel = $dev->orderBy('devices.valid_thru', 'desc')->get();
				$data = $dev->orderBy('devices.valid_thru', 'desc')->paginate($paginate);
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
                ->with('category', $category)
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
			/*if($row->siup_date==''){
				$siup_date = '';
			}else{
				$siup_date = date("d-m-Y", strtotime($row->siup_date));
			}
			if($row->qs_certificate_date==''){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date("d-m-Y", strtotime($row->qs_certificate_date));
			}*/
			if($row->valid_thru >= date('Y-m-d')){
				$category = 'Aktif';
			}else{
				$category = 'Aktif + 1';
			}
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
        if ($request->has('manufactured_by')){
            $device->manufactured_by = $request->input('manufactured_by');
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

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Perangkat Lulus Uji";
            $logs->data = $oldDevice;
            $logs->created_by = $currentUser->id;
            $logs->page = "Perangkat Lulus Uji";
            $logs->save();

            Session::flash('message', 'Perangkat Lulus Uji successfully updated');
            return redirect('/admin/device');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/device/'.$device->id.'/edit');
        }
	}
}