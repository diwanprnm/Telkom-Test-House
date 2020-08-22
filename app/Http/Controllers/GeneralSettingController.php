<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\Logs;
use App\LogsAdministrator;
use App\GeneralSetting;
use App\Services\Logs\LogService;

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

    private const MESSAGE = 'message';
    private const GEN = '/admin/generalsetting';
    private const ERR = 'error';
    private const MAN = "poh_manager_urel";

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
                    ->with($this::MESSAGE, $message)
                    ->with('data', $generalsetting);
            }else{
                return view('admin.generalsetting.index')
                    ->with($this::MESSAGE, 'Access Denied')
                    ->with('data', null);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

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
            $generalsetting = GeneralSetting::where("code", $this::MAN)->first();
            $oldGeneralSetting = $generalsetting; 
            $generalsetting->value = $request->input('poh_manager_urel');
            $generalsetting->is_active = 1;
        }else{
            $generalsetting = GeneralSetting::where("code", "manager_urel")->first();
            $oldGeneralSetting = $generalsetting; 
            $generalsetting->value = $request->input('manager_urel');

            $generalsettingPOH = GeneralSetting::where("code", $this::MAN)->first();
            $generalsettingPOH->is_active = 0;
            $generalsettingPOH->updated_by = $currentUser->id; 
            $generalsettingPOH->save();
        }

        $generalsetting->updated_by = $currentUser->id; 

        try{
            $generalsetting->save();

           
            $logService = new LogService();
            $logService->createLog("Update General Setting","General Setting",$oldGeneralSetting );


            $logs_a = new LogsAdministrator;
            $logs_a->id = Uuid::uuid4();
            $logs_a->user_id = $currentUser->id;
            $logs_a->action = "Update Manager URel atau POH";
            $logs_a->page = "General Setting";
            $logs_a->reason = $request->input('keterangan');
            $logs_a->data = $oldGeneralSetting;
            $logs_a->save();

            Session::flash($this::MESSAGE, 'General Setting successfully updated');
            return redirect('/admin/generalSetting');
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
            return redirect('/admin/generalSetting');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
}
