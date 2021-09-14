<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Sidang;
use App\Sidang_detail;
use App\Examination;
use App\User;

use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;
use App\Services\NotificationService;
use App\Services\SalesService;

use Auth;
use Session; 
use Validator;
use Excel;
use Response;
use Storage;
use File;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable;

class SidangController extends Controller
{   
    private const ADMIN_SIDANG = '/admin/sidang';
    private const ERROR = 'error';
    private const LOGIN = 'login';
    private const MESSAGE = 'message';
    
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $message = null;
        $paginate = 100;
        $tab = $request->input('tab');
        $search = $request->input('search2');
        $before = $request->input('before');
        $after = $request->input('after');
        
        //return view to saves index with data
        return view('admin.sidang.index')
            ->with('tab', $tab)
            ->with('data_sidang', $this->getData($request, 1)['data']->paginate($paginate, ['*'], 'pageSidang') )
            ->with('data_perangkat', $this->getData($request, 0)['data']->paginate($paginate, ['*'], 'pagePerangkat'))
            ->with('data_pending', $this->getData($request, 2)['data']->paginate($paginate, ['*'], 'pagePending'))
            ->with('search', $search)
            ->with('before_date', $before)
            ->with('after_date', $after)
        ;
    }

    public function getData(Request $request, $type){
        $logService = new LogService();
        $search = $request->input('search2') ? $request->input('search2') : NULL;
        $before = $request->input('before') ? $request->input('before') : NULL;
        $after = $request->input('after') ? $request->input('after') : NULL;

        switch ($type) {
            case '1': //Daftar Sidang QA
                $query = Sidang::orderBy('date', 'DESC');
                $before ? $query->where('date', '<=', $before) : '';
                $after ? $query->where('date', '>=', $after) : '';
                break;

            case '0': //Perangkat Belum Sidang   
            case '2': //Perangkat Pending
                $query = Examination::with('device')->with('company')->with('media')->with('examinationLab')->with('equipmentHistory')
                    ->where('examination_type_id', 1)
                    ->where('resume_status', 1)
                    ->where('qa_status', 0)
                    ->where('qa_passed', $type)
                    ->whereNotIn('id', function ($q) {
                        $q->select('examination_id')
                            ->from('sidang_detail')
                            ->where('result', 0);
                    })
                ;
                if ($search){
                    $query->where(function($qry) use($search){
                        $qry->whereHas('device', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%')
                                    ->orWhere('mark', 'like', '%'.strtolower($search).'%')
                                ;
                            })
                        ->orWhereHas('company', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%');
                            })
                        ->orWhere('spk_code', 'like', '%'.strtolower($search).'%');
                    });
                }
                break;
                
            default: //Get Data Sidang by ID
                $query = Sidang_detail::with('sidang')
                    ->with('examination')
                    ->with('examination.company')
                    ->with('examination.media')
                    ->with('examination.examinationLab')
                    ->with('examination.equipmentHistory')
                    ->where('sidang_id', $type)
                ;
                if ($search){
                    $query->where(function($qry) use($search){
                        $qry->whereHas('examination.device', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%')
                                    ->orWhere('mark', 'like', '%'.strtolower($search).'%')
                                ;
                            })
                        ->orWhereHas('examination.company', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%');
                            })
                        ->orWhereHas('examination', function ($q) use ($search){
                                return $q->where('spk_code', 'like', '%'.strtolower($search).'%');
                            });
                    });
                }
                break;
        }
        
        return array(
            'data' => $query,
            'search' => $search,
            'before' => $before,
            'after' => $after,
        );
    }

    public function create(Request $request, $sidang_id = null)
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            //initial var
            $message = null;
            $paginate = 100;
            $tab = $sidang_id ? 'tab-draft' : $request->input('tab');
            
            //return view to saves index with data
            return view('admin.sidang.create')
                ->with('sidang_id', $sidang_id)
                ->with('tab', $tab)
                ->with('data_perangkat', $this->getData($request, 0)['data']->paginate($paginate, ['*'], 'pagePerangkat'))
                ->with('data_pending', $this->getData($request, 2)['data']->paginate($paginate, ['*'], 'pagePending'))
                ->with('data_draft', $this->getData($request, $sidang_id)['data']->paginate($paginate, ['*'], 'pageDraft'))
            ;
        }else{
            return view('errors.401');
        }
    }


    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $logService = new LogService();
        $sidang_id = $request->has('sidang_id') ? $request->input('sidang_id') : null;
        $draft = $request->input('hidden_tab') == 'tab-draft' ? 1 : 0;
        if($sidang_id){ 
            $sidang = Sidang::find($sidang_id);
            if($draft){ //save draft
                $sidangDetail = Sidang_detail::where('sidang_id', $sidang_id)->delete();

                $sidang->date = $request->input('date');
                $sidang->audience = $request->input('audience');
                $sidang->jml_perangkat = count($request->input('chk-draft'));
                $sidang->status = 'DRAFT';
            }
        }else{ //save pratinjau
            $sidang = new Sidang;
            $sidang->id = Uuid::uuid4();
            $sidang->status = 'PRATINJAU';
        }
        $sidang->created_by = $currentUser->id;
        $sidang->updated_by = $currentUser->id;
        try{
            if($sidang->save()){
                if($request->has('hidden_tab')){
                    if($request->input('hidden_tab') == 'tab-perangkat'){$chk = $request->input('chk-perangkat');}
                    if($request->input('hidden_tab') == 'tab-pending'){$chk = $request->input('chk-pending');}
                    if($request->input('hidden_tab') == 'tab-draft'){$chk = $request->input('chk-draft');}
                }else{
                    if($request->has('chk-perangkat')){$chk = $request->input('chk-perangkat');}
                    if($request->has('chk-pending')){$chk = $request->input('chk-pending');}
                    if($request->has('chk-draft')){$chk = $request->input('chk-draft');}
                }
                foreach($chk as $examination_id){ 
                    $sidangDetail = new Sidang_detail;
                    $sidangDetail->id = Uuid::uuid4();
                    $sidangDetail->sidang_id = $sidang->id;
                    $sidangDetail->examination_id = $examination_id;
                    $sidangDetail->created_by = $currentUser->id;
                    $sidangDetail->updated_by = $currentUser->id;
                    $sidangDetail->save();
                }
            }
            $logService->createLog($draft ? 'Draft Sidang QA' : 'Pratinjau Draft Sidang QA');
            Session::flash(self::MESSAGE, 'Data successfully added');
            return $draft ? redirect(self::ADMIN_SIDANG) : redirect(self::ADMIN_SIDANG.'/create/'.$sidang->id);
        } catch(Exception $e){ return redirect('/admin/sidang/create'.$sidang->id)->with(self::ERROR, 'Save failed');
        }
    } 

    public function show($id)
    {
        return view('admin.sales.detail')
            ->with('data', null)
        ;
    }   

    public function excel(Request $request) 
    {
        $currentUser = Auth::user();
        if (!$currentUser){return redirect(self::LOGIN);}
        
        // initial service sales 
        $salesService = new SalesService();

        // gate Sales Data
        $data = $salesService->getDataByStatus($request, $request->input('payment_status'));

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'No',
            'Company Name',
            'Sales Date',
            'Invoice',
            'Total',
            'Status',
            'Payment Method',
            'Document Code'
        ];

         // Define payment status
        $paymentStatusList = array(
            '-1'=> 'Paid (decline)',
            '0' => 'Unpaid',
            '1' => 'Paid',
            '2' => 'Paid (waiting confirmation)',
            '3' => 'Delivered'
        );
        // Convert each ot the returned collection into an array, and append it to the payments array
        $no = 0;
        foreach ($data['data']->get() as $row) {
            $no ++;
            
            if ($paymentStatusList[$row->payment_status]){
                $payment_status = $paymentStatusList[$row->payment_status];
            }else{
                $payment_status = "Paid";
            }

            $examsArray[] = [
                $no,
                $row->company_name,
                $row->created_at,
                $row->invoice,
                number_format($row->cust_price_payment, 0, '.', ','),
                $payment_status,
                ($row->payment_method == 1)?'ATM':$row->VA_name,
                $row->stel_code
            ];
        }

        // Create log
        $logService = new LogService;
        $logService->createLog('download_excel', self::SALES,'');

        $excel = \App\Services\ExcelService::download($examsArray, 'Data Sales');
        return response($excel['file'], 200, $excel['headers']);
    }

    public function edit(Request $request, $sidang_id)
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            //initial var
            $message = null;
            if($request->has('tag')){
                $sidang = Sidang::find($sidang_id);
                $sidang->status = 'ON GOING';
                $sidang->save();
            }
            $data = $this->getData($request, $sidang_id)['data']->get();
            return view('admin.sidang.edit')
                ->with('data', $data)
            ;
        }else{
            return view('errors.401');
        }
    }

    public function update(Request $request, $id)
    {
        dd($request->all());
        $currentUser = Auth::user();
        $Sidang = Sidang::find($id);
        $notificationService = new NotificationService();
        $logService = new LogService;

        $oldStel = clone $Sidang;  
		$notifUploadSTEL = 0;
        
        try{
            $STELSales->save();
            
            return redirect(self::ADMIN_SIDANG);
        } catch(Exception $e){ return redirect(self::ADMIN_SIDANG.'/'.$STELSales->id.'/edit')->with(self::ERROR, 'Save failed');
        }
 
    }

    public function destroy($id, $reasonOfDeletion){
        //Get data
        $sidang = Sidang::find($id);
        
        // Filter and Feedback if no data found
        if (!$sidang){
            Session::flash('error', 'Undefined Data');
            return redirect('/admin/sidang/');
        }

        // Delete the record(s)
        $detail = Sidang_detail::where('sidang_id',$id)->delete();
        $sidang->delete();

        // Create Admin Log
        $logService = new LogService();
        $logService->createAdminLog('Hapus Data Sidang QA', 'Sidang', $sidang, urldecode($reasonOfDeletion) );

        // Feedback succeed
        Session::flash('message', 'Successfully Delete Data');
        return redirect('/admin/sidang/');
    }

}