<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use App\Http\Requests;

use App\Examination;
use App\Device;
use App\Company;
use App\Logs;
use Ramsey\Uuid\Uuid;

use Auth;
use Session;

class DevClientController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $currentUser = Auth::user();

        // if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
			$datenow = date('Y-m-d');
			$thisMonth = date('m');
			$thisYear = date('Y');
            
            if ($search != null){
                $dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select(
						'companies.name AS namaPerusahaan',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.manufactured_by',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.cert_number',
						'devices.valid_from',
						'devices.valid_thru'
						)
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where('examinations.certificate_status','=','1')
				->where('devices.valid_thru', '>=', $datenow)
				// ->where('devices.is_active','=','1')
				->where(function($query) use ($search){
					$query->where('companies.name', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.name', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.mark', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.model', 'LIKE', '%'.$search.'%');
				})
				// ->orderBy('examinations.updated_at', 'desc') 

				->orderBy('devices.valid_thru', 'desc')
				->paginate($paginate);

//					$logs = new Logs;
//                $currentUser = Auth::user();
               // $logs->user_id = $currentUser->id;
                //$logs->id = Uuid::uuid4();
                //$logs->action = "Search Certified Device";
                //$datasearch = array("search"=>$search);
                //$logs->data = json_encode($datasearch);
                //$logs->created_by = $currentUser->id;
                //$logs->page = "CERTIFIED DEVICE";
                //$logs->save();
            }else{
				$dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select(
						'companies.name AS namaPerusahaan',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.cert_number',
						'devices.valid_from',
						'devices.valid_thru'
						)
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where('examinations.certificate_status','=','1')
				// ->whereYear('devices.valid_thru', '>=', $thisYear)
				// ->whereMonth('devices.valid_thru', '>=', $thisMonth)
				->where('devices.valid_thru', '>=', $datenow)
				// ->where('devices.is_active','=','1')
				// ->orderBy('examinations.updated_at', 'desc')
				->orderBy('devices.valid_thru', 'desc')
				->paginate($paginate);
            }
            // print_r($dev);exit;
            if (count($dev) == 0){
                $message = 'Data not found';
            }
            $page = 'Devclient';
            return view('client.devices.index')
                ->with('message', $message)
                ->with('data', $dev)
                ->with('page', $page)
                ->with('search', $search);
        // }
    }
	
	public function autocomplete($query) {
        $respons_result = Examination::autocomplet($query);
        return response($respons_result);
    }
}
