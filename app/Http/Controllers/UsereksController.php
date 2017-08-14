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

class UsereksController extends Controller
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
			$filterCompany = '';
            $status = -1;

            $companies = Company::where('id','!=', 1)->get();
			$roles = Role::where('id', 2);
            
            if ($search != null){
                $users = User::whereNotNull('created_at')
                    ->with('role')
                    ->with('company')
                    ->where('name','like','%'.$search.'%')
					->where('role_id', '=', '2')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;
                    $logs->id = Uuid::uuid4();
                    $logs->action = "Search User";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "USER EKSTERNAL";
                    try{
                        $logs->save();    
                    }catch(Illuminate\Database\QueryException $e){
                     
                    }
                    
            }else{
                $query = User::whereNotNull('created_at')
					->where('role_id', '=', '2')
					->where('id', '<>', '1')
					->where('is_deleted', '=', '0')
                    ->with('role')
                    ->with('company');
					
				if ($request->has('company')){
                    $filterCompany = $request->get('company');
					if($request->input('company') != 'all'){
						$query->whereHas('company', function ($q) use ($request){
							return $q->where('name', 'like', '%'.$request->get('company').'%');
						});
					}
                }

                if ($request->has('is_active')){
					$status = $request->get('is_active');
                    if ($request->get('is_active') > -1){
                        $query->where('is_active', $request->get('is_active'));
                    }
                }

                $users = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($users) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.usereks.index')
                ->with('message', $message)
                ->with('data', $users)
                ->with('company', $companies)
                ->with('role', $roles)
                ->with('search', $search)
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
        $roles = Role::where('id', '=', 2)->get();
        $companies = Company::where('id', '!=', '1')->orderBy('name')->get();
        
        return view('admin.usereks.create')
            ->with('role', $roles)
            ->with('company', $companies);
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
        $user->role_id = $request->input('role_id');
        $user->company_id = $request->input('company_id');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->is_active = $request->input('is_active');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');
        $user->fax = $request->input('fax');

        if ($request->hasFile('picture')) {
            /*$ext_file = $request->file('picture')->getClientOriginalExtension();
            $name_file = uniqid().'_user_'.$user->id.'.'.$ext_file;*/
            $name_file = 'profile_'.$request->file('picture')->getClientOriginalName();
            $path_file = public_path().'/media/user/'.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('picture')->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash('error', 'Save Profile Picture to directory failed');
                return redirect('/admin/usereks/create');
            }
        }

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
            $logs->page = "USER EKSTERNAL";
            try{
                $logs->save();
            }catch(\Exception $e){
                Session::flash('error', 'Save failed');
                return redirect('/admin/usereks/create')->withInput();
            }
           

            Session::flash('message', 'User successfully created');
            return redirect('/admin/usereks');
        } catch(\Exception $e){ 
            Session::flash('error', 'Save failed');
            return redirect('/admin/usereks/create')->withInput();
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
        $companies = Company::where('id', '!=', '1')->orderBy('name')->get();

        return view('admin.profile.edit')
            ->with('company', $companies)
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
                if ($request->has('new_password') && $request->has('confirm_new_password')){
                    if ($request->get('new_password') == $request->get('confirm_new_password')){
                        $user->password = bcrypt($request->input('new_password'));
                    } else{
                        Session::flash('error', 'New password not matched');
                        return back()
                            ->withInput($request->all());    
                    }
                } else{
                    Session::flash('error', 'Must fill new password and confirm new password');
                    return back()
                        ->withInput($request->all());
                }
            } else{
                Session::flash('error', 'Wrong Old Password');
                return back()
                    ->withInput($request->all());
            }
        }
        if ($request->has('price')){
            $user->price = $request->input('price');
        }
        if ($request->has('is_active')){
            $user->is_active = $request->input('is_active');
        }

        if ($request->hasFile('picture')) {
            /*$ext_file = $request->file('picture')->getClientOriginalExtension();
            $name_file = uniqid().'_user_'.$user->id.'.'.$ext_file;*/
            $name_file = 'profile_'.$request->file('picture')->getClientOriginalName();
            $path_file = public_path().'/media/user/'.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('picture')->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash('error', 'Save Profile Picture to directory failed');
                return redirect('/admin/usereks/'.$user->id.'edit');
            }
        }

        $user->updated_by = $currentUser->id;

        try{
            $user->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update Profile"; 
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "PROFILE";
            $logs->save();

            Session::flash('message', 'User successfully updated');
            return redirect('/admin');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/usereks/'.$user->id.'edit');
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
		$roles = Role::where('id', '=', 2)->get();
        $companies = Company::where('id', '!=', '1')->orderBy('name')->get();
        $currentUser = Auth::user();

        return view('admin.usereks.edit')
            ->with('role', $roles)
            ->with('company', $companies)
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

        if ($request->has('role_id')){
            $user->role_id = $request->input('role_id');
        }
        if ($request->has('company_id')){
            $user->company_id = $request->input('company_id');
        }
        if ($request->has('name')){
            $user->name = $request->input('name');
        }
        if ($request->has('email')){
            $user->email = $request->input('email');
        }
        if ($request->has('password')){
            $user->password = bcrypt($request->input('password'));
        }
        if ($request->has('price')){
            $user->price = $request->input('price');
        }
        if ($request->has('is_active')){
            $user->is_active = $request->input('is_active');
        }

        if ($request->has('address')){
            $user->address = $request->input('address');
        }
        if ($request->has('phone_number')){
            $user->phone_number = $request->input('phone_number');
        }
        if ($request->has('fax')){
            $user->fax = $request->input('fax');
        }

        if ($request->hasFile('picture')) {
            /*$ext_file = $request->file('picture')->getClientOriginalExtension();
            $name_file = uniqid().'_user_'.$user->id.'.'.$ext_file;*/
            $name_file = 'profile_'.$request->file('picture')->getClientOriginalName();
            $path_file = public_path().'/media/user/'.$user->id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('picture')->move($path_file,$name_file)){
                $user->picture = $name_file;
            }else{
                Session::flash('error', 'Save Profile Picture to directory failed');
                return redirect('/admin/usereks/create');
            }
        }

        $user->updated_by = $currentUser->id;

        try{
            $user->save();
			
            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update User"; 
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "USER EKSTERNAL";
            $logs->save();

            Session::flash('message', 'User successfully updated');
            return redirect('/admin/usereks');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/usereks/'.$user->id.'edit');
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
                
                Session::flash('message', 'User successfully deleted');
                return redirect('/admin/usereks');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/usereks');
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
				$user->is_deleted = 1;
				$user->deleted_by = $currentUser->id;
				$user->deleted_at = ''.date('Y-m-d H:i:s').'';
                $user->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete User"; 
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "USER EKSTERNAL";
                $logs->save();

                Session::flash('message', 'User successfully deleted');
                return redirect('/admin/usereks');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/usereks');
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

}
