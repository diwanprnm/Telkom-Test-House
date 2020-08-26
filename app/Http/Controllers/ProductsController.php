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
use App\Services\NotificationService;

class ProductsController extends Controller
{   

    private const SEARCH = 'search';
    private const PO_ID_TPN = 'PO_ID_from_TPN';
    private const UNIQUE_CODE_TPN = 'unique_code_from_TPN';
    private const STELS = 'stels';
    private const STELS_ID = 'stels.id';
    private const PRODUCTS = 'products';
    private const LOGIN = 'login';
    private const CREATED_AT = 'created_at';
    private const UPDATED_AT = 'updated_at';
    private const EMAIL = 'email';
    private const MEDIA_STEL = '/media/stel/';
    private const STELSALES_ID = 'stelsales_id';
    private const FILEPEMBAYARAN = 'filePembayaran';
    private const HIDE_FILE_PEMBAYARAN = 'hide_file_pembayaran';
    private const ERROR = 'error';
    private const MESSAGE = 'message';
    private const ADMIN = 'admin';
    private const FORMAT_DATE = 'Y-m-d H:i:s';
    private const CONTENT_TYPE = 'Content-Type: application/octet-stream';


    public function index(Request $request)
    {   
        $request->session()->forget(self::PO_ID_TPN);
        $request->session()->forget(self::UNIQUE_CODE_TPN);
        $currentUser = Auth::user();
        $search = trim($request->input(self::SEARCH));
        if($currentUser){
            $query_url = "SELECT * FROM youtube WHERE id = 1";
            $data_url = DB::select($query_url);

            $video_url = "https://www.youtube.com/embed/cew5AE7Kwwk";
            if (count($data_url) > 0){
                $video_url = $data_url[0]->buy_stel_url ? $data_url[0]->buy_stel_url : $video_url;
            }

            $paginate = 10;
            $stels = \DB::table(self::STELS)
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
            $stels = $stels->groupBy(self::STELS_ID);
            
            $stels = $stels->paginate($paginate); 
            $page = self::PRODUCTS;
            return view('client.STEL.products') 
                ->with('page', $page)
                ->with(self::SEARCH, $search)
                ->with('video_url', $video_url)
                ->with(self::STELS, $stels);
        }else{
           return  redirect(self::LOGIN);
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
            $data = $query->orderBy(self::CREATED_AT, 'desc')->paginate($paginate);
            $page = "purchase_history";
            return view('client.STEL.purchase_history') 
            ->with('page', $page)
            ->with('data', $data);     
        }else{
          return redirect(self::LOGIN);
        }
        
    } 

    public function payment_status(Request $request)
    { 
        $currentUser = Auth::user();
         $search = trim($request->input(self::SEARCH));
        if($currentUser){

            $STELSales = STELSales::where("user_id",$currentUser->id);
            if ($search != null){
                $STELSales->where("invoice",$search);
                $STELSales->orWhere("payment_code",$search);
            }
            $STELSales = $STELSales->orderBy(self::UPDATED_AT, 'desc');
            $STELSales = $STELSales->get();
            $page = "payment_status";
            return view('client.STEL.payment_status') 
            ->with('page', $page)   
             ->with(self::SEARCH, $search)
            ->with(self::STELS, $STELSales);     
        }else{
          return redirect(self::LOGIN);
        }
        
    } 

