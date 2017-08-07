<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationTable extends Model
{
    protected $table = "notification";
    public $incrementing = false;
    public $timestamps = false;
        
    public function NotificationTable()
    {
        return $this->belongsTo('App\NotificationTable');
    }

}
