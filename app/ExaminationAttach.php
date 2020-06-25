<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExaminationAttach extends Model
{
    protected $table = "examination_attachments";
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
