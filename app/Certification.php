<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Certification extends Model
{
    protected $table = "certifications";
    public $incrementing = false;
}
