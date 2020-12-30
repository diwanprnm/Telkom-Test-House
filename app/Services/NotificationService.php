<?php

namespace App\Services;

use App\Events\Notification;
use App\NotificationTable;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use Auth;

class NotificationService
{
    public function make($data)
    {
        try {
            $notification = new NotificationTable();
            $notification->id = Uuid::uuid4();
            $notification->from = $data['from'];
            $notification->to = $data['to'];
            $notification->message = $data['message'];
            $notification->url = $data['url'];
            $notification->is_read = $data['is_read'];
            if(isset($data['created_by'])){
                $notification->created_by = $data['created_by'];
            }elseif(Auth::user()){
                $notification->created_by = Auth::user()->id;
            }else{
                $notification->created_by = '';
            }
            if(isset($data['updated_by'])){
                $notification->updated_by = $data['updated_by'];
            }elseif(Auth::user()){
                $notification->updated_by = Auth::user()->id;
            }else{
                $notification->updated_by = '';
            }
            $notification->created_at = date("Y-m-d H:i:s");
            $notification->updated_at = date("Y-m-d H:i:s");
            $notification->save();
            
            return $notification->id;
        }catch(Exception $e){
            return null;
        }
    }
}