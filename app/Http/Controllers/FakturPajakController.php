<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use Excel;

use App\Examination;
use App\STELSales;
use App\Company;
use App\Logs;

use App\Services\Logs\LogService;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FakturPajakController extends Controller
{
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
        $logService = new LogService();

        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $noDataFound = '';
        $page = $request->get('page','1');
        $sort_by = $request->get(self::SORT_BY,'payment_date');
        $sort_type = $request->get(self::SORT_TYPE,'desc');
        $paginate = 10;
        $type = '';
        $filterCompany = '';
        $companies = Company::where('id','!=', 1)->get();

        $spb = Examination::select(DB::raw('examinations.id as _id, "SPB" as type, users.name as user_name, companies.name as company_name, devices.name as device_name,devices.model,devices.capacity,
            (
                SELECT attachment
                FROM
                    examination_attachments
                WHERE
                    examination_id = examinations.id
                AND
                    name = "Kuitansi"
            ) as id_kuitansi,
            (
                SELECT attachment
                FROM
                    examination_attachments
                WHERE
                    examination_id = examinations.id
                AND
                    name = "Faktur Pajak"
            ) as faktur_file, 
            (
                SELECT DATE(examination_attachments.tgl)
                FROM
                    examination_attachments
                WHERE
                    examination_id = examinations.id
                AND
                    name = "Pembayaran"
            ) as payment_date'))
            ->join('users', 'examinations.created_by', '=', 'users.id')
            ->join('companies', 'examinations.company_id', '=', 'companies.id')
            ->join('devices', 'examinations.device_id', '=', 'devices.id')
            ->join('examination_attachments', 'examinations.id', '=', 'examination_attachments.examination_id')
            ->whereNotNull('examinations.created_at')
            ->where(function($q1){
                return $q1->where(function($q){
                    return $q->where('examination_attachments.name', 'Kuitansi')
                        ->where('examination_attachments.attachment', '!=', '');
                    })->orWhere(function($q){
                    return $q->where('examination_attachments.name', 'Faktur Pajak')
                        ->where('examination_attachments.attachment', '!=', '');
                    });
                })
            ->distinct('examinations.id')
        ;

        $stel = STELSales::select(DB::raw('stels_sales.id as _id, "STEL" as type, users.name as user_name, companies.name as company_name, 
                            stels.name as description,stels.type,stels.code, 
                            stels_sales.id_kuitansi, stels_sales.faktur_file, DATE(stels_sales_attachment.updated_at) as payment_date'))
            ->join('users', 'stels_sales.created_by', '=', 'users.id')
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->join('stels_sales_detail', 'stels_sales.id', '=', 'stels_sales_detail.stels_sales_id')
            ->leftJoin('stels_sales_attachment', 'stels_sales.id', '=', 'stels_sales_attachment.stel_sales_id')
            ->join('stels', 'stels_sales_detail.stels_id', '=', 'stels.id')
            ->where(function($q){
                return $q->where('stels_sales.id_kuitansi', '!=', '')
                    ->orWhere('stels_sales.faktur_file', '!=', '');
                })
        ;

        if ($search){
            $spb->having('company_name', 'like', '%'.strtolower($search).'%')
                ->orHaving('description', 'like', '%'.strtolower($search).'%')
                ->orHaving('faktur_file', 'like', '%'.strtolower($search).'%')
            ;
            $stel->having('company_name', 'like', '%'.strtolower($search).'%')
                ->orHaving('description', 'like', '%'.strtolower($search).'%')
                ->orHaving('faktur_file', 'like', '%'.strtolower($search).'%')
            ;
            
            $logService->createLog('search faktur pajak','Rekap Kuitansi dan Faktur Pajak', json_encode(array(self::SEARCH => $search)));
        }

        $query = $spb->union($stel)->orderBy($sort_by, $sort_type);
        $data = json_decode(json_encode($query->get(), true));
        
        $offSet = ($page * $paginate) - $paginate;
        $itemsForCurrentPage = array_slice($data, $offSet, $paginate, true);
        $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($data), $paginate, $page, ['path' => $request->url(), 'query' => $request->query()]);

        if (count($data) == 0){
            $noDataFound = 'Data not found';
        }
        
        return view('admin.fakturpajak.index')
            ->with('noDataFound', $noDataFound)
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with('filterType', $type)
            ->with('company', $companies)
            ->with('filterCompany', $filterCompany)
            ->with(self::SORT_BY, $sort_by)
            ->with(self::SORT_TYPE, $sort_type);
    }
}
