<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\Logs;
use App\GeneralSetting;

use Auth;
use File;
use Response;
use Session;
use Input;

use Ramsey\Uuid\Uuid;

class GeneralSettingController extends Controller
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
            if($currentUser->id == '1' || $currentUser->email == 'admin@mail.com'){
                $generalsetting = GeneralSetting::all();
                if (count($generalsetting) == 0){
                    $message = 'Data not found';
                }
                
                return view('admin.generalsetting.index')
                    ->with('message', $message)
                    ->with('data', $generalsetting);
            }else{
                return view('admin.generalsetting.index')
                    ->with('message', 'Access Denied')
                    ->with('data', null);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.generalsetting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->flash();
        $currentUser = Auth::user(); 
        $generalsetting = new GeneralSetting;
        $generalsetting->id = Uuid::uuid4();
        $generalsetting->question = $request->input('question');
        $generalsetting->answer = $request->input('answer');
      
        $generalsetting->created_by = $currentUser->id;
        $generalsetting->updated_by = $currentUser->id; 

        try{
            $generalsetting->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create GeneralSetting";
            $logs->data = $generalsetting;
            $logs->created_by = $currentUser->id;
            $logs->page = "GeneralSetting";
            $logs->save();
            
            Session::flash('message', 'FAQ successfully created');
            return redirect('/admin/generalsetting');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/generalsetting/create');
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
        $generalsetting = GeneralSetting::find($id);

        return view('admin.generalsetting.edit')
            ->with('data', $generalsetting);
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

        if ($request->has('is_poh')){
            $generalsetting = GeneralSetting::where("code", "poh_manager_urel")->first();
            $oldGeneralSetting = $generalsetting; 
            $generalsetting->value = $request->input('poh_manager_urel');
            $generalsetting->is_active = 1;
        }else{
            $generalsetting = GeneralSetting::where("code", "manager_urel")->first();
            $oldGeneralSetting = $generalsetting; 
            $generalsetting->value = $request->input('manager_urel');

            $generalsettingPOH = GeneralSetting::where("code", "poh_manager_urel")->first();
            $generalsettingPOH->is_active = 0;
            $generalsettingPOH->updated_by = $currentUser->id; 
            $generalsettingPOH->save();
        }

        $generalsetting->updated_by = $currentUser->id; 

        try{
            $generalsetting->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update General Setting";
            $logs->data = $oldGeneralSetting;
            $logs->created_by = $currentUser->id;
            $logs->page = "General Setting";
            $logs->save();

            Session::flash('message', 'General Setting successfully updated');
            return redirect('/admin/generalSetting');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/generalSetting');
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
        $generalsetting = GeneralSetting::find($id);
        $oldGeneralSetting = $generalsetting;
        $currentUser = Auth::user();
        if ($generalsetting){
            try{
                $generalsetting->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete GeneralSetting";
                $logs->data = $oldGeneralSetting;
                $logs->created_by = $currentUser->id;
                $logs->page = "GeneralSetting";
                $logs->save();

                Session::flash('message', 'FAQ successfully deleted');
                return redirect('/admin/generalsetting');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/generalsetting');
            }
        }else{
             Session::flash('error', 'Role Not Found');
                return redirect('/admin/generalsetting');
        }
    }    
}
