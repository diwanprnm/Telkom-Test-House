<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sidang_detail extends Model
{
    protected $table = "sidang_detail";
    public $incrementing = true;
    public $timestamps = true;
    public $guarded = [];

    public function sidang()
    {
        return $this->belongsTo('App\Sidang', 'sidang_id');
    }

    public function examination()
    {
        return $this->belongsTo('App\Examination', 'examination_id');
    }
}
