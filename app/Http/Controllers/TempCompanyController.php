<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\TempCompany;
use App\Company;

use Auth;
use Session;
use File;
use Response;
use Storage;
// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class TempCompanyController extends Controller
{


    private const COMPANY = 'company';
    private const IS_COMMITED = 'is_commited';
    private const BEFORE_DATE = 'before_date';
    private const AFTER_DATE = 'after_date';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';
    private const MESSAGE = 'message';
    private const MEDIA_COMPANY = 'company/';
    private const MEDIA_TEMPCOMPANY = 'tempCompany/';
    private const ERROR = 'error';
    private const PAGE_EDIT = '/edit';
    private const PAGE_TEMPCOMPANY = '/admin/tempcompany';
    private const CONTENT_TYPE = 'Content-Type: application/octet-stream';

    private const MINIO = 'minio';
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
            $before_date = null;
            $after_date = null;

            $sort_by_filter = 'updated_at';
            $sort_type_filter = 'desc';
			
			$query = TempCompany::whereNotNull('created_at')
						->with('user')
                        ->with(self::COMPANY);
            
            if ($search != null){
				$query->where(function($qry) use($search){
                    $qry->whereHas(self::COMPANY, function ($q) use ($search){
							return $q->where('name', 'like', '%'.strtolower($search).'%');
						});
                });
            }
            if ($request->has(self::IS_COMMITED)){
                $status = $request->get(self::IS_COMMITED);
				if($request->input(self::IS_COMMITED) != 'all'){
					$query->where(self::IS_COMMITED, $request->get(self::IS_COMMITED));
				}
            }

            if ($request->has(self::BEFORE_DATE)){
                $query->where(DB::raw('DATE(created_at)'), '<=', $request->get(self::BEFORE_DATE));
                $before_date = $request->get(self::BEFORE_DATE);
            }

            if ($request->has(self::AFTER_DATE)){
                $query->where(DB::raw('DATE(created_at)'), '>=', $request->get(self::AFTER_DATE));
                $after_date = $request->get(self::AFTER_DATE);
            }

            if ($request->has(self::SORT_BY)){
                $sort_by_filter = $request->get(self::SORT_BY);
            }
            if ($request->has(self::SORT_TYPE)){
                $sort_type_filter = $request->get(self::SORT_TYPE);
            }

            $companies = $query->orderBy($sort_by_filter, $sort_type_filter)->paginate($paginate);
			
            if (count($companies) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.tempcompany.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $companies)
                ->with('search', $search)
                ->with('status', $status)
                ->with(self::BEFORE_DATE, $before_date)
                ->with(self::AFTER_DATE, $after_date)
                ->with(self::SORT_BY, $sort_by_filter)
                ->with(self::SORT_TYPE, $sort_type_filter);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = TempCompany::where('id', $id)
                            ->with(self::COMPANY)
                            ->with('user')
                            ->first();

        return view('admin.tempcompany.edit')
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
		$tempcompany = TempCompany::find($id);
		if(!empty($tempcompany)){
		    $company = Company::find($tempcompany->company_id);
            if ($request->has(self::IS_COMMITED)){
                $tempcompany->updated_by = $currentUser->id;
                $tempcompany->is_commited = $request->input(self::IS_COMMITED);
                $tempcompany->save();
            }
            
            if($request->input(self::IS_COMMITED) == 1){
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
                if ($request->has('npwp_file')) {
                    $npwp_file = public_path().self::MEDIA_COMPANY.$company->id.'/'.$company->npwp_file;
                    $npwp_file_temp = public_path().self::MEDIA_TEMPCOMPANY.$company->id.'/'.$id.'/'.$tempcompany->npwp_file;
                    $npwp_file_now = public_path().self::MEDIA_COMPANY.$company->id.'/'.$tempcompany->npwp_file;
                    
                if(copy($npwp_file_temp,$npwp_file_now)){
                        $company->npwp_file = $request->input('npwp_file');
                        if (File::exists($npwp_file)){File::delete($npwp_file);}
                    }else{
                        Session::flash(self::ERROR, 'Save NPWP to directory failed');
                        return redirect(self::PAGE_TEMPCOMPANY.'/'.$id.self::PAGE_EDIT);
                    }
                }        
                if ($request->has('siup_number')){
                    $company->siup_number = $request->input('siup_number');
                }
                if ($request->has('siup_date')){
                    $company->siup_date = $request->input('siup_date');
                }
                if ($request->has('siup_file')) {
                    $siup_file = public_path().self::MEDIA_COMPANY.$company->id.'/'.$company->siup_file;
                    $siup_file_temp = public_path().self::MEDIA_TEMPCOMPANY.$company->id.'/'.$id.'/'.$tempcompany->siup_file;
                    $siup_file_now = public_path().self::MEDIA_COMPANY.$company->id.'/'.$tempcompany->siup_file;
                    if(copy($siup_file_temp,$siup_file_now)){
                        $company->siup_file = $request->input('siup_file');
                        if (File::exists($siup_file)){File::delete($siup_file);}
                    }else{
                        Session::flash(self::ERROR, 'Save SIUPP to directory failed');
                        return redirect(self::PAGE_TEMPCOMPANY.'/'.$id.self::PAGE_EDIT);
                    }
                }   
                if ($request->has('qs_certificate_number')){
                    $company->qs_certificate_number = $request->input('qs_certificate_number');
                }
                if ($request->has('qs_certificate_date')){
                    $company->qs_certificate_date = $request->input('qs_certificate_date');
                }
                if ($request->has('qs_certificate_file')) {
                    $qs_certificate_file = public_path().self::MEDIA_COMPANY.$company->id.'/'.$company->qs_certificate_file;
                    $qs_certificate_file_temp = public_path().self::MEDIA_TEMPCOMPANY.$company->id.'/'.$id.'/'.$tempcompany->qs_certificate_file;
                    $qs_certificate_file_now = public_path().self::MEDIA_COMPANY.$company->id.'/'.$tempcompany->qs_certificate_file;
                    if(copy($qs_certificate_file_temp,$qs_certificate_file_now)){
                        $company->qs_certificate_file = $request->input('qs_certificate_file');
                        if (File::exists($qs_certificate_file)){File::delete($qs_certificate_file);}
                    }else{
                        Session::flash(self::ERROR, 'Save Certificate to directory failed');
                        return redirect(self::PAGE_TEMPCOMPANY.'/'.$id.self::PAGE_EDIT);
                    }
                }
                
                $company->updated_by = $currentUser->id;
                try{
                    $company->save();
                    Session::flash(self::MESSAGE, 'Company successfully updated');
                    return redirect(self::PAGE_TEMPCOMPANY);
                } catch(Exception $e){
                    Session::flash(self::ERROR, 'Save failed');
                    return redirect(self::PAGE_TEMPCOMPANY.'/'.$id.self::PAGE_EDIT);
                }
            }else{
                Session::flash(self::ERROR, 'Update was decline');
                return redirect(self::PAGE_TEMPCOMPANY);
            }
        }else{
            Session::flash(self::ERROR, 'TempCompany Not Found');
           return redirect(self::PAGE_TEMPCOMPANY);
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
        $company = TempCompany::find($id);

        if ($company){
            try{
                $company->delete();
		        $file = public_path().self::MEDIA_TEMPCOMPANY.$company->company_id.'/'.$id;
				File::deleteDirectory($file);
                
                Session::flash(self::MESSAGE, 'Edit Request successfully deleted');
                return redirect(self::PAGE_TEMPCOMPANY);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::PAGE_TEMPCOMPANY);
            }
        }else{
            Session::flash(self::MESSAGE, 'Company Not Found');
            return redirect(self::PAGE_TEMPCOMPANY);
        }
    }
	
	public function viewMedia($id, $name)
    {
        $company = TempCompany::find($id);

        if ($company){
            switch ($name) { 
                case 'npwp':  
                    $file = Storage::disk(self::MINIO)->url(self::MEDIA_TEMPCOMPANY.$id."/".$company->npwp_file);
                     
                    $filename = $company->npwp_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    $response =  response()->download($tempImage, $filename);
                    break;

                case 'siup':  
                    $file = Storage::disk(self::MINIO)->url(self::MEDIA_TEMPCOMPANY.$id."/".$company->siup_file);
                     
                    $filename = $company->siup_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    $response =  response()->download($tempImage, $filename);
                    break;

                case 'qs': 

                    $file = Storage::disk(self::MINIO)->url(self::MEDIA_TEMPCOMPANY.$id."/".$company->qs_certificate_file);
                     
                    $filename = $company->qs_certificate_file;
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($file, $tempImage);

                    $response =  response()->download($tempImage, $filename);
                    break;
                 default:
                    return false;
            }

            return $response;
        }
    }
	
	public function autocomplete($query) {
        return TempCompany::join('companies', 'temp_company.company_id', '=', 'companies.id')
                ->select('companies.name as autosuggest')
				->where('companies.name', 'like','%'.$query.'%')
				->orderBy('companies.name')
                ->take(5)
				->distinct()
                ->get(); 
    }
}
