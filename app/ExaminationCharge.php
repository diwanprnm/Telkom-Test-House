<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExaminationCharge extends Model
{
    protected $table = "examination_charges";
    public $incrementing = false;
    
    public function newExaminationChargeDetail()
    {
        return $this->hasMany('App\newExaminationChargeDetail');
    }
}
