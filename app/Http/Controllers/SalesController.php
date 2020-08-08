<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\STEL;
use App\Logs;
use App\Logs_administrator;
use App\STELSales;
use App\STELSalesAttach;
use App\STELSalesDetail;
use App\User;

use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;
use App\Services\NotificationService;

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

class SalesController extends Controller
{ 
    
    private const ADMIN = 'admin';
    private const ADMIN_SALES = '/admin/sales';
    private const APP_GATEWAY_TPN = 'app.gateway_tpn';
    private const APP_URL_API_TPN = 'app.url_api_tpn';
    private const AUTHORIZATION = 'Authorization';
    private const BASE_URI = 'base_uri';
    private const ERROR = 'error';
    private const HEADERS = 'headers';
    private const HTTP_ERROR = 'http_errors';
    private const IS_READ = 'is_read';
    private const LOGIN = 'login';
    private const MESSAGE = 'message';
    private const MINIO = 'minio';
    private const PAYMENT_DETAIL = 'payment_detail/';
    private const PAYMENT_STATUS = 'payment_status';
    private const SALES = 'SALES';
    private const SEARCH = 'search';
    private const STELS = 'stels';
    private const TIMEOUT = 'timeout';
    private const USERS = 'users';
    private const USER_ID_R = 'users_id';
    private const V1_INVOICE = 'v1/invoices/';

