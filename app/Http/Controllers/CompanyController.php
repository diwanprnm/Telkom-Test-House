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

class CompanyController extends Controller
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
            $status = '';
            $before = null;
            $after = null;

            $sort_by = 'name';
            $sort_type = 'asc';
            
            if ($search != null){
                $query = Company::whereNotNull('created_at')
					->where('id', '<>', '1')
                    ->where('name','like','%'.$search.'%');
				
                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Company";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = "COMPANY";
                    $logs->save();
            }else{
                $query = Company::whereNotNull('created_at')->where('id', '<>', '1');

                if ($request->has('is_active')){
                    $status = $request->get('is_active');
					if($request->input('is_active') != '0'){
						$query->where('is_active', $request->get('is_active'));
					}
                }
            }

            if ($request->has('before_date')){
                $query->where(DB::raw('DATE(created_at)'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $query->where(DB::raw('DATE(created_at)'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            if ($request->has('sort_by')){
            $sort_by = $request->get('sort_by');
            }
            if ($request->has('sort_type')){
                $sort_type = $request->get('sort_type');
            }
            
            $companies = $query->orderBy($sort_by, $sort_type)
                        ->paginate($paginate);
            
			// $data_excel = $query->orderBy('updated_at', 'desc')->get();
			$data_excel = Company::whereNotNull('created_at')->where('id', '<>', '1')->orderBy('updated_at', 'desc')->get();
			$request->session()->put('excel_pengujian', $data_excel);
			
            if (count($companies) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.company.index')
                ->with('message', $message)
                ->with('data', $companies)
                ->with('search', $search)
                ->with('status', $status)
                ->with('before_date', $before)
                ->with('after_date', $after)
                ->with('sort_by', $sort_by)
                ->with('sort_type', $sort_type);
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
        $company->address = $request->input('address');
        $company->plg_id = $request->input('plg_id');
        $company->nib = $request->input('nib');
        $company->city = $request->input('city');
        $company->email = $request->input('email');
        $company->postal_code = $request->input('postal_code');
        $company->phone_number = $request->input('phone_number');
        $company->fax = $request->input('fax');
        $company->npwp_number = $request->input('npwp_number');
        $company->siup_number = $request->input('siup_number');
        $company->siup_date = $request->input('siup_date');
        $company->qs_certificate_number = $request->input('qs_certificate_number');
        $company->keterangan = $request->input('keterangan');
 
        $allowedImage = ['jpeg','jpg','png'];
        $allowedFile = ['pdf'];
        if ($request->hasFile('npwp_file')) { 
            $file = $request->file('npwp_file');
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'npwp_'.$request->file('npwp_file')->getClientOriginalName();
            
            $is_uploaded = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_uploaded = Storage::disk('minio')->putFileAs("company/",$file,$file_name);
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("company/$file_name",(string)$image->encode()); 
            }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/company/create');
            } 
             
            if($is_uploaded){
                $company->npwp_file = $file_name;
            }else{
                Session::flash('error', 'Save NPWP to directory failed');
                return redirect('/admin/company/create');
            }
        }        
        if ($request->hasFile('siup_file')) {  

            $file = $request->file('siup_file');
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'siupp_'.$request->file('siup_file')->getClientOriginalName();
            
            $is_uploaded = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_uploaded = Storage::disk('minio')->putFileAs("company/",$file,$file_name);
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("company/$file_name",(string)$image->encode()); 
            }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/company/create');
            } 
             
            if($is_uploaded){
                $company->siup_file = $file_name;
            }else{
                Session::flash('error', 'Save SIUP to directory failed');
                return redirect('/admin/company/create');
            }
        }
        if ($request->hasFile('qs_certificate_file')) {  
            $file = $request->file('qs_certificate_file');
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'serti_uji_mutu_'.$request->file('qs_certificate_file')->getClientOriginalName();
            
            $is_uploaded = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_uploaded = Storage::disk('minio')->putFileAs("company/",$file,$file_name);
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("company/$file_name",(string)$image->encode()); 
            }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/company/create');
            } 
             
            if($is_uploaded){
                $company->qs_certificate_file = $file_name;
            }else{
                Session::flash('error', 'Save QS certificate to directory failed');
                return redirect('/admin/company/create');
            }
        }
        
        $company->qs_certificate_date = $request->input('qs_certificate_date');
        $company->is_active = $request->input('is_active');
        $company->created_by = $currentUser->id;
        $company->updated_by = $currentUser->id;

        try{
            $company->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Company";
            $logs->data = $company;
            $logs->created_by = $currentUser->id;
            $logs->page = "COMPANY";
            $logs->save();

            Session::flash('message', 'Company successfully created');
            return redirect('/admin/company');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/company/create');
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
        $company = Company::find($id);

        return view('admin.company.detail')
            ->with('data', $company);
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
        $oldData = $company;

        if ($request->has('name')){
            $company->name = $request->input('name');
        }
        if ($request->has('address')){
            $company->address = $request->input('address');
        }
        if ($request->has('plg_id')){
            $company->plg_id = $request->input('plg_id');
        }
        if ($request->has('nib')){
            $company->nib = $request->input('nib');
        }
        if ($request->has('city')){
            $company->city = $request->input('city');
        }
        if ($request->has('email')){
            $company->email = $request->input('email');
        }
        if ($request->has('postal_code')){
            $company->postal_code = $request->input('postal_code');
        }
        if ($request->has('phone_number')){
            $company->phone_number = $request->input('phone_number');
        }
        if ($request->has('fax')){
            $company->fax = $request->input('fax');
        }
        if ($request->has('npwp_number')){
            $company->npwp_number = $request->input('npwp_number');
        }
        $allowedImage = ['jpeg','jpg','png'];
        $allowedFile = ['pdf'];
        if ($request->hasFile('npwp_file')) { 
            $file = $request->file('npwp_file');
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'npwp_'.$request->file('npwp_file')->getClientOriginalName();
            
            $is_uploaded = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_uploaded = Storage::disk('minio')->putFileAs("company/",$file,$file_name);
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("company/$file_name",(string)$image->encode()); 
            }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/company/create');
            } 
             
            if($is_uploaded){
                $company->npwp_file = $file_name;
            }else{
                Session::flash('error', 'Save NPWP to directory failed');
                return redirect('/admin/company/create');
            }
        }        
        if ($request->hasFile('siup_file')) {  

            $file = $request->file('siup_file');
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'siupp_'.$request->file('siup_file')->getClientOriginalName();
            
            $is_uploaded = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_uploaded = Storage::disk('minio')->putFileAs("company/",$file,$file_name);
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("company/$file_name",(string)$image->encode()); 
            }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/company/create');
            } 
             
            if($is_uploaded){
                $company->siup_file = $file_name;
            }else{
                Session::flash('error', 'Save SIUP to directory failed');
                return redirect('/admin/company/create');
            }
        }
        if ($request->hasFile('qs_certificate_file')) {  
            $file = $request->file('qs_certificate_file');
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'serti_uji_mutu_'.$request->file('qs_certificate_file')->getClientOriginalName();
            
            $is_uploaded = false;
            if (in_array($ext, $allowedFile))
            { 
                $is_uploaded = Storage::disk('minio')->putFileAs("company/",$file,$file_name);
            }
            else if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("company/$file_name",(string)$image->encode()); 
            }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/company/create');
            } 
             
            if($is_uploaded){
                $company->qs_certificate_file = $file_name;
            }else{
                Session::flash('error', 'Save QS certificate to directory failed');
                return redirect('/admin/company/create');
            }
        }
        if ($request->has('siup_number')){
            $company->siup_number = $request->input('siup_number');
        }
        if ($request->has('siup_date')){
            $company->siup_date = $request->input('siup_date');
        }
        if ($request->has('qs_certificate_number')){
            $company->qs_certificate_number = $request->input('qs_certificate_number');
        }
        if ($request->has('qs_certificate_date')){
            $company->qs_certificate_date = $request->input('qs_certificate_date');
        }
        if ($request->has('qs_certificate_date')){
            $company->keterangan = $request->input('keterangan');
        }
        if ($request->has('is_active')){
            $company->is_active = $request->input('is_active');
        }

        $company->updated_by = $currentUser->id;

        try{
            $company->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Company";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "COMPANY";
            $logs->save();

            Session::flash('message', 'Company successfully updated');
            return redirect('/admin/company');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/company/'.$company->id.'/edit');
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
        $oldData = $company;
        $currentUser = Auth::user();
        if ($company){
            try{
                $company->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Company";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "COMPANY";
                $logs->save();

                Session::flash('message', 'Company successfully deleted');
                return redirect('/admin/company');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/company');
            }
        }
    }

    public function viewMedia($id, $name)
    {
        $company = Company::find($id);

        if ($company){
            switch ($name) {
                case 'npwp': 
                    $file = Storage::disk('minio')->url('company/'.$company->npwp_file);
                     
                    $filename = $company->npwp_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    return response()->download($tempImage, $filename);
                    break;

                case 'siup':  
                    $file = Storage::disk('minio')->url('company/'.$company->siup_file);
                     
                    $filename = $company->npwp_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    return response()->download($tempImage, $filename);
                    break;

                case 'qs': 

                    $file = Storage::disk('minio')->url('company/'.$company->qs_certificate_file);
                     
                    $filename = $company->qs_certificate_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    return response()->download($tempImage, $filename);
                    break;
            }
        }
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
	
	public function importExcel(Request $request)
	{
		$currentUser = Auth::user();
		
		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			$datenow = date('Y-m-d H:i:s');
			if(!empty($data) && $data->count()){
				foreach ($data->toArray() as $key => $value) {
					// if(!empty($value)){
						// foreach ($value as $v) {        
							$insert[] = [
								'id' => Uuid::uuid4(),
								'name' => $value['nama'],
								'address' => $value['alamat'],
								'city' => $value['kota'],
								'email' => $value['email'],
								'postal_code' => $value['kode_pos'],
								'phone_number' => $value['no_telp'],
								'fax' => $value['no_fax'],
								'npwp_number' => $value['no_npwp'],
								'siup_number' => $value['no_siupp'],
								'siup_date' => $value['tgl_siupp'],
								'qs_certificate_number' => $value['no_sertifikat'],
								'qs_certificate_date' => $value['tgl_sertifikat'],
								'is_active' => 0,
								'created_by' => $currentUser->id,
								'updated_by' => $currentUser->id,
								'created_at' => $datenow,
								'updated_at' => $datenow
							];
						// }
					// }
				}
				if(!empty($insert)){
					Company::insert($insert);
                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Import Company";
                    $logs->data = "";
                    $logs->created_by = $currentUser->id;
                    $logs->page = "COMPANY";
                    $logs->save();
						return back()->with('message','Insert Record successfully.');
						// Session::flash('message', 'Company successfully created');
						// try{
							// $company->save();
							// Session::flash('message', 'Company successfully created');
							// return redirect('/admin/company');
						// } catch(Exception $e){
							// Session::flash('error', 'Save failed');
							// return redirect('/admin/company/create');
						// }
				}
			}
		}
		return back()->with('error','Please Check your file, Something is wrong there.');
	}
	
	public function autocomplete($query) {
        $respons_result = Company::autocomplet($query);
        return response($respons_result);
    }
}
