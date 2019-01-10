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

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SPKController extends Controller
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
            $filterSpk = '';
            $type = '';
            $filterCompany = '';
            $lab = '';

            $sort_by = 'spk_date';
            $sort_type = 'desc';

            $examType = ExaminationType::all();
            $companies = Company::where('id','!=', 1)->get();
            $examLab = ExaminationLab::all();

            $query = TbMSPK::select(DB::raw('tb_m_spk.*, examinations.spk_date as spk_date, examination_labs.name as lab_name, examination_types.description as TESTING_TYPE_DESC'))
                        ->join("examinations","examinations.id","=","tb_m_spk.ID")
                        ->join("examination_labs","examination_labs.lab_code","=","tb_m_spk.LAB_CODE")
                        ->join("examination_types","examination_types.name","=","tb_m_spk.TESTING_TYPE")
                        ->with('examination');

            $spk = $query->get();

            if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->where('DEVICE_NAME', 'like', '%'.strtolower($search).'%')
                    ->orwhere('COMPANY_NAME', 'like', '%'.strtolower($search).'%')
                    ->orWhere('SPK_NUMBER', 'like', '%'.strtolower($search).'%');
                });

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Riwayat SPK";
                $logs->save();
            }

            if ($request->has('before_date')){
                $query->where(DB::raw('DATE(examinations.spk_date)'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $query->where(DB::raw('DATE(examinations.spk_date)'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            if ($request->has('spk')){
                $filterSpk = $request->get('spk');
                if($request->input('spk') != 'all'){
                    $query->where('SPK_NUMBER', $request->get('spk'));
                }
            }
            
            if ($request->has('type')){
                $type = $request->get('type');
                if($request->input('type') != 'all'){
                    $query->where('TESTING_TYPE', 'like', '%'.$request->get('type').'%');
                }
            }

            if ($request->has('company')){
                $filterCompany = $request->get('company');
                if($request->input('company') != 'all'){
                    $query->where('COMPANY_NAME', 'like', '%'.$request->get('company').'%');
                }
            }

            if ($request->has('lab')){
                $lab = $request->get('lab');
                if($request->input('lab') != 'all'){
                    $query->where('tb_m_spk.LAB_CODE', $request->get('lab'));
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
            
            return view('admin.spk.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
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
                ->with('sort_type', $sort_type);
        }
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

        $search = trim($request->input('search'));
        
        $before = null;
        $after = null;
        $filterSpk = '';
        $type = '';
        $filterCompany = '';

        $sort_by = 'SPK_NUMBER';
        $sort_type = 'desc';

        $query = TbMSPK::select(DB::raw('tb_m_spk.*, examinations.spk_date as spk_date'))
                    ->join("examinations","examinations.id","=","tb_m_spk.ID")
                    ->with('examination');

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->where('DEVICE_NAME', 'like', '%'.strtolower($search).'%')
                ->orwhere('COMPANY_NAME', 'like', '%'.strtolower($search).'%')
                ->orWhere('SPK_NUMBER', 'like', '%'.strtolower($search).'%');
            });

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "search";  
            $dataSearch = array('search' => $search);
            $logs->data = json_encode($dataSearch);
            $logs->created_by = $currentUser->id;
            $logs->page = "Riwayat SPK";
            $logs->save();
        }

        if ($request->has('before_date')){
            $query->where(DB::raw('DATE(examinations.spk_date)'), '<=', $request->get('before_date'));
            $before = $request->get('before_date');
        }

        if ($request->has('after_date')){
            $query->where(DB::raw('DATE(examinations.spk_date)'), '>=', $request->get('after_date'));
            $after = $request->get('after_date');
        }

        if ($request->has('spk')){
            $filterSpk = $request->get('spk');
            if($request->input('spk') != 'all'){
                $query->where('SPK_NUMBER', $request->get('spk'));
            }
        }
        
        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where('TESTING_TYPE', 'like', '%'.$request->get('type').'%');
            }
        }

        if ($request->has('company')){
            $filterCompany = $request->get('company');
            if($request->input('company') != 'all'){
                $query->where('COMPANY_NAME', 'like', '%'.$request->get('company').'%');
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
            'Tanggal SPK',
            'Nomor SPK',
            'Nama Perusahaan',
            'Nama Perangkat',
            'Status'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            switch ($row->FLOW_STATUS) {
                case 1:
                    $status = 'Draft SPK';
                    break;
                case 2:
                    $status = 'SPK dikirim ke Manajer UREL';
                    break;
                case 3:
                    $status = 'SPK disetujui Manajer UREL';
                    break;
                case 4:
                    $status = 'SPK disetujui SMPIA';
                    break;
                case 5:
                    $status = 'SPK disetujui Manager Lab';
                    break;
                case 6:
                    $status = 'Proses Uji';
                    break;
                case 7:
                    $status = 'SPK ditolak Manajer UREL';
                    break;
                case 8:
                    $status = 'SPK ditolak SMPIA';
                    break;
                case 9:
                    $status = 'SPK ditolak Manager Lab';
                    break;
                case 10:
                    $status = 'TE meminta revisi target uji';
                    break;
                case 11:
                    $status = 'Laporan dikirim ke Manajer Lab';
                    break;
                case 12:
                    $status = 'Laporan disetujui Manajer Lab';
                    break;
                case 13:
                    $status = 'Laporan disetujui SMPIA';
                    break;
                case 14:
                    $status = 'Laporan disetujui Manajer UREL';
                    break;
                case 15:
                    $status = 'Laporan dikembalikan Manajer Lab';
                    break;
                case 16:
                    $status = 'Laporan dikembalikan SMPIA';
                    break;
                case 17:
                    $status = 'Laporan dikembalikan Manajer UREL';
                    break;
                case 18:
                    $status = 'Laporan ditolak EGM';
                    break;
                case 19:
                    $status = 'Selesai SPK';
                    break;
                case 20:
                    $status = 'Selesai Uji';
                    break;
                case 21:
                    $status = 'Selesai Sidang';
                    break;
                case 22:
                    $status = 'Sidang Ditunda';
                    break;
                case 23:
                    $status = 'Draft Laporan';
                    break;
                
                default:
                    $status = '';
                    break;
            }
            
            $examsArray[] = [
                $row->TESTING_TYPE,
                $row->spk_date,
                $row->SPK_NUMBER,
                $row->COMPANY_NAME,
                $row->DEVICE_NAME,
                $status
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Riwayat SPK";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data SPK', function($excel) use ($examsArray) {

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
