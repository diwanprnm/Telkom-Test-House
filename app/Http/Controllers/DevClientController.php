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
use Ramsey\Uuid\Uuid;

use Auth;
use Session;

class DevClientController extends Controller
{

	private const DEVICE_DOT_VALID_THRU = 'devices.valid_thru';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
			$datenow = date('Y-m-d');
            $select = "'companies.name AS namaPerusahaan',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.manufactured_by',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.cert_number',
						'devices.valid_from',
						".self::DEVICE_DOT_VALID_THRU;
            if ($search != null){
                $dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select($select)
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where('examinations.certificate_status','=','1')
				->where(self::DEVICE_DOT_VALID_THRU, '>=', $datenow)
				->where(function($query) use ($search){
					$query->where('companies.name', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.name', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.mark', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.model', 'LIKE', '%'.$search.'%');
				})

				->orderBy(self::DEVICE_DOT_VALID_THRU, 'desc')
				->paginate($paginate);

            }else{
				$dev = DB::table('examinations')
				->join('devices', 'examinations.device_id', '=', 'devices.id')
				->join('companies', 'examinations.company_id', '=', 'companies.id')
				->select($select)
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where('examinations.certificate_status','=','1')
				->where(self::DEVICE_DOT_VALID_THRU, '>=', $datenow)
				->orderBy(self::DEVICE_DOT_VALID_THRU, 'desc')
				->paginate($paginate);
            }
            if (count($dev) == 0){
                $message = 'Data not found';
            }
            $page = 'Devclient';
            return view('client.devices.index')
                ->with('message', $message)
                ->with('data', $dev)
                ->with('page', $page)
                ->with('search', $search);
    }
	
	public function autocomplete($query) {
        $respons_result = Examination::autocomplet($query);
        return response($respons_result);
    }
}
