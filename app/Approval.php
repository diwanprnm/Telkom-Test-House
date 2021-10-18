<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    //
    protected $table = "approval";
    public $incrementing = false;
    public $timestamps = true;
    public $guarded = [];

    public function approveBy()
    {
        return $this->hasMany('App\ApproveBy', 'approval_id');
    }

    public function authentikasi()
    {
        return $this->belongsTo('App\AuthentikasiEditor', 'autentikasi_editor_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
