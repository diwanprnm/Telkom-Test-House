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
use App\ExaminationLab;

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
    private const REQUIRED = 'required';
    private const SEARCH = 'search';
    private const STEL = 'stel';
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
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $category = '';
        $status = -1;
        
        if ($search){
            $examinationCharge = ExaminationCharge::whereNotNull(self::CREATED_AT)
                ->where(self::DEVICE_NAME,'like','%'.$search.'%')
                ->orWhere('stel','like','%'.$search.'%')
                ->orderByRaw('category, device_name')
                ->paginate($paginate);

                $logService->createLog('Search Charge', self::EXAMINATION_CHARGE, json_encode(array(self::SEARCH=>$search)) );
        }else{
            $query = ExaminationCharge::whereNotNull(self::CREATED_AT);

            if ($request->has(self::CATEGORY)){
                $category = $request->get(self::CATEGORY);
                if($request->input(self::CATEGORY) != 'all'){
                    $query->where(self::CATEGORY, $request->get(self::CATEGORY));
                }
            }

            if ($request->has(self::IS_ACTIVE)){
                $status = $request->get(self::IS_ACTIVE);
                if ($request->get(self::IS_ACTIVE) > -1){
                    $query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
                }
            }

            $examinationCharge = $query->orderByRaw('category, device_name')
                                        ->paginate($paginate);
        }
        
        if (count($examinationCharge) == 0){
            $notFound = 'Data not found';
        }

        $examinationLabs = ExaminationLab::orderBy('lab_code', 'asc')->get();        
        
        return view('admin.charge.index')
            ->with('notFound', $notFound)
            ->with(self::MESSAGE, $message)
            ->with('data', $examinationCharge)
            ->with(self::SEARCH, $search)
            ->with(self::CATEGORY, $category)
            ->with('status', $status)
            ->with('labs',  $examinationLabs);
        
    }


    public function create()
    {
        $examinationLabs = ExaminationLab::orderBy('lab_code', 'asc')->get();

        return view('admin.charge.create')
            ->with('labs',  $examinationLabs)
        ;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            self::DEVICE_NAME => self::REQUIRED,
            self::STEL => self::REQUIRED,
            self::CATEGORY => self::REQUIRED,
            self::DURATION => self::REQUIRED,
            self::PRICE => self::REQUIRED,
            self::TA_PRICE => self::REQUIRED,
            self::VT_PRICE => self::REQUIRED,
            self::IS_ACTIVE => 'required|boolean',
        ]);

        $currentUser = Auth::user();
        $logService = new LogService();
			
		$name_exists = $this->cekNamaPerangkat($request->input(self::DEVICE_NAME));
		if($name_exists == 1){
			return redirect()->back()
                ->with('error_name', 1)
                ->withInput($request->all());
		}

        $charge = new ExaminationCharge;
        $charge->id = Uuid::uuid4();
        $charge->device_name = $request->input(self::DEVICE_NAME);
        $charge->stel = $request->input(self::STEL);
        $charge->category = $request->input(self::CATEGORY);
        $charge->duration = str_replace(",","",$request->input(self::DURATION));
        $charge->price = str_replace(",","",$request->input(self::PRICE));
        $charge->ta_price = str_replace(",","",$request->input(self::TA_PRICE));
        $charge->vt_price = str_replace(",","",$request->input(self::VT_PRICE));
        $charge->is_active = $request->input(self::IS_ACTIVE);
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logService->createLog('Create Charge', self::EXAMINATION_CHARGE, $charge );

            Session::flash(self::MESSAGE, 'Charge successfully created');
            return redirect(self::ADMIN_CHARGE);
        } catch(Exception $e){ return redirect('/admin/charge/create')->with(self::ERROR, 'Save failed');
        }
    }


    public function edit($id)
    {
        $charge = ExaminationCharge::find($id);
        $examinationLabs = ExaminationLab::orderBy('lab_code', 'asc')->get();

        return view('admin.charge.edit')
            ->with('data', $charge)
            ->with('labs',  $examinationLabs)
        ;
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            self::IS_ACTIVE => 'boolean',
        ]);

        $currentUser = Auth::user();
        $logService = new LogService();

        $charge = ExaminationCharge::find($id);
        $oldData = $charge;

        if ($request->has(self::DEVICE_NAME)){
            $charge->device_name = $request->input(self::DEVICE_NAME);
        }
        if ($request->has(self::STEL)){
            $charge->stel = $request->input(self::STEL);
        }
        if ($request->has(self::CATEGORY)){
            $charge->category = $request->input(self::CATEGORY);
        }
        if ($request->has(self::DURATION)){
			$charge->duration = str_replace(",","",$request->input(self::DURATION));
        }
        if ($request->has(self::PRICE)){
			$charge->price = str_replace(",","",$request->input(self::PRICE));
        }
        if ($request->has(self::TA_PRICE)){
			$charge->ta_price = str_replace(",","",$request->input(self::TA_PRICE));
        }
        if ($request->has(self::VT_PRICE)){
            $charge->vt_price = str_replace(",","",$request->input(self::VT_PRICE));
        }
        if ($request->has(self::IS_ACTIVE)){
            $charge->is_active = $request->input(self::IS_ACTIVE);
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logService->createLog('Update Charge', self::EXAMINATION_CHARGE, $oldData );

            Session::flash(self::MESSAGE, 'Charge successfully updated');
            return redirect(self::ADMIN_CHARGE);
        } catch(Exception $e){ return redirect('/admin/charge/'.$charge->id.'/edit')->with(self::ERROR, 'Save failed');
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
                
                $logService->createLog('Delete Charge', self::EXAMINATION_CHARGE, $oldData );

                Session::flash(self::MESSAGE, 'Charge successfully deleted');
                return redirect(self::ADMIN_CHARGE);
            }catch (Exception $e){ return redirect(self::ADMIN_CHARGE)->with(self::ERROR, 'Delete failed');
            }
        }
        Session::flash(self::ERROR, 'Charge not found');
        return redirect(self::ADMIN_CHARGE);
    }
    
    
	function cekNamaPerangkat($name)
    {
		$charge = ExaminationCharge::where(self::DEVICE_NAME,'=',''.$name.'')->get();
		return count($charge);
    }
    
    
	public function autocomplete($query) {
        return ExaminationCharge::select('device_name as autosuggest')
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(5)
                ->distinct()
                ->get();
    }


    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input(self::SEARCH));
        
        if ($search != null){
            $examinationCharge = ExaminationCharge::whereNotNull(self::CREATED_AT)
                ->where(self::DEVICE_NAME,'like','%'.$search.'%')
                ->orWhere('stel','like','%'.$search.'%')
                ->orderBy(self::DEVICE_NAME);
        }else{
            $query = ExaminationCharge::whereNotNull(self::CREATED_AT);

            if ($request->has(self::CATEGORY) && $request->input(self::CATEGORY) != 'all' ){
                $query->where(self::CATEGORY, $request->get(self::CATEGORY));
            }

            if ($request->has(self::IS_ACTIVE) && $request->get(self::IS_ACTIVE) > -1 ){
                $query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
            }

            $examinationCharge = $query->orderBy(self::DEVICE_NAME);
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
        $excel = \App\Services\ExcelService::download($examsArray, 'Tarif Pengujian');
        return response($excel['file'], 200, $excel['headers']);
    }
}
