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









use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CalibrationChargeController extends Controller
{
    private const SEARCH = 'search';
    private const SEARCH = 'search';
    private const CREATE = 'created_at';
    private const DEVICE = 'device_name';
    private const CALIBRATION = 'CALIBRATION CHARGE';
    private const IS_ACTIVE = 'is_active';
    private const MESSAGE = 'message';
    private const PRICE = 'price';
    private const ADM = '/admin/calibration';
    private const ERR = 'error';

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
            $search = trim($request->input($this::SEARCH_));
            $status = -1;
            
            if ($search != null){
                $charge = CalibrationCharge::whereNotNull($this::CREATE)
                    ->where($this::DEVICE,'like','%'.$search.'%')
                    ->orderBy($this::DEVICE)
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Calibration Charge";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = $this::CALIBRATION;
                    $logs->save();
            }else{
                $query = CalibrationCharge::whereNotNull($this::CREATE);

                if ($request->has($this::IS_ACTIVE)){
                    $status = $request->get($this::IS_ACTIVE);
                    if ($request->get($this::IS_ACTIVE) > -1){
                        $query->where($this::IS_ACTIVE, $request->get($this::IS_ACTIVE));
                    }
                }

                $charge = $query->orderBy($this::DEVICE)
                               ->paginate($paginate);
            }
            
            if (count($charge) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.calibration.index')
                ->with($this::MESSAGE, $message)
                ->with('data', $charge)
                ->with($this::SEARCH, $search)
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
        $charge->device_name = $request->input($this::DEVICE);
        $charge->price = str_replace(",","",$request->input($this::PRICE));
        $charge->is_active = $request->input($this::IS_ACTIVE);
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Calibration Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::CALIBRATION;
            $logs->save();

            Session::flash($this::MESSAGE, 'Charge successfully created');
            return redirect();
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
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
        if ($request->has($this::DEVICE)){
            $charge->device_name = $request->input($this::DEVICE);
        }
        if ($request->has($this::PRICE)){
            $charge->price = str_replace(",","",$request->input($this::PRICE));
        }
        if ($request->has($this::IS_ACTIVE)){
            $charge->is_active = $request->input($this::IS_ACTIVE);
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Calibration Charge";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::CALIBRATION;
            $logs->save();

            Session::flash($this::MESSAGE, 'Charge successfully updated');
            return redirect($this::ADM);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
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
                $logs->page = $this::CALIBRATION;
                $logs->save();

                Session::flash($this::MESSAGE, 'Charge successfully deleted');
                return redirect($this::ADM);
            }catch (Exception $e){
                Session::flash($this::ERR, 'Delete failed');
                return redirect($this::ADM);
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

        $search = trim($request->input($this::SEARCH_));
       

        if ($search != null){
            $charge = CalibrationCharge::whereNotNull($this::CREATE)
                ->where($this::DEVICE,'like','%'.$search.'%')
                ->orderBy($this::DEVICE);

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search Calibration Charge";
                $datasearch = array("search"=>$search);
                $logs->data = json_encode($datasearch);
                $logs->created_by = $currentUser->id;
                $logs->page = $this::CALIBRATION;
                $logs->save();
        }else{
            $query = CalibrationCharge::whereNotNull($this::CREATE);

            if ($request->has($this::IS_ACTIVE)){
                $status = $request->get($this::IS_ACTIVE);
                if ($request->get($this::IS_ACTIVE) > -1){
                    $query->where($this::IS_ACTIVE, $request->get($this::IS_ACTIVE));
                }
            }

            $charge = $query->orderBy($this::DEVICE);
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
