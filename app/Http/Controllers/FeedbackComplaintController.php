<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\Examination;
use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\Questioner;
use App\Logs;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FeedbackComplaintController extends Controller
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
            $type = '';
            $filterCompany = '';
            $lab = '';

            $sort_by = 'complaint_date';
            $sort_type = 'desc';

            $examType = ExaminationType::all();
            $companies = Company::where('id','!=', 1)->get();
            $examLab = ExaminationLab::all();

            $query = Questioner::with('examination.device')->with('examination.company')->with('user');

            $questioner = $query->get();
            
            if ($search != null){
                    $query->whereHas('examination.company', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.strtolower($search).'%');
                    })->orWhereHas('examination.device', function ($query) use ($search) {
                        $query->orWhere('name', 'like', '%'.strtolower($search).'%');
                    });

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Rekap Feedback dan Complaint";
                $logs->save();
            }
            
            /*if ($request->has('before_date')){
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
                )'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
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
                )'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            if ($request->has('type')){
                $type = $request->get('type');
                if($request->input('type') != 'all'){
                    $query->where('examination_type_id', $request->get('type'));
                }
            }

            if ($request->has('company')){
                $filterCompany = $request->get('company');
                if($request->input('company') != 'all'){
                    $query->where('companies.name', $request->get('company'));
                }
            }

            if ($request->has('lab')){
                $lab = $request->get('lab');
                if($request->input('lab') != 'all'){
                    $query->where('examination_lab_id', $request->get('lab'));
                }
            }
*/
            if ($request->has('sort_by')){
                $sort_by = $request->get('sort_by');
            }
            if ($request->has('sort_type')){
                $sort_type = $request->get('sort_type');
            }

            $data = $query->orderBy($sort_by, $sort_type)
                        ->paginate($paginate);
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.feedbackncomplaint.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('before_date', $before)
                ->with('after_date', $after)
                ->with('type', $examType)
                ->with('filterType', $type)
                ->with('company', $companies)
                ->with('filterCompany', $filterCompany)
                ->with('lab', $examLab)
                ->with('filterLab', $lab)
                ->with('sort_by', $sort_by)
                ->with('sort_type', $sort_type);
        }
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input('search'));
        
        $before = null;
        $after = null;
        $filterNoGudang = '';
        $type = '';
        $filterCompany = '';
        $lab = '';

        $sort_by = 'no';
        $sort_type = 'desc';

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
        $query = Equipment::selectRaw(implode(",", $select))  
                ->join("examinations","examinations.id","=","equipments.examination_id")
                ->join("examination_types","examination_types.id","=","examinations.examination_type_id")
                ->join("devices","devices.id","=","examinations.device_id")
                ->join("users","users.id","=","examinations.created_by")
                ->join("companies","companies.id","=","users.company_id")  
                ->whereNotNull('equipments.no');

        if ($search != null){
            $query->where(DB::raw('companies.name'), 'like', '%'.strtolower($search).'%')
                ->orWhere(DB::raw('devices.name'), 'like', '%'.strtolower($search).'%')
                ->orWhere(DB::raw('no'), 'like', '%'.strtolower($search).'%');
        }

        if ($request->has('before_date')){
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
            )'), '<=', $request->get('before_date'));
            $before = $request->get('before_date');
        }

        if ($request->has('after_date')){
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
            )'), '>=', $request->get('after_date'));
            $after = $request->get('after_date');
        }

        if ($request->has('nogudang')){
            $filterNoGudang = $request->get('nogudang');
            if($request->input('nogudang') != 'all'){
                $query->where('no', $request->get('nogudang'));
            }
        }
        
        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where('examination_type_id', $request->get('type'));
            }
        }

        if ($request->has('company')){
            $filterCompany = $request->get('company');
            if($request->input('company') != 'all'){
                $query->where('companies.name', $request->get('company'));
            }
        }

        if ($request->has('lab')){
            $lab = $request->get('lab');
            if($request->input('lab') != 'all'){
                $query->where('examination_lab_id', $request->get('lab'));
            }
        }

        if ($request->has('sort_by')){
            $sort_by = $request->get('sort_by');
        }
        if ($request->has('sort_type')){
            $sort_type = $request->get('sort_type');
        }

        $data = $query->orderBy($sort_by, $sort_type)->get();

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
            $examType_name = !isset($row->examType_name) ? '' : $row->examType_name;
            $examType_desc = !isset($row->examType_desc) ? '' : $row->examType_desc;
            $no = $row->no;
            $company_name = !isset($row->company_name) ? '' : $row->company_name;
            
            /*Device*/
            $device_name = !isset($row->device_name) ? '' : $row->device_name;
            $device_mark = !isset($row->device_mark) ? '' : $row->device_mark;
            $device_capacity = !isset($row->device_capacity) ? '' : $row->device_capacity;
            $device_model = !isset($row->device_model) ? '' : $row->device_model;
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
