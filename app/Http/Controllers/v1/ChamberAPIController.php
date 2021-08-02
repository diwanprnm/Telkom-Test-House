<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Response;

class ChamberAPIController extends AppBaseController
{
    
    public function getDateRented()
    {
      $rentedDates = DB::table('chamber_detail')
        ->join('chamber', 'chamber.id', '=', 'chamber_detail.chamber_id')
        ->select('chamber_id as id', 'date')
        ->whereIn('chamber.payment_status', [2,3])
        ->get()
      ;
      return response()->json($rentedDates);
    }
    
}
