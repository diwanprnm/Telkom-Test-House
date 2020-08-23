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
class UserController extends Controller
{

    private const SEARCH = 'search'; 
    private const IS_ACTIVE = 'is_active'; 
    private const MESSAGE = 'message'; 
    private const ERROR = 'error'; 
    private const COMPANY = 'company'; 
    private const PARENT_ID = 'parent_id'; 
    private const ROLE_ID = 'role_id'; 
    private const COMPANY_ID = 'company_id'; 
    private const PASSWORD = 'password'; 
    private const EMAIL = 'email'; 
    private const ADDRESS = 'address'; 
    private const PHONE_NUMBER = 'phone_number'; 
    private const PICTURE = 'picture'; 
    private const PATH_PROFILE = 'profile_';  
    private const MEDIA_USER = '/media/user/';
    private const FAILED_USER_MSG = 'Save Profile Picture to directory failed'; 
    private const FAILED_LOG_MSG = 'Save failed'; 
    private const NEW_TEXT = 'new';
    private const PRICE = 'price';
    private const ADMIN_USER = '/admin/user';
    private const ADMIN_USER_CREATE = '/admin/user/create/';

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
            $filterCompany = '';
            $filterRole = '';
            $status = -1;

            $companies = Company::where(self::IS_ACTIVE, 1)->where('id', '<>', '1')->get();
            $roles = Role::all();
            
            if ($search != null){
                $users = User::whereNotNull('created_at')
                    ->with('role')
                    ->with(self::COMPANY)
                    ->where('name','like','%'.$search.'%')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0')
                    ->orderBy('name')
                    ->paginate($paginate);  

                    $logService = new LogService();  
                    $logService->createLog('Search User',"USER",json_encode(array("search"=>$search)));
                    
            }else{
                $query = User::whereNotNull('created_at')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0')
                    ->with('role')
                    ->with(self::COMPANY);

                if ($request->has(self::COMPANY)){
                    $filterCompany = $request->get(self::COMPANY);
					if($request->input(self::COMPANY) != 'all'){
						$query->whereHas(self::COMPANY, function ($q) use ($request){
							return $q->where('name', 'like', '%'.$request->get(self::COMPANY).'%');
						});
					}
                }

                if ($request->has('role')){
                    $filterRole = $request->get('role');
					if($request->input(self::COMPANY) != 'all'){
						$query->whereHas('role', function ($q) use ($request){
							return $q->where('name', 'like', '%'.$request->get('role').'%');
						});
					}
                }

                if ($request->has(self::IS_ACTIVE)){
					$status = $request->get(self::IS_ACTIVE);
                    if ($request->get(self::IS_ACTIVE) > -1){
                        $query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
                    }
                }

                $users = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($users) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.user.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $users)
                ->with(self::COMPANY, $companies)
                ->with('role', $roles)
                ->with(self::SEARCH, $search)
                ->with('filterRole', $filterRole)
                ->with('filterCompany', $filterCompany)
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
        $roles = Role::all();
        $companies = Company::where(self::IS_ACTIVE, true)->where('id', '<>', '1')->orderBy('name')->get();
         
        $tree = $this->getTree();
      
