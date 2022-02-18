<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = "devices";
    
    public $incrementing = false;

    public function examination()
    {
        return $this->hasMany('App\Examination', 'device_id');
    }
}
