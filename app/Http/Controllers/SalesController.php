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
use GuzzleHttp\Stream\Stream;

use App\Events\Notification;
use App\NotificationTable;

class SalesController extends Controller
{ 
     public function __construct()
    {
        $this->middleware('auth.admin');
    }
    public function index(Request $request)
    { 
        $currentUser = Auth::user();
        $before = null;
        $after = null;
        $payment_status = '';
        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            $select = array(
                "logs.action","logs.page","logs.created_at as search_date","users.name"
            );
            $select = array("stels_sales.id","stels_sales.created_at","stels_sales.invoice","stels_sales.payment_status","stels_sales.payment_method","stels_sales.total","stels_sales.cust_price_payment","companies.name as company_name","stels.name as stel_name","stels.code as stel_code"); 

            $dataSales = STELSales::select($select)->whereNotNull('stels_sales.created_at')
                        ->join("users","users.id","=","stels_sales.user_id")
                        ->join("companies","users.company_id","=","companies.id")
                        ->join("stels_sales_detail","stels_sales_detail.stels_sales_id","=","stels_sales.id")
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id");
            
            if ($search != null){
            
                $dataSales = $dataSales->where('invoice','like','%'.$search.'%')
                            ->orWhere('companies.name', 'like', '%'.$search.'%');

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Search Sales"; 
                $dataSearch = array("search"=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Sales";
                $logs->save();

            }

             if ($request->has('payment_status')){
                $payment_status = $request->get('payment_status');
                if($request->input('payment_status') != 'all'){
                    $dataSales->where('payment_status', $request->get('payment_status'));
                }
            }

            if ($request->has('before_date')){  
                $dataSales->where(DB::raw('DATE(stels_sales.created_at)'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){ 
                $dataSales->where(DB::raw('DATE(stels_sales.created_at)'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            $data_excel = $dataSales->orderBy('stels_sales.created_at', 'desc')->get();
            $data = $dataSales->orderBy('stels_sales.created_at', 'desc')
                                ->paginate($paginate);
           
            $request->session()->put('excel_sales', $data_excel);
            
            if (count($dataSales) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.sales.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('payment_status', $payment_status)
                ->with('before_date', $before)
                ->with('after_date', $after);
        }
    } 

    public function show($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array("stels.name","stels.price","stels.code","stels_sales_detail.qty","stels_sales_detail.id","stels_sales_detail.attachment"); 
            $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                        ->get();
			$STELSales_idKuitansi = STELSales::find($id);
			return view('admin.sales.detail')
            ->with('data', $STELSales) 
            ->with('id_sales', $id) 
            ->with('id_kuitansi', $STELSales_idKuitansi->id_kuitansi) 
            ->with('faktur_file', $STELSales_idKuitansi->faktur_file) 
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
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.
        
        $data = $request->session()->get('excel_sales');
        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'No',
            'Sales Date',
            'Invoice',
            'Type',
            'Status',
            'Payment Method'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
            $no = 0;
        foreach ($data as $row) {
            $no ++;
            $examsArray[] = [
                $no,
                $row->created_at,
                $row->invoice,
                'Stels',
                ($row->payment_status == 1)?'Pending':'Timeout',
                ($row->payment_method == 1)?'ATM':'Kartu Kredit'
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->id = Uuid::uuid4();
        $logs->user_id = $currentUser->id;
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "SALES";
        $logs->save();

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
		$currentUser = Auth::user();

        $STELSales = STELSales::find($id);
        $oldStel = $STELSales;  
		$notifUploadSTEL = 0;
        $data = array();
        if ($request->has('payment_status')){

            /*TPN api_invoice*/
            if($STELSales->payment_status !=1){
                $data_invoices = [
                    "billing_id" => $STELSales->BILLING_ID,
                    "created" => [
                        "by" => "SUPERADMIN UREL",
                        "reference_id" => "1"
                        /*"by" => $currentUser->name,
                        "reference_id" => $currentUser->id*/
                    ]
                ];

                $invoice = $this->api_invoice($data_invoices);
                $STELSales->INVOICE_ID = $invoice && $invoice->status == true ? $invoice->data->_id : null;
                if($invoice && $invoice->status == false){
                    Session::flash('error', $invoice->message);
                    return redirect('/admin/sales/'.$STELSales->id.'/edit');
                }
            }


			for($i=0;$i<count($request->input('stels_sales_detail_id'));$i++){
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

                        /*TPN api_upload*/
                        $data [] = 
                            [
                                'name' => "file",
                                'contents' => fopen($path_file.'/'.$name_file, 'r'),
                                'filename' => $request->file('stel_file')[$i]->getClientOriginalName()
                            ]
                        ;
					}else{
						Session::flash('error', 'Save STEL to directory failed');
						return redirect('/admin/sales/'.$STELSales->id.'/edit');
					}
				}
			}
            /*TPN api_upload*/
            if($STELSales->BILLING_ID != null && $data != null){
                $data [] = array(
                    'name'=>"delivered",
                    'contents'=>json_encode(['by'=>$currentUser->name, "reference_id" => $currentUser->id]),
                );

                $upload = $this->api_upload($data,$STELSales->BILLING_ID);
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
					return redirect('/admin/sales/'.$STELSales->id.'/edit');
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
					return redirect('/admin/sales/'.$STELSales->id.'/edit');
				}
			}
            $STELSales->updated_by = $currentUser->id; 
            $STELSales->payment_status = $request->input('payment_status');

            try{
                $STELSales->save();
				
				if($notifUploadSTEL == 1){
					$data= array( 
						"from"=>"admin",
						"to"=>$STELSales->created_by,
						"message"=>"STEL telah diupload",
						"url"=>"payment_detail/".$STELSales->id,
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
						);

						$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
						$notification->from = $data['from'];
						$notification->to = $data['to'];
						$notification->message = $data['message'];
						$notification->url = $data['url'];
						$notification->is_read = $data['is_read'];
						$notification->created_at = $data['created_at'];
						$notification->updated_at = $data['updated_at'];
						$notification->save();
						$data['id'] = $notification->id; 
						event(new Notification($data));
						
						$logs = new Logs;
						$logs->user_id = $currentUser->id;
						$logs->id = Uuid::uuid4();
						$logs->action = "Upload Dokumen Pembelian STEL";
						$logs->data = $oldStel;
						$logs->created_by = $currentUser->id;
						$logs->page = "SALES";
						$logs->save();
						
						Session::flash('message', 'STELS successfully uploaded');
				}else{
					if ($STELSales->payment_status == 1) {
						$data= array( 
						"from"=>"admin",
						"to"=>$STELSales->created_by,
						"message"=>"Pembayaran Stel Telah diterima",
						"url"=>"payment_detail/".$STELSales->id,
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
						);

						$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
						$notification->from = $data['from'];
						$notification->to = $data['to'];
						$notification->message = $data['message'];
						$notification->url = $data['url'];
						$notification->is_read = $data['is_read'];
						$notification->created_at = $data['created_at'];
						$notification->updated_at = $data['updated_at'];
						$notification->save();
						$data['id'] = $notification->id; 
						event(new Notification($data));
					} else if($STELSales->payment_status == -1) {
						$data= array( 
						"from"=>"admin",
						"to"=>$STELSales->created_by,
						"message"=>"Pembayaran Stel Telah ditolak",
						"url"=>"payment_detail/".$STELSales->id,
						"is_read"=>0,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
						);

						$notification = new NotificationTable();
						$notification->id = Uuid::uuid4();
						$notification->from = $data['from'];
						$notification->to = $data['to'];
						$notification->message = $data['message'];
						$notification->url = $data['url'];
						$notification->is_read = $data['is_read'];
						$notification->created_at = $data['created_at'];
						$notification->updated_at = $data['updated_at'];
						$notification->save();
						$data['id'] = $notification->id; 
						event(new Notification($data));
					}

					$logs = new Logs;
					$logs->user_id = $currentUser->id;
					$logs->id = Uuid::uuid4();
					$logs->action = "Update Status Pembayaran STEL";
					$logs->data = $oldStel;
					$logs->created_by = $currentUser->id;
					$logs->page = "SALES";
					$logs->save();
					Session::flash('message', 'SALES successfully updated');
				}

                return redirect('/admin/sales');
            } catch(Exception $e){
                Session::flash('error', 'Save failed');
                return redirect('/admin/sales/'.$STELSales->id.'/edit');
            }
        }else{
            return redirect('/admin/sales');
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
            $upload = json_decode($res_upload);

            /*get
                $upload->status; //if true lanjut, else panggil lagi API nya, dan jalankan API invoices
                $upload->data->_id;
            */

            return $upload;
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
            $invoice = json_decode($res_invoices);

            /*get
                $invoice->status; //if true lanjut, else panggil lagi API ny
                $invoice->data->_id; //INVOICE_ID
            */

            return $invoice;
        } catch(Exception $e){
            return null;
        }
    }

    public function upload($id)
    {
        $select = array("stels_sales.id","stels_sales_attachment.attachment","stels_sales_attachment.stel_sales_id");  
        $stel = STELSalesAttach::select($select)->rightJoin("stels_sales","stels_sales.id","=","stels_sales_attachment.stel_sales_id")
                ->where("stels_sales.id",$id)->first();
                
        $select = array("stels.name","stels.price","stels.code","stels_sales_detail.qty","stels_sales_detail.id","stels_sales_detail.attachment","stels.attachment as stelAttach","stels_sales.invoice","companies.name as company_name","stels_sales.payment_status"); 
        $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                    ->join("stels_sales","stels_sales.id","=","stels_sales_detail.stels_sales_id")
                    ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                    ->join("users","users.id","=","stels_sales.user_id")
                    ->join("companies","companies.id","=","users.company_id")
                    ->get();
        return view('admin.sales.upload')
            ->with('data', $stel)
            ->with('dataStel', $STELSales);
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

    public function generateTaxInvoice(Request $request) {
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $STELSales = STELSales::where("id", $request->input('id'))->first();

        if($STELSales){
            try {
                // $INVOICE_ID = "5e2d64971220bd00151b778f";
                $INVOICE_ID = $STELSales->INVOICE_ID;
                $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
                $invoice = json_decode($res_invoice->getBody());
                
                if($invoice && $invoice->status == true){
                    $status_invoice = $invoice->data->status_invoice;
                    if($status_invoice == "approved"){
                        $status_faktur = $invoice->data->status_faktur;
                        if($status_faktur == "received"){
                            /*SAVE FAKTUR PAJAK*/
                            $name_file = 'faktur_stel_'.$INVOICE_ID.'.pdf';

                            $path_file = public_path().'/media/stel/'.$request->input('id');
                            if (!file_exists($path_file)) {
                                mkdir($path_file, 0775);
                            }

                            $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                            $stream = (String)$response->getBody();

                            if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                                $STELSales->faktur_file = $name_file;
                                $STELSales->save();
                                return "Faktur Pajak Berhasil Disimpan.";
                            }else{
                                return "Gagal Menyimpan Faktur Pajak!";
                            }
                        }else{
                            return $invoice->data->status_faktur;
                        }
                    }else{
                        switch ($status_invoice) {
                            case 'invoiced':
                                return "Faktur Pajak belum Tersedia, karena Invoice Baru Dibuat.";
                                break;
                            
                            case 'returned':
                                return $invoice->data->$status_invoice->message;
                                break;
                            
                            default:
                                return "Faktur Pajak belum Tersedia. Invoice sudah dikirim ke DJP.";
                                break;
                        }
                    }
                }else{
                    return "Data Invoice Tidak Ditemukan!";        
                }
            } catch(Exception $e){
                return null;
            }
        }else{
            return "Data Pembelian Tidak Ditemukan!";
        }
    }

}
