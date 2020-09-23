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
        $request->session()->forget('PO_ID_from_TPN');
        $request->session()->forget('unique_code_from_TPN');
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

    public function payment_confirmation($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $STELSales = STELSales::where("id", $id)
                        ->with('sales_detail')
                        ->with('sales_detail.stel')->get();
            $page = "payment_confirmation";
            return view('client.STEL.payment_confirmation') 
            ->with('page', $page)   
            ->with('data', $STELSales);
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
                $STELSales->payment_status = $STELSales->payment_status == 0 ? 2 : $STELSales->payment_status;
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
/*
            $total_price = $purchase && $purchase->status ? $purchase->data->total_price : Cart::subtotal();
            $unique_code = $purchase && $purchase->status ? $purchase->data->unique_code : '0';
            $tax = $purchase && $purchase->status ? $purchase->data->tax : Cart::tax();
            $final_price = $purchase && $purchase->status == true ? $purchase->data->final_price : Cart::total();
*/
            $PO_ID = $request->session()->get('PO_ID_from_TPN') ? $request->session()->get('PO_ID_from_TPN') : ($purchase && $purchase->status ? $purchase->data->_id : null);
            $request->session()->put('PO_ID_from_TPN', $PO_ID);
            $total_price = Cart::subtotal();
            $unique_code = $request->session()->get('unique_code_from_TPN') ? $request->session()->get('unique_code_from_TPN') : ($purchase && $purchase->status ? $purchase->data->unique_code : '0');
            $request->session()->put('unique_code_from_TPN', $unique_code);
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
                ->with('payment_method', $this->api_get_payment_methods())
                ;
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
            $res_purchase = $client->post("v1/draftbillings", $params)->getBody();
            $purchase = json_decode($res_purchase);

            return $purchase;
        } catch(Exception $e){
            return null;
        }
    }

    public function api_get_payment_methods(){
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
            $res_payment_method = $client->get("v1/products/".config("app.product_id_tth")."/paymentmethods")->getBody();
            $payment_method = json_decode($res_payment_method);

            return $payment_method;
        } catch(Exception $e){
            return null;
        }
    }

    public function doCheckout(Request $request){  
        $PO_ID = $request->session()->get('PO_ID_from_TPN');
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
           $mps_info = explode('||', $request->input("payment_method"));
           $STELSales->payment_method = $mps_info[2] == "atm" ? 1 : 2;
           $STELSales->payment_status = 0;
           $STELSales->invoice = $request->input("invoice_number");
           $STELSales->user_id = $currentUser->id;
           $STELSales->total = $request->input("final_price");
           $STELSales->created_by =$currentUser->id;
           $STELSales->created_at = date("Y-m-d H:i:s");

            if($PO_ID){
                $stel_code_string = '';
                foreach (Cart::content() as $row) {
                    $res = explode('myTokenProduct', $row->name);
                    $stel_code = $res[1] ? $res[1] : '-';
                    $stel_code_string = $stel_code_string ? $stel_code_string.', '.$res[1] : $res[1];
                }

                $data = [
                    "draft_id" => $PO_ID,
                    "created" => [
                        "by" => $currentUser->name,
                        "reference_id" => $currentUser->id
                    ],
                    "config" => [
                        "kode_wapu" => "01",
                        "afiliasi" => "non-telkom",
                        "tax_invoice_text" => $stel_code_string.'.',
                        "payment_method" => $mps_info[2] == "atm" ? "internal" : "mps",
                    ],
                    "mps" => [
                        "gateway" => $mps_info[0],
                        "product_code" => $mps_info[1],
                        "product_type" => $mps_info[2],
                        "manual_expired" => 20160
                    ]
                ];

                $billing = $this->api_billing($data);

                $STELSales->PO_ID = $PO_ID;
                $STELSales->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;
                if($mps_info[2] != "atm"){
                    $STELSales->VA_name = $mps_info ? $mps_info[3] : null;
                    $STELSales->VA_image_url = $mps_info ? $mps_info[4] : null;
                    $STELSales->VA_number = $billing && $billing->status == true ? $billing->data->mps->va->number : null;
                    $STELSales->VA_amount = $billing && $billing->status == true ? $billing->data->mps->va->amount : null;
                    $STELSales->VA_expired = $billing && $billing->status == true ? $billing->data->mps->va->expired : null;
                }
                
                if(!$STELSales->VA_number){
                    $request->session()->forget('PO_ID_from_TPN');
                    Session::flash('error', 'Failed to generate '.$mps_info[3].', please choose another bank!');
                    return back();
                }
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

                // event(new Notification($data));


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

                        $request->session()->forget('PO_ID_from_TPN');
                        $request->session()->forget('unique_code_from_TPN');

                    } catch(\Illuminate\Database\QueryException $e){ 
                        Session::flash('error', 'Failed To Checkout');
                        return redirect('products');
                    } 
                return redirect('payment_confirmation/'.$STELSales->id);
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

            return $billing;
        } catch(Exception $e){
            return null;
        }
    }

    public function api_resend_va($id){
        $STELSales = STELSales::find($id);
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $res_resend = $client->post("v1/billings/mps/resend/".$STELSales->BILLING_ID)->getBody();
            $resend = json_decode($res_resend);
            if($resend){
                $STELSales->VA_number = $resend && $resend->status == true ? $resend->data->mps->va->number : null;
                $STELSales->VA_amount = $resend && $resend->status == true ? $resend->data->mps->va->amount : null;
                $STELSales->VA_expired = $resend && $resend->status == true ? $resend->data->mps->va->expired : null;
                
                $STELSales->save();
            }
                        
            return redirect('/payment_confirmation/'.$id);
        } catch(Exception $e){
            return null;
        }
    }

    public function api_cancel_va($id){
        $STELSales = STELSales::where('id', $id)
            ->with('user')
            ->with('user.company')
            ->with('sales_detail')
            ->with('sales_detail.stel')
            ->first()
        ;

        Session::flash('message', 'Please choose another bank to complete cancel payment process!');
        return view('client.STEL.cancel') 
            ->with('STELSales', $STELSales)
            ->with('payment_method', $this->api_get_payment_methods());
    }

    public function doCancel(Request $request){
        $currentUser = Auth::user();
        if($currentUser){ 
            $STELSales = STELSales::where('id', $request->input('id'))
                ->with('user')
                ->with('user.company')
                ->with('sales_detail')
                ->with('sales_detail.stel')
                ->first()
            ;
            $PO_ID = $this->regeneratePO($STELSales);
            $last_BILLING_ID = $STELSales->BILLING_ID;
           
            $mps_info = explode('||', $request->input("payment_method"));

            if($PO_ID){
                $stel_code_string = '';
                foreach ($STELSales->sales_detail as $row) {
                    $stel_code_string = $stel_code_string ? $stel_code_string.', '.$row->stel->code : $row->stel->code;
                }

                $data = [
                    "draft_id" => $PO_ID,
                    "created" => [
                        "by" => $currentUser->name,
                        "reference_id" => $currentUser->id
                    ],
                    "config" => [
                        "kode_wapu" => "01",
                        "afiliasi" => "non-telkom",
                        "tax_invoice_text" => $stel_code_string.'.',
                        "payment_method" => $mps_info[2] == "atm" ? "internal" : "mps",
                    ],
                    "mps" => [
                        "gateway" => $mps_info[0],
                        "product_code" => $mps_info[1],
                        "product_type" => $mps_info[2],
                        "manual_expired" => 20160
                    ]
                ];

                $billing = $this->api_billing($data);

                $STELSales->PO_ID = $PO_ID;
                $STELSales->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;
                if($mps_info[2] != "atm"){
                    $STELSales->VA_name = $mps_info ? $mps_info[3] : null;
                    $STELSales->VA_image_url = $mps_info ? $mps_info[4] : null;
                    $STELSales->VA_number = $billing && $billing->status == true ? $billing->data->mps->va->number : null;
                    $STELSales->VA_amount = $billing && $billing->status == true ? $billing->data->mps->va->amount : null;
                    $STELSales->VA_expired = $billing && $billing->status == true ? $billing->data->mps->va->expired : null;
                }
                
                if(!$STELSales->VA_number){
                    Session::flash('error', 'Failed to generate '.$mps_info[3].', please choose another bank!');
                    return back();
                }
            }

            try{
                $save = $STELSales->save();

                if($last_BILLING_ID){
                    $data_cancel_billing = [
                        "canceled" => [
                            "message" => "-",
                            "by" => $currentUser->name,
                            "reference_id" => $currentUser->id
                        ]
                    ];
                    $this->api_cancel_billing($last_BILLING_ID, $data_cancel_billing);
                }

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

                // event(new Notification($data));

                return redirect('payment_confirmation/'.$STELSales->id);
            } catch(\Illuminate\Database\QueryException $e){
                
                Session::flash('error', 'Failed To Checkout');
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
                    "price" => $row->stel->price,
                    "total" => $row->stel->price*$row->qty
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

        return $purchase && $purchase->status ? $purchase->data->_id : null;
    }

    public function api_cancel_billing($BILLING_ID,$data){
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
            $res_cancel_billing = $client->put("v1/billings/".$BILLING_ID."/cancel", $params)->getBody();
            $cancel_billing = json_decode($res_cancel_billing);

            return $cancel_billing;
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
                  'Content-Type: application/octet-stream',
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
