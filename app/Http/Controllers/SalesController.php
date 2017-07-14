<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\STEL;
use App\Logs;
use App\STELSales;
use App\STELSalesAttach;
use App\STELSalesDetail;

use Auth;
use Session; 
use Validator;
use Excel;
use Response;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

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
        $type = '';
        $payment_status = '';
        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            $select = array(
                "logs.action","logs.page","logs.created_at as search_date","users.name"
            );
            $select = array("stels_sales.id","stels_sales.created_at","stels_sales.invoice","stels_sales.payment_status","stels_sales.payment_method","stels_sales.total"); 
            if ($search != null){
            
                $dataSales = STELSales::select($select)->where('invoice','like','%'.$search.'%');

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Search Sales"; 
                $dataSearch = array("search"=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Sales";
                $logs->save();

            }else{
                $dataSales = STELSales::select($select)->whereNotNull('stels_sales.created_at')
                            ->join("users","users.id","=","stels_sales.user_id");
            }
             if ($request->has('payment_status')){
                $payment_status = $request->get('payment_status');
                if($request->input('type') != 'all'){
                    $dataSales->where('payment_status', $request->get('payment_status'));
                }
            }

            if ($request->has('before_date')){  
                $rawRangeDate = "DATE_FORMAT(stels_sales.created_at,'%Y-%m-%d') >= '".$request->get('before_date')."'";
                $dataSales = $dataSales->where(\DB::raw($rawRangeDate), 1); 
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){ 
                $rawRangeDate = "DATE_FORMAT(stels_sales.created_at,'%Y-%m-%d') <= '".$request->get('after_date')."'";
                $dataSales = $dataSales->where(\DB::raw($rawRangeDate), 1); 

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
                ->with('type', $type)
                ->with('payment_status', $payment_status)
                ->with('before_date', $before)
                ->with('after_date', $after);
        }
    } 

    public function show($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array("stels.name","stels.price","stels.code"); 
            $STELSales = STELSalesDetail::select($select)->where("stels_sales_id",$id)
                        ->join("stels","stels.id","=","stels_sales_detail.stels_id")
                        ->get();
            return view('admin.sales.detail')
            ->with('data', $STELSales) ;     
        }else{
            redirect("login");
        }
        
    }  

    public function sales_detail($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $select = array("stels.name","stels.price","stels.code"); 
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
        $select = array("stels_sales.id","stels_sales_attachment.attachment","stels_sales_attachment.stel_sales_id");  
        $stel = STELSalesAttach::select($select)->rightJoin("stels_sales","stels_sales.id","=","stels_sales_attachment.stel_sales_id")
                ->where("stels_sales.id",$id)->first();
        
        return view('admin.sales.edit')
            ->with('data', $stel);
    }

      public function update(Request $request, $id)
    {
        $currentUser = Auth::user();

        $STELSales = STELSales::find($id);
        $oldStel = $STELSales;  
        if ($request->has('payment_status')){
            $STELSales->updated_by = $currentUser->id; 
            $STELSales->payment_status = $request->input('payment_status');

            try{
                $STELSales->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;
                $logs->id = Uuid::uuid4();
                $logs->action = "Update Status Pembayaran STEL";
                $logs->data = $oldStel;
                $logs->created_by = $currentUser->id;
                $logs->page = "SALES";
                $logs->save();

                Session::flash('message', 'SALES successfully updated');
                return redirect('/admin/sales');
            } catch(Exception $e){
                Session::flash('error', 'Save failed');
                return redirect('/admin/sales/'.$STELSales->id.'/edit');
            }
        }else{
            return redirect('/admin/sales');
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


}