        return view('admin.user.create')
            ->with('role', $roles)
            ->with('tree', $tree)
            ->with(self::COMPANY, $companies);
    }

    public function createTree(&$list, $parent){

        foreach ($parent as $l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        } 
        return $tree;
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
        $menus = $request->input('menus');
        
        $user = new User;
        $user->id = Uuid::uuid4();
        $user->role_id = $request->input(self::ROLE_ID);
        $user->company_id = $request->input(self::COMPANY_ID);
        $user->name = $request->input('name');
        $user->email = $request->input(self::EMAIL);
        $user->password = bcrypt($request->input(self::PASSWORD));
        $user->is_active = $request->input(self::IS_ACTIVE);
        $user->address = $request->input(self::ADDRESS);
        $user->phone_number = $request->input(self::PHONE_NUMBER);
        $user->fax = $request->input('fax');
        $status = FALSE;
        if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash(self::ERROR, self::FAILED_USER_MSG); 
            }
        }

        $user->created_by = $currentUser->id;
        $user->updated_by = $currentUser->id;

        try{
            $user->save(); 
 
            $logService = new LogService();  
            $logService->createLog('Create User',"USER",json_encode($user));
         
            foreach ($menus as  $value) {
                $usersmenus = new UsersMenus;
                $usersmenus->user_id =  $user->id; 
                $usersmenus->menu_id = $value; 
                $usersmenus->created_by = $currentUser->id;
                try{
                    $usersmenus->save();
                }catch(\Exception $e){
                    Session::flash(self::ERROR, self::FAILED_LOG_MSG);
                    return redirect(self::ADMIN_USER_CREATE)->withInput();
                }
            }  
            Session::flash(self::MESSAGE, 'User successfully created');
            $status = TRUE;
        }catch(Exception $e){
            Session::flash(self::ERROR, "User Failed created");
            return redirect(self::ADMIN_USER_CREATE)->withInput();
        } 

        if($status){
             return redirect(self::ADMIN_USER);
        }else{
             return redirect(self::ADMIN_USER_CREATE)->withInput();
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
        $user = User::find($id);
        $companies = Company::where(self::IS_ACTIVE, true)->where('id', '<>', '1')->get(); 

        return view('admin.profile.edit')
            ->with(self::COMPANY, $companies)
            ->with('data', $user);   
    }

    public function updateProfile($id, Request $request)
    {
        $currentUser = Auth::user();

        $user = User::find($id);
        $oldData = $user;
        if ($request->has('name')){
            $user->name = $request->input('name');
        }
        if ($request->has('old_password')){
            if (Hash::check($request->get('old_password'), $user->password)) {
                if ($request->has(self::NEW_TEXT.self::PASSWORD) && $request->has('confirm_new_password')){
                    if ($request->get(self::NEW_TEXT.self::PASSWORD) == $request->get('confirm_new_password')){
                        $user->password = bcrypt($request->input(self::NEW_TEXT.self::PASSWORD));
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
            $user->price = $request->input(self::PRICE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $user->is_active = $request->input(self::IS_ACTIVE);
        }

        if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash(self::ERROR, self::FAILED_USER_MSG);
                return redirect(self::ADMIN_USER.'/'.$user->id.'edit');
            }
        }

        $user->updated_by = $currentUser->id;

        try{
            $user->save(); 

            $logService = new LogService();  
            $logService->createLog('Update Profile',"PROFILE",json_encode($oldData));

            Session::flash(self::MESSAGE, 'User successfully updated');
            return redirect('/admin');
        } catch(Exception $e){
            Session::flash(self::ERROR, self::FAILED_LOG_MSG);
            return redirect(self::ADMIN_USER.'/'.$user->id.'edit');
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
        $user = User::find($id);
        $roles = Role::all();
        $companies = Company::where('id', '<>', '1')->orderBy('name')->get();
        $currentUser = Auth::user();
        
    
        $select = array('menus.id');
        $menu_user = Menu::selectRaw(implode(",", $select))->join("users_menus","menus.id","=","users_menus.menu_id")
                ->where("user_id",$currentUser->id)
                ->get()->toArray();

       $tree = $this->getTree();

        return view('admin.user.edit')
            ->with('role', $roles)
            ->with('tree', $tree)
            ->with('menu_user', $menu_user)
            ->with(self::COMPANY, $companies)
            ->with('data', $user);
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
        $menus = $request->input('menus');

        $user = User::find($id);
        $oldData = $user;

        if ($request->has(self::ROLE_ID)){
            $user->role_id = $request->input(self::ROLE_ID);
        }
        if ($request->has(self::COMPANY_ID)){
            $user->company_id = $request->input(self::COMPANY_ID);
        }
        if ($request->has('name')){
            $user->name = $request->input('name');
        }
        if ($request->has(self::EMAIL)){
            $user->email = $request->input(self::EMAIL);
        }
        if ($request->has(self::PASSWORD)){
            $user->password = bcrypt($request->input(self::PASSWORD));
        }
        if ($request->has(self::PRICE)){
            $user->price = $request->input(self::PRICE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $user->is_active = $request->input(self::IS_ACTIVE);
        }

        if ($request->has(self::ADDRESS)){
            $user->address = $request->input(self::ADDRESS);
        }
        if ($request->has(self::PHONE_NUMBER)){
            $user->phone_number = $request->input(self::PHONE_NUMBER);
        }
        if ($request->has('fax')){
            $user->fax = $request->input('fax');
        }

        if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash(self::ERROR, self::FAILED_USER_MSG);
                return redirect(self::ADMIN_USER_CREATE);
            }
        }

        $user->updated_by = $currentUser->id;

        try{
            $user->save();
            UsersMenus::where("user_id",$user->id)->delete(); 

            $logService = new LogService();  
            $logService->createLog('Update User',"USER",json_encode($oldData));

            foreach ($menus as  $value) {
                $usersmenus = new UsersMenus;
                $usersmenus->user_id =  $user->id; 
                $usersmenus->menu_id = $value; 
                $usersmenus->created_by = $currentUser->id;
                try{
                    $usersmenus->save();
                }catch(\Exception $e){
                    Session::flash(self::ERROR, self::FAILED_LOG_MSG);
                    return redirect(self::ADMIN_USER_CREATE)->withInput();
                }
            }
            
            Session::flash(self::MESSAGE, 'User successfully updated');
            return redirect(self::ADMIN_USER);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::FAILED_LOG_MSG);
            return redirect(self::ADMIN_USER.'/'.$user->id.'edit');
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
		$user = User::find($id);

        if ($user){
            try{
                $user->delete();
                
                Session::flash(self::MESSAGE, 'User successfully deleted');
                return redirect(self::ADMIN_USER);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_USER);
            }
        }
    }

    private function getTree()
    {
        $menu = Menu::get()->toArray();
        $parentMenu = Menu::where("parent_id",0)->get()->toArray();

        $new = array(); 
        foreach ($menu as $a){
            $new[$a[self::PARENT_ID]][] = $a;
        }
        $tree = array();
        
        foreach ($parentMenu as  $value) {
            $tree[] = $this->createTree($new, array($value));
        } 

        return $tree;
    }
	
	public function softDelete($id)
    {
		$currentUser = Auth::user();
        $user = User::find($id);
        $oldData = $user;
        if ($user){
            try{
				$user->is_deleted = 1;
				$user->deleted_by = $currentUser->id;
				$user->deleted_at = ''.date('Y-m-d H:i:s').'';
                $user->save(); 

                $logService = new LogService();  
                $logService->createLog('Delete User',"USER",json_encode($oldData));

                Session::flash(self::MESSAGE, 'User successfully deleted');
                return redirect(self::ADMIN_USER);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_USER);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = User::autocomplet($query);
        return response($respons_result);
    }

    public function logout(){
        //LOG LOGOUT 

        $logService = new LogService();  
        $logService->createLog('Logout',"AUTH");

        Auth::logout();
        return redirect('/admin/login');
    }

}
