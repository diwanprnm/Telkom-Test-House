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

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SPBController extends Controller
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
            $filterSpb = '';
            $type = '';
            $filterCompany = '';
            $filterPayment_status = '';

            $sort_by = 'spb_number';
            $sort_type = 'desc';

            $examType = ExaminationType::all();
            $companies = Company::where('id','!=', 1)->get();

            $query = Examination::select(DB::raw('examinations.*, companies.name as company_name'))
            ->join('companies', 'examinations.company_id', '=', 'companies.id')
                                ->whereNotNull('examinations.created_at')
                                ->with('user')
                                ->with('company')
                                ->with('examinationType')
                                ->with('examinationLab')
                                ->with('media')
                                ->with('device');
            $query->whereNotNull('spb_number');
            $query->where('registration_status', 1);
            $query->where('function_status', 1);
            $query->where('contract_status', 1);

            $spb = $query->get();

            if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->whereHas('device', function ($q) use ($search){
                            return $q->where('name', 'like', '%'.strtolower($search).'%');
                        })
                    ->orWhereHas('company', function ($q) use ($search){
                            return $q->where('name', 'like', '%'.strtolower($search).'%');
                        })
                    ->orWhere('spb_number', 'like', '%'.strtolower($search).'%');
                });

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Rekap Nomor SPB";
                $logs->save();
            }

            if ($request->has('before_date')){
                $query->where('spb_date', '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $query->where('spb_date', '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            if ($request->has('spb')){
                $filterSpb = $request->get('spb');
                if($request->input('spb') != 'all'){
                    $query->where('spb_number', $request->get('spb'));
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
                    $query->whereHas('company', function ($q) use ($request){
                        return $q->where('name', 'like', '%'.$request->get('company').'%');
                    });
                }
            }

            if ($request->has('payment_status')){
                $filterPayment_status = $request->get('payment_status');
                if($request->input('payment_status') != 'all'){
                    $request->input('payment_status') == '1' ? $query->where('payment_status', '=', 1) : $query->where('payment_status', '!=', 1);
                }
            }

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
            
            return view('admin.spb.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('before_date', $before)
                ->with('after_date', $after)
                ->with('spb', $spb)
                ->with('filterSpb', $filterSpb)
                ->with('type', $examType)
                ->with('filterType', $type)
                ->with('company', $companies)
                ->with('filterCompany', $filterCompany)
                ->with('filterPayment_status', $filterPayment_status)
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
        $filterSpb = '';
        $type = '';
        $filterCompany = '';
        $filterPayment_status = '';

        $sort_by = 'spb_number';
        $sort_type = 'desc';

        $query = Examination::select(DB::raw('examinations.*, companies.name as company_name'))
        ->join('companies', 'examinations.company_id', '=', 'companies.id')
                            ->whereNotNull('examinations.created_at')
                            ->with('user')
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('media')
                            ->with('device');
        $query->whereNotNull('spb_number');
        $query->where('registration_status', 1);
        $query->where('function_status', 1);
        $query->where('contract_status', 1);

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas('device', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas('company', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhere('spb_number', 'like', '%'.strtolower($search).'%');
            });
        }

        if ($request->has('before_date')){
            $query->where('spb_date', '<=', $request->get('before_date'));
            $before = $request->get('before_date');
        }

        if ($request->has('after_date')){
            $query->where('spb_date', '>=', $request->get('after_date'));
            $after = $request->get('after_date');
        }

        if ($request->has('spb')){
            $filterSpb = $request->get('spb');
            if($request->input('spb') != 'all'){
                $query->where('spb_number', $request->get('spb'));
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
                $query->whereHas('company', function ($q) use ($request){
                    return $q->where('name', 'like', '%'.$request->get('company').'%');
                });
            }
        }

        if ($request->has('payment_status')){
            $filterPayment_status = $request->get('payment_status');
            if($request->input('payment_status') != 'all'){
                $request->input('payment_status') == '1' ? $query->where('payment_status', '=', 1) : $query->where('payment_status', '!=', 1);
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
            $examType_name = !isset($row->examinationType->name) ? '' : $row->examinationType->name;
            $examType_desc = !isset($row->examinationType->description) ? '' : $row->examinationType->description;
            $spb_date = date("d-m-Y", strtotime($row->spb_date));
            $spb_number = $row->spb_number;
            $company_name = !isset($row->company->name) ? '' : $row->company->name;
            
            /*Device*/
            $device_name = !isset($row->device->name) ? '' : $row->device->name;
            $device_mark = !isset($row->device->mark) ? '' : $row->device->mark;
            $device_capacity = !isset($row->device->capacity) ? '' : $row->device->capacity;
            $device_model = !isset($row->device->model) ? '' : $row->device->model;
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
