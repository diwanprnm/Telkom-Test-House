<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chamber_detail extends Model
{
    protected $table = "chamber_detail";
    public $incrementing = true;
    public $timestamps = true;
    public $guarded = [];
}
