<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kuitansi extends Model
{
    protected $table = "kuitansi";
    
    public $incrementing = false;
}
