<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AdminRole;
use App\User;

use Auth;
use Session;

class PrivilegeController extends Controller
{


    private const USER_NAME = 'username';
    private const MESSAGE = 'message';
    private const ERROR = 'error';
    private const CHECK_PRIVILEGE = 'check-privilege';
    private const ADMIN_PRIVILEGE = '/admin/privilege';
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

            if ($search != null){
                $adminrole = Adminrole::whereNotNull('created_at')
                    ->where(self::USER_NAME,'like','%'.$search.'%')
					->orWhere('user_email','like','%'.$search.'%')
                    ->orderBy(self::USER_NAME)
                    ->paginate($paginate);
            }else{
                $adminrole = Adminrole::whereNotNull('created_at')
					->orderBy(self::USER_NAME)
                    ->paginate($paginate);
            }
            
            if (count($adminrole) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.privilege.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $adminrole)
				->with('search', $search);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::where('role_id','=','1')->get();

        return view('admin.privilege.create')
            ->with('user', $user);
    }

    private function setAdminRole($adminrole,$request){ 
        $adminrole->registration_status = 0;
        $adminrole->function_status = 0;
        $adminrole->contract_status = 0;
        $adminrole->spb_status = 0;
        $adminrole->payment_status = 0;
        $adminrole->spk_status = 0;
        $adminrole->examination_status = 0;
        $adminrole->resume_status = 0;
        $adminrole->qa_status = 0;
        $adminrole->certificate_status = 0;
        switch ($request->input(self::CHECK_PRIVILEGE)) {
            case in_array("1", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->registration_status = 1;
                break;
            case in_array("2", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->function_status = 1;
                break;
            case in_array("3", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->contract_status = 1;
                break;
            case in_array("4", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->spb_status = 1;
                break;
            case in_array("5", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->payment_status = 1;
                break;
            case in_array("6", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->spk_status = 1;
                break;
            case in_array("7", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->examination_status = 1;
                break;
            case in_array("8", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->resume_status = 1;
                break;
            case in_array("9", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->qa_status = 1;
                break;
            case in_array("10", $request->input(self::CHECK_PRIVILEGE)):
                $adminrole->certificate_status = 1;
                break; 
            default: 
                break;
        } 

        return $adminrole
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if(count($request->input('check-privilege')) > 0)
		{
			$currentUser = Auth::user();
			$user = User::find($request->input('user_id'));

			$adminrole = new Adminrole;
			$adminrole->user_id = $user->id;
			$adminrole->user_name = $user->name;
			$adminrole->user_email = $user->email;
			
            $this->setAdminRole($adminrole,$request);
			
			$adminrole->created_by = $currentUser->id;
			$adminrole->updated_by = $currentUser->id;

			try{
				$adminrole->save();
				Session::flash(self::MESSAGE, 'User successfully created');
				return redirect(self::ADMIN_PRIVILEGE);
			} catch(\Exception $e){
				Session::flash(self::ERROR, 'Save failed');
				return redirect('/admin/privilege/create')
							->withInput();
			}
		}else{
			Session::flash(self::ERROR, 'No Privilege selected');
				return redirect('/admin/privilege/create')
							->withInput();
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
        $adminrole = Adminrole::find($id);

        return view('admin.profile.edit')
            ->with('data', $adminrole);   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $adminrole = Adminrole::find($id);

        return view('admin.privilege.edit')
		->with('data', $adminrole);
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
		if(count($request->input('check-privilege')) > 0)
		{
			$currentUser = Auth::user();

			$adminrole = Adminrole::find($id);

			$this->setAdminRole($adminrole,$request);
			
			$adminrole->updated_by = $currentUser->id;

			try{
				$adminrole->save();
				Session::flash(self::MESSAGE, 'Privilege successfully updated');
				return redirect(self::ADMIN_PRIVILEGE);
			} catch(Exception $e){
				Session::flash(self::ERROR, 'Save failed');
				return redirect(self::ADMIN_PRIVILEGE.$adminrole->user_id.'edit');
			}
		}else{
			Session::flash(self::ERROR, 'No Privilege selected');
				return redirect(self::ADMIN_PRIVILEGE.$adminrole->user_id.'edit')
							->withInput();
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
		$adminrole = Adminrole::find($id);

        if ($adminrole){
            try{
                $adminrole->delete();
                
                Session::flash(self::MESSAGE, 'Privilege successfully deleted');
                return redirect(self::ADMIN_PRIVILEGE);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_PRIVILEGE);
            }
        }
    }
}
