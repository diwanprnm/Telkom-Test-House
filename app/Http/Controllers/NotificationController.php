<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use App\Article;
use App\Logs;
use App\Menu;
use App\UsersMenus;

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
        $currentUser = Auth::user();

        if ($currentUser){
			$dataNotificationH = NotificationTable::where("is_read",0)->where("to",$currentUser->id)->orderBy("created_at","desc")->limit(10)->get();
			$countNotificationH = NotificationTable::where("is_read",0)->where("to",$currentUser->id)->orderBy("created_at","desc")->get()->count();
			
			$dataNotification = NotificationTable::where("to",$currentUser->id)->orderBy("is_read")->orderBy("created_at","desc")->get();
			$countNotification = NotificationTable::where("to",$currentUser->id)->orderBy("is_read")->orderBy("created_at","desc")->get()->count();
			// View()->share('notification_data_user', $dataNotification->toArray());
			// View()->share('notification_count', $countNotification);
			return view('client.notification')
				->with('notification_data_user', $dataNotificationH)
				->with('notification_count', $countNotificationH)
				
				->with('notification_data', $dataNotification)
				->with('notification_data_count', $countNotification)
			;
        }
    }

	public function indexAdmin(Request $request)
    {
        $currentUser = Auth::user();
		if($currentUser){
            $select = array('menus.id','menus.name','menus.url','menus.parent_id','menus.icon');
            $menu = Menu::selectRaw(implode(",", $select))->join("users_menus","menus.id","=","users_menus.menu_id")
                    ->where("user_id",$currentUser['id'])
                    ->get()->toArray();

            $parentMenu = Menu::selectRaw(implode(",", $select))->join("users_menus","menus.id","=","users_menus.menu_id")
                    ->where("parent_id",0)
                    ->where("user_id",$currentUser['id'])
                    ->get()->toArray();
        }else{
             $menu = Menu::get()->toArray();

             $parentMenu = Menu::get()->toArray();
        }
        

        $new = array(); 
        foreach ($menu as $a){
            $new[$a['parent_id']][] = $a;
        }
        $tree = array();
        
        foreach ($parentMenu as $key => $value) {
            $tree[] = $this->createTree($new, array($value));
        } 
		
        if ($currentUser){
			$dataNotificationH = NotificationTable::where("is_read",0)
                            ->where(function($q) use ($currentUser){
                            return $q->where('to', "admin")
                                ->orWhere('to', $currentUser->id)
                                ;
                            })
                            ->orderBy("created_at","desc")
                            ->limit(10)->get()->toArray();

            $countNotificationH = NotificationTable::where("is_read",0)
                            ->where(function($q) use ($currentUser){
                            return $q->where('to', "admin")
                                ->orWhere('to', $currentUser->id)
                                ;
                            })
                            ->orderBy("created_at","desc")
                            ->get()->count();
							
			$dataNotification = NotificationTable::where("to","admin")->orWhere("to",$currentUser->id)->orderBy("is_read")->orderBy("created_at","desc")->get();
			$countNotification = NotificationTable::where("to","admin")->orWhere("to",$currentUser->id)->orderBy("is_read")->orderBy("created_at","desc")->get()->count();
			// View()->share('notification_data_user', $dataNotification->toArray());
			// View()->share('notification_count', $countNotification);
			return view('admin.notification')
				->with('tree_menus', $tree)
				
				->with('notification_data', $dataNotificationH)
				->with('notification_count', $countNotificationH)
				
				->with('notification_data_admin', $dataNotification)
				->with('notification_data_admin_count', $countNotification)
			;
        }
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
	
	public function createTree(&$list, $parent){

        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        } 
        return $tree;
    }
   
}
