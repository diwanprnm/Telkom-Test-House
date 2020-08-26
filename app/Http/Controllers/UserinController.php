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
use App\AdminRole;

use Auth;
use Session;
use Hash;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UserinController extends Controller
{


    private const SEARCH = 'search';
    private const COMPANY = 'company';
    private const ROLE_ID = 'role_id';
    private const EMAIL = 'email';
    private const USER_INTERNAL = 'USER INTERNAL';
    private const ERROR = 'error';
    private const MESSAGE = 'message';
    private const PAGE_USERIN = '/admin/userin';
    private const IS_ACTIVE = 'is_active';
    private const PARENT_ID = 'parent_id';
    private const COMPANY_ID = 'company_id';
    private const ADDRESS = 'address';
    private const PHONE_NUMBER = 'phone_number';
    private const PICTURE = 'picture';
    private const PATH_PROFILE = 'profile_';
    private const MEDIA_USER = '/media/user/';
    private const USER_IMG_FAILED = 'Save Profile Picture to directory failed';
    private const PAGE_USERIN_CREATE = '/admin/userin/create';
    private const USER_MSG_FAILED = 'Save Failed';
    private const NEW_PASS_TEXT = 'new_password';
    private const PRICE = 'price';
    private const USER_ID = 'user_id';
    private const PAGE_EDIT = '/edit';
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
            $status = -1;

            $companies = Company::where('id', 1)->get();
			$roles = Role::where('id', '!=', 2);
            
            if ($search != null){
                $usersIn = User::whereNotNull('created_at')
                    ->with('role')
                    ->with(self::COMPANY)
                    ->where('name','like','%'.$search.'%')
					->where(self::ROLE_ID, '!=', '2')
                    ->where('id', '<>', '1')
					->where(self::EMAIL, '<>', 'admin@mail.com')
					->where('is_deleted', '=', '0')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;
                    $logs->id = Uuid::uuid4();
                    $logs->action = "Search User";
                    $datasearch = array(self::SEARCH=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = self::USER_INTERNAL;
                    try{
                        $logs->save();    
                    }catch(Illuminate\Database\QueryException $e){ 
                        Session::flash(self::ERROR, 'Failed Create Log');
                        return redirect(self::PAGE_USERIN);
                    }
                    
            }else{
                $query = User::whereNotNull('created_at')
					->where(self::ROLE_ID, '!=', '2')
					->where('id', '<>', '1')
                    ->where(self::EMAIL, '<>', 'admin@mail.com')
					->where('is_deleted', '=', '0')
                    ->with('role')
                    ->with(self::COMPANY);

                if ($request->has(self::IS_ACTIVE)){
					$status = $request->get(self::IS_ACTIVE);
                    if ($request->get(self::IS_ACTIVE) > -1){
                        $query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
                    }
                }

                $usersIn = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($usersIn) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.userin.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $usersIn)
                ->with(self::COMPANY, $companies)
                ->with('role', $roles)
                ->with(self::SEARCH, $search)
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
        $roles = Role::where('id', '!=', 2)->get();
        $companies = Company::where('id', '=', '1')->orderBy('name')->get();
        $menu = Menu::get()->toArray();
        $parentMenu = Menu::where(self::PARENT_ID,0)->get()->toArray();
        $new = array(); 
        foreach ($menu as $a){
            $new[$a[self::PARENT_ID]][] = $a;
        }
        $treeUserIn = array();
        
        foreach ($parentMenu as $value) {
            $treeUserIn[] = $this->createTree($new, array($value));
        } 
      
        return view('admin.userin.create')
            ->with('role', $roles)
            ->with('tree', $treeUserIn)
            ->with(self::COMPANY, $companies);
    }

    public function createTree(&$list, $parent){

        foreach ($parent as $l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $treeUserIn[] = $l;
        } 
        return $treeUserIn;
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
        $roles = $request->input('examinations');
        $hide_admin_role = $request->input('hide_admin_role');
        
        $user = new User;
        $user->id = Uuid::uuid4();
        $user->role_id = $request->input(self::ROLE_ID);
        $user->company_id = $request->input(self::COMPANY_ID);
        $user->name = $request->input('name');
        $user->email = $request->input(self::EMAIL);
        $user->password = bcrypt($request->input('password'));
        $user->is_active = $request->input(self::IS_ACTIVE);
        $user->address = $request->input(self::ADDRESS);
        $user->phone_number = $request->input(self::PHONE_NUMBER);
        $user->fax = $request->input('fax'); 


        $this->uploadPictureUserin($request,$user); 

        $user->created_by = $currentUser->id;
        $user->updated_by = $currentUser->id;

        try{
            $user->save();
            
            $logs = new Logs;
            $logs->id = Uuid::uuid4();
            $logs->user_id = $currentUser->id; 
            $logs->action = "Create User"; 
            $logs->data = $user;
            $logs->created_by = $currentUser->id;
            $logs->page = self::USER_INTERNAL;
            try{
                $logs->save();

                foreach ($menus as $value) {
                    $usersmenus = new UsersMenus;
                    $usersmenus->user_id =  $user->id; 
                    $usersmenus->menu_id = $value; 
                    $usersmenus->created_by = $currentUser->id;
                    try{
                        $usersmenus->save();
                    }catch(\Exception $e){
                        Session::flash(self::ERROR, self::USER_MSG_FAILED);
                        return redirect(self::PAGE_USERIN_CREATE)->withInput();
                    }
                }

                if($hide_admin_role && $roles){
                    $usersroles = new AdminRole;
                    $usersroles->user_id =  $user->id; 
                    $usersroles->user_name =  $user->name; 
                    $usersroles->user_email =  $user->email; 
                    foreach ($roles as $value) {
                        $usersroles->$value = 1; 
                    }
                    $usersroles->created_by = $currentUser->id;
                    try{
                        $usersroles->save();
                    }catch(\Exception $e){
                        Session::flash(self::ERROR, self::USER_MSG_FAILED);
                        return redirect(self::PAGE_USERIN_CREATE)->withInput();
                    }
                }
              
            }catch(\Exception $e){
                Session::flash(self::ERROR, self::USER_MSG_FAILED);
                return redirect(self::PAGE_USERIN_CREATE)->withInput();
            }
           

            Session::flash(self::MESSAGE, 'User successfully created');
            return redirect(self::PAGE_USERIN);
        } catch(\Exception $e){ 
            Session::flash(self::ERROR, self::USER_MSG_FAILED);
            return redirect(self::PAGE_USERIN_CREATE)->withInput();
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
        $companies = Company::where('id', '=', '1')->orderBy('name')->get();

        return view('admin.profile.edit')
            ->with(self::COMPANY, $companies)
            ->with('data', $user);   
    }

    public function updateProfile($id, Request $request)
    {
        $currentUser = Auth::user();

        $userIn = User::find($id);
        $oldData = $userIn;
        if ($request->has('name')){
            $userIn->name = $request->input('name');
        }
        if ($request->has('old_password')){

            $msg_userin_password = "";
            $status_userin_password = TRUE;
            if (Hash::check($request->get('old_password'), $userIn->password)) {
                if ($request->has(self::NEW_PASS_TEXT) && $request->has('confirm_new_password')){
                    if ($request->get(self::NEW_PASS_TEXT) == $request->get('confirm_new_password')){
                        $userIn->password = bcrypt($request->input(self::NEW_PASS_TEXT));
                    } else{  
                        $status_userin_password = FALSE;
                        $msg_userin_password = 'New password not matched';  
                    }
                } else{ 

                    $status_userin_password = FALSE;
                    $msg_userin_password = 'Must fill new password and confirm new password';
                }
            } else{
               
                $status_userin_password = FALSE;
                $msg_userin_password = 'Wrong Old Password';
            }


            if(!$status_userin_password){ 
                Session::flash(self::ERROR, $msg_userin_password);
                return back()->withInput($request->all());
            }
        }
        if ($request->has(self::PRICE)){
            $userIn->price = $request->input(self::PRICE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $userIn->is_active = $request->input(self::IS_ACTIVE);
        }

        
        $this->uploadPictureUserin($request,$userIn); 

        $userIn->updated_by = $currentUser->id;

        try{
            $userIn->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update Profile"; 
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "PROFILE";
            $logs->save();

            Session::flash(self::MESSAGE, 'User successfully updated');
            return redirect('/admin');
        } catch(Exception $e){
            Session::flash(self::ERROR, self::USER_MSG_FAILED);
            return redirect(self::PAGE_USERIN.'/'.$user->id.'edit');
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
		$roles = Role::where('id', '!=', 2)->get();
        $companies = Company::where('id', '=', '1')->orderBy('name')->get();
        
        $select = array('menus.id');
        $menu_user = Menu::selectRaw(implode(",", $select))->join("users_menus","menus.id","=","users_menus.menu_id")
                ->where(self::USER_ID,$id)
                ->get()->toArray();

        $menu = Menu::get()->toArray();
         $parentMenu = Menu::where(self::PARENT_ID,0)->get()->toArray();

        $new = array(); 
        foreach ($menu as $a){
            $new[$a[self::PARENT_ID]][] = $a;
        }
        $tree = array();
        
        foreach ($parentMenu as $value) {
            $tree[] = $this->createTree($new, array($value));
        } 

        $admin_role = AdminRole::where(self::USER_ID,$id)->get();

        return view('admin.userin.edit')
            ->with('role', $roles)
            ->with('tree', $tree)
            ->with('menu_user', $menu_user)
            ->with('admin_role', $admin_role)
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
        $roles = $request->input('examinations');
        $hide_admin_role = $request->input('hide_admin_role');

        $userIn = User::find($id);
        $oldData = $userIn;

        if ($request->has(self::ROLE_ID)){
            $userIn->role_id = $request->input(self::ROLE_ID);
        }
        if ($request->has(self::COMPANY_ID)){
            $userIn->company_id = $request->input(self::COMPANY_ID);
        }
        if ($request->has('name')){
            $userIn->name = $request->input('name');
        }
        if ($request->has(self::EMAIL)){
            $userIn->email = $request->input(self::EMAIL);
        }
        if ($request->has('password')){
            $userIn->password = bcrypt($request->input('password'));
        }
        if ($request->has(self::PRICE)){
            $userIn->price = $request->input(self::PRICE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $userIn->is_active = $request->input(self::IS_ACTIVE);
        }

        if ($request->has(self::ADDRESS)){
            $userIn->address = $request->input(self::ADDRESS);
        }
        if ($request->has(self::PHONE_NUMBER)){
            $userIn->phone_number = $request->input(self::PHONE_NUMBER);
        }
        if ($request->has('fax')){
            $userIn->fax = $request->input('fax');
        }

        if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$userIn->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $userIn->picture = $name_file;
            }else{
                Session::flash(self::ERROR,self::USER_IMG_FAILED);
                return redirect(self::PAGE_USERIN_CREATE);
            }
        }

        $userIn->updated_by = $currentUser->id;

        try{
            $userIn->save();
            UsersMenus::where(self::USER_ID,$userIn->id)->delete();
            AdminRole::where(self::USER_ID,$userIn->id)->delete();
          

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update User"; 
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = self::USER_INTERNAL;
            $logs->save();

            foreach ($menus as  $value) {
                $usersmenus = new UsersMenus;
                $usersmenus->user_id =  $userIn->id; 
                $usersmenus->menu_id = $value; 
                $usersmenus->created_by = $currentUser->id;
                try{
                    $usersmenus->save();
                }catch(\Exception $e){
                    Session::flash(self::ERROR, self::USER_MSG_FAILED);
                    return redirect(self::PAGE_USERIN.'/'.$userIn->id.self::PAGE_EDIT)->withInput();
                }
            }
            if($hide_admin_role && $roles){
                $usersroles = new AdminRole;
                $usersroles->user_id =  $userIn->id; 
                $usersroles->user_name =  $userIn->name; 
                $usersroles->user_email =  $userIn->email; 
                foreach ($roles as  $value) {
                    $usersroles->$value = 1; 
                }
                $usersroles->created_by = $currentUser->id;
                try{
                    $usersroles->save();
                }catch(\Exception $e){
                    Session::flash(self::ERROR, self::USER_MSG_FAILED);
                    return redirect(self::PAGE_USERIN.'/'.$userIn->id.self::PAGE_EDIT)->withInput();
                }
            }
            
            Session::flash(self::MESSAGE, 'User successfully updated');
            return redirect(self::PAGE_USERIN);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::USER_MSG_FAILED);
            return redirect(self::PAGE_USERIN.'/'.$userIn->id.self::PAGE_EDIT);
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
                return redirect(self::PAGE_USERIN);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::PAGE_USERIN);
            }
        }
    }
	
	public function softDelete($id)
    {
		$currentUser = Auth::user();
        $user = User::find($id);
        $oldData = $user;
        if ($user){
            try{
                $user->is_active = 0;
				$user->is_deleted = 1;
				$user->deleted_by = $currentUser->id;
				$user->deleted_at = ''.date('Y-m-d H:i:s').'';
                $user->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete User"; 
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = self::USER_INTERNAL;
                $logs->save();

                Session::flash(self::MESSAGE, 'User successfully deleted');
                return redirect(self::PAGE_USERIN);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::PAGE_USERIN);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = User::autocomplet($query);
        return response($respons_result);
    }

    public function logout(){
        //LOG LOGOUT
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "Logout"; 
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "AUTH";
        $logs->save();
        Auth::logout();
        return redirect('/admin/login');
    }

     private function uploadPictureUserin($request, $user){

       if ($request->hasFile(self::PICTURE)) { 
            $name_file = self::PATH_PROFILE.$request->file(self::PICTURE)->getClientOriginalName();
            $path_file = public_path().self::MEDIA_USER.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::PICTURE)->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash(self::ERROR, self::USER_IMG_FAILED);
                return redirect(self::PAGE_USERIN_CREATE);
            }
        }
        return $user;
    }

}
