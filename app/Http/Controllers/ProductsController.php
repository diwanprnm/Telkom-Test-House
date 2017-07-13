<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Events\Notification;

use App\STEL;
use App\STELSales;
use App\STELSalesDetail;
use App\Logs; 

use Auth;
use Session;
use Cart;
use Response;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ProductsController extends Controller
{
    public function index(Request $request)
    {   
         $currentUser = Auth::user();

        if($currentUser){
            $paginate = 10;
            $stels = STEL::whereNotNull('created_at')
                ->paginate($paginate);
      
            $page = "products";
            return view('client.STEL.products') 
                ->with('page', $page)
                ->with('stels', $stels);
        }else{
           return  redirect('login');
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
            $select = array("stels.id", "stels.name","stels.price","stels.code","stels.attachment","stels_sales.invoice"); 
            $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels_sales","stels_sales.id","=","stels_sales_detail.stels_sales_id")
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                        ->get();
            $page = "payment_detail";
            return view('client.STEL.payment_detail') 
            ->with('page', $page)   
            ->with('stels', $STELSales);     
        }else{
           return redirect("login");
        }
        
    } 

    public function store(Request $request){
        Cart::add(['id' => $request->id, 'name' => $request->name,'code' => $request->code, 'qty' => 1, 'price' => $request->price]);
        return redirect('products');
    }

    public function checkout(Request $request){ 
        $countStelsSales =  STELSales::where(array())->count();
        $invoice_id = 0; 
        $fill = 6;
        $STELSales = new STELSales();
        if(!empty($countStelsSales)){
          
            $lastInvoiceID = $STELSales->count();   

            $number = $lastInvoiceID+1; 
            $invoice_number = "INV-".str_pad($number, $fill, '0', STR_PAD_LEFT);
             
        }else{ 
            $number = 1; 
            $invoice_number = "INV-".str_pad($number, $fill, '0', STR_PAD_LEFT);
        }

        if($request->input('agree')){
            $logs = new Logs;
            $currentUser = Auth::user();
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
                 ->with('invoice_number', $invoice_number);
        }else{
            return redirect('products');
        } 
    }

    public function doCheckout(Request $request){ 

        //
        // A very simple PHP example that sends a HTTP POST to a remote site
        //

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded' ,'Authorization: Bearer 8a2677ce150bb2b9b2b7c801479618d2' ,'Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL,config('app.main_api_server').'/transactions');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "merchant_id=".config('app.merchant_id')."&merchant_secret=".config('app.merchant_secret')."&invoice=".$request->input("invoice_number")."&amount=".Cart::total()."&add_info1=tax(10%)&timeout=3000&return_url=https://www.mainapi.net/store/client/#/api");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_result = curl_exec ($ch);

        curl_close ($ch);
        $result =  json_decode($server_result); 
       
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
           $STELSales->total = Cart::total();
           $STELSales->created_by =$currentUser->id;
           $STELSales->created_at = date("Y-m-d H:i:s");
            // $STELSales->payment_code =  $result->payment_code;
            try{
                $save = $STELSales->save();
                    try{  
                        foreach(Cart::content() as $row){ 
                            $STELSalesDetail = new STELSalesDetail;
                            $STELSalesDetail->stels_sales_id = $STELSales->id;
                            $STELSalesDetail->stels_id = $row->id;
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
                return redirect('payment_status');
            } catch(\Illuminate\Database\QueryException $e){
                
                Session::flash('error', 'Failed To Checkout');
                return redirect('products');
            }
        }else{
           return redirect('/');
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
                "from"=>"35a35ea3-6fd5-43ae-8b97-ddf7525e94d1",
                "to"=>"admin",
                "action"=>"sales",
                "message"=>"Notification Message"
                );
          event(new Notification($data));
     return "event fired";
    }

}
