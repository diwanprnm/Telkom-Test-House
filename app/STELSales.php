<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class STELSales extends Model
{
    protected $table = "stels_sales";

    public function sales_detail()
    {
        return $this->hasMany('App\STELSalesDetail', 'stels_sales_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
