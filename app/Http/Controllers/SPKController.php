<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

use Auth;
use Session;
use Excel;
use Storage;

use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\TbMSPK;
use App\TbHSPK;
use App\Logs;
use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SPKController extends Controller
{
    private const COMPANY = 'company';
    private const COMPANY_NAME = 'COMPANY_NAME';
    private const EXAMINATION_ID = 'examinations.id';
    private const EXAMINATION_SPK_DATE = 'DATE(examinations.spk_date)';
    private const TBMSPK_LAB_CODE = 'tb_m_spk.LAB_CODE';
    private const TBMSPK_SPK_NUMBER = 'tb_m_spk.SPK_NUMBER';
    private const RIWAYAT_SPK = 'Riwayat SPK';
    private const SEARCH = 'search';
    private const SPK_NUMBER = 'SPK_NUMBER';   
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
        $noDataFound = '';
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));
        $sort_by = self::TBMSPK_SPK_NUMBER;
        $sort_type = 'desc';
        $examType = ExaminationType::all();
        $companies = Company::where('id','!=', 1)->get();
        $examLab = ExaminationLab::all();

        $query = TbMSPK::select(DB::raw('tb_m_spk.*, examinations.spk_date as spk_date, examination_labs.name as lab_name, examination_types.description as TESTING_TYPE_DESC'))
                    ->join("examinations",self::EXAMINATION_ID,"=","tb_m_spk.ID")
                    ->join("examination_labs","examination_labs.lab_code","=",self::TBMSPK_LAB_CODE)
                    ->join("examination_types","examination_types.name","=","tb_m_spk.TESTING_TYPE")
                    ->groupBy(self::EXAMINATION_ID);
        $spk = $query->get();

        $queryFilter = $this->getData($request, $query);

        $data = $queryFilter
            ->getQuery()
            ->paginate($paginate)
        ;

        if (count($data) == 0){
            $noDataFound = 'Data not found';
        }
        
        return view('admin.spk.index')
            ->with('listFlowStatus', $this->getListFlowStatus())
            ->with('noDataFound', $noDataFound)
            ->with('spk', $spk)
            ->with('data', $data)
            ->with('lab', $examLab)
            ->with('type', $examType)
            ->with(self::COMPANY, $companies)
            ->with('before_date', $queryFilter->before)
            ->with('after_date', $queryFilter->after)
            ->with('filterSpk', $queryFilter->spkNumber)
            ->with('filterType', $queryFilter->testingType)
            ->with('filterCompany', $queryFilter->companyName)
            ->with('filterLab', $queryFilter->labCode)
            ->with('sort_by', $sort_by)
            ->with('sort_type', $sort_type)
            ->with(self::SEARCH, $search)
        ;
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
        $logService = new LogService();
        $query = TbMSPK::select(DB::raw('tb_m_spk.*, examinations.spk_date as spk_date'))
                    ->join("examinations",self::EXAMINATION_ID,"=","tb_m_spk.ID")
                    //->with('examination')
        ;

        $queryFilter = $this->getData($request, $query);
        $data = $queryFilter
            ->getQuery()
            ->get()
        ;

        // Define the Excel spreadsheet headers
        $examsArray = [];
        $examsArray[] = [
            'Tipe Pengujian',
            'Tanggal SPK',
            'Nomor SPK',
            'Nama Perusahaan',
            'Nama Perangkat',
            'Status'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        $listFlowStatus = $this->getListFlowStatus();

        

        foreach ($data as $row) {
            $status = $listFlowStatus[0];
            if(isset($listFlowStatus[$row->FLOW_STATUS])){
                $status = $listFlowStatus[$row->FLOW_STATUS];
            }
            $examsArray[] = [
                $row->TESTING_TYPE,
                $row->spk_date,
                $row->SPK_NUMBER,
                $row->COMPANY_NAME,
                $row->DEVICE_NAME,
                $status,
            ];
        }

        $logService->createLog('download_excel', self::RIWAYAT_SPK, '');
        $excel = \App\Services\ExcelService::download($examsArray, 'Data SPK');
        return response($excel['file'], 200, $excel['headers']);
    }

    private function getListFlowStatus()
    {
        return array(
            0 => '',
            1 => 'Draft SPK',
            2 => 'SPK dikirim ke Manajer UREL',
            3 => 'SPK disetujui Manajer UREL',
            4 => 'SPK disetujui SMPIA',
            5 => 'SPK disetujui Manager Lab',
            6 => 'Proses Uji',
            7 => 'SPK ditolak Manajer UREL',
            8 => 'SPK ditolak SMPIA',
            9 => 'SPK ditolak Manager Lab',
            10 => 'TE meminta revisi target uji',
            11 => 'Laporan dikirim ke Manajer Lab',
            12 => 'Laporan disetujui Manajer Lab',
            13 => 'Laporan disetujui SMPIA',
            14 => 'Laporan disetujui Manajer UREL',
            15 => 'Laporan dikembalikan Manajer Lab',
            16 => 'Laporan dikembalikan SMPIA',
            17 => 'Laporan dikembalikan Manajer UREL',
            18 => 'Laporan ditolak EGM',
            19 => 'Selesai SPK',
            20 => 'Selesai Uji',
            21 => 'Selesai Sidang',
            22 => 'Sidang Ditunda',
            23 => 'Draft Laporan'
        );
    }

    private function getData($request, $query)
    {
        $logService = new LogService();
        $search = trim($request->input(self::SEARCH));
        $sort_by = self::TBMSPK_SPK_NUMBER;
        $sort_type = 'desc';

        $queryFilter =  new QueryFilter($request, $query);
        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->where('DEVICE_NAME', 'like', '%'.strtolower($search).'%')
                ->orwhere(self::COMPANY_NAME, 'like', '%'.strtolower($search).'%')
                ->orWhere(self::SPK_NUMBER, 'like', '%'.strtolower($search).'%');
            });
            $queryFilter =  new QueryFilter($request, $query);
            $logService->createLog(self::SEARCH, self::RIWAYAT_SPK, json_encode(array(self::SEARCH => $search)));
        }

        $queryFilter
            ->beforeDate(DB::raw(self::EXAMINATION_SPK_DATE))
            ->afterDate(DB::raw(self::EXAMINATION_SPK_DATE))
            ->spkNumber('spk',self::TBMSPK_SPK_NUMBER)
            ->testingType()
            ->companyName(self::COMPANY,'tb_m_spk.COMPANY_NAME')
            ->labCode('lab',self::TBMSPK_LAB_CODE)
            ->getSortedAndOrderedData($sort_by, $sort_type)
        ;

        return $queryFilter;
    }
}
