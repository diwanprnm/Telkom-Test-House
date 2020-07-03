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
    private const SEARCH = 'search';
    private const REG = 'registration_status';
    private const SPB = 'spb_status';
    private const PAYSTAT = 'payment_status';
    private const COMP = 'company';
    private const MEDIA = 'media';
    private const DEVICE = 'device';
    private const STAT = 'status';
    private const FUNCSTAT = 'function_status';
    private const CONTRSTAT = 'contract_status';
  //  $search_v='search';
//$reg_v='registration_status';
//SPB='spb_status';
//PAYSTAT='payment_status';
//COMP='company';
//MEDIA='media';
//$device_v='device';
//STAT='status';
//FUNCSTAT='function_status';
//CONTRSTAT='contract_status';
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
        $search = trim($request->input($this::SEARCH));
        $type = '';
		$status = '';

        $examType = ExaminationType::all();

        $query = Examination::whereNotNull('created_at')
                            ->where(function($q){
                                $q->where($this::REG, 0)
                                    ->orWhere($this::REG, 1)
									->orWhere($this::REG, -1)
                                    ->orWhere($this::SPB, 0)
									->orWhere($this::SPB, -1)
                                    ->orWhere($this::SPB, 1)
									->orWhere($this::PAYSTAT, -1);
                            })
                            ->where($this::PAYSTAT, 0)
                            ->with('user')
                            ->with($this::COMP)
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with($this::MEDIA)
                            ->with($this::DEVICE);

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas($this::DEVICE, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas(COMP, function ($q) use ($search){
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
            $dataSearch = array($this::SEARCH => $search);
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

        if ($request->has($this::COMP)){
            $query->whereHas(COMP, function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get(COMP)).'%');
            });
        }

        if ($request->has($this::DEVICE)){
            $query->whereHas($this::DEVICE, function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get(DEVICE)).'%');
            });
        }
		if ($request->has($this::STAT)){
			switch ($request->get($this::STAT)) {
				case 1:
					$query->where($this::REG, '!=', '1');
					$status = 1;
					break;
				case 2:
                    $query->where($this::REG, 1);
                    $query->where($this::FUNCSTAT, 1);
                    $query->where($this::CONTRSTAT, 1);
                    $query->where($this::SPB, '!=', 1);
                    $status = 2;
                    break;
                case 3:
					$query->where($this::REG, 1);
                    $query->where($this::FUNCSTAT, 1);
                    $query->where($this::CONTRSTAT, 1);
                    $query->where($this::SPB, 1);
                    $query->where($this::PAYSTAT, '!=', 1);
                    $query->whereHas($this::MEDIA, function ($q) use ($request){
                        return $q->where('name', '=', 'File Pembayaran')
                                ->where('attachment', '=' ,'');
                    });
                    $status = 3;
                    break;
                case 4:
                    $query->where($this::REG, 1);
                    $query->where($this::FUNCSTAT, 1);
                    $query->where($this::CONTRSTAT, 1);
					$query->where($this::SPB, 1);
					$query->where($this::PAYSTAT, '!=', 1);
                    $query->whereHas($this::MEDIA, function ($q) use ($request){
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
            ->with($this::SEARCH, $search)
            ->with('filterType', $type)
			->with($this::STAT, $status);
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
