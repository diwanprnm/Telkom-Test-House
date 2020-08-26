<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Slideshow extends Model
{
    protected $table = "slideshows";
    public $incrementing = false;
}
