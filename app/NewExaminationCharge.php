<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewExaminationCharge extends Model
{
    protected $table = "new_examination_charges";

    public $incrementing = false;

    public function newExaminationChargeDetail()
    {
        return $this->hasMany('App\NewExaminationChargeDetail', 'new_exam_charges_id');
    }

    public function examinationLab()
    {
        return $this->belongsTo('App\ExaminationLab', 'category');
    }
}
