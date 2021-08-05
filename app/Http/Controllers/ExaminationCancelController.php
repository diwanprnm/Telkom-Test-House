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
class ExaminationCancelController extends Controller
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
		
		$dev = DB::table('examinations')
		->join('devices', 'examinations.device_id', '=', 'devices.id')
		->join('companies', 'examinations.company_id', '=', 'companies.id')
		->select(
				'examinations.price AS totalBiaya','examinations.spk_code','examinations.jns_perusahaan','companies.name AS namaPerusahaan',
				'devices.id AS deviceId','devices.name AS namaPerangkat','devices.mark AS merk','devices.model AS tipe',
				'devices.capacity AS kapasitas','devices.test_reference AS standarisasi','devices.manufactured_by','devices.serial_number','devices.cert_number',
				'companies.address','companies.city','companies.postal_code','companies.email','companies.phone_number','companies.fax',
				'companies.npwp_number','companies.siup_number','companies.siup_date','companies.qs_certificate_number',
				'companies.qs_certificate_date','examinations.reason_cancel'
				)
		->where('examinations.is_cancel','=','1');

		if ($search){
			$dev->where(function($q) use($search){
				$q->where('devices.name','like','%'.$search.'%')
					->orWhere('devices.mark','like','%'.$search.'%')
					->orWhere('devices.model','like','%'.$search.'%')
					->orWhere('companies.name','like','%'.$search.'%');
			});
			
			$logService->createLog('Search Device', 'Customer Request', json_encode(array("search"=>$search)) );
		}

		$data = $dev->orderBy('examinations.created_at', 'desc')->paginate($paginate);

		if (count($data) == 0){
			$message = 'Data not found';
		}
		
		return view('admin.examinationcancel.index')
			->with('message', $message)
			->with('data', $data)
			->with($this::SEARCH, $search);
    }
	
	public function edit($id)
    {
        
    }

	public function update(Request $request, $id)
	{
		
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