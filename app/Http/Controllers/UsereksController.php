<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Company;
use App\Role;
use App\User;
use App\Logs;
use App\Menu;
use App\UsersMenus;

use Auth;
use Session;
use Hash;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;


use App\Services\Logs\LogService;
class UsereksController extends Controller
{

    private const SEARCH = 'search';
    private const USER_EKSTERNAL = 'USER EKSTERNAL';
    private const COMPANY = 'company';
    private const ROLE_ID = 'role_id';
    private const ADMIN_USEREKS = '/admin/usereks';
    private const ADMIN_USEREKS_CREATE = '/admin/usereks/create';
    private const IS_ACTIVE = 'is_active';
    private const MESSAGE = 'message'; 
    private const ERROR = 'error'; 
    private const COMPANY_ID = 'company_id';
    private const EMAIL = 'email';
    private const PASS_TEXT = 'password';
    private const ADDRESS = 'address';
    private const PHONE_NUMBER = 'phone_number';
    private const PICTURE = 'picture';
    private const PATH_PROFILE = 'profile_';
    private const MEDIA_USER = '/media/user/';
    private const FAILED_USER_MSG = 'Save Profile Picture to directory failed';
    private const FAILED_LOG_MSG = 'Save failed';
    private const NEW_TEXT = 'new';
    private const PRICE = 'price';
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
            $search = trim($request->input(self::SEARCH));
			$filterCompanyUser = '';
            $status = -1;

            $companies = Company::where('id','!=', 1)->get();
			$roles = Role::where('id', 2);
            
            if ($search != null){
                $usersEks = User::whereNotNull('created_at')
                    ->with('role')
                    ->with(self::COMPANY)
                    ->where('name','like','%'.$search.'%')
					->where(self::ROLE_ID, '=', '2')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logService = new LogService();
                    $logService->createLog('Search User', self::USER_EKSTERNAL, json_encode(array("search"=>$search))); 
                    
            }else{
                $query = User::whereNotNull('created_at')
					->where(self::ROLE_ID, '=', '2')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0')
                    ->with('role')
                    ->with(self::COMPANY);
					
				if ($request->has(self::COMPANY)){
                    $filterCompanyUser = $request->get(self::COMPANY);
					if($request->input(self::COMPANY) != 'all'){
						$query->whereHas(self::COMPANY, function ($q) use ($request){
							return $q->where('name', 'like', '%'.$request->get(self::COMPANY).'%');
						});
					}
                }

                if ($request->has(self::IS_ACTIVE)){
					$status = $request->get(self::IS_ACTIVE);
                    if ($request->get(self::IS_ACTIVE) > -1){
                        $query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
                    }
                }

                $usersEks = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($usersEks) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.usereks.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $usersEks)
                ->with(self::COMPANY, $companies)
                ->with('role', $roles)
                ->with(self::SEARCH, $search)
				->with('filterCompany', $filterCompanyUser)
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
        $roles = Role::where('id', '=', 2)->get();
        $companies = Company::where('id', '!=', '1')->orderBy('name')->get();
        
