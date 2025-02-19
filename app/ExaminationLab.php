<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExaminationLab extends Model
{
    protected $table = "examination_labs";
    public $incrementing = false;


    public function examinationCharge()
    {
        return $this->hasMany('App\ExaminationCharge', 'category');
    }
    
}
