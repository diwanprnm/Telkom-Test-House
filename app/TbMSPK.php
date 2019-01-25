<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TbMSPK extends Model
{
    protected $table = "tb_m_spk";
    public $incrementing = false;
    public $timestamps = false;

    public function examination()
    {
        return $this->belongsTo('App\Examination');
    }

    public function SPKHistory()
    {
        return $this->hasMany('App\TbHSPK', 'ID');
    }
}
