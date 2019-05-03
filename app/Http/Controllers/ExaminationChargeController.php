<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Response;
use Session;
use Input;

use App\Logs;
use App\ExaminationCharge;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationChargeController extends Controller
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
            $category = '';
            $status = -1;
            
            if ($search != null){
                $examinationCharge = ExaminationCharge::whereNotNull('created_at')
                    ->where('device_name','like','%'.$search.'%')
                    ->orWhere('stel','like','%'.$search.'%')
                    ->orderByRaw('category, device_name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Charge";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "EXAMINATION CHARGE";
                    $logs->save();
            }else{
                $query = ExaminationCharge::whereNotNull('created_at');

                if ($request->has('category')){
                    $category = $request->get('category');
					if($request->input('category') != 'all'){
						$query->where('category', $request->get('category'));
					}
                }

                if ($request->has('is_active')){
					$status = $request->get('is_active');
                    if ($request->get('is_active') > -1){
                        $query->where('is_active', $request->get('is_active'));
                    }
                }

                $examinationCharge = $query->orderByRaw('category, device_name')
                                           ->paginate($paginate);
            }
            
            if (count($examinationCharge) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.charge.index')
                ->with('message', $message)
                ->with('data', $examinationCharge)
                ->with('search', $search)
                ->with('category', $category)
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
        return view('admin.charge.create');
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
			
			$name_exists = $this->cekNamaPerangkat($request->input('device_name'));
		if($name_exists == 1){
			return redirect()->back()
			->with('error_name', 1)
			->withInput($request->all());
		}

        $charge = new ExaminationCharge;
        $charge->id = Uuid::uuid4();
        $charge->device_name = $request->input('device_name');
        $charge->stel = $request->input('stel');
        $charge->category = $request->input('category');
        $charge->duration = str_replace(",","",$request->input('duration'));
        $charge->price = str_replace(",","",$request->input('price'));
        $charge->ta_price = str_replace(",","",$request->input('ta_price'));
        $charge->vt_price = str_replace(",","",$request->input('vt_price'));
        $charge->is_active = $request->input('is_active');
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = "EXAMINATION CHARGE";
            $logs->save();

            Session::flash('message', 'Charge successfully created');
            return redirect('/admin/charge');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/charge/create');
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
        $charge = ExaminationCharge::find($id);

        return view('admin.charge.edit')
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

        $charge = ExaminationCharge::find($id);
        $oldData = $charge;

        if ($request->has('device_name')){
            $charge->device_name = $request->input('device_name');
        }
        if ($request->has('stel')){
            $charge->stel = $request->input('stel');
        }
        if ($request->has('category')){
            $charge->category = $request->input('category');
        }
        if ($request->has('duration')){
			$charge->duration = str_replace(",","",$request->input('duration'));
        }
        if ($request->has('price')){
			$charge->price = str_replace(",","",$request->input('price'));
        }
        if ($request->has('ta_price')){
			$charge->ta_price = str_replace(",","",$request->input('ta_price'));
        }
        if ($request->has('vt_price')){
            $charge->vt_price = str_replace(",","",$request->input('vt_price'));
        }
        if ($request->has('is_active')){
            $charge->is_active = $request->input('is_active');
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Charge";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "EXAMINATION CHARGE";
            $logs->save();

            Session::flash('message', 'Charge successfully updated');
            return redirect('/admin/charge');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/charge/'.$charge->id.'/edit');
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
        $charge = ExaminationCharge::find($id);
        $currentUser = Auth::user();
        $oldData = $charge;
        if ($charge){
            try{
                $charge->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Charge";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "EXAMINATION CHARGE";
                $logs->save();
                
                Session::flash('message', 'Charge successfully deleted');
                return redirect('/admin/charge');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/charge');
            }
        }
    }
	
	function cekNamaPerangkat($name)
    {
		$charge = ExaminationCharge::where('device_name','=',''.$name.'')->get();
		return count($charge);
    }
	
	public function autocomplete($query) {
        $respons_result = ExaminationCharge::autocomplet($query);
        return response($respons_result);
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input('search'));
        
        $category = '';
        $status = -1;

        if ($search != null){
            $examinationCharge = ExaminationCharge::whereNotNull('created_at')
                ->where('device_name','like','%'.$search.'%')
                ->orWhere('stel','like','%'.$search.'%')
                ->orderBy('device_name');
        }else{
            $query = ExaminationCharge::whereNotNull('created_at');

            if ($request->has('category')){
                $category = $request->get('category');
                if($request->input('category') != 'all'){
                    $query->where('category', $request->get('category'));
                }
            }

            if ($request->has('is_active')){
                $status = $request->get('is_active');
                if ($request->get('is_active') > -1){
                    $query->where('is_active', $request->get('is_active'));
                }
            }

            $examinationCharge = $query->orderBy('device_name');
        }

        $data = $examinationCharge->get();

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'Nama Perangkat',
            'Referensi Uji',
            'Kategori',
            'Durasi (Hari)',
            'Biaya QA (Rp.)',
            'Biaya VT (Rp.)',
            'Biaya TA (Rp.)',
            'Status'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            $examsArray[] = [
                $row->device_name,
                $row->stel,
                $row->category,
                number_format($row->duration, 0, '.', ','),
                number_format($row->price, 0, '.', ','),
                number_format($row->vt_price, 0, '.', ','),
                number_format($row->ta_price, 0, '.', ','),
                $row->is_active == '1' ? 'Active' : 'Not Active'
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Tarif Pengujian";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data Tarif Pengujian', function($excel) use ($examsArray) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('payments file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
