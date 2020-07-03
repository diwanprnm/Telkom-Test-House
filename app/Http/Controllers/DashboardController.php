<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\ChangeLocale;

use App\Company;
use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\User;
use App\Logs;

use Auth;
use Response;

$search_v='search';
$reg_v='registration_status';
$spbstat_v='spb_status';
$paymstat_v='payment_status';
$comp_v='company';
$medi_v='media';
$device_v='device';
$stat_v='status';
$funcstat_v='function_status';
$contrstat_v='contract_status';
// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */




    public function index(Request $request)
    {
		$message = null;
        $paginate = 10;
        $search = trim($request->input($search_v));
        $type = '';
		$status = '';

        $examType = ExaminationType::all();

        $query = Examination::whereNotNull('created_at')
                            ->where(function($q){
                                $q->where($reg_v, 0)
                                    ->orWhere($reg_v, 1)
									->orWhere($reg_v, -1)
                                    ->orWhere($spbstat_v, 0)
									->orWhere($spbstat_v, -1)
                                    ->orWhere($spbstat_v, 1)
									->orWhere( $paymstat_v, -1);
                            })
                            ->where( $paymstat_v, 0)
                            ->with('user')
                            ->with($comp_v)
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with($medi_v)
                            ->with($device_v);

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas($device_v, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas($comp_v, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas('examinationLab', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhere('function_test_NO', 'like', '%'.strtolower($search).'%');
            });

            $currentUser = Auth::user();
            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Search Dashboard";  
            $dataSearch = array($search_v => $search);
            $logs->data = json_encode($dataSearch);
            $logs->created_by = $currentUser->id;
            $logs->page = "DASHBOARD";
            $logs->save();
        }

        if ($request->has('type')){
            $type = $request->get('type');
			if($request->input('type') != 'all'){
				$query->where('examination_type_id', $request->get('type'));
			}
        }

        if ($request->has($comp_v)){
            $query->whereHas($comp_v, function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get($comp_v)).'%');
            });
        }

        if ($request->has($device_v)){
            $query->whereHas($device_v, function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get($device_v)).'%');
            });
        }
		if ($request->has($stat_v)){
			switch ($request->get($stat_v)) {
				case 1:
					$query->where($reg_v, '!=', '1');
					$status = 1;
					break;
				case 2:
                    $query->where($reg_v, 1);
                    $query->where($funcstat_v, 1);
                    $query->where($contrstat_v, 1);
                    $query->where($spbstat_v, '!=', 1);
                    $status = 2;
                    break;
                case 3:
					$query->where($reg_v, 1);
                    $query->where($funcstat_v, 1);
                    $query->where($contrstat_v, 1);
                    $query->where($spbstat_v, 1);
                    $query->where( $paymstat_v, '!=', 1);
                    $query->whereHas($medi_v, function ($q) use ($request){
                        return $q->where('name', '=', 'File Pembayaran')
                                ->where('attachment', '=' ,'');
                    });
                    $status = 3;
                    break;
                case 4:
                    $query->where($reg_v, 1);
                    $query->where($funcstat_v, 1);
                    $query->where($contrstat_v, 1);
					$query->where($spbstat_v, 1);
					$query->where( $paymstat_v, '!=', 1);
                    $query->whereHas($medi_v, function ($q) use ($request){
                        return $q->where('name', '=', 'File Pembayaran')
                                ->where('attachment', '!=' ,'');
                    });
					$status = 4;
					break;
				default:
					$status = 'all';
					break;
			}
		}

        $data = $query->orderBy('created_at')
                    ->paginate($paginate);

        if (count($data) == 0){
            $message = 'Data not found';
        }
		
        return view('home')
            ->with('message', $message)
            ->with('data', $data)
            ->with('type', $examType)
            ->with($search_v, $search)
            ->with('filterType', $type)
			->with($stat_v, $status);
    }
	
	public function downloadUsman()
    {
		$file = public_path().'/media/usman/User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Admin].pdf';
		$headers = array(
		  'Content-Type: application/octet-stream',
		);

		return Response::download($file, 'User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Admin].pdf', $headers);
	}
	
	public function autocomplete($query) {
        $respons_result = Examination::adm_dashboard_autocomplet($query);
        return response($respons_result);
    }
}
