<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\STEL;
use App\Logs;
use App\Role;

use Auth;
use File;
use Response;
use Session;
use Input;

use Ramsey\Uuid\Uuid;
class RoleController extends Controller
{

    private const SEARCH = 'search';
    private const MESSAGE = 'message';
    private const ADMIN_ROLE = '/admin/role';
    private const ERROR = 'error';
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
            
            if ($search != null){
                $stels = Role::whereNotNull('created_at')
                    ->where('name','like','%'.$search.'%')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Role";
                    $datasearch = array(self::SEARCH=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->updated_by = $currentUser->id;
                    $logs->page = "Role";
                    $logs->save();
            }else{
                $query = Role::whereNotNull('created_at'); 
                
                $stels = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($stels) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.role.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $stels)
                ->with(self::SEARCH, $search) ;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.create');
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
		$role = new Role;
	    $role->id = Uuid::uuid4();
		$role->name = $request->input('name');
	  
		$role->created_by = $currentUser->id;
		$role->updated_by = $currentUser->id; 

		try{
			$role->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create Role";
            $logs->data = $role;
            $logs->created_by = $currentUser->id;
            $logs->updated_by = $currentUser->id;
            $logs->page = "Role";
            $logs->save();
			
            Session::flash(self::MESSAGE, 'role successfully created');
			return redirect(self::ADMIN_ROLE);
		} catch(Exception $e){
			Session::flash(self::ERROR, 'Save failed');
			return redirect('/admin/role/create');
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stel = Role::find($id);

        return view('admin.role.edit')
            ->with('data', $stel);
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

        $stel = Role::find($id);
        $oldStel = $stel; 

        if ($request->has('name')){
            $stel->name = $request->input('name');
        } 
        $stel->updated_by = $currentUser->id; 
        try{
            $stel->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update Role";
            $logs->data = $oldStel;
            $logs->created_by = $currentUser->id;
            $logs->updated_by = $currentUser->id;
            $logs->page = "Role";
            $logs->save();

            Session::flash(self::MESSAGE, 'Role successfully updated');
            return redirect(self::ADMIN_ROLE);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect('/admin/role/'.$stel->id.'/edit');
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
        $role = Role::find($id);
        $oldStel = $role;
        $currentUser = Auth::user();
        if ($role){
            try{
                $role->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Role";
                $logs->data = $oldStel;
                $logs->created_by = $currentUser->id;
                $logs->updated_by = $currentUser->id;
                $logs->page = "Role";
                $logs->save();

                Session::flash(self::MESSAGE, 'Role successfully deleted');
                return redirect(self::ADMIN_ROLE);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_ROLE);
            }
        }else{
             Session::flash(self::ERROR, 'Role Not Found');
                return redirect(self::ADMIN_ROLE);
        }
    }

    public function viewMedia($id)
    {
        $stel = STEL::find($id);

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file, $headers);
        }
    } 
}
