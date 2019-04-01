<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


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

        $this->initTree();

		if (auth()->check() && auth()->user()->role->id != 2) { 
            if (auth()->user()->is_active == 1 && auth()->user()->is_deleted == 0) { 
                return $next($request);
            }else{
                return redirect('admin/login')
                ->withInput($request->only($this->email())
                ->withErrors('email');
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
