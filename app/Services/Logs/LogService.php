<?php

namespace App\Services\Logs;

use Auth;
use App\Logs;
use App\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class LogService
{

    public function createLog($action = '', $page = '',$search = '' )
    {
        $dataSearch = array("search"=>$search);
        $currentUser = Auth::user();

        $logs = new Logs;
        $logs->id = Uuid::uuid4();
        $logs->user_id = $currentUser->id;
        $logs->action = $action;
        $logs->data = json_encode($dataSearch);
        $logs->created_by = $currentUser->id;
        $logs->page = $page;
        $logs->save();
    }


}