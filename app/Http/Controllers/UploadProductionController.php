<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;


use App\Article;
use App\Logs;
use App\Slideshow;
use App\TempCompany;
use App\LogsAdministrator;

use Auth;
use Session;
use Validator;
use Excel;
use App\Services\Logs\LogService; 

class UploadProductionController extends Controller
{
    private const PROD_URL = 'https://telkomtesthouse.co.id/media/';
    
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
     ini_set('memory_limit','-1');
     // $this->uploadTempCompany();   
        
    } 

    public function uploadSlideShow()
    {
        $data = Slideshow::all();  
        foreach($data as $value){
            $name_file = $value->image;
            $file = file_get_contents(self::PROD_URL.'slideshow/'.rawurlencode($name_file));  
            \Storage::disk('minio')->put("slideshow/".$name_file, $file);
        }
    }
    public function uploadTempCompany()
    {
        $data = TempCompany::all();  
        foreach($data as $value){
            $npwp_file = $value->npwp_file;
            if(!empty($npwp_file)){
                $file = file_get_contents(self::PROD_URL."tempCompany/".$value->company_id."/".$value->id."/".rawurlencode($npwp_file));  
                \Storage::disk('minio')->put("tempCompany/".$value->company_id."/".$value->id."/".$npwp_file, $file); 
            }
            $qs_certificate_file = $value->qs_certificate_file;
            if(!empty($qs_certificate_file)){
                $file = file_get_contents(self::PROD_URL."tempCompany/".$value->company_id."/".$value->id."/".rawurlencode($qs_certificate_file));  
                \Storage::disk('minio')->put("tempCompany/".$value->company_id."/".$value->id."/".$qs_certificate_file, $file); 
            }
            $siup_file = $value->siup_file;
            if(!empty($siup_file)){
                $file = file_get_contents(self::PROD_URL."tempCompany/".$value->company_id."/".$value->id."/".rawurlencode($siup_file));  
                \Storage::disk('minio')->put("tempCompany/".$value->company_id."/".$value->id."/".$siup_file, $file); 
            }
            
        }
    }
}
