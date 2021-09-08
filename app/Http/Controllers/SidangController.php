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
    
    private const ADMIN = 'admin';
    private const ADMIN_SALES = '/admin/sales';
    private const APP_GATEWAY_TPN = 'app.gateway_tpn';
    private const APP_URL_API_TPN = 'app.url_api_tpn';
    private const AUTHORIZATION = 'Authorization';
    private const BASE_URI = 'base_uri';
    private const DATA_NOT_FOUND = 'Data not found';
    private const ERROR = 'error';
    private const HEADERS = 'headers';
    private const HTTP_ERROR = 'http_errors';
    private const IS_READ = 'is_read';
    private const LOGIN = 'login';
    private const MESSAGE = 'message';
    private const MINIO = 'minio';
    private const PAYMENT_DETAIL = 'payment_detail/';
    private const PAYMENT_STATUS = 'payment_status';
    private const PAYMENT_STATUS2 = 'payment_status2';
    private const PAYMENT_STATUS3 = 'payment_status3';
    private const REQUIRED = 'required';
    private const SALES = 'SALES';
    private const SEARCH = 'search';
    private const SEARCH2 = 'search2';
    private const SEARCH3 = 'search3';
    private const STELS = 'stels';
    private const TIMEOUT = 'timeout';
    private const USERS = 'users';
    private const USER_ID_R = 'user_id';
    private const V1_INVOICE = 'v3/invoices/';

    //Databse related const
    private const AS_COMPANY_NAME = 'companies.name as company_name';
    private const COMPANIES = 'companies';
    private const COMPANIES_DOT_ID = 'companies.id';
    private const STELS_CODE = 'stels.code';
    private const STELS_ID = 'stels.id';
    private const STELS_NAME = 'stels.name';
    private const STELS_PRICE = 'stels.price';
    private const STELS_SALES = 'stels_sales';
    private const STELS_SALES_ATTACHMENT = 'stels_sales_attachment';
    private const STELS_SALES_ATTACHMENT_DOT_STEL_SALES_ID = 'stels_sales_attachment.stel_sales_id';
    private const STELS_SALES_DOT_ID = 'stels_sales.id';
    private const STELS_SALES_DETAIL_ATTACHMENT = 'stels_sales_detail.attachment';
    private const STELS_SALES_DETAIL_DOT_ID = 'stels_sales_detail.id';
    private const STELS_SALES_DETAIL_DOT_STELS_ID = 'stels_sales_detail.stels_id';
    private const STELS_SALES_DETAIL_QTY = 'stels_sales_detail.qty';
    private const STELS_SALES_DETAIL_STELS_ID = 'stels_sales_detail.stels_sales_id';
    private const STELS_SALES_DOT_USER_ID = 'stels_sales.user_id';
    private const STELS_SALES_INVOICE = 'stels_sales.invoice';
    private const STELS_SALES_DOT_PAYMENT_STATUS = 'stels_sales.payment_status';
    private const STELS_SALES_ID = 'stels_sales_id';
    private const USER_COMPANIES_ID = 'users.company_id';
    private const USER_ID = 'users.id'; 

    protected const STELS_SALES_DETAIL_ID = 'stels_sales_detail_id'; 
    protected const STEL_FILE = 'stel_file';

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $message = null;
        $paginate = 10;
        $tab = $request->input('tab');
        $search = $request->input('search2');
        $before = $request->input('before');
        $after = $request->input('after');
        
        //return view to saves index with data
        return view('admin.sidang.index')
            ->with('tab', $tab)
            ->with('data_sidang', $this->getData($request, 1)['data']->paginate($paginate, ['*'], 'pageSidang') )
            ->with('data_perangkat', $this->getData($request, 2)['data']->paginate($paginate, ['*'], 'pagePerangkat'))
            ->with('data_pending', $this->getData($request, 3)['data']->paginate($paginate, ['*'], 'pagePending'))
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
                
            case '2': //Perangkat Belum Sidang
                $query = Examination::with('device')->with('company')
                    ->where('examination_type_id', 1)
                    ->where('resume_status', 1)
                    ->where('qa_status', 0)
                    ->where('qa_passed', 0)
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
                
            case '3': //Perangkat Pending
                $query = Sidang_detail::with('examination')
                    ->with('examination.device')
                    ->with('examination.company')
                    ->where('result', 2)
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
                
            default: //Get Data Sidang by ID
                $query = Sidang_detail::with('examination')
                    ->with('examination.device')
                    ->with('examination.company')
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

    public function create(Request $request)
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            //initial var
            $message = null;
            $paginate = 10;
            $tab = $request->input('tab');
            $search = $request->input('search2');
            $sidang_id = null;
            
            //return view to saves index with data
            return view('admin.sidang.create')
                ->with('tab', $tab)
                ->with('data_perangkat', $this->getData($request, 2)['data']->paginate($paginate, ['*'], 'pagePerangkat'))
                ->with('data_pending', $this->getData($request, 3)['data']->paginate($paginate, ['*'], 'pagePending'))
                ->with('data_draft', $this->getData($request, $sidang_id)['data']->paginate($paginate, ['*'], 'pageDraft'))
                ->with('search', $search)
            ;
        }else{
            return view('errors.401');
        }
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            self::STELS => self::REQUIRED,
            self::USER_ID_R => self::REQUIRED,
        ]);
        
        $currentUser = Auth::user();
        $logService = new LogService();
        foreach ($request->input(self::STELS) as $key => $value) {
            $stels = explode('-myToken-', $value);
            $stel_id[] = $stels[0];
            $stel_price[] = $stels[1];
        }

        $tax = $request->has('is_tax') ? 0.1*array_sum($stel_price) : 0;

        $sales = new STELSales;
        $sales->user_id = $request->input(self::USER_ID_R);
        $sales->invoice = '';
        $sales->name = '';
        $sales->exp = '';
        $sales->cvc = '';
        $sales->cvv = '';
        $sales->type = '';
        $sales->no_card = '';
        $sales->no_telp = '';
        $sales->email = '';
        $sales->country = '';
        $sales->province = '';
        $sales->city = '';
        $sales->postal_code = '';
        $sales->birthdate = '';
        $sales->payment_method = 1;
        $sales->payment_status = 1;
        $sales->total = array_sum($stel_price) + $tax;
        $sales->cust_price_payment = array_sum($stel_price) + $tax;
        $sales->created_by = $request->input(self::USER_ID_R);
        $sales->updated_by = $currentUser->id;

        try{
            if($sales->save()){
                foreach($stel_id as $key => $row){ 
                    $STELSalesDetail = new STELSalesDetail;
                    $STELSalesDetail->stels_sales_id = $sales->id;
                    $STELSalesDetail->stels_id = $stel_id[$key];
                    $STELSalesDetail->qty = 1;
                    $STELSalesDetail->created_by = $request->input(self::USER_ID_R);
                    $STELSalesDetail->updated_by = $request->input(self::USER_ID_R);
                    $STELSalesDetail->save();
                }
            }
            $logService->createAdminLog('Tambah Data Pembelian STEL', 'Rekap Pembelian STEL', $sales.$STELSalesDetail, '' );
            Session::flash(self::MESSAGE, 'Sales successfully created');
            return redirect(self::ADMIN_SALES);
        } catch(Exception $e){ return redirect('/admin/sales/create')->with(self::ERROR, 'Save failed');
        }

        
    } 

    public function show($id)
    {
        $select = array(self::STELS_NAME,self::STELS_PRICE,self::STELS_CODE,self::STELS_SALES_DETAIL_QTY,self::STELS_SALES_DETAIL_DOT_ID,self::STELS_SALES_DETAIL_ATTACHMENT); 
        $STELSales_detail = STELSalesDetail::select($select)->where(self::STELS_SALES_ID,$id)
                    ->join(self::STELS,self::STELS_ID,"=",self::STELS_SALES_DETAIL_DOT_STELS_ID)
                    ->get();
        $STELSales = STELSales::find($id);
        if(!$STELSales){return redirect(self::ADMIN_SALES)->with(self::ERROR, 'Undefined Data'); }
        return view('admin.sales.detail')
            ->with('data', $STELSales_detail) 
            ->with('id_sales', $id) 
            ->with('id_kuitansi', $STELSales->id_kuitansi) 
            ->with('faktur_file', $STELSales->faktur_file) 
            ->with('price_total', $STELSales->total)
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

    public function edit($id)
    {
        $select = array(self::STELS_SALES_DOT_ID,"stels_sales.id_kuitansi","stels_sales.faktur_file","stels_sales_attachment.attachment",self::STELS_SALES_ATTACHMENT_DOT_STEL_SALES_ID);  
        $stel = STELSales::select($select)->leftJoin(self::STELS_SALES_ATTACHMENT,self::STELS_SALES_ATTACHMENT_DOT_STEL_SALES_ID,"=",self::STELS_SALES_DOT_ID)
                ->where(self::STELS_SALES_DOT_ID,$id)->first();        
        
		$select = array(self::STELS_NAME,self::STELS_PRICE,self::STELS_CODE,self::STELS_SALES_DETAIL_QTY,self::STELS_SALES_DETAIL_DOT_ID,self::STELS_SALES_DETAIL_ATTACHMENT,"stels.attachment as stelAttach",self::STELS_SALES_INVOICE, self::AS_COMPANY_NAME,self::STELS_SALES_DOT_PAYMENT_STATUS); 
		$STELSales = STELSalesDetail::select($select)->where(self::STELS_SALES_ID,$id)
					->join(self::STELS_SALES,self::STELS_SALES_DOT_ID,"=",self::STELS_SALES_DETAIL_STELS_ID)
					->join(self::STELS,self::STELS_ID,"=",self::STELS_SALES_DETAIL_DOT_STELS_ID)
					->join(self::USERS,self::USER_ID,"=",self::STELS_SALES_DOT_USER_ID)
					->join(self::COMPANIES,self::COMPANIES_DOT_ID,"=",self::USER_COMPANIES_ID)
					->get();
        return view('admin.sales.edit')
            ->with('data', $stel)
            ->with('dataStel', $STELSales);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            self::PAYMENT_STATUS => self::REQUIRED,
            self::STELS_SALES_DETAIL_ID => self::REQUIRED,
            self::STELS_SALES_ATTACHMENT => self::REQUIRED,
            self::STEL_FILE => self::REQUIRED,
        ]);

		$currentUser = Auth::user();
        $STELSales = STELSales::find($id);
        $notificationService = new NotificationService();
        $salesService = new SalesService();
        $logService = new LogService;

        $oldStel = clone $STELSales;  
		$notifUploadSTEL = 0;
        $data = array();

        //Save STEL files *************************************************************************
        $savedSTELFiles = $salesService->saveSTELFiles($request, $STELSales, $data);
        $attachment_count = $savedSTELFiles['attachment_count'];
        $success_count = $savedSTELFiles['success_count'];
        $data = $savedSTELFiles['data'];
        $notifUploadSTEL = $savedSTELFiles['notifUploadSTEL'];
        
        /*TPN api_  */
        if($STELSales->BILLING_ID != null && $data != null){
            $data [] = array(
                'name'=>"delivered",
                'contents'=>json_encode(['by'=>$currentUser->name, "reference_id" => '1']),
            );
            $data [] = array(
                'name'=>"type",
                'contents'=>"bast",
            );
            $this->api_upload($data,$STELSales->BILLING_ID);
        }

        $receiptAndInvoice = $salesService->saveReceiptAndInvoiceFiles($request, $STELSales);
        $STELSales = $receiptAndInvoice['STELSales'];

        //Update STELSales payment Status
        $STELSales->updated_by = $currentUser->id; 
        $STELSales->payment_status = ( $success_count == $attachment_count && $attachment_count > 0 ) ? 3 : $request->input(self::PAYMENT_STATUS);

        //Making Log, Notification, Redirect according to Stelsales payment status. 
        try{
            $STELSales->save();
            if($notifUploadSTEL == 1){
                    
                $data['id'] = $notificationService->make(array(
                    "from"=>self::ADMIN,
                    "to"=>$STELSales->created_by,
                    self::MESSAGE=>"STEL telah diupload",
                    "url"=>self::PAYMENT_DETAIL.$STELSales->id,
                    self::IS_READ=>0,
                ));
                // event(new Notification($data));
                
                // Create log
                $logService->createLog('Upload Dokumen Pembelian STEL', self::SALES,$oldStel);
                Session::flash(self::MESSAGE, 'STELS successfully uploaded');
            }else{
                if ($STELSales->payment_status == 1) {

                    // Make PAYMENT ACCEPTED notification & event
                    $data['id'] = $notificationService->make(array(
                        "from"=>self::ADMIN,
                        "to"=>$STELSales->created_by,
                        self::MESSAGE=>"Pembayaran Stel Telah diterima",
                        "url"=>self::PAYMENT_DETAIL.$STELSales->id,
                        self::IS_READ=>0,
                    ));
                    // event(new Notification($data));
                } else if($STELSales->payment_status == -1) {

                    // Make PAYMENT REJECTED notification & event
                    $data['id'] = $notificationService->make(array(
                        "from"=>self::ADMIN,
                        "to"=>$STELSales->created_by,
                        self::MESSAGE=>"Pembayaran Stel Telah ditolak",
                        "url"=>self::PAYMENT_DETAIL.$STELSales->id,
                        self::IS_READ=>0,
                    ));
                    // event(new Notification($data));
                }

                //create log
                $logService->createLog('Update Status Pembayaran STEL', self::SALES,$oldStel);

                //flash message
                Session::flash(self::MESSAGE, 'SALES successfully updated');
            }
            return redirect(self::ADMIN_SALES);
        } catch(Exception $e){ return redirect(self::ADMIN_SALES.'/'.$STELSales->id.'/edit')->with(self::ERROR, 'Save failed');
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

	public function deleteProduct($id,$reason = null)
    {
        $logService = new LogService();
        $logs_a_stel_sales = NULL;
        $logs_a_stel_sales_detail = NULL;
        
        $stel_sales_detail = STELSalesDetail::with('stel')->find($id);
        if($stel_sales_detail){
            $stel_sales = STELSales::find($stel_sales_detail->stels_sales_id);

            $logs_a_stel_sales_detail = $stel_sales_detail;
            
            // unlink stels_sales_detail.attachment
            if (\Storage::disk(self::MINIO)->exists("stelAttach/$id")){ \Storage::disk(self::MINIO)->deleteDirectory("stelAttach/$id"); }

            // update total stels_sales by stels_sales_detail.stels_sales_id
            if($stel_sales){
                $logs_a_stel_sales = $stel_sales;
                $qty = $stel_sales_detail->qty;
                $tax = 0.1;
                $price = $stel_sales_detail->stel->price;
                $total = $price+($price*$tax*$qty);
                $stel_sales->total -= $total;
                $stel_sales->cust_price_payment -= $total;
                $stel_sales->update();
            }

            // delete stels_sales_detail
            $stel_sales_detail->delete();
            $logService->createAdminLog('Hapus Data Pembelian STEL', 'Detail Pembelian STEL', $logs_a_stel_sales.$logs_a_stel_sales_detail, urldecode($reason) );
            Session::flash(self::MESSAGE, 'Successfully Delete Data');
            return redirect('/admin/sales/'.$stel_sales->id);
        }else{
            Session::flash(self::ERROR, 'Undefined Data');
            return redirect('/admin/sales/');
        }

    }

}