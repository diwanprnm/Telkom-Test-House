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

            $sort_by = 'questioner_date';
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
        $type = '';
        $filterCompany = '';
        $lab = '';

        $sort_by = 'questioner_date';
        $sort_type = 'desc';

        $query = Questioner::with('examination.device')->with('examination.company')->with('examination.questionerdynamic')->with('examination.examinationType')->with('user');

        $questioner = $query->get();
        
        if ($search != null){
            $query->whereHas('examination.company', function ($query) use ($search) {
                $query->where('name', 'like', '%'.strtolower($search).'%');
            })->orWhereHas('examination.device', function ($query) use ($search) {
                $query->orWhere('name', 'like', '%'.strtolower($search).'%');
            });
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
            'No',
            'Tanggal',
            'Nama Responden',
            'Nama Perusahaan',
            'No. Telp/HP',
            'Jenis Pengujian',
            'Nama Perangkat',
            'Tingkat Kepentingan',
        ]; 

        $merge_value = 0;
        for ($i=0; $i<count($data[0]->examination->questionerdynamic); $i++) {
            if ($i>0) {
                $data[0]->examination->questionerdynamic[$i]->is_essay == 0 ? array_push($examsArray[0], '') : '';
                $merge_value++;
            } 
        }
        array_push($examsArray[0], 'Tingkat Kepuasan');
        $examsArray[1] = [];
        array_push($examsArray[1], 
            '','','','','','','',
            'T1','T2','T3','T4',
            'A1','A2','A3','A4','A5',
            'R1','R2','R3','R4','R5','R6',
            'E1','E2','E3','E4','E5',
            'RP1','RP2','RP3','RP4','RP5',
            'T1','T2','T3','T4',
            'A1','A2','A3','A4','A5',
            'R1','R2','R3','R4','R5','R6',
            'E1','E2','E3','E4','E5',
            'RP1','RP2','RP3','RP4','RP5'
        );
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        $no = 3;
        foreach ($data as $row) {
            $questioner_date = !isset($row->questioner_date) ? '' : $row->questioner_date;
            $user_name = !isset($row->user->name) ? '' : $row->user->name;
            $company_name = !isset($row->examination->company->name) ? '' : $row->examination->company->name;
            $phone_number = !isset($row->user->phone_number) ? '' : $row->user->phone_number;
            $examType_name = !isset($row->examination->examinationType->name) ? '' : $row->examination->examinationType->name;
            $examType_desc = !isset($row->examination->examinationType->description) ? '' : $row->examination->examinationType->description;
            $device_name = !isset($row->examination->device->name) ? '' : $row->examination->device->name;
                        
            $examsArray[] = [
                $no-2,
                $questioner_date,
                $user_name,
                $company_name,
                $phone_number,
                "".$examType_name." (".$examType_desc.")",
                $device_name
            ];
            foreach ($row->examination->questionerdynamic as $questioner_value) {
                $questioner_value->is_essay == 0 ? array_push($examsArray[$no-1], $questioner_value->eks_answer) : '';
            }
            foreach ($row->examination->questionerdynamic as $questioner_value) {
                $questioner_value->is_essay == 0 ? array_push($examsArray[$no-1], $questioner_value->perf_answer) : '';
            }
            $no++;
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Rekap Feedback dan Complaint";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data Feedback', function($excel) use ($examsArray, $merge_value) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('payments file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray, $merge_value) {
                // $alphabet = range('H', 'Z');
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                // $s = $alphabet[$merge_value];
                $sheet->mergeCells('H1:AF1');
                $sheet->mergeCells('AG1:BE1');
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
