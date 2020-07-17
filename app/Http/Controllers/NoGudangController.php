<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use Excel;
use Storage;

use App\Equipment;
use App\EquipmentHistory;
use App\Examination;
use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\Logs;

use App\Services\Querys\QueryFilter;
use App\Services\MyHelper;
use App\Services\Logs\LogService;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class NoGudangController extends Controller
{
    private const SEARCH = 'search';
    private const QUERY = 'query';
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
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));
        $sort_by = 'no';
        $sort_type = 'desc';

        $examType = ExaminationType::all();
        $examLab = ExaminationLab::all();
        $companies = Company::where('id','!=', 1)->get();

        // Buat query awal
        $query = $this->intialQuery();

        //Get noGudang
        $nogudang = $query->get();
        
        // tambah filter search ke query kalau ada
        $fileredSearch = $this->filterSearch($search, $query);
        $query = $fileredSearch[self::QUERY];
        // Masukan dalam log kalau user mencoba mencari sesuatu
        if (!$fileredSearch['isNull']){
            // Create log
            $logService = new LogService;
            $logService->createLog('search', 'Rekap Nomor Gudang', json_encode(array(self::SEARCH => $search)));
        }
        
        //Filter Query
        $queryFilter = new QueryFilter($request, $query);
        $queryFilter
            ->afterDate($this->getDate())
            ->beforeDate($this->getDate())
            ->noGudang()
            ->examination_type()
            ->examination_lab()
            ->companyName()
        ;

        //Get data from query
        $data = $queryFilter
            ->getSortedAndOrderedData($sort_by, $sort_type)
            ->getQuery()
            ->paginate($paginate)
        ;
        
        return view('admin.nogudang.index')
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with(self::BEFORE_DATE, $queryFilter->before)
            ->with(self::AFTER_DATE, $queryFilter->after)
            ->with('filterNoGudang', $queryFilter->noGudang)
            ->with('filterCompany', $queryFilter->companyName)
            ->with('filterLab', $queryFilter->examination_lab)
            ->with('filterType', $queryFilter->examination_type)
            ->with(self::SORT_BY, $queryFilter->sort_by)
            ->with(self::SORT_TYPE, $queryFilter->sort_type)
            ->with('type', $examType)
            ->with('lab', $examLab)
            ->with(self::NOGUDANG, $nogudang)
            ->with(self::COMPANY, $companies);
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
        $query = $fileredSearch[self::QUERY];

        //Filter Query
        $queryFilter = new QueryFilter($request, $query);
        $queryFilter
            ->afterDate($this->getDate())
            ->beforeDate($this->getDate())
            ->noGudang()
            ->examination_type()
            ->examination_lab()
            ->companyName()
        ;

        //Get data from query
        $data = $queryFilter
            ->getSortedAndOrderedData('no', 'desc')
            ->getQuery()
            ->get()
        ;

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
            $examType_name = MyHelper::filterDefault($row->examType_name);
            $examType_desc = MyHelper::filterDefault($row->examType_desc);
            $company_name = MyHelper::filterDefault($row->company_name);
            /*Device*/
            $device_name = MyHelper::filterDefault($row->device_name);
            $device_mark = MyHelper::filterDefault($row->device_mark);
            $device_capacity = MyHelper::filterDefault($row->device_capacity);
            $device_model = MyHelper::filterDefault($row->device_model);
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

        $logService = new LogService;
        $logService->createLog('download_excel', 'Rekap Nomor Gudang', '' );

        // Generate and return the spreadsheet
        Excel::create('Data Gudang', function($excel) use ($examsArray) {

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->store('xlsx');

        $file = Storage::disk('tmp')->get('Data Gudang.xlsx');

        $headers = [
            'Content-Type' => 'Application/Spreadsheet',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=Data Gudang.xlsx",
            'filename'=> 'Data Gudang.xlsx'
        ];
        return response($file, 200, $headers);
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
        
        if ($search != null){
            $isNull = false;
            $query->where(DB::raw('companies.name'), 'like', '%'.strtolower($search).'%')
                ->orWhere(DB::raw('devices.name'), 'like', '%'.strtolower($search).'%')
                ->orWhere(DB::raw('no'), 'like', '%'.strtolower($search).'%');
        }
        
        return array(
            self::QUERY => $query,
            'isNull' => $isNull
        );
    }

    private function getDate()
    {
        return DB::raw('(
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
        )');
    }

}
