<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\CalibrationCharge;
use App\Logs;

use Excel;

/**Duplicated var
 * */
$search_var='search';
$created_var='created_at';
$device_var='device_name';
$calibration_var="CALIBRATION CHARGE";
$is_active_var='is_active';
$message_var='message';
$price_var='price';
$adm_var='/admin/calibration';
$err_var='error';








use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CalibrationChargeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
       
        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input($search_var));
            $status = -1;
            
            if ($search != null){
                $charge = CalibrationCharge::whereNotNull( $created_var)
                    ->where($device_var,'like','%'.$search.'%')
                    ->orderBy($device_var)
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Calibration Charge";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = $calibration_var;
                    $logs->save();
            }else{
                $query = CalibrationCharge::whereNotNull( $created_var);

                if ($request->has($is_active_var)){
                    $status = $request->get($is_active_var);
                    if ($request->get($is_active_var) > -1){
                        $query->where($is_active_var, $request->get($is_active_var));
                    }
                }

                $charge = $query->orderBy($device_var)
                               ->paginate($paginate);
            }
            
            if (count($charge) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.calibration.index')
                ->with($message_var, $message)
                ->with('data', $charge)
                ->with($search_var, $search)
                ->with('status', $status);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.calibration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        $charge = new CalibrationCharge;
        $charge->id = Uuid::uuid4();
        $charge->device_name = $request->input($device_var);
        $charge->price = str_replace(",","",$request->input($price_var));
        $charge->is_active = $request->input($is_active_var);
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Calibration Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = $calibration_var;
            $logs->save();

            Session::flash($message_var, 'Charge successfully created');
            return redirect();
        } catch(Exception $e){
            Session::flash($err_var, 'Save failed');
            return redirect('/admin/calibration/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $charge = CalibrationCharge::find($id);

        return view('admin.calibration.edit')
            ->with('data', $charge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();

        $charge = CalibrationCharge::find($id);
        $oldData = $charge;
        if ($request->has($device_var)){
            $charge->device_name = $request->input($device_var);
        }
        if ($request->has($price_var)){
            $charge->price = str_replace(",","",$request->input($price_var));
        }
        if ($request->has($is_active_var)){
            $charge->is_active = $request->input($is_active_var);
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Calibration Charge";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = $calibration_var;
            $logs->save();

            Session::flash($message_var, 'Charge successfully updated');
            return redirect($adm_var);
        } catch(Exception $e){
            Session::flash($err_var, 'Save failed');
            return redirect('/admin/calibration/'.$charge->id.'/edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user(); 
        $charge = CalibrationCharge::find($id);
        $oldData = $charge;
        if ($charge){
            try{
                $charge->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Calibration Charge";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = $calibration_var;
                $logs->save();

                Session::flash($message_var, 'Charge successfully deleted');
                return redirect($adm_var);
            }catch (Exception $e){
                Session::flash( $err_var, 'Delete failed');
                return redirect($adm_var);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = CalibrationCharge::autocomplet($query);
        return response($respons_result);
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input($search_var));
       // $status = -1;

        if ($search != null){
            $charge = CalibrationCharge::whereNotNull( $created_var)
                ->where($device_var,'like','%'.$search.'%')
                ->orderBy($device_var);

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search Calibration Charge";
                $datasearch = array("search"=>$search);
                $logs->data = json_encode($datasearch);
                $logs->created_by = $currentUser->id;
                $logs->page = $calibration_var;
                $logs->save();
        }else{
            $query = CalibrationCharge::whereNotNull( $created_var');

            if ($request->has($is_active_var)){
                $status = $request->get($is_active_var);
                if ($request->get($is_active_var) > -1){
                    $query->where($is_active_var, $request->get($is_active_var));
                }
            }

            $charge = $query->orderBy($device_var);
        }

        $data = $charge->get();

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'Nama Alat Uji',
            'Biaya (Rp.)',
            'Status'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            $examsArray[] = [
                $row->device_name,
                number_format($row->price, 0, '.', ','),
                $row->is_active == '1' ? 'Active' : 'Not Active'
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Tarif Kalibrasi";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data Tarif Kalibrasi', function($excel) use ($examsArray) {

            // Set the spreadsheet title, creator, and description
           

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
