<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = "testimonial";

    public $incrementing = false;
    	
    public function examination()
    {
        return $this->belongsTo('App\Examination');
    }

}