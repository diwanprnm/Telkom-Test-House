<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalibrationCharge extends Model
{
    protected $table = "calibration_charges";
    public $incrementing = false;
}
