<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

use Auth;
use Session;
use Excel;
use Storage;

use App\Examination;
use App\ExaminationType;
use App\Company;
use App\Logs;

use App\Services\Querys\QueryFilter;
use App\Services\MyHelper;
use App\Services\Logs\LogService;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SPBController extends Controller
{
    private const COMPANY = 'company';
    private const DEVICE = 'device';
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
        $logService = new LogService();
        $noDataFound = '';
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $sort_by = self::SPB_NUMBER;
        $sort_type = 'desc';
        $query = $this->getInitialQuery();
        $examType = ExaminationType::all();
        $companies = Company::where('id','!=', 1)->get();
        $spb = $query->get();
        $queryFilter = new QueryFilter($request, $query);

        if ($search){
            $query->where(function($qry) use($search){
                $qry->whereHas(self::DEVICE, function ($q) use ($search){
                            return $q->where('name', 'like', '%'.strtolower($search).'%');
                        })
                    ->orWhereHas(self::COMPANY, function ($q) use ($search){
                            return $q->where('name', 'like', '%'.strtolower($search).'%');
                        })
                    ->orWhere(self::SPB_NUMBER, 'like', '%'.strtolower($search).'%')
                ;
            });
            $queryFilter= new QueryFilter($request, $query);
            $logService->createLog(self::SEARCH, 'Rekap Nomor SPB', json_encode(array(self::SEARCH => $search)));
        }

        $queryFilter
            ->beforeDate(self::SPB_DATE)
            ->afterDate(self::SPB_DATE)
            ->spbNumber()
            ->examination_type()
            ->companyName()
            ->paymentStatus()
            ->getSortedAndOrderedData($sort_by, $sort_type)
        ;

        $data = $queryFilter
            ->getQuery()
            ->paginate($paginate)
        ;
        
        if (count($data) == 0){
            $noDataFound = 'Data not found';
        }
        
        return view('admin.spb.index')
            ->with('data', $data)
            ->with('noDataFound', $noDataFound)
            ->with(self::SEARCH, $search)
            ->with(self::COMPANY, $companies)
            ->with('spb', $spb)
            ->with('type', $examType)
            ->with('filterSpb', $queryFilter->spbNumber)
            ->with('before_date', $queryFilter->before)
            ->with('after_date', $queryFilter->after)
            ->with('filterType', $queryFilter->examination_type)
            ->with('filterCompany', $queryFilter->companyName)
            ->with('filterPayment_status', $queryFilter->paymentStatus)
            ->with('sort_by', $queryFilter->sort_by)
            ->with('sort_type', $queryFilter->sort_type);

    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.
        $logService = new LogService();
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $sort_by = self::SPB_NUMBER;
        $sort_type = 'desc';

        $query = $this->getInitialQuery();
        $queryFilter= new QueryFilter($request, $query);

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas(self::DEVICE, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas(self::COMPANY, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhere(self::SPB_NUMBER, 'like', '%'.strtolower($search).'%');
            });
            $queryFilter= new QueryFilter($request, $query);
        }
        $queryFilter
            ->beforeDate(self::SPB_DATE)
            ->afterDate(self::SPB_DATE)
            ->spbNumber()
            ->examination_type()
            ->companyName()
            ->paymentStatus()
            ->getSortedAndOrderedData($sort_by, $sort_type)
        ;

        $data = $queryFilter
            ->getQuery()
            ->get()
        ;

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
            $examType_name = MyHelper::filterDefault($row->examinationType->name);
            $examType_desc = MyHelper::filterDefault($row->examinationType->description);
            $spb_date = date("d-m-Y", strtotime($row->spb_date));
            $spb_number = $row->spb_number;
            $company_name = MyHelper::filterDefault($row->company->name);
            $device_name = MyHelper::filterDefault($row->device->name);
            $device_mark = MyHelper::filterDefault($row->device->mark);
            $device_capacity = MyHelper::filterDefault($row->device->capacity);
            $device_model = MyHelper::filterDefault($row->device->model);

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
        $logService->createLog('download_excel', 'Rekap Nomor SPB', "" );
        $excel = \App\Services\ExcelService::download($examsArray, 'Data SPB');
        return response($excel['file'], 200, $excel['headers']);
    }

    private function getInitialQuery()
    {

        $query = Examination::select(DB::raw('examinations.*, companies.name as company_name'))
        ->join('companies', 'examinations.company_id', '=', 'companies.id')
                            ->whereNotNull('examinations.created_at')
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('media')
                            ->with(self::DEVICE);
        $query->whereNotNull(self::SPB_NUMBER);
        $query->where('registration_status', 1);
        $query->where('function_status', 1);
        $query->where('contract_status', 1);

        return $query;
    }
}