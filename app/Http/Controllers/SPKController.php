<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\TbMSPK;
use App\TbHSPK;
use App\Logs;
use App\Services\Querys\QueryFilter;
use App\Services\Admin\SPKService;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SPKController extends Controller
{
    private const QUERY = 'query';
    private const SEARCH = 'search';
    private const RIWAYAT_SPK = "Riwayat SPK";
    private const EXAMINATION_SPK_DATE = 'DATE(examinations.spk_date)';

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
        if (!$currentUser){ return false;}

        $message = null;

        $examType = ExaminationType::all();
        $companies = Company::where('id','!=', 1)->get();
        $examLab = ExaminationLab::all();

        $query = TbMSPK::select(DB::raw('tb_m_spk.*, examinations.spk_date as spk_date, examination_labs.name as lab_name, examination_types.description as TESTING_TYPE_DESC'))
                    ->join("examinations","examinations.id","=","tb_m_spk.ID")
                    ->join("examination_labs","examination_labs.lab_code","=","tb_m_spk.LAB_CODE")
                    ->join("examination_types","examination_types.name","=","tb_m_spk.TESTING_TYPE")
                    ->with('examination');

        $spk = $query->get();

        $queryFilter = new QueryFilter;

        $searchAndLog = $this->searchAndLog($queryFilter, $query, $currentUser, $request);
        $query = $searchAndLog[self::QUERY];
        $search = $searchAndLog[self::SEARCH];

        $beforeDateFiltered = $queryFilter->beforeDate($request, $query, DB::raw(self::EXAMINATION_SPK_DATE));
        $query = $beforeDateFiltered[self::QUERY];
        $before = $beforeDateFiltered['before'];

        $afterDateFiltered = $queryFilter->afterDate($request, $query, DB::raw(self::EXAMINATION_SPK_DATE));
        $query = $afterDateFiltered[self::QUERY];
        $after = $afterDateFiltered['after'];

        $spkFiltered = $queryFilter->spk($request, $query);
        $query = $spkFiltered[self::QUERY];
        $filterSpk = $spkFiltered['filterSpk'];

        $typeFiltered = $queryFilter->type($request, $query, 'TESTING_TYPE');
        $query = $typeFiltered[self::QUERY];
        $type = $typeFiltered['type'];

        $companyFiltered = $queryFilter->company($request, $query, 'COMPANY_NAME');
        $query = $companyFiltered[self::QUERY];
        $filterCompany = $companyFiltered['filterCompany'];

        $labFiltered = $queryFilter->lab($request, $query, 'tb_m_spk.LAB_CODE');
        $query = $labFiltered[self::QUERY];
        $lab = $labFiltered['lab'];
        
        $sortedAndOrderedData = $queryFilter->getSortedAndOrderedData($request, $query, 'spk_date');
        $data = $sortedAndOrderedData['data'];
        $sort_by = $sortedAndOrderedData['sort_by'];
        $sort_type = $sortedAndOrderedData['sort_type'];

        if (count($data) == 0){
            $message = 'Data not found';
        }

        $SPKServices = new SPKService();
        $examsArray = $SPKServices->collectionToArrayAndApendPayment($data);
        
        return view('admin.spk.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with('before_date', $before)
            ->with('after_date', $after)
            ->with('spk', $spk)
            ->with('filterSpk', $filterSpk)
            ->with('type', $examType)
            ->with('filterType', $type)
            ->with('company', $companies)
            ->with('filterCompany', $filterCompany)
            ->with('lab', $examLab)
            ->with('filterLab', $lab)
            ->with('sort_by', $sort_by)
            ->with('sort_type', $sort_type)
            ->with('examsArray', $examsArray);
    }

    public function show($id)
    {
		$data = TbHSPK::where('ID', $id)->get();
                    
        return view('admin.spk.show')
            ->with('data', $data);
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.
        $currentUser = Auth::user();
        if (!$currentUser){ return false;}

        $query = TbMSPK::select(DB::raw('tb_m_spk.*, examinations.spk_date as spk_date'))
                    ->join("examinations","examinations.id","=","tb_m_spk.ID")
                    ->with('examination');
        
        $queryFilter = new QueryFilter;

        $searchAndLog = $this->searchAndLog($queryFilter, $query, $currentUser, $request);
        $query = $searchAndLog[self::QUERY];

        $beforeDateFiltered = $queryFilter->beforeDate($request, $query, DB::raw(self::EXAMINATION_SPK_DATE));
        $query = $beforeDateFiltered[self::QUERY];

        $afterDateFiltered = $queryFilter->afterDate($request, $query, DB::raw(self::EXAMINATION_SPK_DATE));
        $query = $afterDateFiltered[self::QUERY];

        $spkFiltered = $queryFilter->spk($request, $query);
        $query = $spkFiltered[self::QUERY];

        $typeFiltered = $queryFilter->type($request, $query, 'TESTING_TYPE');
        $query = $typeFiltered[self::QUERY];

        $companyFiltered = $queryFilter->company($request, $query, 'COMPANY_NAME');
        $query = $companyFiltered[self::QUERY];

        $sortedAndOrderedData = $queryFilter->getSortedAndOrderedData($request, $query, 'spk_date');
        $data = $sortedAndOrderedData['data'];

        $SPKServices = new SPKService();
        $examsArray = $SPKServices->collectionToArrayAndApendPayment($data);
        
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = self::RIWAYAT_SPK;
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data SPK', function($excel) use ($examsArray) {

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx');
    }

    private function searchAndLog($queryFilter, $query, $currentUser, Request $request)
    {
        $searchFiltered = $queryFilter->search($request, $query, 'SPK_NUMBER');
        $query = $searchFiltered[self::QUERY];
        $search = $searchFiltered[self::SEARCH];

        if ($searchFiltered['isNull'])
        {
            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = self::SEARCH;  
            $dataSearch = array(self::SEARCH => $search);
            $logs->data = json_encode($dataSearch);
            $logs->created_by = $currentUser->id;
            $logs->page = self::RIWAYAT_SPK;
            $logs->save();
        }

        return array(
            'queryFilter' => $queryFilter,
            'query' => $query,
            'currentUser' => $currentUser,
            'search' => $search,
        );
    }
}
