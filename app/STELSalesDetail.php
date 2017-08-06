<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class STELSalesDetail extends Model
{
    protected $table = "stels_sales_detail";

    public function stel()
    {
        return $this->belongsTo('App\STEL', 'stels_id');
    }
}
