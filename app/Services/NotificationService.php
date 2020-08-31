<?php

namespace App\Services;

use App\Events\Notification;
use App\NotificationTable;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class NotificationService
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
        $notification->created_by = $data['created_by'];
        $notification->updated_by = $data['updated_by'];
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->updated_at = date("Y-m-d H:i:s");
        $notification->save();
        
        return $notification->id;
    }
}