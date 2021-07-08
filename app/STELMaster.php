<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class STELMaster extends Model
{   
    protected $table = 'stels_master';
    public $incrementing = false;

    public function stels()
    {
        return $this->hasMany('App\STEL', 'stels_master_id')->orderBy('is_active', 'DESC')->orderBy('created_at', 'DESC')->orderBy('publish_date', 'DESC');
    }
}
