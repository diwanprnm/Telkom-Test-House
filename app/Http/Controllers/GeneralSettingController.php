<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Logs;
use App\LogsAdministrator;
use App\GeneralSetting;
use App\Services\Logs\LogService;
use App\Services\FileService;

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

    
    public function update(Request $request)
    {
        $currentUser = Auth::user();
        
        $generalsettingSendEmail = GeneralSetting::where("code", "send_email")->first();
        $generalsettingPOH = GeneralSetting::where("code", $this::MAN)->first();
        $generalsetting = GeneralSetting::where("code", "manager_urel")->first();
        $generalsettingPOHSM = GeneralSetting::where("code", "poh_sm_urel")->first();
        $generalsettingSM = GeneralSetting::where("code", "sm_urel")->first();

        $generalsettingSendEmail->is_active = 0;
        $generalsettingPOH->is_active = 0;
        $generalsettingPOHSM->is_active = 0;
        $oldGeneralSetting = $generalsetting; 
        $generalsetting->value = $request->input('manager_urel');
        $generalsettingSM->value = $request->input('sm_urel');
        if ($request->file('attachment_sm')) {
            $fileService = new FileService();
            $fileProperties = array(
                'path' => 'generalsettings/'.$generalsettingSM->id.'/'
            );
            $fileService->upload($request->file('attachment_sm'), $fileProperties);
            $generalsettingSM->attachment = $fileService->getFileName();
        }

        if ($request->has('is_send_email_active')){
            $oldGeneralSetting = $generalsettingSendEmail; 
            $generalsettingSendEmail->is_active = 1;
            $generalsettingSendEmail->updated_by = $currentUser->id;
        } 
        
        if ($request->has('is_poh')){
            $oldGeneralSetting = $generalsettingPOH; 
            $generalsettingPOH->value = $request->input('poh_manager_urel');
            $generalsettingPOH->is_active = 1;
            $generalsettingPOH->updated_by = $currentUser->id; 
        }
        
        if ($request->has('is_poh_sm')){
            $oldGeneralSetting = $generalsettingPOHSM; 
            $generalsettingPOHSM->value = $request->input('poh_sm_urel');
            $generalsettingPOHSM->is_active = 1;
            $generalsettingPOHSM->updated_by = $currentUser->id; 
            if ($request->file('attachment_poh_sm')) {
                $fileService = new FileService();
                $fileProperties = array(
                    'path' => 'generalsettings/'.$generalsettingPOHSM->id.'/'
                );
                $fileService->upload($request->file('attachment_poh_sm'), $fileProperties);
                $generalsettingPOHSM->attachment = $fileService->getFileName();
            }
        }
        
        $generalsettingPOHSM->save();
        $generalsettingPOH->save();
        $generalsettingSendEmail->save();
        $generalsetting->updated_by = $currentUser->id; 

        try{
            $generalsetting->save();
            $generalsettingSM->save();

            $logService = new LogService();
            $logService->createLog("Update General Setting","General Setting",$oldGeneralSetting );
            $logService->createAdminLog('Update Manager URel atau POH' , 'General Setting', $oldGeneralSetting, $request->input('keterangan') );

            Session::flash($this::MESSAGE, 'General Setting successfully updated');
            return redirect(self::GEN);
        } catch(Exception $e){ return redirect(self::GEN)->with($this::ERR, 'Save failed');
        }
    }
  
}
