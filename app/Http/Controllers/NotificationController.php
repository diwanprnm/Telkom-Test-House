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


    private const CREATED_AT = 'created_at';
    private const IS_READ = 'is_read';
    private const MENUS_ID = 'menus.id';
    private const ADMIN = 'admin';
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 

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
			$dataNotificationH = NotificationTable::where(self::IS_READ,0)->where("to",$currentUser->id)->orderBy(self::CREATED_AT,"desc")->limit(10)->get();
			$countNotificationH = NotificationTable::where(self::IS_READ,0)->where("to",$currentUser->id)->orderBy(self::CREATED_AT,"desc")->get()->count();
			
			$dataNotification = NotificationTable::where("to",$currentUser->id)->orderBy(self::IS_READ)->orderBy(self::CREATED_AT,"desc")->get();
			$countNotification = NotificationTable::where("to",$currentUser->id)->orderBy(self::IS_READ)->orderBy(self::CREATED_AT,"desc")->get()->count(); 
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
            $select = array(self::MENUS_ID,'menus.name','menus.url','menus.parent_id','menus.icon');
            $menu = Menu::selectRaw(implode(",", $select))->join("users_menus",self::MENUS_ID,"=","users_menus.menu_id")
                    ->where("user_id",$currentUser['id'])
                    ->get()->toArray();

            $parentMenu = Menu::selectRaw(implode(",", $select))->join("users_menus",self::MENUS_ID,"=","users_menus.menu_id")
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
        
        foreach ($parentMenu as $value) {
            $tree[] = $this->createTree($new, array($value));
        } 
		
        if ($currentUser){
			$dataNotificationH = NotificationTable::where(self::IS_READ,0)
                            ->where(function($q) use ($currentUser){
                            return $q->where('to', self::ADMIN)
                                ->orWhere('to', $currentUser->id)
                                ;
                            })
                            ->orderBy(self::CREATED_AT,"desc")
                            ->limit(10)->get()->toArray();

            $countNotificationH = NotificationTable::where(self::IS_READ,0)
                            ->where(function($q) use ($currentUser){
                            return $q->where('to', self::ADMIN)
                                ->orWhere('to', $currentUser->id)
                                ;
                            })
                            ->orderBy(self::CREATED_AT,"desc")
                            ->get()->count();
							
			$dataNotification = NotificationTable::where("to",self::ADMIN)->orWhere("to",$currentUser->id)->orderBy(self::IS_READ)->orderBy(self::CREATED_AT,"desc")->get();
			$countNotification = NotificationTable::where("to",self::ADMIN)->orWhere("to",$currentUser->id)->orderBy(self::IS_READ)->orderBy(self::CREATED_AT,"desc")->get()->count(); 
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
        $tree = [];
        foreach ($parent as $l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        } 
        return $tree;
    }
   
}
