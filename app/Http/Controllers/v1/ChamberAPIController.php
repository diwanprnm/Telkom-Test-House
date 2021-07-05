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
		//$users = DB::table('users')->get()->toJson();
		$rentedDates = [
			[
				'id' => 1,
				'date' => '20210701'
			],
			[
				'id' => 2,
				'date' => '20210702'
			],
			[
				'id' => 3,
				'date' => '20210709'
			],
			[
				'id' => 4,
				'date' => '20210722'
			]
		];
		return response()->json($rentedDates);
    }
	 
}
