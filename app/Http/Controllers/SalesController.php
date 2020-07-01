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
use App\Services\Admin\SalesService;
use App\Services\Logs\LogService;
use App\Services\NotificationService;

use Auth;
use Session; 
use Validator;
use Excel;
use Response;

use File;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable;

class SalesController extends Controller
{ 
    private const SEARCH = 'search';
    private const MESSAGE = 'message';
    private const LOGIN = 'login';
    private const PAYMENT_STATUS = 'payment_status';
    private const STELS = 'stels';
    private const USERS = 'users';
    private const ADMIN_SALES = '/admin/sales';
    private const FAKTUR_FILE = 'faktur_file';
    private const ERROR = 'error';
    private const SALES = 'SALES';
    private const STEL_FILE = 'stel_file';
    private const EDIT = '/edit';
    private const KUITANSI_FILE = 'kuitansi_file';
    private const MEDIA_STEL = '/media/stel/';
    private const ADMIN = 'admin';
    private const PAYMENT_DETAIL = 'payment_detail/';
    private const IS_READ = 'is_read';
    private const HEADERS = 'headers';
    private const AUTHORIZATION = 'Authorization';
    private const APP_GATEWAY_TPN = 'app.gateway_tpn';
    private const BASE_URI = 'base_uri';
    private const APP_URL_API_TPN = 'app.url_api_tpn';
    private const CONTENT_TYPE = 'Content-Type: application/octet-stream';
    private const V1_INVOICE = 'v1/invoices/';
    private const TIMEOUT = 'timeout';
    private const HTTP_ERROR = 'http_errors';

    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    public function index(Request $request)
    { 
        $currentUser = Auth::user();
        if (!$currentUser){return redirect("login");}

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
            ->with('message', $message)
            ->with('data', $data['data']->paginate($paginate))
            ->with(self::SEARCH, $data[self::SEARCH])
            ->with('payment_status', $data['paymentStatus'])
            ->with('before_date', $data['before'])
            ->with('after_date', $data['after']);
        
    }


