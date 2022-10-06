<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use App\Logs;
use App\ExaminationLab;

use App\CalibrationCharge;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CalibrationChargeClientController extends Controller
{

    private const CREATED_AT = 'created_at';
    private const DEVICE_NAME = 'device_name';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            
            $query = CalibrationCharge::whereNotNull(self::CREATED_AT)->where('is_active', 1);

            if ($search != null){
                $query = $query->where(self::DEVICE_NAME,'like','%'.$search.'%');
            }

			$examinationCharge = $query->orderByRaw('device_name')
                    ->paginate($paginate);
            
            if (count($examinationCharge) == 0){
                $message = 'Data not found';
            }
            $page = "Chargeclient";
            return view('client.calibration_charge.index')
                ->with('message', $message)
                ->with('data', $examinationCharge)
                ->with('search', $search)
                ->with('page', $page)
            ;
    }
}
