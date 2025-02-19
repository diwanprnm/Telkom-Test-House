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

use App\Services\Logs\LogService;

use Auth;
use Response;
use Storage;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class DashboardController extends Controller
{
    private const SEARCH = 'search';
    private const REG = 'registration_status';
    private const SPB = 'spb_status';
    private const COMP = 'company';
    private const MEDIA = 'media';
    private const DEVICE = 'device';
    private const STAT = 'status';
    private const FUNCSTAT = 'function_status';
    private const CONTRSTAT = 'contract_status';
    private const TABLE_DEVICE = 'devices';
	private const EXAM_DEVICES_ID = 'examinations.device_id';
	private const DEVICES_ID = 'devices.id';
    private const DEVICE_NAME_AUTOSUGGEST = 'devices.name as autosuggest';
    private const EXAM_REGISTRATION_STATUS = 'examinations.registration_status';
	private const EXAM_SPB_STATUS = 'examinations.spb_status';
    private const EXAM_PAYMENT_STATUS = 'examinations.payment_status';
    private const PAYMENT_STATUS = 'payment_status';
    private const DEVICE_NAME = 'devices.name';
    private const FILE_PEMBAYARAN = 'File Pembayaran';
    private const ATTACHMENT = 'attachment';

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

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
                    ->orWhere($this::PAYMENT_STATUS, -1);
            })
            ->where($this::PAYMENT_STATUS, 0)
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
                ->orWhereHas(self::COMP, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas('examinationLab', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhere('function_test_NO', 'like', '%'.strtolower($search).'%');
            });

            $logService = new LogService();
            $logService->createLog("Search Dashboard","DASHBOARD", json_encode( array(self::SEARCH=>$search)) );
        }

        if ($request->has('type')){
            $type = $request->get('type');
			if($request->input('type') != 'all'){
				$query->where('examination_type_id', $request->get('type'));
			}
        }

		if ($request->has($this::STAT)){
           
			switch ($request->get($this::STAT)) {
				case 1:
					$query->where($this::REG, '!=', '1');
					$status = 1;
					break;
				case 2:
                    $query->where($this::REG, 1)
                        ->where($this::FUNCSTAT, 1)
                        ->where($this::CONTRSTAT, 1)
                        ->where($this::SPB, '!=', 1);
                    $status = 2;
                    break;
                case 3:
                    $query->where('registration_status', 1);
                    $query->where('function_status', 1);
                    $query->where('contract_status', 1);
                    $query->where('spb_status', 1);
                    $query->where($this::PAYMENT_STATUS, '!=', 1);
                    $query->whereHas('media', function ($q) {
                        return $q->where('name', '=', $this::FILE_PEMBAYARAN)
                                ->where($this::ATTACHMENT, '=' ,'');
                    });
                    $status = 3;
                    break;
                case 4:
                    $query->where($this::REG, 1)
                        ->where($this::FUNCSTAT, 1)
                        ->where($this::CONTRSTAT, 1)
                        ->where($this::SPB, 1)
                        ->where($this::PAYMENT_STATUS, '!=', 1)
                        ->whereHas($this::MEDIA, function ($q) use ($request) {
                       return $request->get($this::STAT) == 3 ? $q->where('name', '=', $this::FILE_PEMBAYARAN)->where($this::ATTACHMENT, '=' ,'') : $q->where('name', '=', $this::FILE_PEMBAYARAN)->where($this::ATTACHMENT, '!=' ,'');
                    });
					$status = $request->get($this::STAT) == 3 ? $request->get($this::STAT) : 4;
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
        $fileName = 'User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Admin].pdf';
        $fileMinio = Storage::disk('minio')->get("usman/$fileName");
        return response($fileMinio, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
	}
	
	public function autocomplete($query) {
        return Examination::join(self::TABLE_DEVICE, self::EXAM_DEVICES_ID, '=', self::DEVICES_ID)
                ->select(self::DEVICE_NAME_AUTOSUGGEST)
				->where(function($q){
					$q->where(self::EXAM_REGISTRATION_STATUS, 0)
						->orWhere(self::EXAM_REGISTRATION_STATUS, 1)
						->orWhere(self::EXAM_REGISTRATION_STATUS, -1)
						->orWhere(self::EXAM_SPB_STATUS, 0)
						->orWhere(self::EXAM_SPB_STATUS, -1)
						->orWhere(self::EXAM_SPB_STATUS, 1)
						->orWhere(self::EXAM_PAYMENT_STATUS, -1);
				})
				->where(self::PAYMENT_STATUS, 0)
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(5)
				->distinct()
                ->get(); 
    }
}
