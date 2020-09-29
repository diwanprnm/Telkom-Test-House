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

use File;
use Image;
use Storage;
// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Services\Logs\LogService;
use App\Services\FileService;
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
    private const MEDIA_USER = '/user/';
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
            
            $query = User::whereNotNull('created_at')
                    ->with('role')
                    ->with(self::COMPANY)
					->where(self::ROLE_ID, '=', '2')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0');
            
            if ($search != null){
                $query->where('name','like','%'.$search.'%');

                $logService = new LogService();
                $logService->createLog('Search User', self::USER_EKSTERNAL, json_encode(array("search"=>$search)));     
            }
					
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

            $usersEks = $query->orderBy('name')->paginate($paginate);
            
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

        if ($request->hasFile(self::PICTURE)) { 
            $fileService = new FileService();
            $fileService->uploadFile($request->file(self::PICTURE), '', self::MEDIA_USER.$usersEks->id);

        }
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
            $fileService = new FileService();
            $fileService->uploadFile($request->file(self::PICTURE), '', self::MEDIA_USER.$usersEks->id);
        }
        $usersEks->updated_by = $currentUser->id;

        try{
            $usersEks->save(); 

            $logService = new LogService();
            // $logService->createLog('Update User', self::USER_EKSTERNAL, $oldData); 

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
         $oldData = $usersEks;
        if ($usersEks){
            try{
                $usersEks->delete();

                $logService = new LogService();
                // $logService->createLog("Delete User",self::USER_EKSTERNAL,$oldData);

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

                $logService = new LogService();
                // $logService->createLog("Delete User",self::USER_EKSTERNAL,$oldData);

                Session::flash(self::MESSAGE, 'User successfully deleted');
                return redirect(self::ADMIN_USEREKS);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_USEREKS);
            }
        }
    }
}
