<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\DB;
use App\NotificationTable;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	public function __construct()
	{
        $query_footers = "SELECT * FROM footers WHERE is_active = 1";
		$data_footers = DB::select($query_footers);
            
		if (count($data_footers) == 0){
			$message_footers = "Data Not Found";
		}
			$currentUser = Auth::user(); 
			if ($currentUser){				
				// Sharing is caring
				View()->share('data_footers', $data_footers);

				$dataNotification = NotificationTable::where("is_read",0)->where("to",$currentUser->id)->orderBy("created_at","desc")->limit(10)->get();
				$countNotification = NotificationTable::where("is_read",0)->where("to",$currentUser->id)->orderBy("created_at","desc")->get()->count();
				View()->share('notification_data_user', $dataNotification->toArray());
				View()->share('notification_count', $countNotification);
			}
	}
}
