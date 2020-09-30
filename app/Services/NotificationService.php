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
            $notification->created_by = isset($data['created_by']) ? $data['created_by'] : Auth::user()->id;
            $notification->updated_by = isset($data['updated_by']) ? $data['updated_by'] : Auth::user()->id;
            $notification->created_at = date("Y-m-d H:i:s");
            $notification->updated_at = date("Y-m-d H:i:s");
            $notification->save();
            
            return $notification->id;
        }catch(Exception $e){
            return null;
        }
    }
}