        return view('admin.usereks.create')
            ->with('role', $roles)
            ->with(self::COMPANY, $companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function uploadPicture($request, $usersEks){

        if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$usersEks->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $usersEks->picture = $name_file;
            }else{
                Session::flash(self::ERROR,self::FAILED_USER_MSG);
                return redirect(self::ADMIN_USEREKS_CREATE);
            }
        }
        return $usersEks;
    }
    public function store(Request $request)
    {
        $currentUser = Auth::user(); 
        
        $usersEks = new User;
        $usersEks->id = Uuid::uuid4();
        $usersEks->role_id = $request->input(self::ROLE_ID);
        $usersEks->company_id = $request->input(self::COMPANY_ID);
        $usersEks->name = $request->input('name');
        $usersEks->email = $request->input(self::EMAIL);
        $usersEks->password = bcrypt($request->input(self::PASS_TEXT));
        $usersEks->is_active = $request->input(self::IS_ACTIVE);
        $usersEks->address = $request->input(self::ADDRESS);
        $usersEks->phone_number = $request->input(self::PHONE_NUMBER);
        $usersEks->fax = $request->input('fax');

        $this->uploadPicture($request,$usersEks); 

        $usersEks->created_by = $currentUser->id;
        $usersEks->updated_by = $currentUser->id;

        try{
            $usersEks->save(); 

            $logService = new LogService();
            $logService->createLog('Create User', self::ADMIN_USEREKS_CREATE, $usersEks); 
           

            Session::flash(self::MESSAGE, 'User successfully created');
            return redirect(self::ADMIN_USEREKS);
        } catch(\Exception $e){ 
            Session::flash(self::ERROR, self::FAILED_LOG_MSG);
            return redirect(self::ADMIN_USEREKS_CREATE)->withInput();
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
        $usereks = User::find($id);
        $companies = Company::where('id', '!=', '1')->orderBy('name')->get();

        return view('admin.profile.edit')
            ->with(self::COMPANY, $companies)
            ->with('data', $usereks);   
    }

    public function updateProfile($id, Request $request)
    {
        $currentUser = Auth::user();

        $usereks = User::find($id);
        $oldData = $usereks;
        if ($request->has('name')){
            $usereks->name = $request->input('name');
        }
        if ($request->has('old_password')){
            if (Hash::check($request->get('old_password'), $usereks->password)) {
                if ($request->has(self::NEW_TEXT.self::NEW_PASSWORD) && $request->has('confirm_new_password')){
                    if ($request->get(self::NEW_TEXT.self::NEW_PASSWORD) == $request->get('confirm_new_password')){
                        $usereks->password = bcrypt($request->input(self::NEW_TEXT.self::NEW_PASSWORD));
                    } else{
                        Session::flash(self::ERROR, 'New password not matched');
                        return back()
                            ->withInput($request->all());    
                    }
                } else{
                    Session::flash(self::ERROR, 'Must fill new password and confirm new password');
                    return back()
                        ->withInput($request->all());
                }
            } else{
                Session::flash(self::ERROR, 'Wrong Old Password');
                return back()
                    ->withInput($request->all());
            }
        }
        if ($request->has(self::PRICE)){
            $usereks->price = $request->input(self::PRICE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $usereks->is_active = $request->input(self::IS_ACTIVE);
        }

        
        $this->uploadPicture($request,$usersEks); 

        $usereks->updated_by = $currentUser->id;

        try{
            $usereks->save(); 

            $logService = new LogService();
            $logService->createLog('Update Profile', 'PROFILE', $oldData); 

            Session::flash(self::MESSAGE, 'User successfully updated');
            return redirect('/admin');
        } catch(Exception $e){
            Session::flash(self::ERROR, self::FAILED_LOG_MSG);
            return redirect('/admin/usereks/'.$usereks->id.'edit');
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
        $usersEks = User::find($id);
		$roles = Role::where('id', '=', 2)->get();
        $companies = Company::where('id', '!=', '1')->orderBy('name')->get(); 

        return view('admin.usereks.edit')
            ->with('role', $roles)
            ->with(self::COMPANY, $companies)
            ->with('data', $usersEks);
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

        $usersEks = User::find($id);
        $oldData = $usersEks;

        if ($request->has(self::ROLE_ID)){
            $usersEks->role_id = $request->input(self::ROLE_ID);
        }
        if ($request->has(self::COMPANY_ID)){
            $usersEks->company_id = $request->input(self::COMPANY_ID);
        }
        if ($request->has('name')){
            $usersEks->name = $request->input('name');
        }
        if ($request->has(self::EMAIL)){
            $usersEks->email = $request->input(self::EMAIL);
        }
        if ($request->has(self::PASS_TEXT)){
            $usersEks->password = bcrypt($request->input(self::PASS_TEXT));
        }
        if ($request->has(self::PRICE)){
            $usersEks->price = $request->input(self::PRICE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $usersEks->is_active = $request->input(self::IS_ACTIVE);
        }

        if ($request->has(self::ADDRESS)){
            $usersEks->address = $request->input(self::ADDRESS);
        }
        if ($request->has(self::PHONE_NUMBER)){
            $usersEks->phone_number = $request->input(self::PHONE_NUMBER);
        }
        if ($request->has('fax')){
            $usersEks->fax = $request->input('fax');
        }

        if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$usersEks->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $usersEks->picture = $name_file;
            }else{
                Session::flash(self::ERROR, self::FAILED_USER_MSG);
                return redirect(self::ADMIN_USEREKS_CREATE);
            }
        }

        $usersEks->updated_by = $currentUser->id;

        try{
            $usersEks->save(); 

            $logService = new LogService();
            $logService->createLog('Update User', self::USER_EKSTERNAL, $oldData); 

            Session::flash(self::MESSAGE, 'User successfully updated');
            return redirect(self::ADMIN_USEREKS);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::FAILED_LOG_MSG);
            return redirect(self::ADMIN_USEREKS.'/'.$usersEks->id.'edit');
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
		$usersEks = User::find($id);

        if ($usersEks){
            try{
                $usersEks->delete();
                
                Session::flash(self::MESSAGE, 'User successfully deleted');
                return redirect(self::ADMIN_USEREKS);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_USEREKS);
            }
        }
    }
	
	public function softDelete($id)
    {
		$currentUser = Auth::user();
        $usersEks = User::find($id);
        $oldData = $usersEks;
        if ($usersEks){
            try{
				$usersEks->is_deleted = 1;
				$usersEks->deleted_by = $currentUser->id;
				$usersEks->deleted_at = ''.date('Y-m-d H:i:s').'';
                $usersEks->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete User"; 
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "USER EKSTERNAL";
                $logs->save();

                Session::flash(self::MESSAGE, 'User successfully deleted');
                return redirect(self::ADMIN_USEREKS);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_USEREKS);
            }
        }
    }
	
	public function autocomplete($query) {
        return response(User::autocomplet($query)); 
    }

    public function logout(){
        //LOG LOGOUT 

        $logService = new LogService();
        $logService->createLog('Logout', "AUTH"); 

        Auth::logout();
        return redirect('/admin/login');
    }

}