    //Databse related const
    private const AS_COMPANY_NAME = 'companies.name as company_name';
    private const COMPANIES = 'companies';
    private const COMPANIES_DOT_ID = 'companies.id';
    private const STELS_CODE = 'stels.code';
    private const STELS_ID = 'stels.id';
    private const STELS_NAME = 'stels.name';
    private const STELS_PRICE = 'stels.price';
    private const STELS_SALES = 'stels_sales';
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

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $message = null;
        $paginate = 10;
        $salesService = new SalesService();
        // gate Sales Data
        $data = $salesService->getData($request);
        // give message if data not found
        if (count($data['data']->paginate($paginate)) == 0){
            $message = 'Data not found';
        }
        //return view to saves index with data
        return view('admin.sales.index')
            ->with(self::MESSAGE, $message)
            ->with('data', $data['data']->paginate($paginate))
            ->with(self::SEARCH, $data[self::SEARCH])
            ->with(self::PAYMENT_STATUS, $data['paymentStatus'])
            ->with('before_date', $data['before'])
            ->with('after_date', $data['after']);
    }


    public function create()
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            $stels = STEL::where("is_active", 1)->orderBy('name')->get();
            $users = User::where("role_id", 2)->where("is_active", 1)->orderBy('name')->get();
            return view('admin.sales.create')
                ->with(self::STELS, $stels)
                ->with(self::USERS, $users);
        }else{
            return view('errors.401');
        }
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            self::STELS => 'required',
            self::USER_ID_R => 'required',
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
                    $STELSalesDetail->save();
                }
            }
            $logService->createAdminLog('Tambah Data Pembelian STEL', 'Rekap Pembelian STEL', $sales.$STELSalesDetail, '' );

            Session::flash(self::MESSAGE, 'Sales successfully created');
            return redirect(self::ADMIN_SALES);
        } catch(Exception $e){
            return redirect('/admin/sales/create')->with(self::ERROR, 'Save failed');
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

    public function sales_detail($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array(self::STELS_NAME,self::STELS_PRICE,self::STELS_CODE,self::STELS_SALES_DETAIL_QTY); 
            $STELSales = STELSalesDetail::select($select)->where(self::STELS_SALES_ID,$id)
                        ->join(self::STELS,self::STELS_ID,"=",self::STELS_SALES_DETAIL_DOT_STELS_ID)
                        ->get();
            $page = "payment_detail";
            return view('client.STEL.payment_detail') 
            ->with('page', $page)   
            ->with(self::STELS, $STELSales) ;     
        }else{
            redirect(self::LOGIN);
        }
        
    }  

    public function excel(Request $request) 
    {

        $currentUser = Auth::user();
        if (!$currentUser){return redirect(self::LOGIN);}
        
        // initial service sales 
        $salesService = new SalesService();

        // gate Sales Data
        $data = $salesService->getData($request)['data']->get();

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
            '1' => 'Paid (success)',
            '2' => 'Paid (waiting confirmation)',
            '3' => 'Paid (delivered)'
        );
        
        // Convert each ot the returned collection into an array, and append it to the payments array
        $no = 0;
        foreach ($data as $row) {
            $no ++;
            
            if ($paymentStatusList[$row->payment_status]){
                $payment_status = $paymentStatusList[$row->payment_status];
            }else{
                $payment_status = "Paid (success)";
            }

            $examsArray[] = [
                $no,
                $row->company_name,
                $row->created_at,
                $row->invoice,
                number_format($row->cust_price_payment, 0, '.', ','),
                $payment_status,
                ($row->payment_method == 1)?'ATM':'Kartu Kredit',
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
        $stel = STELSalesAttach::select($select)->rightJoin(self::STELS_SALES,self::STELS_SALES_DOT_ID,"=",self::STELS_SALES_ATTACHMENT_DOT_STEL_SALES_ID)
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
            self::PAYMENT_STATUS => 'required',
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
        
        /*TPN api_  */
        if($STELSales->BILLING_ID != null && $data != null){
            $data [] = array(
                'name'=>"delivered",
                'contents'=>json_encode(['by'=>$currentUser->name, "reference_id" => $currentUser->id]),
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
                event(new Notification($data));
                
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
                    event(new Notification($data));
                } else if($STELSales->payment_status == -1) {

                    // Make PAYMENT REJECTED notification & event
                    $data['id'] = $notificationService->make(array(
                        "from"=>self::ADMIN,
                        "to"=>$STELSales->created_by,
                        self::MESSAGE=>"Pembayaran Stel Telah ditolak",
                        "url"=>self::PAYMENT_DETAIL.$STELSales->id,
                        self::IS_READ=>0,
                    ));
                    event(new Notification($data));
                }

                //create log
                $logService->createLog('Update Status Pembayaran STEL', self::SALES,$oldStel);

                //flash message
                Session::flash(self::MESSAGE, 'SALES successfully updated');
            }

            return redirect(self::ADMIN_SALES);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::ADMIN_SALES.'/'.$STELSales->id.'/edit');
        }
 
    }

    public function api_upload($data, $BILLING_ID){
        $client = new Client([
            self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERROR => false
        ]);
        try {
            $params['multipart'] = $data;
            $res_upload = $client->post("v1/billings/".$BILLING_ID."/deliver", $params)->getBody(); //BILLING_ID
            return json_decode($res_upload);

        } catch(Exception $e){
            return null;
        }
    }

    public function api_invoice($data_invoices){
        $client = new Client([
            self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERROR => false
        ]);
        try {
            $param_invoices['json'] = $data_invoices;
            $res_invoices = $client->post("v1/invoices", $param_invoices)->getBody()->getContents();
            return json_decode($res_invoices);

            /*get
                $invoice->status; //if true lanjut, else panggil lagi API ny
                $invoice->data->_id; //INVOICE_ID
            */
        } catch(Exception $e){
            return null;
        }
    }

    public function upload($id)
    {
        $select = array(self::STELS_SALES_DOT_ID,"stels_sales_attachment.attachment",self::STELS_SALES_ATTACHMENT_DOT_STEL_SALES_ID);  
        $stel = STELSalesAttach::select($select)->rightJoin(self::STELS_SALES,self::STELS_SALES_DOT_ID,"=",self::STELS_SALES_ATTACHMENT_DOT_STEL_SALES_ID)
                ->where(self::STELS_SALES_DOT_ID,$id)->first();
                
        $select = array(self::STELS_NAME,self::STELS_PRICE,self::STELS_CODE,self::STELS_SALES_DETAIL_QTY,self::STELS_SALES_DETAIL_DOT_ID,self::STELS_SALES_DETAIL_ATTACHMENT,"stels.attachment as stelAttach",self::STELS_SALES_INVOICE,self::AS_COMPANY_NAME,self::STELS_SALES_DOT_PAYMENT_STATUS,"stels_sales.BILLING_ID",self::STELS_SALES_INVOICE); 
        $STELSales = STELSalesDetail::select($select)->where(self::STELS_SALES_ID,$id)
                    ->join(self::STELS_SALES,self::STELS_SALES_DOT_ID,"=",self::STELS_SALES_DETAIL_STELS_ID)
                    ->join(self::STELS,self::STELS_ID,"=",self::STELS_SALES_DETAIL_DOT_STELS_ID)
                    ->join(self::USERS,self::USER_ID,"=", self::STELS_SALES_DOT_USER_ID)
                    ->join(self::COMPANIES,self::COMPANIES_DOT_ID,"=",self::USER_COMPANIES_ID)
                    ->get();
        
        if($STELSales[0]->BILLING_ID && !$STELSales[0]->INVOICE_ID){
            Session::flash(self::ERROR, "Can't upload attachment. Undefined INVOICE_ID!");
            return redirect(self::ADMIN_SALES.'/');
        }else{
            return view('admin.sales.upload')
                ->with('data', $stel)
                ->with('dataStel', $STELSales);
        }
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
            if (File::exists(public_path().'\media\stelAttach\\'.$id)){
                File::deleteDirectory(public_path().'\media\stelAttach\\'.$id);
            }

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

    public function viewMedia($id) //bukti pembayaran
    {
        $stel = STELSalesAttach::where("stel_sales_id",$id)->first();

        if ($stel){
            $fileMinio = Storage::disk(self::MINIO)->get("stel/$stel->stel_sales_id/$stel->attachment");
            
            return response($fileMinio, 200, \App\Services\MyHelper::getHeaderImage($stel->attachment));
        }
    }

    public function viewWatermark($id) //Stel Detail Attachment
    {
        $stel = STELSalesDetail::where("id",$id)->first();

        if ($stel){
            $fileMinio = Storage::disk(self::MINIO)->get("stelAttach/$stel->id/$stel->attachment");
            
            return response($fileMinio, 200, \App\Services\MyHelper::getHeaderImage($stel->attachment));
        }
    }

    public function downloadkuitansistel($id) //download kuitansi
    {
        $stel = STELSales::where("id_kuitansi", 'like', $id . '%' )->first();

        if ($stel){
            $fileMinio = Storage::disk(self::MINIO)->get("stel/$stel->id/$stel->id_kuitansi");
            
            return response($fileMinio, 200, \App\Services\MyHelper::getHeaderImage($stel->id_kuitansi));
        }
    }

    public function downloadfakturstel($id) //download faktur
    {
        $stel = STELSales::where("id",$id)->first();

        if ($stel){
            $fileMinio = Storage::disk(self::MINIO)->get("stel/$stel->id/$stel->faktur_file");
            
            return response($fileMinio, 200, \App\Services\MyHelper::getHeaderImage($stel->faktur_file));
        }
    }

    public function generateKuitansi(Request $request) {
        $this->validate($request, [
            'id' => 'required',
        ]);
    
        $salesService = new SalesService();
        $client = new Client([
            self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERROR => false
        ]);

        $STELSales = STELSales::where("id", $request->input('id'))->first();
        if(!$STELSales){return "Data Pembelian Tidak Ditemukan!";}

        try {
            $INVOICE_ID = $STELSales->INVOICE_ID;
            $res_invoice = $client->request('GET', self::V1_INVOICE.$INVOICE_ID);
            $invoice = json_decode($res_invoice->getBody());
            
            if($INVOICE_ID && $invoice && $invoice->status){
                // Save Kuitansi
                $result = $salesService->saveKuitansi($invoice, $INVOICE_ID, $request, $client, $STELSales);
            }else{
                $result = "Data Invoice Tidak Ditemukan!";        
            }
        } catch(Exception $e){
            $result = null;
        }

        return $result;
    }

    public function generateTaxInvoice(Request $request) {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $salesService = new SalesService;
        $client = new Client([
            self::HEADERS => [self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERROR => false
        ]);

        $STELSales = STELSales::where("id", $request->input('id'))->first();

        if(!$STELSales){return "Data Pembelian Tidak Ditemukan!";}
        
        /* GENERATE NAMA FILE FAKTUR */
        $stel = STELSales::select(DB::raw('companies.name as company_name, 
            (
                SELECT GROUP_CONCAT(stels.code SEPARATOR ", ")
                FROM
                    stels,
                    stels_sales_detail
                WHERE
                    stels_sales_detail.stels_sales_id = stels_sales.id
                AND
                    stels_sales_detail.stels_id = stels.id
            ) as description, DATE(stels_sales_attachment.updated_at) as payment_date'))
            ->join(self::USERS, 'stels_sales.created_by', '=', self::USER_ID)
            ->join(self::COMPANIES, self::USER_COMPANIES_ID, '=', self::COMPANIES_DOT_ID)
            ->join('stels_sales_detail', self::STELS_SALES_DOT_ID, '=', self::STELS_SALES_DETAIL_STELS_ID)
            ->leftJoin('stels_sales_attachment', self::STELS_SALES_DOT_ID, '=', 'stels_sales_attachment.stel_sales_id')
            ->join(self::STELS, self::STELS_SALES_DETAIL_DOT_STELS_ID, '=', self::STELS_ID)
            ->where(self::STELS_SALES_DOT_ID, $request->input('id'))
            ->get();

        $filename = ($stel ? $stel[0]->payment_date.'_'.$stel[0]->company_name.'_'.$stel[0]->description : $STELSales->INVOICE_ID);

        
        try {
            $INVOICE_ID = $STELSales->INVOICE_ID;
            $res_invoice = $client->request('GET', self::V1_INVOICE.$INVOICE_ID);
            $invoice = json_decode($res_invoice->getBody());
            
            if($INVOICE_ID && $invoice && $invoice->status){
                $status_invoice = $invoice->data->status_invoice;
                //here 
                $result = $salesService->saveFakturPajak($status_invoice, $invoice, $filename, $request, $client, $INVOICE_ID, $STELSales );
                
            }else{
                $result = "Data Invoice Tidak Ditemukan!";        
            }
        } catch(Exception $e){
            $result = null;
        }

        return $result;
    }

}

class SalesService
{
    protected const ADMIN_SALES = '/admin/sales';
    protected const AS_COMPANY_NAME = 'companies.name as company_name';
    protected const EDIT = '/edit';
    protected const ERROR = 'error';
    protected const FAKTUR_FILE = 'faktur_file';
    protected const KUITANSI_FILE = 'kuitansi_file';
    protected const MEDIA_STEL = '/media/stel/';
    protected const MINIO = 'minio';
    protected const QUERY = 'query';
    protected const SEARCH = 'search';
    protected const STEL_FILE = 'stel_file';
    protected const STELS_SALES_DETAIL_DOT_STELS_ID = 'stels_sales_detail.stels_id';
    protected const STELS_SALES_DETAIL_ID = 'stels_sales_detail_id';
    protected const STELS_SALES_DETAIL_STELS_ID = 'stels_sales_detail.stels_sales_id';
    protected const STELS_SALES_DOT_ID = 'stels_sales.id';
    protected const STELS_SALES_DOT_USER_ID = 'stels_sales.user_id';
    protected const STELS_SALES_DOT_PAYMENT_STATUS = 'stels_sales.payment_status';
    protected const STELS_SALES_INVOICE = 'stels_sales.invoice';
    protected const USER_COMPANIES_ID = 'users.company_id';
    protected const USER_ID = 'users.id';
    protected const V1_INVOICE = 'v1/invoices/';
    
    public function getData(Request $request)
    {
        $logService = new LogService();
        //query awal untuk sales controller
        $dataSales = $this->initialQuery(); 
        //inisial service sales dan queryFilter
        $queryFilter = new QueryFilter($request, $dataSales);
        //filter search if has input create log.
        $searchFiltered = $this->search($request, $dataSales);
        if ($searchFiltered[self::SEARCH]!=''){
            $queryFilter = new QueryFilter($request, $searchFiltered['dataSales']);
            $logService->createLog('Search Sales','Sales',json_encode(array(self::SEARCH=>$searchFiltered[self::SEARCH])));
        }
        //filterquery
        $queryFilter
            ->paymentAllStatus()
            ->beforeDate(DB::raw('DATE(stels_sales.created_at)'))
            ->afterDate(DB::raw('DATE(stels_sales.created_at)'))
            ->getSortedAndOrderedData('created_at','desc')
        ;
        //get the data and sort them
        return array(
            'data' => $queryFilter->getQuery(),
            $this::SEARCH => $searchFiltered[$this::SEARCH],
            'paymentStatus' => $queryFilter->paymentStatus,
            'before' => $queryFilter->before,
            'after' => $queryFilter->after,
        );
    }

    public function initialQuery()
    {
        $select = array(self::STELS_SALES_DOT_ID,"stels_sales.created_at",self::STELS_SALES_INVOICE, self::STELS_SALES_DOT_PAYMENT_STATUS,"stels_sales.payment_method","stels_sales.total","stels_sales.cust_price_payment",self::AS_COMPANY_NAME,
        DB::raw('stels_sales.id as _id,
                (
                    SELECT GROUP_CONCAT(stels.name SEPARATOR ", ")
                    FROM
                        stels,
                        stels_sales_detail
                    WHERE
                        stels_sales_detail.stels_sales_id = _id
                    AND
                        stels_sales_detail.stels_id = stels.id
                ) as stel_name
                ,(
                    SELECT GROUP_CONCAT(stels.code SEPARATOR ", ")
                    FROM
                        stels,
                        stels_sales_detail
                    WHERE
                        stels_sales_detail.stels_sales_id = _id
                    AND
                        stels_sales_detail.stels_id = stels.id
                ) as stel_code')
        ); 

        return STELSales::select($select)->distinct()->whereNotNull('stels_sales.created_at')
            ->join("users", self::USER_ID,"=", self::STELS_SALES_DOT_USER_ID)
            ->join("companies",self::USER_COMPANIES_ID,"=","companies.id")
            ->join("stels_sales_detail", self::STELS_SALES_DETAIL_STELS_ID,"=",self::STELS_SALES_DOT_ID)
            ->join("stels","stels.id","=",self::STELS_SALES_DETAIL_DOT_STELS_ID);
    }

    public function search(Request $request, $dataSales)
    {
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        if($search!=''){
            $dataSales = $dataSales->where('invoice','like','%'.$search.'%')
            ->orWhere('companies.name', 'like', '%'.$search.'%')
            ->orWhere('stels.name', 'like', '%'.$search.'%')
            ->orWhere('stels.code', 'like', '%'.$search.'%');
        }
        return array(
            'dataSales' => $dataSales,
            $this::SEARCH => $search,
        );
    }

    public function saveFakturPajak($status_invoice, $invoice, $filename, $request, $client, $INVOICE_ID, $STELSales )
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE FAKTUR PAJAK*/
                $name_file = 'faktur_stel_'.$filename.'.pdf';
                $path_file = storage_path('tmp/');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }

                $response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.'/taxinvoice/pdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment; ".$stream)){
                    $fileFaktur = Storage::disk('tmp')->get($name_file);
                    Storage::disk(self::MINIO)->put("stel/$request->input('id')/$name_file", $fileFaktur );
                    File::delete(storage_path("tmp/$name_file"));

                    $STELSales->faktur_file = $name_file;
                    $STELSales->save();
                    $result = "Faktur Pajak Berhasil Disimpan.";
                }else{
                    $result = "Gagal Menyimpan Faktur Pajak!";
                }
            }else{
                $result = $invoice->data->status_faktur;
            }
        }else{
            switch ($status_invoice) {
                case 'invoiced':
                    $result = "Faktur Pajak belum Tersedia, karena Invoice Baru Dibuat.";
                    break;
                
                case 'returned':
                    $result = $invoice->data->$status_invoice->message;
                    break;
                
                default:
                $result = "Faktur Pajak belum Tersedia. Invoice sudah dikirim ke DJP.";
                    break;
            }
        }

        return $result;
    }

    public function saveKuitansi($invoice, $INVOICE_ID, $request, $client, $STELSales)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $status_invoice = $invoice->data->status_invoice;
        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE KUITANSI*/
                $name_file = 'kuitansi_stel_'.$INVOICE_ID.'.pdf';
                $path_file = storage_path('tmp/');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }
                $response = $client->request('GET', self::V1_INVOICE.$INVOICE_ID.'/exportpdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment; ".$stream)){
                    $fileKuitansi = Storage::disk('tmp')->get($name_file);
                    Storage::disk(self::MINIO)->put("stel/$request->input('id')/$name_file", $fileKuitansi );
                    File::delete(storage_path("tmp/$name_file"));

                    $STELSales->id_kuitansi = $name_file;
                    $STELSales->save();
                    $result = "Kuitansi Berhasil Disimpan.";
                }else{
                    $result = "Gagal Menyimpan Kuitansi!";
                }
            }else{
                return $invoice->data->status_faktur;
            }
        }else{
            switch ($status_invoice) {
                case 'invoiced':
                    $result = "Invoice Baru Dibuat.";
                    break;
                
                case 'returned':
                    $result = $invoice->data->$status_invoice->message;
                    break;
                
                default:
                    $result = "Invoice sudah dikirim ke DJP.";
                    break;
            }
        }

        return $result;
    }


    //Save Receipt files *************************************************************************
    public function saveReceiptAndInvoiceFiles($request, $STELSales)
    {
        if ($request->hasFile(self::KUITANSI_FILE)) {
            $name_file = 'kuitansi_stel_'.$request->file(self::KUITANSI_FILE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_STEL.$STELSales->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::KUITANSI_FILE)->move($path_file,$name_file)){
                $STELSales->id_kuitansi = $name_file;					
            }else{
                Session::flash(self::ERROR, 'Save Receipt to directory failed');
                return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
            }
        }
    
        //Save Invoice files *************************************************************************
        if ($request->hasFile(self::FAKTUR_FILE)) {
            $name_file = 'faktur_stel_'.$request->file(self::FAKTUR_FILE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_STEL.$STELSales->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::FAKTUR_FILE)->move($path_file,$name_file)){
                $STELSales->faktur_file = $name_file;					
            }else{
                Session::flash(self::ERROR, 'Save Invoice to directory failed');
                return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
            }
        }

        return array(
            'STELSales' => $STELSales,
        );
    }

    public function saveSTELFiles($request, $STELSales, $data)
    {
        $this->validate($request, [
            self::STELS_SALES_DETAIL_ID => 'required',
            'stels_sales_attachment' => 'required',
            self::STEL_FILE => 'required',
        ]);

        $notifUploadSTEL = null;
        $attachment_count = 0;
        $success_count = 0;
        for($i=0;$i<count($request->input(self::STELS_SALES_DETAIL_ID));$i++){
            $STELSalesDetail = STELSalesDetail::find($request->input(self::STELS_SALES_DETAIL_ID)[$i]);
            $attachment = $request->input('stels_sales_attachment')[$i];
            if(!$attachment){$attachment_count++;}
            if ($request->file(self::STEL_FILE)[$i]) {

                $file = $request->file(self::STEL_FILE)[$i];
                $name_file = 'stel_file_'.$request->file(self::STEL_FILE)[$i]->getClientOriginalName();

                $is_uploaded = Storage::disk(self::MINIO)->put("stelAttach/".$STELSalesDetail->id."/$name_file", file_get_contents($file));
                $path_file = Storage::disk(self::MINIO)->url("stelAttach/".$STELSalesDetail->id."/$name_file");

                if($is_uploaded){
                    $STELSalesDetail->attachment = $name_file;
                    $STELSalesDetail->save();
                    $notifUploadSTEL = 1;
                    $success_count++;

                    /*TPN api_upload*/
                    $data [] = [
                            'name' => "file",
                            'contents' => fopen($path_file, 'r'),
                            'filename' => $request->file(self::STEL_FILE)[$i]->getClientOriginalName()
                        ];
                }else{
                    Session::flash(self::ERROR, 'Save STEL to directory failed');
                    return redirect(self::ADMIN_SALES.'/'.$STELSales->id.self::EDIT);
                }
            }
        }

        return array(
            'attachment_count' => $attachment_count,
            'success_count' => $success_count,
            'notifUploadSTEL' => $notifUploadSTEL,
            'data' => $data,
        );
    } 
}