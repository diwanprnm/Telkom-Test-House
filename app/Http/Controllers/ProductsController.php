<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Events\Notification;

use App\STEL;
use App\STELSales;
use App\STELSalesDetail;
use App\STELSalesAttach;
use App\Logs; 

use Auth;
use Session;
use Cart;
use Response;
use File;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\NotificationTable;

class ProductsController extends Controller
{
    public function index(Request $request)
    {   
        $currentUser = Auth::user();
        $search = trim($request->input('search'));
        if($currentUser){
            $query_url = "SELECT * FROM youtube WHERE id = 1";
            $data_url = DB::select($query_url);

            $video_url = "https://www.youtube.com/embed/cew5AE7Kwwk";
            if (count($data_url) > 0){
                $video_url = $data_url[0]->buy_stel_url ? $data_url[0]->buy_stel_url : $video_url;
            }

            $paginate = 10;
            $stels = \DB::table('stels')
                ->selectRaw('stels.*,(SELECT count(*) FROM stels_sales 
                            join stels_sales_detail on (stels_sales.id = stels_sales_detail.stels_sales_id) 
                            join users on(stels_sales.user_id = users.id)
                            where users.company_id ="'.$currentUser->company_id.'" 
                            and stels_sales_detail.stels_id = stels.id 
                            group by users.company_id
                            ) as is_buyed'
                )
                ->where("stels.is_active",1)
                ;

            if ($search != null){
				$stels->where(function($q) use ($search){
					return $q->where('stels.name','like','%'.$search.'%')
						->orWhere('stels.code','like','%'.$search.'%')
						;
					});
            }
            $stels = $stels->groupBy('stels.id');
            
            $stels = $stels->paginate($paginate);
            // $stels = $stels->toSql();
			// dd($stels);exit;
            $page = "products";
            return view('client.STEL.products') 
                ->with('page', $page)
                ->with('search', $search)
                ->with('video_url', $video_url)
                ->with('stels', $stels);
        }else{
           return  redirect('login');
        }
    }

    public function purchase_history(Request $request)
    { 
        $currentUser = Auth::user();
        if($currentUser){
            $paginate = 10;

            $query = STELSales::whereHas('user', function ($query) use ($currentUser) {
                                    $query->where('company_id', $currentUser->company_id);
                                })
                                ->with('user')
                                ->with('user.company')
                                ->with('sales_detail')
                                ->with('sales_detail.stel');
            $data = $query->orderBy('created_at', 'desc')->paginate($paginate);
            $page = "purchase_history";
            return view('client.STEL.purchase_history') 
            ->with('page', $page)
            ->with('data', $data);     
        }else{
          return redirect("login");
        }
        
    } 

    public function payment_status(Request $request)
    { 
        $currentUser = Auth::user();
         $search = trim($request->input('search'));
        if($currentUser){

            $STELSales = STELSales::where("user_id",$currentUser->id);
            if ($search != null){
                $STELSales->where("invoice",$search);
                $STELSales->orWhere("payment_code",$search);
            }
            $STELSales = $STELSales->orderBy('updated_at', 'desc');
            $STELSales = $STELSales->get();
            $page = "payment_status";
            return view('client.STEL.payment_status') 
            ->with('page', $page)   
             ->with('search', $search)
            ->with('stels', $STELSales);     
        }else{
          return redirect("login");
        }
        
    } 

     public function payment_detail($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array("stels.id", "stels.name","stels.price","stels.code","stels.attachment","stels_sales.invoice","stels_sales.payment_status","companies.name as company_name","stels_sales_detail.id as id_attachment_stel","stels_sales_detail.qty","stels_sales.id_kuitansi","stels_sales.faktur_file","stels_sales_detail.attachment as manual_attachment","stels_sales.id as manual_id"); 
            $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels_sales","stels_sales.id","=","stels_sales_detail.stels_sales_id")
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                         ->join("users","users.id","=","stels_sales.user_id")
                         ->join("companies","companies.id","=","users.company_id")
                        ->get();
            $page = "payment_detail";
            return view('client.STEL.payment_detail') 
            ->with('page', $page)   
            ->with('stels', $STELSales);     
        }else{
           return redirect("login");
        }
        
    } 

    public function upload_payment($id)
    { 
        $currentUser = Auth::user();
        $data = STELSales::find($id);
        $page = "upload_payment";
        return view('client.STEL.upload_payment') 
        ->with('page', $page) 
        ->with('data', $data)   
        ->with('id', $id);    
    } 

    public function pembayaranstel(Request $request){
        $currentUser = Auth::user();
     
        $jml_pembayaran = str_replace(".",'',$request->input('jml-pembayaran'));
        $jml_pembayaran = str_replace("Rp",'',$jml_pembayaran);
        
        $user_name = ''.$currentUser['attributes']['name'].'';
        $user_email = ''.$currentUser['attributes']['email'].'';
        $path_file = public_path().'/media/stel/'.$request->input('stelsales_id').'';
        if ($request->hasFile('filePembayaran')) {
            // $ext_file = $request->file('filePembayaran')->getClientOriginalName();
            // $name_file = uniqid().'_user_'.$request->input('hide_id_exam').'.'.$ext_file;
            $name_file = 'stel_payment_'.$request->file('filePembayaran')->getClientOriginalName();
            if($request->file('filePembayaran')->move($path_file,$name_file)){
                $fPembayaran = $name_file;
                if (File::exists(public_path().'\media\stel\\'.$request->input('stelsales_id').'\\'.$request->input('hide_file_pembayaran'))){
                    File::delete(public_path().'\media\stel\\'.$request->input('stelsales_id').'\\'.$request->input('hide_file_pembayaran'));
                }
            }else{
                Session::flash('error', 'Upload Payment Attachment to directory failed');
                return redirect('/upload_payment/'.$request->input('stelsales_id'));
            }

            try{
                $STELSalesAttach = STELSalesAttach::where("stel_sales_id",$request->input('stelsales_id'))->first();
                if($STELSalesAttach){
                    $STELSalesAttach->delete();
                }  
                $STELSalesAttach = new STELSalesAttach;
                $currentUser = Auth::user(); 
                $STELSalesAttach->id = Uuid::uuid4(); 
                $STELSalesAttach->created_by = $currentUser->id;
                $STELSalesAttach->stel_sales_id = $request->input('stelsales_id');
                $STELSalesAttach->attachment = $name_file;
                $STELSalesAttach->save();
                $id = $request->input('stelsales_id');
                $STELSales = STELSales::find($id);
                $STELSales->payment_status = 2;
                $STELSales->cust_price_payment = $jml_pembayaran;
                $STELSales->save();

                 $data= array( 
                    "from"=>$currentUser->name,
                    "to"=>"admin",
                    "message"=>$currentUser->company->name." Upload pembayaran STEL",
                    "url"=>"sales/".$STELSales->id."/edit",
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

                Session::flash('message', 'Upload successfully'); 
            } catch(Exception $e){
                Session::flash('error', 'Upload failed');
                
            }
        }else{
            $fPembayaran = $request->input('hide_file_pembayaran');
        }

        return back();
    }

    public function store(Request $request){
        Cart::add(['id' => $request->id, 'name' => $request->name.'myTokenProduct'.$request->code, 'qty' => 1, 'price' => $request->price]);
        return redirect('products');
    }

    public function checkout(Request $request){ 
        $currentUser = Auth::user();
        $countStelsSales =  STELSales::where(array())->count();
        $invoice_id = 0; 
        $fill = 3;
        $STELSales = new STELSales();
        $array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $bulan = $array_bulan[date('n')];
        if(!empty($countStelsSales)){ 
            $lastInvoiceID = $STELSales->count();   

            $number = $lastInvoiceID+1; 
            $invoice_number = "STEL ".str_pad($number, $fill, '0', STR_PAD_LEFT)."/".$bulan.'/'.date('Y');
             
        }else{ 
            $number = 1; 
            $invoice_number = "STEL ".str_pad($number, $fill, '0', STR_PAD_LEFT)."/".$bulan.'/'.date('Y');
        }

        /*SEMENTARA*/
        $details = array();
        foreach (Cart::content() as $row) {
            $res = explode('myTokenProduct', $row->name);
            $stel_name = $res[0] ? $res[0] : '-';
            $stel_code = $res[1] ? $res[1] : '-';
            $details [] = 
                [
                    "item" => $stel_code,
                    "description" => $stel_name,
                    "quantity" => $row->qty,
                    "price" => $row->price,
                    "total" => $row->price*$row->qty
                ]
            ;
        }

        $data = [
            "from" => [
                "name" => "PT TELEKOMUNIKASI INDONESIA, TBK.",
                "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
                "phone" => "(+62) 812-2483-7500",
                "email" => "urelddstelkom@gmail.com",
                "npwp" => "01.000.013.1-093.000"
            ],
            "to" => [
                "name" => $currentUser->company->name ? $currentUser->company->name : "-",
                "address" => $currentUser->company->address ? $currentUser->company->address : "-",
                "phone" => $currentUser->company->phone_number ? $currentUser->company->phone_number : "-",
                "email" => $currentUser->company->email ? $currentUser->company->email : "-",
                "npwp" => $currentUser->company->npwp_number ? $currentUser->company->npwp_number : "-"
            ],
            "product_id" => config("app.product_id_tth"), //product_id TTH
            "details" => $details,
            "created" => [
                "by" => $currentUser->name,
                "reference_id" => $currentUser->id
            ]
        ];

        $purchase = $this->api_purchase($data);

        if($request->input('agree')){
            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Checkout Stel";   
            $logs->data = "";
            $logs->created_by = $currentUser->id;
            $logs->page = "Client STEL";
            $logs->save();

            $page = "checkout";
            return view('client.STEL.checkout') 
                ->with('page', $page)
                ->with('PO_ID', $purchase && $purchase->status == true ? $purchase->data->_id : null)
                ->with('total_price', $purchase && $purchase->status ? $purchase->data->total_price : Cart::subtotal())
                ->with('tax', $purchase && $purchase->status == true ? $purchase->data->tax : Cart::tax())
                ->with('unique_code', $purchase && $purchase->status == true ? $purchase->data->unique_code : '0')
                ->with('final_price', $purchase && $purchase->status == true ? $purchase->data->final_price : Cart::total())
                ->with('invoice_number', $invoice_number);
        }else{
            return redirect('products');
        } 
    }

    public function api_purchase($data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false,
            'verify' => false
        ]);
        try {
            
            $params['json'] = $data;
            $res_purchase = $client->post("v1/orders", $params)->getBody();
            $purchase = json_decode($res_purchase);

            /*while ($purchase->status != true) {
                $this->api_purchase($data);
            }*/

            /*get
                $purchase->status; //if true lanjut, else panggil lagi API nya
                $purchase->data->_id;
                $purchase->data->total_price;
                $purchase->data->tax;
                $purchase->data->unique_code;
                $purchase->data->final_price;
            */

            return $purchase;
        } catch(Exception $e){
            return null;
        }
    }

    public function doCheckout(Request $request){  
        
        $currentUser = Auth::user();
        $STELSales = new STELSales;
        if($currentUser){ 
           if($request->input("payment_method") == "cc"){
                $STELSales->name = $request->input("name"); 
                $STELSales->exp = $request->input("exp");
                $STELSales->cvv = $request->input("cvv");
                $STELSales->cvc = $request->input("cvc");
                $STELSales->type = $request->input("type");
                $STELSales->no_card = $request->input("no_card");
                $STELSales->no_telp = $request->input("no_telp");
                $STELSales->email = $request->input("email");
                $STELSales->country = $request->input("country");
                $STELSales->province = $request->input("province");
                $STELSales->city = $request->input("city");
                $STELSales->postal_code = $request->input("postal_code");
                $STELSales->birthdate = $request->input("birthdate");
           } 
           $STELSales->payment_method = ($request->input("payment_method")=="atm")?1:2;
           $STELSales->payment_status = 0;
           $STELSales->invoice = $request->input("invoice_number");
           $STELSales->user_id = $currentUser->id;
           $STELSales->total = $request->input("final_price");
           $STELSales->created_by =$currentUser->id;
           $STELSales->created_at = date("Y-m-d H:i:s");
            // $STELSales->payment_code =  $result->payment_code;

           /*SEMENTARA*/
           if($request->input("PO_ID")){
               $data = [
                    "po_id" => $request->input("PO_ID"),
                    // "due_date" => "2019-12-23 10:00:00", //kalo SPB 14 hari
                    "include_tax_invoice" => true,
                    "created" => [
                        "by" => $currentUser->name,
                        "reference_id" => $currentUser->id
                    ],
                    "config" => [
                        "kode_wapu" => "01",
                        "afiliasi" => "non-telkom",
                        "tax_invoice_text" => "String"
                    ],
                    "include_tax_invoice" => true
                ];

                $billing = $this->api_billing($data);

                $STELSales->PO_ID = $request->input("PO_ID");
                $STELSales->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;
           }

            try{
                $save = $STELSales->save();

                $data= array( 
                    "from"=>$currentUser->name,
                    "to"=>"admin",
                    "message"=>"Permohonan Pembelian STEL",
                    "url"=>"sales/".$STELSales->id."/edit",
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


                    try{  
                        foreach(Cart::content() as $row){ 
                            $STELSalesDetail = new STELSalesDetail;
                            $STELSalesDetail->stels_sales_id = $STELSales->id;
                            $STELSalesDetail->stels_id = $row->id;
                            $STELSalesDetail->qty = 1;
                            $STELSalesDetail->save();
                        }

                        $logs = new Logs;
                        $currentUser = Auth::user();
                        $logs->user_id = $currentUser->id;
                        $logs->id = Uuid::uuid4();
                        $logs->action = "Order Stel";   
                        $logs->data = "";
                        $logs->created_by = $currentUser->id;
                        $logs->page = "Client STEL";
                        $logs->save();

                        Cart::destroy();

                    } catch(\Illuminate\Database\QueryException $e){ 
                        Session::flash('error', 'Failed To Checkout');
                        return redirect('products');
                    } 
                return redirect('purchase_history');
            } catch(\Illuminate\Database\QueryException $e){
                
                Session::flash('error', 'Failed To Checkout');
                return redirect('products');
            }
        }else{
           return redirect('/');
        } 
        
    }

    public function api_billing($data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $params['json'] = $data;
            $res_billing = $client->post("v1/billings", $params)->getBody();
            $billing = json_decode($res_billing);

            /*while ($billing->status != true) {
                $this->api_billing($data);
            }*/

            /*get
                $billing->status; //if true lanjut, else panggil lagi API ny
                $billing->data->_id; //BILLING_ID
            */

            return $billing;
        } catch(Exception $e){
            return null;
        }
    }

    public function destroy($id)
    {
        
      Cart::remove($id);
      return redirect("/products");
    }

    public function downloadStel($id)
    {
        $stel = STEL::find($id);

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::download($file, $stel->attachment, $headers);
        }
    }

    public function test_notification(){
        $data= array(
            "id"=>1,
                "from"=>"admin",
                "to"=>"35a35ea3-6fd5-43ae-8b97-ddf7525e94d1",
                "url"=>"sales",
                "message"=>"Notification Message"
                );
          event(new Notification($data));
     return "event fired";
    }
	
	public function downloadfakturstel($id)
    {
        $stel = STELSales::where("id",$id)->first();

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->id."/".$stel->faktur_file;
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


}
