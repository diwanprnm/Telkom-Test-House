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
	
	private const TABLE_DEVICE = 'devices';
	private const EXAM_DEVICES_ID = 'examinations.device_id';
	private const DEVICES_ID = 'devices.id';
	private const TABLE_COMPANIES = 'companies';
	private const EXAM_COMPANY_ID = 'examinations.company_id';
	private const COMPANIES_ID = 'companies.id';
	private const COMPANY_AUTOSUGGEST = 'companies.name as autosuggest';
	private const EXAM_CERTIFICATE_STATUS = 'examinations.certificate_status';
	private const DEVICES_VALID_THRU = 'devices.valid_thru';
	private const COMPANIES_NAME = 'companies.name';
	private const DEVICE_NAME_AUTOSUGGEST = 'devices.name as autosuggest';
	private const DEVICE_NAME = 'devices.name';
	private const DEVICE_MARK = 'devices.mark';
	private const DEVICE_MODEL = 'devices.model';
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
            $select = ['companies.name AS namaPerusahaan',
						'devices.name AS namaPerangkat',
						'devices.mark AS merk',
						'devices.manufactured_by',
						'devices.model AS tipe',
						'devices.capacity AS kapasitas',
						'devices.test_reference AS standarisasi',
						'devices.cert_number',
						'devices.valid_from',
						self::DEVICE_DOT_VALID_THRU];
            if ($search != null){
                $dev = DB::table('examinations')
				->join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
				->select($select)
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICE_DOT_VALID_THRU, '>=', $datenow)
				->where(function($query) use ($search){
					$query->where('companies.name', 'LIKE', '%'.$search.'%');
					$query->orWhere('devices.name', 'LIKE', '%'.$search.'%');
					$query->orWhere(self::DEVICE_MARK, 'LIKE', '%'.$search.'%');
					$query->orWhere(self::DEVICE_MODEL, 'LIKE', '%'.$search.'%');
				})

				->orderBy(self::DEVICE_DOT_VALID_THRU, 'desc')
				->paginate($paginate);

            }else{
				$dev = DB::table('examinations')
				->join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
				->select($select)
				->where('examinations.resume_status','=','1')
				->where('examinations.qa_status','=','1')
				->where('examinations.qa_passed','=','1')
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
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
        $datenow = date('Y-m-d');
		
        $data1 = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::COMPANY_AUTOSUGGEST)
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where(self::COMPANIES_NAME, 'like','%'.$query.'%')
				->orderBy(self::COMPANIES_NAME)
                ->take(2)
				->distinct()
                ->get();
		$data2 = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(2)
				->distinct()
                ->get();
		$data3 = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.mark as autosuggest')
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where(self::DEVICE_MARK, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_MARK)
                ->take(2)
				->distinct()
                ->get();
		$data4 = Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
				->join(self::TABLE_COMPANIES, self::EXAM_COMPANY_ID, '=', self::COMPANIES_ID)
                ->select('devices.model as autosuggest')
				->where(self::EXAM_CERTIFICATE_STATUS,'=','1')
				->where(self::DEVICES_VALID_THRU, '>=', $datenow)
                ->where(self::DEVICE_MODEL, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_MODEL)
                ->take(2)
				->distinct()
                ->get();
		
		if( is_array($data1) && is_array($data2) && is_array($data3) &&is_array($data4) ){ return array_merge($data1,$data2,$data3,$data4); }
		return null;
    }
}
