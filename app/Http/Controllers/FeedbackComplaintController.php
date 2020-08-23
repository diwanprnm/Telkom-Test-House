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
use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;
use App\Services\MyHelper;


use Storage;
use File;
use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FeedbackComplaintController extends Controller
{
    private const EXAMINATION_COMPANY = 'examination.company';
    private const EXAMINATION_DEVICE = 'examination.device';
    private const RECAP_FEEDBACK_COMPLAINT = "Rekap Feedback dan Complaint";
    private const SEARCH = 'search';
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
        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $before = null;
        $after = null;
        $type = '';
        $filterCompany = '';
        $lab = '';
        $sort_by = 'questioner_date';
        $sort_type = 'desc';

        $logService = new LogService();
        $examType = ExaminationType::all();
        $companies = Company::where('id','!=', 1)->get();
        $examLab = ExaminationLab::all();

        $query = Questioner::with(self::EXAMINATION_DEVICE)->with(self::EXAMINATION_COMPANY)->with('user');
        $queryFilter = new QueryFilter($request, $query);
        if ($search){
            $query->whereHas(self::EXAMINATION_COMPANY, function ($query) use ($search) {
                $query->where('name', 'like', '%'.strtolower($search).'%');
            })->orWhereHas(self::EXAMINATION_DEVICE, function ($query) use ($search) {
                $query->orWhere('name', 'like', '%'.strtolower($search).'%');
            });
            $queryFilter = new QueryFilter($request, $query);
            $logService->createLog('search', self::RECAP_FEEDBACK_COMPLAINT, json_encode(array(self::SEARCH => $search)) );
        }

        $data = $queryFilter
            ->getSortedAndOrderedData($sort_by, $sort_type)
            ->query
            ->paginate($paginate);
        
        if (count($data) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.feedbackncomplaint.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with('before_date', $before)
            ->with('after_date', $after)
            ->with('type', $examType)
            ->with('filterType', $type)
            ->with('company', $companies)
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
        $logService = new LogService();
        $search = trim($request->input(self::SEARCH));

        $query = Questioner::with(self::EXAMINATION_DEVICE)
                            ->with(self::EXAMINATION_COMPANY)
                            ->with('examination.questionerdynamic')
                            ->with('examination.examinationType')
                            ->with('user');
        $queryFilter = new QueryFilter($request, $query);

        if ($search != null){
            $query->whereHas(self::EXAMINATION_COMPANY, function ($query) use ($search) {
                $query->where('name', 'like', '%'.strtolower($search).'%');
            })->orWhereHas(self::EXAMINATION_DEVICE, function ($query) use ($search) {
                $query->orWhere('name', 'like', '%'.strtolower($search).'%');
            });
            $queryFilter = new QueryFilter($request, $query);
        }

        $data = $queryFilter
            ->getSortedAndOrderedData('questioner_date', 'desc')
            ->query
            ->get();

        if(!count( $data)){
            Session::flash('error', 'Cannot download file - Data not found');
            return redirect('admin/feedbackncomplaint');
        }


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
            if ($i==0) {continue;}

            $data[0]->examination->questionerdynamic[$i]->is_essay == 0 ? array_push($examsArray[0], '') : '';
            $merge_value++;
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
            $questioner_date = MyHelper::filterDefault($row->questioner_date);
            $user_name = MyHelper::filterDefault($row->user->name);
            $company_name = MyHelper::filterDefault($row->examination->company->name);
            $phone_number = MyHelper::filterDefault($row->user->phone_number);
            $examType_name = MyHelper::filterDefault($row->examination->examinationType->name);
            $examType_desc = MyHelper::filterDefault($row->examination->examinationType->description);
            $device_name = MyHelper::filterDefault($row->examination->device->name);
                        
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
                $questioner_value->is_essay == 0 ? array_push($examsArray[$no-1], $questioner_value->eks_answer) && array_push($examsArray[$no-1], $questioner_value->perf_answer) : '';
            }
            $no++;
        }

        $logService->createLog('download_excel', self::RECAP_FEEDBACK_COMPLAINT, '' );

        $excelFileName = 'Data Feedback';
        // Generate and return the spreadsheet
        Excel::create($excelFileName, function($excel) use ($examsArray) {

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:AF1');
                $sheet->mergeCells('AG1:BE1');
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->store('xlsx');

        $file = Storage::disk('tmp')->get("$excelFileName.xlsx");
        return response($file, 200, \App\Services\MyHelper::getHeaderExcel("$excelFileName.xlsx"));
    }
}