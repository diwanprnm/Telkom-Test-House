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

use App\Services\Logs\LogService;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationChargeController extends Controller
{
    private const ADMIN_CHARGE = 'admin/charge';
    private const CATEGORY = 'category';
    private const CREATED_AT = 'created_at';
    private const DEVICE_NAME = 'device_name';
    private const DURATION = 'duration';
    private const ERROR = 'error';
    private const EXAMINATION_CHARGE = 'EXAMINATION CHARGE';
    private const IS_ACTIVE = 'is_active';
    private const MESSAGE = 'message';
    private const PRICE = 'price';
    private const SEARCH = 'search';
    private const TA_PRICE = 'ta_price';
    private const VT_PRICE = 'vt_price';
    

    public function __construct()
    {
        $this->middleware('auth.admin');
    }


    public function index(Request $request)
    {
        $logService = new LogService();

        $notFound = null;
        $message = null;
        $paginate = 10;
        $search = trim($request->input($this::SEARCH));
        $category = '';
        $status = -1;
        
        if ($search != null){
            $examinationCharge = ExaminationCharge::whereNotNull($this::CREATED_AT)
                ->where($this::DEVICE_NAME,'like','%'.$search.'%')
                ->orWhere('stel','like','%'.$search.'%')
                ->orderByRaw('category, device_name')
                ->paginate($paginate);

                $logService->createLog('Search Charge', $this::EXAMINATION_CHARGE, json_encode(array("search"=>$search)) );
        }else{
            $query = ExaminationCharge::whereNotNull($this::CREATED_AT);

            if ($request->has($this::CATEGORY)){
                $category = $request->get($this::CATEGORY);
                if($request->input($this::CATEGORY) != 'all'){
                    $query->where($this::CATEGORY, $request->get($this::CATEGORY));
                }
            }

            if ($request->has($this::IS_ACTIVE)){
                $status = $request->get($this::IS_ACTIVE);
                if ($request->get($this::IS_ACTIVE) > -1){
                    $query->where($this::IS_ACTIVE, $request->get($this::IS_ACTIVE));
                }
            }

            $examinationCharge = $query->orderByRaw('category, device_name')
                                        ->paginate($paginate);
        }
        
        if (count($examinationCharge) == 0){
            $notFound = 'Data not found';
        }
        
        return view('admin.charge.index')
            ->with('notFound', $notFound)
            ->with($this::MESSAGE, $message)
            ->with('data', $examinationCharge)
            ->with($this::SEARCH, $search)
            ->with($this::CATEGORY, $category)
            ->with('status', $status);
        
    }


    public function create()
    {
        return view('admin.charge.create');
    }


    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $logService = new LogService();
			
		$name_exists = $this->cekNamaPerangkat($request->input($this::DEVICE_NAME));
		if($name_exists == 1){
			return redirect()->back()
                ->with('error_name', 1)
                ->withInput($request->all());
		}

        $charge = new ExaminationCharge;
        $charge->id = Uuid::uuid4();
        $charge->device_name = $request->input($this::DEVICE_NAME);
        $charge->stel = $request->input('stel');
        $charge->category = $request->input($this::CATEGORY);
        $charge->duration = str_replace(",","",$request->input($this::DURATION));
        $charge->price = str_replace(",","",$request->input($this::PRICE));
        $charge->ta_price = str_replace(",","",$request->input($this::TA_PRICE));
        $charge->vt_price = str_replace(",","",$request->input($this::VT_PRICE));
        $charge->is_active = $request->input($this::IS_ACTIVE);
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logService->createLog('Create Charge', $this::EXAMINATION_CHARGE, $charge );

            Session::flash($this::MESSAGE, 'Charge successfully created');
            return redirect($this::ADMIN_CHARGE);
        } catch(Exception $e){
            return redirect('/admin/charge/create')->with($this::ERROR, 'Save failed');
        }
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $charge = ExaminationCharge::find($id);

        return view('admin.charge.edit')
            ->with('data', $charge);
    }


    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $logService = new LogService();

        $charge = ExaminationCharge::find($id);
        $oldData = $charge;

        if ($request->has($this::DEVICE_NAME)){
            $charge->device_name = $request->input($this::DEVICE_NAME);
        }
        if ($request->has('stel')){
            $charge->stel = $request->input('stel');
        }
        if ($request->has($this::CATEGORY)){
            $charge->category = $request->input($this::CATEGORY);
        }
        if ($request->has($this::DURATION)){
			$charge->duration = str_replace(",","",$request->input($this::DURATION));
        }
        if ($request->has($this::PRICE)){
			$charge->price = str_replace(",","",$request->input($this::PRICE));
        }
        if ($request->has($this::TA_PRICE)){
			$charge->ta_price = str_replace(",","",$request->input($this::TA_PRICE));
        }
        if ($request->has($this::VT_PRICE)){
            $charge->vt_price = str_replace(",","",$request->input($this::VT_PRICE));
        }
        if ($request->has($this::IS_ACTIVE)){
            $charge->is_active = $request->input($this::IS_ACTIVE);
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logService->createLog('Update Charge', $this::EXAMINATION_CHARGE, $oldData );

            Session::flash($this::MESSAGE, 'Charge successfully updated');
            return redirect($this::ADMIN_CHARGE);
        } catch(Exception $e){
            return redirect('/admin/charge/'.$charge->id.'/edit')->with($this::ERROR, 'Save failed');
        }
    }

    
    public function destroy($id)
    {
        $charge = ExaminationCharge::find($id);
        $logService = new LogService();

        $oldData = $charge;
        if ($charge){
            try{
                $charge->delete();
                
                $logService->createLog('Delete Charge', $this::EXAMINATION_CHARGE, $oldData );

                Session::flash($this::MESSAGE, 'Charge successfully deleted');
                return redirect($this::ADMIN_CHARGE);
            }catch (Exception $e){
                Session::flash($this::ERROR, 'Delete failed');
                return redirect($this::ADMIN_CHARGE);
            }
        }
    }
    
    
	function cekNamaPerangkat($name)
    {
		$charge = ExaminationCharge::where($this::DEVICE_NAME,'=',''.$name.'')->get();
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

        $search = trim($request->input($this::SEARCH));
        
        if ($search != null){
            $examinationCharge = ExaminationCharge::whereNotNull($this::CREATED_AT)
                ->where($this::DEVICE_NAME,'like','%'.$search.'%')
                ->orWhere('stel','like','%'.$search.'%')
                ->orderBy($this::DEVICE_NAME);
        }else{
            $query = ExaminationCharge::whereNotNull($this::CREATED_AT);

            if ($request->has($this::CATEGORY) && $request->input($this::CATEGORY) != 'all' ){
                $query->where($this::CATEGORY, $request->get($this::CATEGORY));
            }

            if ($request->has($this::IS_ACTIVE) && $request->get($this::IS_ACTIVE) > -1 ){
                $query->where($this::IS_ACTIVE, $request->get($this::IS_ACTIVE));
            }

            $examinationCharge = $query->orderBy($this::DEVICE_NAME);
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

        // Create log
        $logService = new LogService;
        $logService->createLog('download_excel', 'Tarif Pengujian','');

        // Generate and return the spreadsheet
        Excel::create('Data Tarif Pengujian', function($excel) use ($examsArray) {
                $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                    $sheet->fromArray($examsArray, null, 'A1', false, false);
                });
            })->download('xlsx');
    }
}
