<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewExaminationChargeDetail extends Model
{
    protected $table = "new_examination_charges_detail";
    // public $incrementing = false;
	public $timestamps = false;
		
    public function newExaminationCharge()
    {
        return $this->belongsTo('App\NewExaminationCharge');
    }

    public function examinationCharge()
    {
        return $this->belongsTo('App\ExaminationCharge', 'examination_charges_id');
    }

}
