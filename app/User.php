<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    protected $table = "users";

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'picture',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
	
	static function autocomplet($query){
		return DB::table('users')
				->select('name as autosuggest')
				->where('name', 'like','%'.$query.'%')
                ->orderBy('name')
                ->take(5)
				->distinct()
                ->get(); 
	}
}
