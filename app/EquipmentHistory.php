<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentHistory extends Model
{
    protected $table = "equipment_histories";
    // public $incrementing = false;
	public $timestamps = false;
		
    public function equipment()
    {
        return $this->belongsTo('App\Equipment');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

}
