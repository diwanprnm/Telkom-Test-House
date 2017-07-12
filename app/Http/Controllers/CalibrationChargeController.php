<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;

use App\CalibrationCharge;
use App\Logs;

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
            $search = trim($request->input('search'));
            $status = -1;
            
            if ($search != null){
                $charge = CalibrationCharge::whereNotNull('created_at')
                    ->where('device_name','like','%'.$search.'%')
                    ->orderBy('device_name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Calibration Charge";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "CALIBRATION CHARGE";
                    $logs->save();
            }else{
                $query = CalibrationCharge::whereNotNull('created_at');

                if ($request->has('is_active')){
                    $status = $request->get('is_active');
                    if ($request->get('is_active') > -1){
                        $query->where('is_active', $request->get('is_active'));
                    }
                }

                $charge = $query->orderBy('device_name')
                               ->paginate($paginate);
            }
            
            if (count($charge) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.calibration.index')
                ->with('message', $message)
                ->with('data', $charge)
                ->with('search', $search)
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
        $charge->device_name = $request->input('device_name');
        $charge->price = str_replace(",","",$request->input('price'));
        $charge->is_active = $request->input('is_active');
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Calibration Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = "CALIBRATION CHARGE";
            $logs->save();

            Session::flash('message', 'Charge successfully created');
            return redirect('/admin/calibration');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
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
        if ($request->has('device_name')){
            $charge->device_name = $request->input('device_name');
        }
        if ($request->has('price')){
            $charge->price = str_replace(",","",$request->input('price'));
        }
        if ($request->has('is_active')){
            $charge->is_active = $request->input('is_active');
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Calibration Charge";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "CALIBRATION CHARGE";
            $logs->save();

            Session::flash('message', 'Charge successfully updated');
            return redirect('/admin/calibration');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
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
                $logs->page = "CALIBRATION CHARGE";
                $logs->save();

                Session::flash('message', 'Charge successfully deleted');
                return redirect('/admin/calibration');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/calibration');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = CalibrationCharge::autocomplet($query);
        return response($respons_result);
    }
}
