<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Response;

class ChamberAPIController extends AppBaseController
{
    
    public function getDateRented(Request $param)
    {
		$rentedDates = DB::table('chamber_detail')->select('chamber_id as id', 'date')->get();
		return response()->json($rentedDates);
    }
	 
}
