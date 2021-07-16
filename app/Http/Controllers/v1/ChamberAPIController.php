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
				'date' => '20210802'
			],
			[
				'id' => 2,
				'date' => '20210803'
			],
			[
				'id' => 3,
				'date' => '20210804'
			],
			[
				'id' => 4,
				'date' => '20210806'
			],
			[
				'id' => 5,
				'date' => '20210809'
			],
			[
				'id' => 6,
				'date' => '20210730'
			],
			[
				'id' => 7,
				'date' => '20210805'
			],
			[
				'id' => 8,
				'date' => '20210817'
			]
		];
		return response()->json($rentedDates);
    }
	 
}
