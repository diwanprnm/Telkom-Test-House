<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApproveBy extends Model
{
    //
    protected $table = "approve_by";
    public $incrementing = false;
    public $timestamps = true;
    public $guarded = [];

    public function approval()
    {
        return $this->belongsTo('App\Approval', 'approval_id');
    }
}
