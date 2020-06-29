<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\Examination;
use App\ExaminationType;
use App\Company;
use App\Logs;

use App\Services\Querys\QueryFilter;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SPBController extends Controller
{
    private const COMPANY = 'company';
    private const QUERY = 'query';
    private const SEARCH = 'search';
    private const SPB_NUMBER = 'spb_number';
    private const SPB_DATE = 'spb_date';
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

        $examType = ExaminationType::all();
        $companies = Company::where('id','!=', 1)->get();

        $query = Examination::select(DB::raw('examinations.*, companies.name as company_name'))
        ->join('companies', 'examinations.company_id', '=', 'companies.id')
                            ->whereNotNull('examinations.created_at')
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('media')
                            ->with('device');
        $query->whereNotNull(self::SPB_NUMBER);
        $query->where('registration_status', 1);
        $query->where('function_status', 1);
        $query->where('contract_status', 1);

        $spb = $query->get();

        $queryFilter = new QueryFilter;

        $searchFiltered = $queryFilter->search($request, $query, self::SPB_NUMBER);
        $query = $searchFiltered[self::QUERY];
        $search = $searchFiltered[self::SEARCH];

        if ($searchFiltered['isNull'])
        {
            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "search";  
            $dataSearch = array(self::SEARCH => $search);
            $logs->data = json_encode($dataSearch);
            $logs->created_by = $currentUser->id;
            $logs->page = "Rekap Nomor SPB";
            $logs->save();
        }

        $beforeDateFiltered = $queryFilter->beforeDate($request, $query, self::SPB_DATE);
        $query = $beforeDateFiltered[self::QUERY];
        $before = $beforeDateFiltered['before'];

        $afterDateFiltered = $queryFilter->afterDate($request, $query, self::SPB_DATE);
        $query = $afterDateFiltered[self::QUERY];
        $after = $afterDateFiltered['after'];

        $spbFiltered = $queryFilter->spb($request, $query);
        $query = $spbFiltered[self::QUERY];
        $filterSpb = $spbFiltered['filterSpb'];

        $typeFiltered = $queryFilter->type($request, $query, 'examination_type_id');
        $query = $typeFiltered[self::QUERY];
        $type = $typeFiltered['type'];

        $companyFiltered = $queryFilter->company($request, $query, 'name');
        $query = $companyFiltered[self::QUERY];
        $filterCompany = $companyFiltered['filterCompany'];

        $paymentStatusFiltered = $queryFilter->paymentStatus($request, $query);
        $query = $paymentStatusFiltered[self::QUERY];
        $filterPayment_status = $paymentStatusFiltered['filterPayment_status'];

        $sortedAndOrderedData = $queryFilter->getSortedAndOrderedData($request, $query, self::SPB_NUMBER);
        $data = $sortedAndOrderedData['data'];
        $sort_by = $sortedAndOrderedData['sort_by'];
        $sort_type = $sortedAndOrderedData['sort_type'];
        
       (count($data) == 0) ? $message = 'Data not found': $message = '';

        
        return view('admin.spb.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with('before_date', $before)
            ->with('after_date', $after)
            ->with('spb', $spb)
            ->with('filterSpb', $filterSpb)
            ->with('type', $examType)
            ->with('filterType', $type)
            ->with(self::COMPANY, $companies)
            ->with('filterCompany', $filterCompany)
            ->with('filterPayment_status', $filterPayment_status)
            ->with('sort_by', $sort_by)
            ->with('sort_type', $sort_type);
    
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $query = Examination::select(DB::raw('examinations.*, companies.name as company_name'))
        ->join('companies', 'examinations.company_id', '=', 'companies.id')
                            ->whereNotNull('examinations.created_at')
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('media')
                            ->with('device');
        $query->whereNotNull(self::SPB_NUMBER);
        $query->where('registration_status', 1);
        $query->where('function_status', 1);
        $query->where('contract_status', 1);

        $queryFilter = new QueryFilter;

        $searchFiltered = $queryFilter->search($request, $query);
        $query = $searchFiltered[self::QUERY];

        $beforeDateFiltered = $queryFilter->beforeDate($request, $query, self::SPB_DATE);
        $query = $beforeDateFiltered[self::QUERY];

        $afterDateFiltered = $queryFilter->afterDate($request, $query, self::SPB_DATE);
        $query = $afterDateFiltered[self::QUERY];

        $spbFiltered = $queryFilter->spb($request, $query);
        $query = $spbFiltered[self::QUERY];

        $typeFiltered = $queryFilter->type($request, $query, 'examination_type_id');
        $query = $typeFiltered[self::QUERY];

        $companyFiltered = $queryFilter->company($request, $query, 'name');
        $query = $companyFiltered[self::QUERY];

        $paymentStatusFiltered = $queryFilter->paymentStatus($request, $query);
        $query = $paymentStatusFiltered[self::QUERY];

        $data = $queryFilter->getDataSortedAndOrdered($request, $query);

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'Tipe Pengujian',
            'Tanggal SPB',
            'Nomor SPB',
            'Nama Perusahaan',
            'Nama Perangkat',
            'Merk/Pabrik Perangkat',
            'Model/Tipe Perangkat',
            'Kapasitas/Kecepatan Perangkat',
            'Nominal',
            'Status Bayar'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            $spb_number = $row->spb_number;
            $spb_date = date("d-m-Y", strtotime($row->spb_date));
            $examType_name = Helper::filterDefault($row->examinationType->name);
            $examType_desc = Helper::filterDefault($row->examinationType->description);
            $company_name = Helper::filterDefault($row->company->name);
            /*Device*/
            $device_name = Helper::filterDefault($row->device->name);
            $device_mark = Helper::filterDefault($row->device->mark);
            $device_capacity = Helper::filterDefault($row->device->capacity);
            $device_model = Helper::filterDefault($row->device->model);
            /*EndDevice*/

            $price = $row->price;
            $status_bayar = $row->payment_status == '1' ? 'SUDAH' : 'BELUM';
            
            $examsArray[] = [
                "".$examType_name." (".$examType_desc.")",
                $spb_date,
                $spb_number,
                $company_name,
                $device_name,
                $device_mark,
                $device_capacity,
                $device_model,
                $price,
                $status_bayar
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Rekap Nomor SPB";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data SPB', function($excel) use ($examsArray) {

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
