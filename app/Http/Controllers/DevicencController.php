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

class DevicencController extends Controller
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
				->where('examinations.examination_type_id','=','1')
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','-1')
				->where('examinations.certificate_status','=','1')
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
				->where('examinations.examination_type_id','=','1')
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','-1')
				->where('examinations.certificate_status','=','1');

				if ($request->has('before_date')){
					$dev->where('devices.valid_thru', '<=', $request->get('before_date'));
					$before = $request->get('before_date');
				}

				if ($request->has('after_date')){
					$dev->where('devices.valid_thru', '>=', $request->get('after_date'));
					$after = $request->get('after_date');
				}

				$data = $dev->paginate($paginate);
            }
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.devicenc.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('before_date', $before)
                ->with('after_date', $after);
        }
    }
}