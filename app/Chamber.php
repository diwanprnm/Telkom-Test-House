<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chamber extends Model
{
    //
    protected $table = "chamber";
    public $incrementing = false;
    public $timestamps = true;
    public $guarded = [];
}