     public function payment_detail($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array(self::STELS_ID, "stels.name","stels.price","stels.code","stels.attachment","stels_sales.invoice","stels_sales.payment_status","companies.name as company_name","stels_sales_detail.id as id_attachment_stel","stels_sales_detail.qty","stels_sales.id_kuitansi","stels_sales.faktur_file","stels_sales_detail.attachment as manual_attachment","stels_sales.id as manual_id"); 
            $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels_sales","stels_sales.id","=","stels_sales_detail.stels_sales_id")
                        ->join("stels",self::STELS_ID,"=","stels_sales_detail.stels_id")
                         ->join("users","users.id","=","stels_sales.user_id")
                         ->join("companies","companies.id","=","users.company_id")
                        ->get();
            $page = "payment_detail";
            return view('client.STEL.payment_detail') 
            ->with('page', $page)   
            ->with(self::STELS, $STELSales);     
        }else{
           return redirect(self::LOGIN);
        }
        
    } 

    public function upload_payment($id)
    { 
        Auth::user();
        $data = STELSales::find($id);
        $page = "upload_payment";
        return view('client.STEL.upload_payment') 
        ->with('page', $page) 
        ->with('data', $data)   
        ->with('id', $id);    
    } 

    public function pembayaranstel(Request $request){
        $currentUser = Auth::user();
        $notificationService = new NotificationService();
     
        $jml_pembayaran = str_replace(".",'',$request->input('jml-pembayaran'));
        $jml_pembayaran = str_replace("Rp",'',$jml_pembayaran);
         
        $path_file = public_path().self::MEDIA_STEL.$request->input(self::STELSALES_ID).'';
        if ($request->hasFile(self::FILEPEMBAYARAN)) { 
            $name_file = 'stel_payment_'.$request->file(self::FILEPEMBAYARAN)->getClientOriginalName();
            if($request->file(self::FILEPEMBAYARAN)->move($path_file,$name_file)){ 
                if (File::exists(public_path().'\media\stel\\'.$request->input(self::STELSALES_ID).'\\'.$request->input(self::HIDE_FILE_PEMBAYARAN))){
                    File::delete(public_path().'\media\stel\\'.$request->input(self::STELSALES_ID).'\\'.$request->input(self::HIDE_FILE_PEMBAYARAN));
                }
            }else{
                Session::flash(self::ERROR, 'Upload Payment Attachment to directory failed');
                return redirect('/upload_payment/'.$request->input(self::STELSALES_ID));
            }

            try{
                $STELSalesAttach = STELSalesAttach::where("stel_sales_id",$request->input(self::STELSALES_ID))->first();
                if($STELSalesAttach){
                    $STELSalesAttach->delete();
                }  
                $STELSalesAttach = new STELSalesAttach;
                $currentUser = Auth::user(); 
                $STELSalesAttach->id = Uuid::uuid4(); 
                $STELSalesAttach->created_by = $currentUser->id;
                $STELSalesAttach->stel_sales_id = $request->input(self::STELSALES_ID);
                $STELSalesAttach->attachment = $name_file;
                $STELSalesAttach->save();
                $id = $request->input(self::STELSALES_ID);
                $STELSales = STELSales::find($id);
                $STELSales->payment_status = $STELSales->payment_status == 0 ? 2 : $STELSales->payment_status;
                $STELSales->cust_price_payment = $jml_pembayaran;
                $STELSales->save();

                 $data= array( 
                    "from"=>$currentUser->name,
                    "to"=>self::ADMIN,
                    self::MESSAGE=>$currentUser->company->name." Upload pembayaran STEL",
                    "url"=>"sales/".$STELSales->id."/edit",
                    "is_read"=>0,
                    self::CREATED_AT=>date(self::FORMAT_DATE),
                    self::UPDATED_AT=>date(self::FORMAT_DATE)
                );
                $notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;

                event(new Notification($data));

                Session::flash(self::MESSAGE, 'Upload successfully'); 
            } catch(Exception $e){
                Session::flash(self::ERROR, 'Upload failed');
                
            }
        }

        return back();
    }

    public function store(Request $request){
        Cart::add(['id' => $request->id, 'name' => $request->name.'myTokenProduct'.$request->code, 'qty' => 1, 'price' => $request->price]);
        return redirect(self::PRODUCTS);
    }

    public function checkout(Request $request){ 
        $currentUser = Auth::user();
        $countStelsSales =  STELSales::where(array())->count(); 
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

        $details = array();
        $stel_code_string = '';
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
            $stel_code_string = $stel_code_string ? $stel_code_string.', '.$res[1] : $res[1];
        }

        $data = [
            "from" => [
                "name" => "PT TELEKOMUNIKASI INDONESIA, TBK.",
                "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
                "phone" => "(+62) 812-2483-7500",
                self::EMAIL => "urelddstelkom@gmail.com",
                "npwp" => "01.000.013.1-093.000"
            ],
            "to" => [
                "name" => $currentUser->company->name ? $currentUser->company->name : "-",
                "address" => $currentUser->company->address ? $currentUser->company->address : "-",
                "phone" => $currentUser->company->phone_number ? $currentUser->company->phone_number : "-",
                self::EMAIL => $currentUser->company->email ? $currentUser->company->email : "-",
                "npwp" => $currentUser->company->npwp_number ? $currentUser->company->npwp_number : "-"
            ],
            "product_id" => config("app.product_id_tth"), //product_id TTH
            "details" => $details,
            "created" => [
                "by" => $currentUser->name,
                "reference_id" => $currentUser->id
            ],
            "config" => [
                "kode_wapu" => "01",
                "afiliasi" => "non-telkom",
                "tax_invoice_text" => $stel_code_string.'.'
            ],
            "include_tax_invoice" => true,
            "bank" => [
                "owner" => "Divisi RisTI TELKOM",
                "account_number" => "131-0096022712",
                "bank_name" => "BANK MANDIRI",
                "branch_office" => "KCP KAMPUS TELKOM BANDUNG"         
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
            /* DATA DARI TPN  */ 
            $PO_ID = $request->session()->get(self::PO_ID_TPN) ? $request->session()->get(self::PO_ID_TPN) : ($purchase && $purchase->status ? $purchase->data->_id : null);
            $request->session()->put(self::PO_ID_TPN, $PO_ID);
            $total_price = Cart::subtotal();
            $unique_code = $request->session()->get(self::UNIQUE_CODE_TPN) ? $request->session()->get(self::UNIQUE_CODE_TPN) : ($purchase && $purchase->status ? $purchase->data->unique_code : '0');
            $request->session()->put(self::UNIQUE_CODE_TPN, $unique_code);
            $tax = floor(0.1*($total_price + $unique_code));
            $final_price = $total_price + $unique_code + $tax;

            $page = "checkout";
            return view('client.STEL.checkout') 
                ->with('page', $page)
                ->with('PO_ID', $PO_ID)
                ->with('total_price', $total_price)
                ->with('tax', $tax)
                ->with('unique_code', $unique_code)
                ->with('final_price', $final_price)
                ->with('invoice_number', $invoice_number);
        }else{
            return redirect(self::PRODUCTS);
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
            $res_purchase = $client->post("v1/draftbillings", $params)->getBody();
            return json_decode($res_purchase);

            
        } catch(Exception $e){
            return null;
        }
    }

    public function doCheckout(Request $request){  
        $PO_ID = $request->session()->get(self::PO_ID_TPN);
        $currentUser = Auth::user();
        $notificationService = new NotificationService();
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
                $STELSales->email = $request->input(self::EMAIL);
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
           $STELSales->created_at = date(self::FORMAT_DATE);

           if($PO_ID){
                $data = [
                    "draft_id" => $PO_ID,
                    "created" => [
                        "by" => $currentUser->name,
                        "reference_id" => $currentUser->id
                    ]
                ];

                $billing = $this->api_billing($data);

                $STELSales->PO_ID = $PO_ID;
                $STELSales->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;
            }

            try{
                $STELSales->save();

                $data= array( 
                    "from"=>$currentUser->name,
                    "to"=>self::ADMIN,
                    self::MESSAGE=>"Permohonan Pembelian STEL",
                    "url"=>"sales/".$STELSales->id."/edit",
                    "is_read"=>0,
                    self::CREATED_AT=>date(self::FORMAT_DATE),
                    self::UPDATED_AT=>date(self::FORMAT_DATE)
                );
                $notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;

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

                        $request->session()->forget(self::PO_ID_TPN);
                        $request->session()->forget(self::UNIQUE_CODE_TPN);

                    } catch(\Illuminate\Database\QueryException $e){ 
                        Session::flash(self::ERROR, 'Failed To Checkout');
                        return redirect(self::PRODUCTS);
                    } 
                return redirect('purchase_history');
            } catch(\Illuminate\Database\QueryException $e){
                
                Session::flash(self::ERROR, 'Failed To Checkout');
                return redirect(self::PRODUCTS);
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
            return json_decode($res_billing); 
            
        } catch(Exception $e){
            return null;
        }
    }

    public function destroy($id)
    {
        
      Cart::remove($id);
      return redirect("/".self::PRODUCTS);
    }

    public function downloadStel($id)
    {
        $stel = STEL::find($id);

        if ($stel){
            $file = public_path().self::MEDIA_STEL.$stel->attachment;
            $headers = array(
              self::CONTENT_TYPE,
            );

            return Response::download($file, $stel->attachment, $headers);
        }
    }

    public function test_notification(){
        $data= array(
            "id"=>1,
                "from"=>self::ADMIN,
                "to"=>"35a35ea3-6fd5-43ae-8b97-ddf7525e94d1",
                "url"=>"sales",
                self::MESSAGE=>"Notification Message"
                );
          event(new Notification($data));
     return "event fired";
    }
	
	public function downloadfakturstel($id)
    {
        $stel = STELSales::where("id",$id)->first();

        if ($stel){
            $file = public_path().self::MEDIA_STEL.$stel->id."/".$stel->faktur_file;
            $headers = array(
              self::CONTENT_TYPE,
            );

            return Response::file($file, $headers);
        }
    }
	
	public function downloadkuitansistel($id)
    {
        $stel = STELSales::where("id_kuitansi",$id)->first();

        if ($stel){
            $file = public_path().self::MEDIA_STEL.$stel->id."/".$stel->id_kuitansi;
            $headers = array(
              self::CONTENT_TYPE,
            );

            return Response::file($file, $headers);
        }
    }
	
    public function viewWatermark($id)
    {
        $currentUser = Auth::user();
        if($currentUser){
            $query = STELSales::
            join('users', function ($join) use ($currentUser) {
                $join->on('stels_sales.created_by', '=', 'users.id')
                 ->where('users.company_id', '=', $currentUser->company_id);
            })
            ->join('stels_sales_detail', function ($join) use ($id) {
                $join->on('stels_sales.id', '=', 'stels_sales_detail.stels_sales_id')
                 ->where('stels_sales_detail.id', '=', $id);
            });
            $stel = $query->get();
            if (count($stel)>0){
                $file = public_path().'/media/stelAttach/'.$id."/".$stel[0]->attachment;
                $headers = array(
                  self::CONTENT_TYPE,
                );

                return Response::file($file, $headers);
            }else{
               return redirect()->back();
            }
        }
        else{
           return redirect()->back();
        }
    }


}
