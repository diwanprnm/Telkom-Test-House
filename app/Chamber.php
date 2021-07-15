<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chamber extends Model
{
    //
    protected $table = "chamber";
    public $incrementing = false;
    public $timestamps = true;
    public $guarded = [];

    public function chamber_detail()
    {
        return $this->hasMany('App\Chamber_detail', 'chamber_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
