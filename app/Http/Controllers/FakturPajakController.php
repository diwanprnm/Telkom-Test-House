<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\Examination;
use App\STELSales;
use App\Company;
use App\Logs;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FakturPajakController extends Controller
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
            $search = trim($request->input('search'));
            $message = null;
            $page = $request->has('page') ? $request->get('page') : '1';
            $sort_by = $request->has('sort_by') ? $request->get('sort_by') : 'payment_date';
            $sort_type = $request->has('sort_type') ? $request->get('sort_type') : 'desc';
            $paginate = 10;
            $type = '';
            $filterCompany = '';
            $companies = Company::where('id','!=', 1)->get();

            $spb = Examination::select(DB::raw('examinations.id as _id, "SPB" as type, users.name as user_name, companies.name as company_name, CONCAT(devices.name, ", tipe ", devices.model, ", kapasitas ", devices.capacity) as description, examination_attachments.attachment as faktur_file, DATE(examination_attachments.tgl) as payment_date'))
                ->join('users', 'examinations.created_by', '=', 'users.id')
                ->join('companies', 'examinations.company_id', '=', 'companies.id')
                ->join('devices', 'examinations.device_id', '=', 'devices.id')
                ->leftJoin('examination_attachments', 'examinations.id', '=', 'examination_attachments.examination_id')
                ->whereNotNull('examinations.created_at')
                ->where('examination_attachments.name', 'Faktur Pajak')
                ->where('examination_attachments.attachment', '!=', '')
            ;

            $stel = STELSales::select(DB::raw('stels_sales.id as _id, "STEL" as type, users.name as user_name, companies.name as company_name, 
                                (
                                    SELECT GROUP_CONCAT(stels.code SEPARATOR ", ")
                                    FROM
                                        stels,
                                        stels_sales_detail
                                    WHERE
                                        stels_sales_detail.stels_sales_id = _id
                                    AND
                                        stels_sales_detail.stels_id = stels.id
                                ) as description,
                                stels_sales.faktur_file, DATE(stels_sales_attachment.updated_at) as payment_date'))
                ->join('users', 'stels_sales.created_by', '=', 'users.id')
                ->join('companies', 'users.company_id', '=', 'companies.id')
                ->join('stels_sales_detail', 'stels_sales.id', '=', 'stels_sales_detail.stels_sales_id')
                ->leftJoin('stels_sales_attachment', 'stels_sales.id', '=', 'stels_sales_attachment.stel_sales_id')
                ->join('stels', 'stels_sales_detail.stels_id', '=', 'stels.id')
                ->where('stels_sales.faktur_file', '!=', '')
            ;

            if ($search != null){
                $spb->having('company_name', 'like', '%'.strtolower($search).'%')
                    ->orHaving('description', 'like', '%'.strtolower($search).'%')
                    ->orHaving('faktur_file', 'like', '%'.strtolower($search).'%');

                $stel->having('company_name', 'like', '%'.strtolower($search).'%')
                    ->orHaving('description', 'like', '%'.strtolower($search).'%')
                    ->orHaving('faktur_file', 'like', '%'.strtolower($search).'%');
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Rekap Faktur Pajak";
                $logs->save();
            }

            $query = $spb->union($stel)->orderBy($sort_by, $sort_type);
            $data = json_decode(json_encode($query->get(), true));
            
            $offSet = ($page * $paginate) - $paginate;
            $itemsForCurrentPage = array_slice($data, $offSet, $paginate, true);
            $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($data), $paginate, $page, ['path' => $request->url(), 'query' => $request->query()]);

            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.fakturpajak.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('filterType', $type)
                ->with('company', $companies)
                ->with('filterCompany', $filterCompany)
                ->with('sort_by', $sort_by)
                ->with('sort_type', $sort_type);
        }
    }
}
