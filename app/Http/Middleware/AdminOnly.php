<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


use App\Company;
use App\Role;
use App\User;
use App\Logs;
use App\Menu;
use App\UsersMenus;
 
use View;
use Session;
use Hash; 
use App\NotificationTable;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $string = explode("/", Route::getCurrentRoute()->getPath());
        $user_id = (!empty(auth()->user())) ? (auth()->user()->id) : '';
        $link = ($string[0] == 'admin' && !empty($string[1])) ? $string[1] : '';

        $select = array('menus.id','menus.url');
        $menu = Menu::selectRaw(implode(",", $select))->join("users_menus","menus.id","=","users_menus.menu_id")
                ->where("user_id",$user_id)
                ->where("menus.url",$link)
                ->get()->toArray();
        // dd($menu);
        $this->initTree();

        $free_link = array("","logout","user","downloadUsman","delete","downloadbukti","downloadstelwatermark","downloadkuitansistel","downloadfakturstel",
            "adm_exam_autocomplete","adm_exam_done_autocomplete","adm_dev_autocomplete","adm_feedback_autocomplete","adm_article_autocomplete","adm_stel_autocomplete",
            "adm_charge_autocomplete","adm_calibration_autocomplete","adm_slideshow_autocomplete","adm_labs_autocomplete","adm_company_autocomplete",
            "adm_temp_company_autocomplete","adm_user_autocomplete","adm_footer_autocomplete","adm_inc_autocomplete");

		if (auth()->check() && auth()->user()->role->id != 2) {
            if(count($menu)>0 or in_array($link, $free_link)){
                return $next($request);
            } else{
                return view('errors.401');
            }
        } elseif (Auth::guard($guard)->guest()) {
            return redirect()->guest('admin/login');
        }
        return redirect()->back();
    }

    public function initTree(){
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

        if($currentUser){
            $dataNotification = NotificationTable::where("is_read",0)
                            ->where(function($q) use ($currentUser){
                            return $q->where('to', "admin")
                                ->orWhere('to', $currentUser->id)
                                ;
                            })
                            ->orderBy("created_at","desc")
                            ->limit(10)->get()->toArray();

            $countNotification = NotificationTable::where("is_read",0)
                            ->where(function($q) use ($currentUser){
                            return $q->where('to', "admin")
                                ->orWhere('to', $currentUser->id)
                                ;
                            })
                            ->orderBy("created_at","desc")
                            ->get()->count();
        }else{
            $dataNotification = array();
            $countNotification = 0;
        }
       View::share('tree_menus', $tree);
       View::share('notification_data', $dataNotification);
       View::share('notification_count', $countNotification);
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