    public function create()
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            $stels = STEL::where("is_active", 1)->orderBy('name')->get();
            $users = User::where("role_id", 2)->where("is_active", 1)->orderBy('name')->get();
            return view('admin.sales.create')
                ->with('stels', $stels)
                ->with('users', $users);
        }else{
            return view('errors.401');
        }
    }


    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        foreach ($request->input('stels') as $key => $value) {
            $stels = explode('-myToken-', $value);
            $stel_id[] = $stels[0];
            $stel_price[] = $stels[1];
        }

        $tax = $request->has('is_tax') ? 0.1*array_sum($stel_price) : 0;

        $sales = new STELSales;
        $sales->user_id = $request->input('user_id');
        $sales->payment_method = 1;
        $sales->payment_status = 1;
        $sales->total = array_sum($stel_price) + $tax;
        $sales->cust_price_payment = array_sum($stel_price) + $tax;
        $sales->created_by = $request->input('user_id');
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

            $logs = new Logs_administrator;
            $logs->id = Uuid::uuid4();
            $logs->user_id = $currentUser->id;
            $logs->action = "Tambah Data Pembelian STEL";
            $logs->page = "Rekap Pembelian STEL";
            $logs->reason = "";
            $logs->data = $sales.$STELSalesDetail;
            $logs->save();

            Session::flash('message', 'Sales successfully created');
            return redirect('/admin/sales');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/sales/create');
        }
    } 

    public function show($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array("stels.name","stels.price","stels.code","stels_sales_detail.qty","stels_sales_detail.id","stels_sales_detail.attachment"); 
            $STELSales_detail = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                        ->get();
			$STELSales = STELSales::find($id);
			return view('admin.sales.detail')
            ->with('data', $STELSales_detail) 
            ->with('id_sales', $id) 
            ->with('id_kuitansi', $STELSales->id_kuitansi) 
            ->with('faktur_file', $STELSales->faktur_file) 
            ->with('price_total', $STELSales->total)
			;     
        }else{
            redirect("login");
        }
    }  

    public function sales_detail($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array("stels.name","stels.price","stels.code","stels_sales_detail.qty"); 
            $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                        ->get();
            $page = "payment_detail";
            return view('client.STEL.payment_detail') 
            ->with('page', $page)   
            ->with('stels', $STELSales) ;     
        }else{
            redirect("login");
        }
        
    }  

    public function excel(Request $request) 
    {

        $currentUser = Auth::user();
        if (!$currentUser){return redirect("login");}
        
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
        $logService->createLog('download_excel', 'SALES','');

        // Generate and return the spreadsheet
        Excel::create('Data Sales', function($excel) use ($examsArray) { 
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }

    public function edit($id)
    {
        $select = array("stels_sales.id","stels_sales.id_kuitansi","stels_sales.faktur_file","stels_sales_attachment.attachment","stels_sales_attachment.stel_sales_id");  
        $stel = STELSalesAttach::select($select)->rightJoin("stels_sales","stels_sales.id","=","stels_sales_attachment.stel_sales_id")
                ->where("stels_sales.id",$id)->first();
				
		$select = array("stels.name","stels.price","stels.code","stels_sales_detail.qty","stels_sales_detail.id","stels_sales_detail.attachment","stels.attachment as stelAttach","stels_sales.invoice","companies.name as company_name","stels_sales.payment_status"); 
		$STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
					->join("stels_sales","stels_sales.id","=","stels_sales_detail.stels_sales_id")
					->join("stels","stels.id","=","stels_sales_detail.stels_id")
					->join("users","users.id","=","stels_sales.user_id")
					->join("companies","companies.id","=","users.company_id")
					->get();
        return view('admin.sales.edit')
            ->with('data', $stel)
            ->with('dataStel', $STELSales);
    }

    public function update(Request $request, $id)
    {
        if (!$request->has('payment_status')){return redirect('/admin/sales');}

		$currentUser = Auth::user();
        $STELSales = STELSales::find($id);
        $notificationService = new NotificationService();

        $oldStel = $STELSales;  
		$notifUploadSTEL = 0;
        $data = array();

        $attachment_count = 0;
        $success_count = 0;
        for($i=0;$i<count($request->input('stels_sales_detail_id'));$i++){
            $attachment = $request->input('stels_sales_attachment')[$i];
            if(!$attachment){$attachment_count++;}
            if ($request->file('stel_file')[$i]) {
                $name_file = 'stel_file_'.$request->file('stel_file')[$i]->getClientOriginalName();
                $path_file = public_path().'/media/stelAttach/'.$request->input('stels_sales_detail_id')[$i];
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }
                if($request->file('stel_file')[$i]->move($path_file,$name_file)){
                    $STELSalesDetail = STELSalesDetail::find($request->input('stels_sales_detail_id')[$i]);
                    $STELSalesDetail->attachment = $name_file;
                    $STELSalesDetail->save();
                    $notifUploadSTEL = 1;
                    $success_count++;

                    /*TPN api_upload*/
                    $data [] = [
                            'name' => "file",
                            'contents' => fopen($path_file.'/'.$name_file, 'r'),
                            'filename' => $request->file('stel_file')[$i]->getClientOriginalName()
                        ];
                }else{
                    Session::flash('error', 'Save STEL to directory failed');
                    return redirect('/admin/sales'.'/'.$STELSales->id.'/edit');
                }
            }
        }
        
        /*TPN api_upload*/
        if($STELSales->BILLING_ID != null && $data != null){
            $data [] = array(
                'name'=>"delivered",
                'contents'=>json_encode(['by'=>$currentUser->name, "reference_id" => $currentUser->id]),
            );

            //$upload = $this->api_upload($data,$STELSales->BILLING_ID)
        }

        if ($request->hasFile('kuitansi_file')) {
            $name_file = 'kuitansi_stel_'.$request->file('kuitansi_file')->getClientOriginalName();
            $path_file = public_path().'/media/stel/'.$STELSales->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('kuitansi_file')->move($path_file,$name_file)){
                $STELSales->id_kuitansi = $name_file;					
            }else{
                Session::flash('error', 'Save Receipt to directory failed');
                return redirect('/admin/sales'.'/'.$STELSales->id.'/edit');
            }
        }
        if ($request->hasFile('faktur_file')) {
            $name_file = 'faktur_stel_'.$request->file('faktur_file')->getClientOriginalName();
            $path_file = public_path().'/media/stel/'.$STELSales->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('faktur_file')->move($path_file,$name_file)){
                $STELSales->faktur_file = $name_file;					
            }else{
                Session::flash('error', 'Save Invoice to directory failed');
                return redirect('/admin/sales'.'/'.$STELSales->id.'/edit');
            }
        }
        $STELSales->updated_by = $currentUser->id; 
        $STELSales->payment_status = $success_count == $attachment_count && $attachment_count > 0 ? 3 : $request->input('payment_status');

        try{
            $STELSales->save();
            
            if($notifUploadSTEL == 1){
                    
                $data['id'] = $notificationService->make(array(
                    "from"=>"admin",
                    "to"=>$STELSales->created_by,
                    "message"=>"STEL telah diupload",
                    "url"=>"payment_detail/".$STELSales->id,
                    "is_read"=>0,
                ));
                event(new Notification($data));
                
                // Create log
                $logService = new LogService;
                $logService->createLog('Upload Dokumen Pembelian STEL', 'SALES',$oldStel);

                    
                    Session::flash('message', 'STELS successfully uploaded');
            }else{
                if ($STELSales->payment_status == 1) {

                    // Make PAYMENT ACCEPTED notification & event
                    $data['id'] = $notificationService->make(array(
                        "from"=>"admin",
                        "to"=>$STELSales->created_by,
                        "message"=>"Pembayaran Stel Telah diterima",
                        "url"=>"payment_detail/".$STELSales->id,
                        "is_read"=>0,
                    ));
                    event(new Notification($data));
                } else if($STELSales->payment_status == -1) {

                    // Make PAYMENT REJECTED notification & event
                    $data['id'] = $notificationService->make(array(
                        "from"=>"admin",
                        "to"=>$STELSales->created_by,
                        "message"=>"Pembayaran Stel Telah ditolak",
                        "url"=>"payment_detail/".$STELSales->id,
                        "is_read"=>0,
                    ));
                    event(new Notification($data));
                }

                //create log
                $logService->createLog('Update Status Pembayaran STEL', 'SALES',$oldStel);

                //flash message
                Session::flash('message', 'SALES successfully updated');
            }

            return redirect('/admin/sales');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/sales'.'/'.$STELSales->id.'/edit');
        }
 
    }

    public function api_upload($data, $BILLING_ID){
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
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
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
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
        $select = array("stels_sales.id","stels_sales_attachment.attachment","stels_sales_attachment.stel_sales_id");  
        $stel = STELSalesAttach::select($select)->rightJoin("stels_sales","stels_sales.id","=","stels_sales_attachment.stel_sales_id")
                ->where("stels_sales.id",$id)->first();
                
        $select = array("stels.name","stels.price","stels.code","stels_sales_detail.qty","stels_sales_detail.id","stels_sales_detail.attachment","stels.attachment as stelAttach","stels_sales.invoice","companies.name as company_name","stels_sales.payment_status","stels_sales.BILLING_ID","stels_sales.INVOICE_ID"); 
        $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                    ->join("stels_sales","stels_sales.id","=","stels_sales_detail.stels_sales_id")
                    ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                    ->join("users","users.id","=","stels_sales.user_id")
                    ->join("companies","companies.id","=","users.company_id")
                    ->get();
        
        if($STELSales[0]->BILLING_ID && !$STELSales[0]->INVOICE_ID){
            Session::flash('error', "Can't upload attachment. Undefined INVOICE_ID!");
            return redirect('/admin/sales,'.'/');
        }else{
            return view('admin.sales.upload')
                ->with('data', $stel)
                ->with('dataStel', $STELSales);
        }
    }

	public function deleteProduct($id,$reason = null)
    {
        $currentUser = Auth::user();
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

            $logs = new Logs_administrator;
            $logs->id = Uuid::uuid4();
            $logs->user_id = $currentUser->id;
            $logs->action = "Hapus Data Pembelian STEL";
            $logs->page = "Detail Pembelian STEL";
            $logs->reason = urldecode($reason);
            $logs->data = $logs_a_stel_sales.$logs_a_stel_sales_detail;
            $logs->save();

            Session::flash('message', 'Successfully Delete Data');
            return redirect('/admin/sales/'.$stel_sales->id);
        }else{
            Session::flash('error', 'Undefined Data');
            return redirect('/admin/sales/'.$stel_sales->id);
        }

    }

    public function viewMedia($id)
    {
        $stel = STELSalesAttach::where("stel_sales_id",$id)->first();

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->stel_sales_id."/".$stel->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file, $headers);
        }
    }

    public function viewWatermark($id)
    {
        $stel = STELSalesDetail::where("id",$id)->first();

        if ($stel){
            $file = public_path().'/media/stelAttach/'.$stel->id."/".$stel->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file, $headers);
        }
    }

    public function downloadkuitansistel($id)
    {
        $stel = STELSales::where("id_kuitansi",$id)->first();

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->id."/".$stel->id_kuitansi;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file, $headers);
        }
    }

    public function downloadfakturstel($id)
    {
        $stel = STELSales::where("id",$id)->first();

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->id."/".$stel->faktur_file;
            $headers = array(
              'Content-Type: application/octet-stream',
              'Content-Disposition' => 'inline; filename="'.$stel->faktur_file.'"',
            );

            return Response::file($file, $headers);
        }
    }

    public function generateKuitansi(Request $request) {
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $STELSales = STELSales::where("id", $request->input('id'))->first();
        if(!$STELSales){return "Data Pembelian Tidak Ditemukan!";}

        try {
            $INVOICE_ID = $STELSales->INVOICE_ID;
            $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
            $invoice = json_decode($res_invoice->getBody());
            
            if($INVOICE_ID && $invoice && $invoice->status){
                $status_invoice = $invoice->data->status_invoice;
                if($status_invoice == "approved"){
                    $status_faktur = $invoice->data->status_faktur;
                    if($status_faktur == "received"){
                        /*SAVE KUITANSI*/
                        $name_file = 'kuitansi_stel_'.$INVOICE_ID.'.pdf';

                        $path_file = public_path().'/media/stel/'.$request->input('id');
                        if (!file_exists($path_file)) {
                            mkdir($path_file, 0775);
                        }
                        $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/exportpdf');
                        $stream = (String)$response->getBody();

                        if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                            $STELSales->id_kuitansi = $name_file;
                            $STELSales->save();
                            return "Kuitansi Berhasil Disimpan.";
                        }else{
                            return "Gagal Menyimpan Kuitansi!";
                        }
                    }else{
                        return $invoice->data->status_faktur;
                    }
                }else{
                    switch ($status_invoice) {
                        case 'invoiced':
                            return "Invoice Baru Dibuat.";
                            break;
                        
                        case 'returned':
                            return $invoice->data->$status_invoice->message;
                            break;
                        
                        default:
                            return "Invoice sudah dikirim ke DJP.";
                            break;
                    }
                }
            }else{
                return "Data Invoice Tidak Ditemukan!";        
            }
        } catch(Exception $e){
            return null;
        }

    }

    public function generateTaxInvoice(Request $request) {
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
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
            ->join('users', 'stels_sales.created_by', '=', 'users.id')
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->join('stels_sales_detail', 'stels_sales.id', '=', 'stels_sales_detail.stels_sales_id')
            ->leftJoin('stels_sales_attachment', 'stels_sales.id', '=', 'stels_sales_attachment.stel_sales_id')
            ->join('stels', 'stels_sales_detail.stels_id', '=', 'stels.id')
            ->where('stels_sales.id', $request->input('id'))
            ->get();

        $filename = $stel ? $stel[0]->payment_date.'_'.$stel[0]->company_name.'_'.$stel[0]->description : $STELSales->INVOICE_ID;

        
        try {
            $INVOICE_ID = $STELSales->INVOICE_ID;
            $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
            $invoice = json_decode($res_invoice->getBody());
            
            if($INVOICE_ID && $invoice && $invoice->status){
                $status_invoice = $invoice->data->status_invoice;
                //here 
                $this->saveFakturPajak($status_invoice, $invoice, $filename, $request, $client, $INVOICE_ID, $STELSales );
                
            }else{
                $result = "Data Invoice Tidak Ditemukan!";        
            }
        } catch(Exception $e){
            $result = null;
        }

        return $result;

    }

    private function saveFakturPajak($status_invoice, $invoice, $filename, $request, $client, $INVOICE_ID, $STELSales )
    {
        if($status_invoice == "approved"){
            $status_faktur = $invoice->data->status_faktur;
            if($status_faktur == "received"){
                /*SAVE FAKTUR PAJAK*/
                $name_file = 'faktur_stel_'.$filename.'.pdf';

                $path_file = public_path().'/media/stel/'.$request->input('id');
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }

                $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                $stream = (String)$response->getBody();

                if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
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

}
