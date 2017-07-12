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
                    ->where('user_name','like','%'.$search.'%')
					->orWhere('user_email','like','%'.$search.'%')
                    ->orderBy('user_name')
                    ->paginate($paginate);
            }else{
                $adminrole = Adminrole::whereNotNull('created_at')
					->orderBy('user_name')
                    ->paginate($paginate);
            }
            
            if (count($adminrole) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.privilege.index')
                ->with('message', $message)
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
			if (in_array("1", $request->input('check-privilege'))){$adminrole->registration_status = 1;}else{$adminrole->registration_status = 0;}
			if (in_array("2", $request->input('check-privilege'))){$adminrole->function_status = 1;}else{$adminrole->function_status = 0;}
			if (in_array("3", $request->input('check-privilege'))){$adminrole->contract_status = 1;}else{$adminrole->contract_status = 0;}
			if (in_array("4", $request->input('check-privilege'))){$adminrole->spb_status = 1;}else{$adminrole->spb_status = 0;}
			if (in_array("5", $request->input('check-privilege'))){$adminrole->payment_status = 1;}else{$adminrole->payment_status = 0;}
			if (in_array("6", $request->input('check-privilege'))){$adminrole->spk_status = 1;}else{$adminrole->spk_status = 0;}
			if (in_array("7", $request->input('check-privilege'))){$adminrole->examination_status = 1;}else{$adminrole->examination_status = 0;}
			if (in_array("8", $request->input('check-privilege'))){$adminrole->resume_status = 1;}else{$adminrole->resume_status = 0;}
			if (in_array("9", $request->input('check-privilege'))){$adminrole->qa_status = 1;}else{$adminrole->qa_status = 0;}
			if (in_array("10", $request->input('check-privilege'))){$adminrole->certificate_status = 1;}else{$adminrole->certificate_status = 0;}
			
			$adminrole->created_by = $currentUser->id;
			$adminrole->updated_by = $currentUser->id;

			try{
				$adminrole->save();
				Session::flash('message', 'User successfully created');
				return redirect('/admin/privilege');
			} catch(\Exception $e){
				Session::flash('error', 'Save failed');
				return redirect('/admin/privilege/create')
							->withInput();
			}
		}else{
			Session::flash('error', 'No Privilege selected');
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

			if (in_array("1", $request->input('check-privilege'))){$adminrole->registration_status = 1;}else{$adminrole->registration_status = 0;}
			if (in_array("2", $request->input('check-privilege'))){$adminrole->function_status = 1;}else{$adminrole->function_status = 0;}
			if (in_array("3", $request->input('check-privilege'))){$adminrole->contract_status = 1;}else{$adminrole->contract_status = 0;}
			if (in_array("4", $request->input('check-privilege'))){$adminrole->spb_status = 1;}else{$adminrole->spb_status = 0;}
			if (in_array("5", $request->input('check-privilege'))){$adminrole->payment_status = 1;}else{$adminrole->payment_status = 0;}
			if (in_array("6", $request->input('check-privilege'))){$adminrole->spk_status = 1;}else{$adminrole->spk_status = 0;}
			if (in_array("7", $request->input('check-privilege'))){$adminrole->examination_status = 1;}else{$adminrole->examination_status = 0;}
			if (in_array("8", $request->input('check-privilege'))){$adminrole->resume_status = 1;}else{$adminrole->resume_status = 0;}
			if (in_array("9", $request->input('check-privilege'))){$adminrole->qa_status = 1;}else{$adminrole->qa_status = 0;}
			if (in_array("10", $request->input('check-privilege'))){$adminrole->certificate_status = 1;}else{$adminrole->certificate_status = 0;}
			
			$adminrole->updated_by = $currentUser->id;

			try{
				$adminrole->save();
				Session::flash('message', 'Privilege successfully updated');
				return redirect('/admin/privilege');
			} catch(Exception $e){
				Session::flash('error', 'Save failed');
				return redirect('/admin/privilege/'.$adminrole->user_id.'edit');
			}
		}else{
			Session::flash('error', 'No Privilege selected');
				return redirect('/admin/privilege/'.$adminrole->user_id.'edit')
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
                
                Session::flash('message', 'Privilege successfully deleted');
                return redirect('/admin/privilege');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/privilege');
            }
        }
    }
}
