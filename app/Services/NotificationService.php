<?php

namespace App\Services;

use App\Events\Notification;
use App\NotificationTable;

class NotificationSerice
{
    public function make($data)
    {
        $notification = new NotificationTable();
        $notification->id = Uuid::uuid4();
        $notification->from = $data['from'];
        $notification->to = $data['to'];
        $notification->message = $data['message'];
        $notification->url = $data['url'];
        $notification->is_read = $data['is_read'];
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->updated_at = date("Y-m-d H:i:s");
        $notification->save();
        
        return $notification->id; 
    }
}