<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    //
    protected $table = "sidang";
    public $incrementing = false;
    public $timestamps = true;
    public $guarded = [];

    public function sidang_detail()
    {
        return $this->hasMany('App\Sidang_detail', 'id_sidang');
    }
}
