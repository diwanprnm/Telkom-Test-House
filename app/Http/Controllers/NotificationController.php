<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use App\Article;
use App\Logs;

use Auth;
use Session;
use Validator;
use Excel; 
use App\NotificationTable;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class NotificationController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth.admin');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    public function updateNotif(Request $request) {
        $data = NotificationTable::find($request->notif_id); 
        $respons_result = array();
        if($data){
            $data->is_read = 1;
            $data->update();    
            $respons_result['data'] = "";
            $respons_result['status'] = true;
            $respons_result['msg'] = "";
        }else{
            $respons_result['status'] = false;
            $respons_result['msg'] = "Data Tidak Ditemukan";
        }
        
        return response($respons_result);
    }
   
}
