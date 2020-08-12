<?php

namespace App\Services\Logs;

use Auth;
use App\Logs;
use App\User;
use App\LogsAdministrator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class LogService
{

    public function createLog($action = '', $page = '',$data = '' )
    {
        $currentUser = Auth::user();

        $logs = new Logs;
        $logs->id = Uuid::uuid4();
        $logs->user_id = $currentUser->id;
        $logs->action = $action;
        $logs->data = $data;
        $logs->created_by = $currentUser->id;
        $logs->updated_by = $currentUser->id;
        $logs->page = $page;
        $logs->save();
    }

    public function createAdminLog($action = '', $page = '',$data = '', $reason = '' )
    {
        $currentUser = Auth::user();
        
        $logs = new LogsAdministrator;
        $logs->id = Uuid::uuid4();
        $logs->user_id = $currentUser->id;
        $logs->action = $action;
        $logs->page = $page;
        $logs->reason = $reason;
        $logs->data = $data;
        $logs->save();
    }


}