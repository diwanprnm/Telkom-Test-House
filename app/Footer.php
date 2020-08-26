<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Footer extends Model
{
    protected $table = "footers";
    public $incrementing = false;
}
