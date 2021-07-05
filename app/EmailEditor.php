<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailEditor extends Model
{
    protected $table = "email_editors";
    
    public $incrementing = false;
}
