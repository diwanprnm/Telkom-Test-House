<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Slideshow extends Model
{
    protected $table = "slideshows";
    public $incrementing = false;
	
	static function autocomplet($query){
		return DB::table('slideshows')
				->select('title as autosuggest')
				->where('title', 'like','%'.$query.'%')
                ->orderBy('title')
                ->take(5)
				->distinct()
                ->get();
	}
}
