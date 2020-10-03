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
    private const GEN = '/admin/generalSetting';
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
        if (!$currentUser){ return redirect('login');}

        $message = null;
        if($currentUser->id == '1' || $currentUser->email == 'admin@mail.com'){
            $generalsetting = GeneralSetting::all();

            if (!count($generalsetting)){
                $message = 'Data not found';
            }
            
            return view('admin.generalsetting.index')
                ->with($this::MESSAGE, $message)
                ->with('data', $generalsetting);
        }
        else { return view('admin.generalsetting.index') ->with($this::MESSAGE, 'Access Denied') >with('data', null); }
        
    }

    
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
            $logService->createAdminLog('Update Manager URel atau POH' , 'General Setting', $oldGeneralSetting, $request->input('keterangan') );

            Session::flash($this::MESSAGE, 'General Setting successfully updated');
            return redirect(self::GEN);
        } catch(Exception $e){ return redirect(self::GEN)->with($this::ERR, 'Save failed');
        }
    }
  
}
