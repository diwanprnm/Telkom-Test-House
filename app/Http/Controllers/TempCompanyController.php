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

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class TempCompanyController extends Controller
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

            $sort_by = 'updated_at';
            $sort_type = 'desc';
			
			$query = TempCompany::whereNotNull('created_at')
						->with('user')
                        ->with('company');
            
            if ($search != null){
				$query->where(function($qry) use($search){
                    $qry->whereHas('company', function ($q) use ($search){
							return $q->where('name', 'like', '%'.strtolower($search).'%');
						})
						// ->orWhereHas('user', function ($q) use ($search){
							// return $q->where('name', 'like', '%'.strtolower($search).'%');
						// })
						;
                });
            }
            if ($request->has('is_commited')){
                $status = $request->get('is_commited');
				if($request->input('is_commited') != 'all'){
					$query->where('is_commited', $request->get('is_commited'));
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

                $companies = $query->orderBy($sort_by, $sort_type)->paginate($paginate);
			
            if (count($companies) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.tempcompany.index')
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
        $company = TempCompany::find($id)->with('user')
                        ->with('company');
		$company = TempCompany::where('id', $id)
                            ->with('company')
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
		$company = Company::find($tempcompany->company_id);
		
		if ($request->has('is_commited')){
			$tempcompany->updated_by = $currentUser->id;
            $tempcompany->is_commited = $request->input('is_commited');
			$tempcompany->save();
        }
		
		if($request->input('is_commited') == 1){
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
				$npwp_file = public_path().'/media/company/'.$company->id.'/'.$company->npwp_file;
				$npwp_file_temp = public_path().'/media/tempCompany/'.$company->id.'/'.$id.'/'.$tempcompany->npwp_file;
				$npwp_file_now = public_path().'/media/company/'.$company->id.'/'.$tempcompany->npwp_file;
				
if(copy($npwp_file_temp,$npwp_file_now)){
					$company->npwp_file = $request->input('npwp_file');
					if (File::exists($npwp_file)){File::delete($npwp_file);}
				}else{
					Session::flash('error', 'Save NPWP to directory failed');
					return redirect('/admin/tempcompany/'.$id.'/edit');
				}
			}        
			if ($request->has('siup_number')){
				$company->siup_number = $request->input('siup_number');
			}
			if ($request->has('siup_date')){
				$company->siup_date = $request->input('siup_date');
			}
			if ($request->has('siup_file')) {
				$siup_file = public_path().'/media/company/'.$company->id.'/'.$company->siup_file;
				$siup_file_temp = public_path().'/media/tempCompany/'.$company->id.'/'.$id.'/'.$tempcompany->siup_file;
				$siup_file_now = public_path().'/media/company/'.$company->id.'/'.$tempcompany->siup_file;
				if(copy($siup_file_temp,$siup_file_now)){
					$company->siup_file = $request->input('siup_file');
					if (File::exists($siup_file)){File::delete($siup_file);}
				}else{
					Session::flash('error', 'Save SIUPP to directory failed');
					return redirect('/admin/tempcompany/'.$id.'/edit');
				}
			}   
			if ($request->has('qs_certificate_number')){
				$company->qs_certificate_number = $request->input('qs_certificate_number');
			}
			if ($request->has('qs_certificate_date')){
				$company->qs_certificate_date = $request->input('qs_certificate_date');
			}
			if ($request->has('qs_certificate_file')) {
				$qs_certificate_file = public_path().'/media/company/'.$company->id.'/'.$company->qs_certificate_file;
				$qs_certificate_file_temp = public_path().'/media/tempCompany/'.$company->id.'/'.$id.'/'.$tempcompany->qs_certificate_file;
				$qs_certificate_file_now = public_path().'/media/company/'.$company->id.'/'.$tempcompany->qs_certificate_file;
				if(copy($qs_certificate_file_temp,$qs_certificate_file_now)){
					$company->qs_certificate_file = $request->input('qs_certificate_file');
					if (File::exists($qs_certificate_file)){File::delete($qs_certificate_file);}
				}else{
					Session::flash('error', 'Save Certificate to directory failed');
					return redirect('/admin/tempcompany/'.$id.'/edit');
				}
			}
			
			$company->updated_by = $currentUser->id;
			try{
				$company->save();
				Session::flash('message', 'Company successfully updated');
				return redirect('/admin/tempcompany');
			} catch(Exception $e){
				Session::flash('error', 'Save failed');
				return redirect('/admin/tempcompany/'.$id.'/edit');
			}
		}else{
			Session::flash('error', 'Update was decline');
			return redirect('/admin/tempcompany');
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
		$file = public_path().'/media/tempCompany/'.$company->company_id.'/'.$id;

        if ($company){
            try{
                $company->delete();
				File::deleteDirectory($file);
                
                Session::flash('message', 'Edit Request successfully deleted');
                return redirect('/admin/tempcompany');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/tempcompany');
            }
        }
    }
	
	public function viewMedia($id, $name)
    {
        $company = TempCompany::find($id);

        if ($company){
            switch ($name) {
                case 'npwp':
                    $file = public_path().'/media/tempCompany/'.$company->company_id.'/'.$company->id.'/'.$company->npwp_file;
                    $headers = array(
                      'Content-Type: application/octet-stream',
                    );

                    return Response::file($file, $headers);
                    break;

                case 'siup':
                    $file = public_path().'/media/tempCompany/'.$company->company_id.'/'.$company->id.'/'.$company->siup_file;
                    $headers = array(
                      'Content-Type: application/octet-stream',
                    );

                    return Response::file($file, $headers);
                    break;

                case 'qs':
                    $file = public_path().'/media/tempCompany/'.$company->company_id.'/'.$company->id.'/'.$company->qs_certificate_file;
                    $headers = array(
                      'Content-Type: application/octet-stream',
                    );

                    return Response::file($file, $headers);
                    break;
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = TempCompany::autocomplet($query);
        return response($respons_result);
    }
}
