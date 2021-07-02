<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class STEL extends Model
{   
    protected $table = 'stels';

    public function examinationLab()
    {
        return $this->belongsTo('App\ExaminationLab', 'type')->orderBy('name');
    }

    public function stelMaster()
    {
        return $this->belongsTo('App\STELMaster', 'stels_master_id');
    }
}
