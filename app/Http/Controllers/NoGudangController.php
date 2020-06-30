<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\Equipment;
use App\EquipmentHistory;
use App\Examination;
use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\Logs;

use App\Services\Logs\LogService;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class NoGudangController extends Controller
{
    private const SEARCH = 'search';
    private const BEFORE_DATE = 'before_date';
    private const AFTER_DATE = 'after_date';
    private const NOGUDANG = 'nogudang';
    private const COMPANY = 'company';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';

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

        if (!$currentUser){return false;}

        $message = null;
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));

        $sort_by = 'no';
        $sort_type = 'desc';

        $examType = ExaminationType::all();
        $companies = Company::where('id','!=', 1)->get();
        $examLab = ExaminationLab::all();

        // Buat query awal
        $query = $this->intialQuery();

        //Get noGudang
        $nogudang = $query->get();
        
        // tambah filter search ke query kalau ada
        $fileredSearch = $this->filterSearch($search, $query);
        $query = $fileredSearch->$query;

        // Masukan dalam log kalau user mencoba mencari sesuatu
        if (!$fileredSearch->isNull){
            // Create log
            $logService = new LogService;
            $logService->createLog('search', 'Rekap Nomor Gudang', json_encode(array(self::SEARCH => $search)));
        }
        
        /// tambah filter before_date ke query kalau ada
        $filteredBeforeDate = $this->filterBeforeDate($request, $query);
        $query = $filteredBeforeDate->query;
        $before = $filteredBeforeDate->before;

        /// tambah filter after_date ke query kalau ada
        $filteredAfterDate = $this->filterAfterDate($request, $query);
        $query = $filteredAfterDate->query;
        $after = $filteredAfterDate->after;

        /// tambah filter no_gudang ke query kalau ada
        $filteredNoGudang = $this->filterNoGudang($request, $query);
        $query = $filteredNoGudang->query;
        $filterNoGudang = $filteredNoGudang->noGudang;

        /// Tambah filter type  ke query kalau ada
        $filterType = $this->filterType($request, $query);
        $query = $filterType->query;
        $type = $filterType->type;

        /// Tambah filter company ke query kalau ada
        $filteredCompany = $this->filterType($request, $query);
        $query = $filteredCompany->query;
        $filterCompany = $filteredCompany->filterCompany;

        /// Tambah filter lab ke query kalau ada
        $filteredLab = $this->filterLab($request, $query);
        $query = $filteredLab->query;
        $lab = $filteredLab->lab;
        
        /// Geting data with filter sort_by, sort_type & Order By
        $data = $this->getDataSortAndOrdered($request, $query, $paginate);
        
        if (count($data) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.nogudang.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with(self::BEFORE_DATE, $before)
            ->with(self::AFTER_DATE, $after)
            ->with(self::NOGUDANG, $nogudang)
            ->with('filterNoGudang', $filterNoGudang)
            ->with('type', $examType)
            ->with('filterType', $type)
            ->with(self::COMPANY, $companies)
            ->with('filterCompany', $filterCompany)
            ->with('lab', $examLab)
            ->with('filterLab', $lab)
            ->with(self::SORT_BY, $sort_by)
            ->with(self::SORT_TYPE, $sort_type);
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input(self::SEARCH));

        // Buat query awal
        $query = $this->intialQuery();

        // tambah filter search ke query kalau ada
        $fileredSearch = $this->filterSearch($search, $query);
        $query = $fileredSearch->$query;

        /// tambah filter before_date ke query kalau ada
        $filteredBeforeDate = $this->filterBeforeDate($request, $query);
        $query = $filteredBeforeDate->query;

        /// tambah filter after_date ke query kalau ada
        $filteredAfterDate = $this->filterAfterDate($request, $query);
        $query = $filteredAfterDate->query;

        /// tambah filter no_gudang ke query kalau ada
        $filteredNoGudang = $this->filterNoGudang($request, $query);
        $query = $filteredNoGudang->query;

        /// Tambah filter type  ke query kalau ada
        $filterType = $this->filterType($request, $query);
        $query = $filterType->query;

        /// Tambah filter company ke query kalau ada
        $filteredCompany = $this->filterType($request, $query);
        $query = $filteredCompany->query;

        /// Tambah filter lab ke query kalau ada
        $filteredLab = $this->filterLab($request, $query);
        $query = $filteredLab->query;
        
        /// Geting data with filter sort_by, sort_type & Order By
        $data = $this->getDataSortAndOrdered($request, $query, $paginate);

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'Tipe Pengujian',
            'Nomor Gudang',
            'Nama Perusahaan',
            'Nama Perangkat',
            'Merk/Pabrik Perangkat',
            'Model/Tipe Perangkat',
            'Kapasitas/Kecepatan Perangkat',
            'Tanggal Masuk',
            'Tanggal Keluar'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            $no = $row->no;
            $examType_name = $this->filterDefault($row->examType_name);
            $examType_desc = $this->filterDefault($row->examType_desc);
            $company_name = $this->filterDefault($row->company_name);
            /*Device*/
            $device_name = $this->filterDefault($row->device_name);
            $device_mark = $this->filterDefault($row->device_mark);
            $device_capacity = $this->filterDefault($row->device_capacity);
            $device_model = $this->filterDefault($row->device_model);
            /*EndDevice*/

            $tgl_masuk_barang = date("d-m-Y", strtotime($row->tgl_masuk_barang));
            $tgl_keluar_barang = $row->tgl_keluar_barang ? date("d-m-Y", strtotime($row->tgl_keluar_barang)) : '';
            
            $examsArray[] = [
                "".$examType_name." (".$examType_desc.")",
                $no,
                $company_name,
                $device_name,
                $device_mark,
                $device_capacity,
                $device_model,
                $tgl_masuk_barang,
                $tgl_keluar_barang
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Rekap Nomor Gudang";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data Gudang', function($excel) use ($examsArray) {

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }

    private function filterDefault($string, $is_number = false)
    {
        $is_number? $defaultValue = '' : '0';
        return isset($string)? $string : $defaultValue ;
    }

    private function intialQuery(){
        $select = array(
            "equipments.no",
            "examination_types.name as examType_name",
            "examination_types.description as examType_desc",
            "companies.name as company_name",
            "devices.name as device_name",
            "devices.mark as device_mark",
            "devices.capacity as device_capacity",
            "devices.model as device_model",
            "(
                SELECT
                    action_date AS tgl_masuk_gudang
                FROM
                    equipment_histories
                WHERE
                    examination_id = examinations.id
                AND
                    location = 2
                ORDER BY
                    created_at ASC
                LIMIT 1
            ) AS tgl_masuk_barang",
            "(
                SELECT
                    action_date AS tgl_keluar_gudang
                FROM
                    equipment_histories
                WHERE
                    examination_id = examinations.id
                AND
                    location = 1
                ORDER BY
                    created_at DESC
                LIMIT 1
            ) AS tgl_keluar_barang",
        );
        return Equipment::selectRaw(implode(",", $select))  
                ->join("examinations","examinations.id","=","equipments.examination_id")
                ->join("examination_types","examination_types.id","=","examinations.examination_type_id")
                ->join("devices","devices.id","=","examinations.device_id")
                ->join("users","users.id","=","examinations.created_by")
                ->join("companies","companies.id","=","users.company_id")  
                ->whereNotNull('equipments.no');

    }

    private function filterSearch($search, $query){
        $isNull = true;
        $result = new stdClass();
        
        if ($search != null){
            $isNull = false;
            $query->where(DB::raw('companies.name'), 'like', '%'.strtolower($search).'%')
            ->orWhere(DB::raw('devices.name'), 'like', '%'.strtolower($search).'%')
            ->orWhere(DB::raw('no'), 'like', '%'.strtolower($search).'%');
        }
        
        $result->isNull = $isNull;
        $result->query = $query;
        return $result;
    }

    private function filterBeforeDate($request, $query){
        $result = new stdClass();
        $before = null;

        if ($request->has(self::BEFORE_DATE)){
            $query->where(DB::raw('(
                SELECT
                    action_date AS tgl_masuk_gudang
                FROM
                    equipment_histories
                WHERE
                    examination_id = examinations.id
                AND location = 2
                ORDER BY
                    created_at ASC
                LIMIT 1
            )'), '<=', $request->get(self::BEFORE_DATE));
            $before = $request->get(self::BEFORE_DATE);
        }

        $result->query = $query;
        $result->$before = $before;
        return $result;
    }

    private function filterAfterDate($request, $query){
        $result = new stdClass();
        $after = null;

        if ($request->has(self::AFTER_DATE)){
            $query->where(DB::raw('(
                SELECT
                    action_date AS tgl_masuk_gudang
                FROM
                    equipment_histories
                WHERE
                    examination_id = examinations.id
                AND location = 2
                ORDER BY
                    created_at ASC
                LIMIT 1
            )'), '>=', $request->get(self::AFTER_DATE));
            $after = $request->get(self::AFTER_DATE);
        }

        $result->query = $query;
        $result->$after = $after;
        return $result;
    }

    private function filterNoGudang($request, $query){
        $result = new stdClass();
        $noGudang = '';

        if ($request->has(self::NOGUDANG)){
            $noGudang = $request->get(self::NOGUDANG);
            if($request->input(self::NOGUDANG) != 'all'){
                $query->where('no', $request->get(self::NOGUDANG));
            }
        }

        $result->query = $query;
        $result->$noGudang = $noGudang;
        return $result;
    }

    private function filterType($request, $query){
        $result = new stdClass();
        $type = '';

        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where('examination_type_id', $request->get('type'));
            }
        }

        $result->query = $query;
        $result->$type = $type;
        return $result;
    }

    private function filterCompany($request, $query){
        $result = new stdClass();
        $filterCompany = '';

        if ($request->has(self::COMPANY)){
            $filterCompany = $request->get(self::COMPANY);
            if($request->input(self::COMPANY) != 'all'){
                $query->where('companies.name', $request->get(self::COMPANY));
            }
        }

        $result->query = $query;
        $result->$filterCompany = $filterCompany;
        return $result;
    }

    private function filterLab($request, $query){
        $result = new stdClass();
        $lab = '';

        if ($request->has('lab')){
            $lab = $request->get('lab');
            if($request->input('lab') != 'all'){
                $query->where('examination_lab_id', $request->get('lab'));
            }
        }

        $result->query = $query;
        $result->$lab = $lab;
        return $result;
    }

    private function getDataSortAndOrdered($request, $query, $paginate){
        $sort_by = 'no';
        $sort_type = 'desc';

        if ($request->has(self::SORT_BY)){
            $sort_by = $request->get(self::SORT_BY);
        }
        if ($request->has(self::SORT_TYPE)){
            $sort_type = $request->get(self::SORT_TYPE);
        }

        return $query->orderBy($sort_by, $sort_type)
                    ->paginate($paginate);
    }

}
