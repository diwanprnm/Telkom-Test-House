<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Questioner extends Model
{
    protected $table = "questioners";
	public $incrementing = false;
}
