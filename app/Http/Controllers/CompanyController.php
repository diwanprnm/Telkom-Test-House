<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Company;
use App\Logs;

use Auth;
use Session;
use File;
use Response;
use Excel;

use Image;
use Storage;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Services\Logs\LogService;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    private const IS_ACTIVE = 'is_active';
    private const COMPANY = 'COMPANY';
    private const CREATED_AT = 'created_at';
    private const BEFORE_DATE = 'before_date';
    private const AFTER_DATE = 'after_date';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';
    private const MESSAGE = 'message';
    private const ADDRESS = 'address';
    private const PLG_ID = 'plg_id';
    private const EMAIL = 'email';
    private const POSTAL_CODE = 'postal_code';
    private const PHONE_NUMBER = 'phone_number';
    private const NPWP_NUMBER = 'npwp_number';
    private const SIUP_NUMBER = 'siup_number';
    private const ERROR = 'error';
    private const FORMAT_NOT_AVAILABLE = 'Format Not Available';
    private const ADMIN_CREATE = '/admin/company/create';
    private const ADMIN_COMPANY = '/admin/company';
    private const SIUP_FILE = 'siup_file';
    private const QS_CERTIFICATE_FILE = 'qs_certificate_file';
    private const QS_CERTIFICATE_DATE = 'qs_certificate_date';
    private const QS_CERTIFICATE_NUMBER = 'qs_certificate_number';
    private const NPWP_FILE = 'npwp_file';
    private const MINIO = 'minio';
    private const SEARCH = 'search';
    private const SIUP_DATE = 'siup_date';
    private const COMPANY_PATH = 'company/';

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
            $search = trim($request->input(self::SEARCH));
            $status = '';
            $before = null;
            $after = null;

            $sort_by = 'name';
            $sort_type = 'asc';
            $query = Company::whereNotNull(self::CREATED_AT)
                    ->where('id', '<>', '1');
            if ($search != null){
               
                    $query->where('name','like','%'.$search.'%');
				
                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Company";
                    $logs->data = json_encode(array(self::SEARCH=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->updated_by = $currentUser->id;
                    $logs->page = self::COMPANY;
                    $logs->save();
            } 

            if ($request->has(self::IS_ACTIVE)){
                $status = $request->get(self::IS_ACTIVE);
                if($request->input(self::IS_ACTIVE) != '0'){
                    $query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
                }
            }

            if ($request->has(self::BEFORE_DATE)){
                $query->where(DB::raw('DATE(created_at)'), '<=', $request->get(self::BEFORE_DATE));
                $before = $request->get(self::BEFORE_DATE);
            }

            if ($request->has(self::AFTER_DATE)){
                $query->where(DB::raw('DATE(created_at)'), '>=', $request->get(self::AFTER_DATE));
                $after = $request->get(self::AFTER_DATE);
            }

            if ($request->has(self::SORT_BY)){
                $sort_by = $request->get(self::SORT_BY);
            }
            if ($request->has(self::SORT_TYPE)){
                $sort_type = $request->get(self::SORT_TYPE);
            }
            
            $companies = $query->orderBy($sort_by, $sort_type)
                        ->paginate($paginate);
             
			$data_excel = Company::whereNotNull(self::CREATED_AT)->where('id', '<>', '1')->orderBy('updated_at', 'desc')->get();
			$request->session()->put('excel_pengujian', $data_excel);
			
            if (count($companies) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.company.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $companies)
                ->with(self::SEARCH, $search)
                ->with('status', $status)
                ->with(self::BEFORE_DATE, $before)
                ->with(self::AFTER_DATE, $after)
                ->with(self::SORT_BY, $sort_by)
                ->with(self::SORT_TYPE, $sort_type);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company.create');
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
        
        $company = new Company;
        $company->id = Uuid::uuid4();
        $company->name = $request->input('name');
        $company->address = $request->input(self::ADDRESS);
        $company->plg_id = $request->input(self::PLG_ID);
        $company->nib = $request->input('nib');
        $company->city = $request->input('city');
        $company->email = $request->input(self::EMAIL);
        $company->postal_code = $request->input(self::POSTAL_CODE);
        $company->phone_number = $request->input(self::PHONE_NUMBER);
        $company->fax = $request->input('fax');
        $company->npwp_number = $request->input(self::NPWP_NUMBER);
        $company->siup_number = $request->input(self::SIUP_NUMBER);
        $company->siup_date = $request->input(self::SIUP_DATE);
        $company->qs_certificate_number = $request->input(self::QS_CERTIFICATE_NUMBER);
        $company->keterangan = $request->input('keterangan');
 
        $this->uploadFile($request,$company);

        
        $company->qs_certificate_date = $request->input(self::QS_CERTIFICATE_DATE);
        $company->is_active = $request->input(self::IS_ACTIVE);
        $company->created_by = $currentUser->id;
        $company->updated_by = $currentUser->id;

        try{
            $company->save(); 

            $logService = new LogService();  
            $logService->createLog('Create Company',self::COMPANY);

            Session::flash(self::MESSAGE, 'Company successfully created');
            return redirect(self::ADMIN_COMPANY);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::ADMIN_CREATE);
        }
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);

        return view('admin.company.edit')
            ->with('data', $company);
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

        $company = Company::find($id); 

        if ($request->has('name')){
            $company->name = $request->input('name');
        }
        if ($request->has(self::ADDRESS)){
            $company->address = $request->input(self::ADDRESS);
        }
        if ($request->has(self::PLG_ID)){
            $company->plg_id = $request->input(self::PLG_ID);
        }
        if ($request->has('nib')){
            $company->nib = $request->input('nib');
        }
        if ($request->has('city')){
            $company->city = $request->input('city');
        }
        if ($request->has(self::EMAIL)){
            $company->email = $request->input(self::EMAIL);
        }
        if ($request->has(self::POSTAL_CODE)){
            $company->postal_code = $request->input(self::POSTAL_CODE);
        }
        if ($request->has(self::PHONE_NUMBER)){
            $company->phone_number = $request->input(self::PHONE_NUMBER);
        }
        if ($request->has('fax')){
            $company->fax = $request->input('fax');
        }
        if ($request->has(self::NPWP_NUMBER)){
            $company->npwp_number = $request->input(self::NPWP_NUMBER);
        }
        

        $this->uploadFile($request,$company);

        if ($request->has(self::SIUP_NUMBER)){
            $company->siup_number = $request->input(self::SIUP_NUMBER);
        }
        if ($request->has(self::SIUP_DATE)){
            $company->siup_date = $request->input(self::SIUP_DATE);
        }
        if ($request->has(self::QS_CERTIFICATE_NUMBER)){
            $company->qs_certificate_number = $request->input(self::QS_CERTIFICATE_NUMBER);
        }
        if ($request->has(self::QS_CERTIFICATE_DATE)){
            $company->qs_certificate_date = $request->input(self::QS_CERTIFICATE_DATE);
        }
        if ($request->has(self::QS_CERTIFICATE_DATE)){
            $company->keterangan = $request->input('keterangan');
        }
        if ($request->has(self::IS_ACTIVE)){
            $company->is_active = $request->input(self::IS_ACTIVE);
        }

        $company->updated_by = $currentUser->id;

        try{
            $company->save(); 

            $logService = new LogService();  
            $logService->createLog('Update Company',self::COMPANY);

            Session::flash(self::MESSAGE, 'Company successfully updated');
            return redirect(self::ADMIN_COMPANY);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::ADMIN_COMPANY.$company->id.'/edit');
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
        $company = Company::find($id);  
        if ($company){
            try{
                $company->delete(); 

                $logService = new LogService();  
                $logService->createLog('Delete Company',self::COMPANY);

                Session::flash(self::MESSAGE, 'Company successfully deleted');
                return redirect(self::ADMIN_COMPANY);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_COMPANY);
            }
        }
    }

    public function viewMedia($id, $name)
    {
        $company = Company::find($id);
        $response = false;
        if ($company){
            switch ($name) {
                case 'npwp': 
                    $file = Storage::disk(self::MINIO)->url(self::COMPANY_PATH.$id."/".$company->npwp_file);
                     
                    $filename = $company->npwp_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    $response =  response()->download($tempImage, $filename);
                    break;

                case 'siup':  
                    $file = Storage::disk(self::MINIO)->url(self::COMPANY_PATH.$id."/".$company->siup_file);
                     
                    $filename = $company->npwp_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    $response =  response()->download($tempImage, $filename);
                    break;

                case 'qs': 

                    $file = Storage::disk(self::MINIO)->url(self::COMPANY_PATH.$id."/".$company->qs_certificate_file);
                     
                    $filename = $company->qs_certificate_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    $response =  response()->download($tempImage, $filename);
                    break;
                default:
                    return false; 
            }
        }

        return $response;
    }
	
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		
		$data = $request->session()->get('excel_pengujian');
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'No',
			'Nama',
			'Alamat',
			'Email',
			'Telepon',
			'Faksimil',
			'NPWP Perusahaan',
			'SIUPP Perusahaan',
			'Tgl. SIUPP Perusahaan',
			'Sertifikat Perusahaan',
			'Tgl. Sertifikat Perusahaan'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
			$no = 1;
		foreach ($data as $row) {
			if($row->siup_date==''){
				$siup_date = '';
			}else{
				$siup_date = date("d-m-Y", strtotime($row->siup_date));
			}
			if($row->qs_certificate_date==''){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date("d-m-Y", strtotime($row->qs_certificate_date));
			}
			$examsArray[] = [
				$no,
				$row->name,
				$row->address.", kota ".$row->city.", kode pos ".$row->postal_code,
				$row->email,
				$row->phone_number,
				$row->fax,
				$row->npwp_number,
				$row->siup_number,
				$siup_date,
				$row->qs_certificate_number,
				$qs_certificate_date
			];
			$no++;
		}
		// Generate and return the spreadsheet
		Excel::create('Data Perusahaan', function($excel) use ($examsArray) {
 
			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx');
	}
	
	public function importExcel(Request $request)
	{
		$currentUser = Auth::user();
		
		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();
			$data = Excel::load($path)->get();
			$datenow = date('Y-m-d H:i:s');
			if(!empty($data) && $data->count()){
				foreach ($data->toArray() as $value) {        
					$insert[] = [
						'id' => Uuid::uuid4(),
						'name' => $value['nama'],
						self::address => $value['alamat'],
						'city' => $value['kota'],
						self::EMAIL => $value[self::EMAIL],
						self::POSTAL_CODE => $value['kode_pos'],
						self::PHONE_NUMBER => $value['no_telp'],
						'fax' => $value['no_fax'],
						self::NPWP_NUMBER => $value['no_npwp'],
						self::SIUP_NUMBER => $value['no_siupp'],
						self::SIUP_DATE => $value['tgl_siupp'],
						self::QS_CERTIFICATE_NUMBER => $value['no_sertifikat'],
						self::QS_CERTIFICATE_DATE => $value['tgl_sertifikat'],
						self::IS_ACTIVE => 0,
						'created_by' => $currentUser->id,
						'updated_by' => $currentUser->id,
						self::CREATED_AT => $datenow,
						'updated_at' => $datenow
					]; 
				}
				if(!empty($insert)){
					Company::insert($insert);
                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Import Company";
                    $logs->data = "";
                    $logs->created_by = $currentUser->id;
                    $logs->page = self::COMPANY;
                    $logs->save();
						return back()->with(self::MESSAGE,'Insert Record successfully.'); 
				}
			}
		}
		return back()->with(self::ERROR,'Please Check your file, Something is wrong there.');
	}
	
	public function autocomplete($query) {
        return Company::select('name as autosuggest')
            ->where('name', 'like','%'.$query.'%')
            ->orderBy('name')
            ->take(5)
            ->distinct()
            ->get(); 
    }

    private function uploadFile($request,$company){
        $allowedImage = ['jpeg','jpg','png'];
        $allowedFile = ['pdf'];

        if ($request->hasFile(self::NPWP_FILE)) { 
            $file = $request->file(self::NPWP_FILE);
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'npwp_'.$request->file(self::NPWP_FILE)->getClientOriginalName();
            
            $is_upload_npwp = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_upload_npwp = Storage::disk(self::MINIO)->put(self::COMPANY_PATH.$company->id."/$file_name",$file->__toString());
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_upload_npwp = Storage::disk(self::MINIO)->put(self::COMPANY_PATH.$company->id."/$file_name",(string)$image->encode()); 
            }else{
                Session::flash(self::ERROR, self::FORMAT_NOT_AVAILABLE);
                return redirect(self::ADMIN_CREATE);
            } 
             
            if($is_upload_npwp){
                $company->npwp_file = $file_name;
            }else{
                Session::flash(self::ERROR, 'Save NPWP to directory failed');
                return redirect(self::ADMIN_CREATE);
            }
        }        
        if ($request->hasFile(self::SIUP_FILE)) {  

            $file = $request->file(self::SIUP_FILE);
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'siupp_'.$request->file(self::SIUP_FILE)->getClientOriginalName();
            
            $is_uploaded_siup = false;
            if (in_array($ext, $allowedFile))
            {  
                $is_uploaded_siup = Storage::disk(self::MINIO)->put(self::COMPANY_PATH.$company->id."/$file_name",$file->__toString());
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded_siup = Storage::disk(self::MINIO)->put(self::COMPANY_PATH.$company->id."/".$file_name,(string)$image->encode()); 
            }else{
                Session::flash(self::ERROR, self::FORMAT_NOT_AVAILABLE);
                return redirect(self::ADMIN_CREATE);
            } 
             
            if($is_uploaded_siup){
                $company->siup_file = $file_name;
            }else{
                Session::flash(self::ERROR, 'Save SIUP to directory failed');
                return redirect(self::ADMIN_CREATE);
            }
        }
        if ($request->hasFile(self::QS_CERTIFICATE_FILE)) {  
            $file = $request->file(self::QS_CERTIFICATE_FILE);
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'serti_uji_mutu_'.$request->file(self::QS_CERTIFICATE_FILE)->getClientOriginalName();
            
            $is_upload_qs = false;
            if (in_array($ext, $allowedFile))
            {  
                $is_upload_qs = Storage::disk(self::MINIO)->put(self::COMPANY_PATH.$company->id."/$file_name",$file->__toString());
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_upload_qs = Storage::disk(self::MINIO)->put(self::COMPANY_PATH.$company->id."/".$file_name,(string)$image->encode()); 
            }else{
                Session::flash(self::ERROR, self::FORMAT_NOT_AVAILABLE);
                return redirect(self::ADMIN_CREATE);
            } 
             
            if($is_upload_qs){
                $company->qs_certificate_file = $file_name;
            }else{
                Session::flash(self::ERROR, 'Save QS certificate to directory failed');
                return redirect(self::ADMIN_CREATE);
            }
        }
    }
}
