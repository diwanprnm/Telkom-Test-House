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
        $search = trim($request->input('search'));
        $type = '';
		$status = '';

        $examType = ExaminationType::all();

        $query = Examination::whereNotNull('created_at')
                            ->where(function($q){
                                $q->where('registration_status', 0)
                                    ->orWhere('registration_status', 1)
									->orWhere('registration_status', -1)
                                    ->orWhere('spb_status', 0)
									->orWhere('spb_status', -1)
                                    ->orWhere('spb_status', 1)
									->orWhere('payment_status', -1);
                            })
                            ->where('payment_status', 0)
                            ->with('user')
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('media')
                            ->with('device');

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas('device', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas('company', function ($q) use ($search){
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
            $dataSearch = array('search' => $search);
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

        if ($request->has('company')){
            $query->whereHas('company', function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get('company')).'%');
            });
        }

        if ($request->has('device')){
            $query->whereHas('device', function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get('device')).'%');
            });
        }
		if ($request->has('status')){
			switch ($request->get('status')) {
				case 1:
					$query->where('registration_status', '!=', '1');
					$status = 1;
					break;
				case 2:
                    $query->where('registration_status', 1);
                    $query->where('function_status', 1);
                    $query->where('contract_status', 1);
                    $query->where('spb_status', '!=', 1);
                    $status = 2;
                    break;
                case 3:
					$query->where('registration_status', 1);
                    $query->where('function_status', 1);
                    $query->where('contract_status', 1);
                    $query->where('spb_status', 1);
                    $query->where('payment_status', '!=', 1);
                    $query->whereHas('media', function ($q) use ($request){
                        return $q->where('name', '=', 'File Pembayaran')
                                ->where('attachment', '=' ,'');
                    });
                    $status = 3;
                    break;
                case 4:
                    $query->where('registration_status', 1);
                    $query->where('function_status', 1);
                    $query->where('contract_status', 1);
					$query->where('spb_status', 1);
					$query->where('payment_status', '!=', 1);
                    $query->whereHas('media', function ($q) use ($request){
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
            ->with('search', $search)
            ->with('filterType', $type)
			->with('status', $status);
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
