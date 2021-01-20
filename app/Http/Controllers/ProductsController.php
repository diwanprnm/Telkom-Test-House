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
use Storage;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\NotificationTable;
use App\Services\NotificationService;

use App\Services\Logs\LogService; 
use App\Services\FileService;


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
    private const STELSALES_ID = 'stelsales_id';
    private const FILEPEMBAYARAN = 'filePembayaran'; 
    private const ERROR = 'error';
    private const MESSAGE = 'message';
    private const ADMIN = 'admin';
    private const FORMAT_DATE = 'Y-m-d H:i:s'; 
    private const MINIO = 'minio'; 
    private const STEL_URL = 'stel/';
    private const SALES_URL = 'sales/';
    private const EDIT = '/edit';
    private const USERS_DOT_COMPANY_ID = 'users.company_id';
    private const SALES_DETAIL_DOT_STEL = 'sales_detail.stel';
    private const SALES_DETAIL = 'sales_detail';
    private const IS_READ = 'is_read';
    private const PRICE = 'price';
    private const PRONE = 'phone';
    private const ADDRESS = 'address';
    private const CREATED = 'created';
    private const REFERENCE_ID = 'reference_id';
    private const MY_TOKEN_PRODUCT = 'myTokenProduct';
    private const APP_DOT_PRODUCT_ID_TTH = "app.product_id_tth";
    private const PAYMENT_METHOD = 'payment_method';
    private const HEADERS = 'headers';
    private const CONTENT_TYPE = 'Content-Type';
    private const APPLICATION_JSON = 'application/json';
    private const AUTHORIZATION = 'Authorization';
    private const APP_GATEWAY_TPN = 'app.gateway_tpn';
    private const BASE_URI = 'base_uri';
    private const APP_URL_API_TPN = 'app.url_api_tpn';
    private const TIMEOUT = 'timeout';
    private const HTTP_ERRORS = 'http_errors';
    private const FAILED_TO_CHECKOUT = 'Failed To Checkout';
    private const DATA_NOT_FOUND = 'Data not found.';

    public function __construct()
    {
        parent::__construct();
		$this->middleware('client', ['only' => [
            'index'
        ]]);
	}

    public function index(Request $request)
    {   
        $request->session()->forget(self::PO_ID_TPN);
        $request->session()->forget(self::UNIQUE_CODE_TPN);
        $currentUser = Auth::user();
        $search = trim($request->input(self::SEARCH));
        if(!$currentUser){ return  redirect(self::LOGIN); }

        $query_url = "SELECT * FROM youtube WHERE id = 1";
        $data_url = DB::select($query_url);

        $video_url = "https://www.youtube.com/embed/cew5AE7Kwwk";
        if (count($data_url)){
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
            ->with(self::STELS, $stels)
        ;
    }

    public function purchase_history(Request $request)
    {
        $currentUser = Auth::user();
        if(!$currentUser){ return redirect(self::LOGIN);}
        $paginate = 10; 
            $select = array("stels_sales.*","users.name"); 
            $query = STELSales::select($select)->distinct('stels_sales.id')
                    ->join("stels_sales_detail","stels_sales.id","=","stels_sales_detail.stels_sales_id")
                    ->join("stels","stels_sales_detail.stels_id","=","stels.id")
                        ->join("users","users.id","=","stels_sales.user_id")
                        ->join("companies","companies.id","=",self::USERS_DOT_COMPANY_ID)
                        ->where(self::USERS_DOT_COMPANY_ID,$currentUser->company_id)  
                        ->orderBy("stels_sales.created_at", 'desc');
                        
        $data = $query->paginate($paginate);

        $page = "purchase_history";
        return view('client.STEL.purchase_history') 
        ->with('page', $page)
        ->with('data', $data);
    } 

    public function payment_status(Request $request)
    { 
        $currentUser = Auth::user();
         $search = trim($request->input(self::SEARCH));
        if(!$currentUser){ return redirect(self::LOGIN); }

        $STELSales = STELSales::where("user_id",$currentUser->id);
        if ($search != null){
            $STELSales->where(function($q) use ($search){
                $q->where("invoice",$search)->orWhere("payment_code",$search);
            });
        }
        $STELSales = $STELSales->orderBy(self::UPDATED_AT, 'desc');
        $STELSales = $STELSales->get();
        $page = "payment_status";
        return view('client.STEL.payment_status') 
            ->with('page', $page)   
            ->with(self::SEARCH, $search)
            ->with(self::STELS, $STELSales)
        ;
        
    } 

    public function payment_confirmation($id)
    { 
        $currentUser = Auth::user();

        if(!$currentUser){ return redirect("login"); }

        $STELSales = STELSales::where("id", $id)
                    ->with(self::SALES_DETAIL)
                    ->with(self::SALES_DETAIL_DOT_STEL)->get();
        $page = "payment_confirmation";
        return view('client.STEL.payment_confirmation') 
            ->with('page', $page)   
            ->with('data', $STELSales)
        ;
    } 

    public function upload_payment($id)
    { 
        Auth::user();
        $data = STELSales::find($id); 
        $page = "upload_payment";
        return view('client.STEL.upload_payment') 
            ->with('page', $page) 
            ->with('data', $data)   
            ->with('id', $id)
        ;
    } 

    public function pembayaranstel(Request $request){
        $currentUser = Auth::user();
        $notificationService = new NotificationService();
     
        $jml_pembayaran = str_replace(".",'',$request->input('jml-pembayaran'));
        $jml_pembayaran = str_replace("Rp",'',$jml_pembayaran);
          
        if ($request->hasFile(self::FILEPEMBAYARAN)) { 

            $fileService = new FileService();
            $fileProperties = array(
                'path' => self::STEL_URL,
                'prefix' => "stel_payment_"
            );
            $fileService->upload($request->file($this::FILEPEMBAYARAN), $fileProperties);


            $name_file = $fileService->isUploaded() ? $fileService->getFileName() : '';
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
                    "url"=>self::SALES_URL.$STELSales->id.self::EDIT,
                    self::IS_READ=>0,
                    self::CREATED_AT=>date(self::FORMAT_DATE),
                    self::UPDATED_AT=>date(self::FORMAT_DATE)
                );
                $notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;

                event(new Notification($data));

                Session::flash(self::MESSAGE, 'Upload successfully'); 
            } catch(Exception $e){ Session::flash(self::ERROR, 'Upload failed');
            }
        }

        return back();
    }

    public function store(Request $request){
        Cart::add(['id' => $request->id, 'name' => $request->name.self::MY_TOKEN_PRODUCT.$request->code, 'qty' => 1, self::PRICE => $request->price]);
        return redirect(self::PRODUCTS);
    }

    public function checkout(Request $request){ 
        $currentUser = Auth::user();
        $countStelsSales =   \DB::table("stels_sales")->count(); 
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
        foreach (Cart::content() as $row) {
            $res = explode(self::MY_TOKEN_PRODUCT, $row->name);
            $stel_name = $res[0] ? $res[0] : '-';
            $stel_code = $res[1] ? $res[1] : '-';
            $details [] = 
                [
                    "item" => $stel_code,
                    "description" => $stel_name,
                    "quantity" => $row->qty,
                    self::PRICE => $row->price,
                    "total" => $row->price*$row->qty
                ]
            ;
        }

        $data = $this->getDataPurchase($currentUser,$details);

        $purchase = $this->api_purchase($data);

        if(!$request->input('agree')){ return redirect(self::PRODUCTS); }

        $logService = new LogService();
        $logService->createLog('Checkout Stel', "Client STEL");    

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
            ->with('invoice_number', $invoice_number)
            ->with(self::PAYMENT_METHOD, $this->api_get_payment_methods())
        ;
    }

    public function api_purchase($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)
                        ],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false,
            'verify' => false
        ]);
        try {
            
            $params['json'] = $data;
            $res_purchase = $client->post("v1/draftbillings", $params)->getBody();
            return json_decode($res_purchase);

            
        } catch(Exception $e){ return null;
        }
    }

    public function api_get_payment_methods(){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)
                        ],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false,
            'verify' => false
        ]);
        try {
            $res_payment_method = $client->get("v1/products/".config(self::APP_DOT_PRODUCT_ID_TTH)."/paymentmethods")->getBody();
            return json_decode($res_payment_method);
        } catch(Exception $e){ return null;
        }
    }
    
    public function doCheckout(Request $request){  
        $PO_ID = $request->session()->get(self::PO_ID_TPN);
        $currentUser = Auth::user();
        $notificationService = new NotificationService();
        $STELSales = new STELSales;
        if($currentUser){ 
           if( strpos($request->input(self::PAYMENT_METHOD), 'cc') !== false){
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
           $mps_info = explode('||', $request->input(self::PAYMENT_METHOD));
           $STELSales->payment_method = $mps_info[2] == "atm" ? 1 : 2;
           $STELSales->payment_status = 0;
           $STELSales->invoice = $request->input("invoice_number");
           $STELSales->user_id = $currentUser->id;
           $STELSales->total = $request->input("final_price");
           $STELSales->created_by =$currentUser->id;
           $STELSales->created_at = date(self::FORMAT_DATE);

           if($PO_ID){
                $stel_code_string = '';
                foreach (Cart::content() as $row) {
                    $res = explode(self::MY_TOKEN_PRODUCT, $row->name);
                    $stel_code = $res[1] ? $res[1] : '-';
                    $stel_code_string = $stel_code_string ? $stel_code_string.', '.$res[1] : $res[1];
                } 
                
                $data = $this->getDataBilling($PO_ID,$currentUser,$stel_code_string,$mps_info);

                $billing = $this->api_billing($data);

                $STELSales->PO_ID = $PO_ID;
                $STELSales->BILLING_ID = $billing && $billing->status ? $billing->data->_id : null;
                if($mps_info[2] != "atm"){
                    $STELSales->VA_name = $mps_info ? $mps_info[3] : null;
                    $STELSales->VA_image_url = $mps_info ? $mps_info[4] : null;
                    $STELSales->VA_number = $billing && $billing->status ? $billing->data->mps->va->number : null;
                    $STELSales->VA_amount = $billing && $billing->status ? $billing->data->mps->total_amount : null;
                    $STELSales->VA_expired = $billing && $billing->status ? $billing->data->mps->va->expired : null;
                }
                if(!$STELSales->VA_number){
                    $request->session()->forget('PO_ID_from_TPN');
                    Session::flash(self::ERROR, 'Failed to generate '.$mps_info[3].', please choose another bank!');
                    return back();
                }
            }

            try{
                $STELSales->save();

                $data= array( 
                    "from"=>$currentUser->name,
                    "to"=>self::ADMIN,
                    self::MESSAGE=>"Permohonan Pembelian STEL",
                    "url"=>self::SALES_URL.$STELSales->id.self::EDIT,
                    self::IS_READ=>0,
                    self::CREATED_AT=>date(self::FORMAT_DATE),
                    self::UPDATED_AT=>date(self::FORMAT_DATE)
                );
                $notification_id = $notificationService->make($data);
			    $data['id'] = $notification_id;

                // event(new Notification($data));


                    try{  
                        foreach(Cart::content() as $row){ 
                            $STELSalesDetail = new STELSalesDetail;
                            $STELSalesDetail->stels_sales_id = $STELSales->id;
                            $STELSalesDetail->stels_id = $row->id;
                            $STELSalesDetail->qty = 1;
                            $STELSalesDetail->save();
                        }
 

                        $logService = new LogService();
                        $logService->createLog('Order Stel', "Client STEL");    

                        Cart::destroy();

                        $request->session()->forget(self::PO_ID_TPN);
                        $request->session()->forget(self::UNIQUE_CODE_TPN);

                    } catch(\Illuminate\Database\QueryException $e){ 
                        Session::flash(self::ERROR, self::FAILED_TO_CHECKOUT);
                        return redirect(self::PRODUCTS);
                    } 
                return redirect('payment_confirmation/'.$STELSales->id);
            } catch(\Illuminate\Database\QueryException $e){
                
                Session::flash(self::ERROR, self::FAILED_TO_CHECKOUT);
                return redirect(self::PRODUCTS);
            }
        }else{
           return redirect('/');
        } 
        
    }

    public function api_billing($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)
                        ],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $params['json'] = $data;
            $res_billing = $client->post("v1/billings", $params)->getBody();
            return json_decode($res_billing); 
            
        } catch(Exception $e){ return null;
        }
    }

    public function api_resend_va($id){
        $STELSales = STELSales::find($id);
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)
                        ],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $res_resend = $client->post("v1/billings/mps/resend/".$STELSales->BILLING_ID)->getBody();
            $resend = json_decode($res_resend);
            if($resend){
                $STELSales->VA_number = $resend && $resend->status ? $resend->data->mps->va->number : null;
                $STELSales->VA_amount = $resend && $resend->status ? $resend->data->mps->total_amount : null;
                $STELSales->VA_expired = $resend && $resend->status ? $resend->data->mps->va->expired : null;
                
                $STELSales->save();
            }
                        
            return redirect('/payment_confirmation/'.$id);
        } catch(Exception $e){ return null;
        }
    }

    public function api_cancel_va($id){
        $STELSales = STELSales::where('id', $id)
            ->with('user')
            ->with('user.company')
            ->with(self::SALES_DETAIL)
            ->with(self::SALES_DETAIL_DOT_STEL)
            ->first()
        ;

        Session::flash(self::MESSAGE, "Please choose another bank. If you leave or move to another page, your process will not be saved!");
        return view('client.STEL.cancel') 
            ->with('STELSales', $STELSales)
            ->with(self::PAYMENT_METHOD, $this->api_get_payment_methods());
    }

    public function doCancel(Request $request){
        $currentUser = Auth::user();
        if($currentUser){ 
            $STELSales = STELSales::where('id', $request->input('id'))
                ->with('user')
                ->with('user.company')
                ->with(self::SALES_DETAIL)
                ->with(self::SALES_DETAIL_DOT_STEL)
                ->first()
            ;
            $PO_ID = $this->regeneratePO($STELSales);
            $last_BILLING_ID = $STELSales->BILLING_ID;
           
            $mps_info = explode('||', $request->input(self::PAYMENT_METHOD));

            if($PO_ID){
                $stel_code_string = '';
                foreach ($STELSales->sales_detail as $row) {
                    $stel_code_string = $stel_code_string ? $stel_code_string.', '.$row->stel->code : $row->stel->code;
                }

                $data = $this->getDataBilling($PO_ID,$currentUser,$stel_code_string,$mps_info);

                $billing = $this->api_billing($data);

                $STELSales->PO_ID = $PO_ID;
                $STELSales->BILLING_ID = $billing && $billing->status ? $billing->data->_id : null;
                if($mps_info[2] != "atm"){
                    $STELSales->VA_name = $mps_info ? $mps_info[3] : null;
                    $STELSales->VA_image_url = $mps_info ? $mps_info[4] : null;
                    $STELSales->VA_number = $billing && $billing->status ? $billing->data->mps->va->number : null;
                    $STELSales->VA_amount = $billing && $billing->status  ? $billing->data->mps->total_amount : null;
                    $STELSales->VA_expired = $billing && $billing->status  ? $billing->data->mps->va->expired : null;
                }
                
                if(!$STELSales->VA_number){
                    Session::flash(self::ERROR, 'Failed to generate '.$mps_info[3].', please choose another bank!');
                    return back();
                }
            }

            try{
                $STELSales->save();

                if($last_BILLING_ID){
                    $data_cancel_billing = [
                        "canceled" => [
                            self::MESSAGE => "-",
                            "by" => $currentUser->name,
                            self::REFERENCE_ID => $currentUser->id
                        ]
                    ];
                    $this->api_cancel_billing($last_BILLING_ID, $data_cancel_billing);
                }

                $data= array( 
                    "from"=>$currentUser->name,
                    "to"=>"admin",
                    self::MESSAGE=>"Permohonan Pembelian STEL",
                    "url"=>self::SALES_URL.$STELSales->id.self::EDIT,
                    self::IS_READ=>0,
                    "created_at"=>date(self::FORMAT_DATE),
                    "updated_at"=>date(self::FORMAT_DATE)
                );

                $notificationService = new NotificationService();
                $data['id'] = $notificationService->make($data);

                event(new Notification($data));

                return redirect('payment_confirmation/'.$STELSales->id);
            } catch(\Illuminate\Database\QueryException $e){
                
                Session::flash(self::ERROR, self::FAILED_TO_CHECKOUT);
                return redirect('products');
            }
        }else{
           return redirect('/');
        } 
    }

    public function regeneratePO($STELSales){
        $currentUser = Auth::user();
        $details = array();

        foreach ($STELSales->sales_detail as $row) {
            $details [] = 
                [
                    "item" => $row->stel->code,
                    "description" => $row->stel->name,
                    "quantity" => $row->qty,
                    self::PRICE => $row->stel->price,
                    "total" => $row->stel->price*$row->qty
                ]
            ;
        }

        $data = $this->getDataPurchase($currentUser,$details);

        $purchase = $this->api_purchase($data);

        return $purchase && $purchase->status ? $purchase->data->_id : null;
    }

    public function api_cancel_billing($BILLING_ID,$data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_JSON, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN)
                        ],
            self::BASE_URI => config(self::APP_URL_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false
        ]);
        try {
            $params['json'] = $data;
            $res_cancel_billing = $client->put("v1/billings/".$BILLING_ID."/cancel", $params)->getBody();
            return json_decode($res_cancel_billing);
        } catch(Exception $e){ return null;
        }
    }

    public function destroy($id)
    {
        $cart = Cart::content()->where('rowId',$id); 
        if(count($cart) > 0 ){
            Cart::remove($id);
        }
      return redirect("/".self::PRODUCTS);
    }

    public function downloadStel($id)
    {
        $stel = STEL::find($id);

        if (!$stel){  return redirect()->back()->with(self::ERROR, self::DATA_NOT_FOUND); }

        $file = Storage::disk(self::MINIO)->get(self::STEL_URL.$stel->attachment);
                    
        return response($file, 200, \App\Services\MyHelper::getHeaderImage($stel->attachment));
        
    } 
	public function downloadfakturstel($id)
    {
        $stel = STELSales::where("id",$id)->first();

        if (!$stel){  return redirect()->back()->with(self::ERROR, self::DATA_NOT_FOUND); }
        $file = Storage::disk(self::MINIO)->get(self::STEL_URL.$stel->id."/".$stel->faktur_file);
                    
        return response($file, 200, \App\Services\MyHelper::getHeaderImage($stel->faktur_file));
    }
	
	public function downloadkuitansistel($id)
    {
        $stel = STELSales::where("id_kuitansi",$id)->first();

        if (!$stel){  return redirect()->back()->with(self::ERROR, self::DATA_NOT_FOUND); }

        $file = Storage::disk(self::MINIO)->get(self::STEL_URL.$stel->id."/".$stel->id_kuitansi);
                    
        return response($file, 200, \App\Services\MyHelper::getHeaderImage($stel->id_kuitansi));
    }
	
    public function viewWatermark($id)
    {
        $currentUser = Auth::user();
        if(!$currentUser){ return redirect()->back(); }

        $query = STELSales::
        join('users', function ($join) use ($currentUser) {
            $join->on('stels_sales.created_by', '=', 'users.id')
                ->where(self::USERS_DOT_COMPANY_ID, '=', $currentUser->company_id);
        })
        ->join('stels_sales_detail', function ($join) use ($id) {
            $join->on('stels_sales.id', '=', 'stels_sales_detail.stels_sales_id')
                ->where('stels_sales_detail.id', '=', $id);
        });
        $stel = $query->get();
        if (count($stel)){ 

            $file = Storage::disk(self::MINIO)->get("stelAttach/".$id."/".$stel[0]->attachment);
                    
            return response($file, 200, \App\Services\MyHelper::getHeaderImage($stel[0]->attachment));


        }else{ return redirect()->back();
        }

    }

    private function getDataPurchase($currentUser,$details){ 
        return [
            "from" => [
                "name" => "PT. TELKOM INDONESIA (PERSERO) TBK",
                self::ADDRESS => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
                self::PRONE => "(+62) 812-2483-7500",
                self::EMAIL => "urelddstelkom@gmail.com",
                "npwp" => "01.000.013.1-093.000"
            ],
            "to" => [
                "name" => $currentUser->company->name ? $currentUser->company->name : "-",
                self::ADDRESS => $currentUser->company->address ? $currentUser->company->address : "-",
                self::PRONE => $currentUser->company->phone_number ? $currentUser->company->phone_number : "-",
                self::EMAIL => $currentUser->email ? $currentUser->email : "-",
                "npwp" => $currentUser->company->npwp_number ? $currentUser->company->npwp_number : "-"
            ],
            "product_id" => config(self::APP_DOT_PRODUCT_ID_TTH), //product_id TTH
            "details" => $details,
            self::CREATED => [
                "by" => $currentUser->name,
                self::REFERENCE_ID => $currentUser->id
            ],
            "include_tax_invoice" => true,
            "bank" => [
                "owner" => "Divisi RisTI TELKOM",
                "account_number" => "131-0096022712",
                "bank_name" => "BANK MANDIRI",
                "branch_office" => "KCP KAMPUS TELKOM BANDUNG"         
            ]
        ];
    }

    private function getDataBilling($PO_ID,$currentUser,$stel_code_string,$mps_info){  
        return [
            "draft_id" => $PO_ID,
            self::CREATED => [
                "by" => $currentUser->name,
                self::REFERENCE_ID => $currentUser->id
            ],
            "config" => [
                "kode_wapu" => "01",
                "afiliasi" => "non-telkom",
                "tax_invoice_text" => $stel_code_string.'.',
                self::PAYMENT_METHOD => $mps_info[2] == "atm" ? "internal" : "mps",
            ],
            "mps" => [
                "gateway" => $mps_info[0],
                "product_code" => $mps_info[1],
                "product_type" => $mps_info[2],
                "manual_expired" => 20160
            ]
        ];
    }


}
