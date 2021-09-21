<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Sidang;
use App\Sidang_detail;
use App\Examination;
use App\ExaminationAttach;
use App\Device;
use App\User;

use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;
use App\Services\NotificationService;
use App\Services\SalesService;
use App\Services\FileService;
use App\Services\ExaminationService;

use Auth;
use Session; 
use Validator;
use Excel;
use Response;
use Storage;
use File;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable;

use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SidangController extends Controller
{   
    private const ADMIN_SIDANG = '/admin/sidang';
    private const ERROR = 'error';
    private const LOGIN = 'login';
    private const MESSAGE = 'message';
    
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $message = null;
        $paginate = 100;
        $tab = $request->input('tab');
        $search = $request->input('search2');
        $before = $request->input('before');
        $after = $request->input('after');

        $data_perangkat = $this->getData($request, 0)['data']->paginate($paginate, ['*'], 'pagePerangkat');
        $this->mergeOTR($data_perangkat->items(), 'perangkat');
        $data_pending = $this->getData($request, 2)['data']->paginate($paginate, ['*'], 'pagePending');
        $this->mergeOTR($data_pending->items(), 'perangkat');
        
        //return view to saves index with data
        return view('admin.sidang.index')
            ->with('tab', $tab)
            ->with('data_sidang', $this->getData($request, 1)['data']->paginate($paginate, ['*'], 'pageSidang') )
            ->with('data_perangkat', $data_perangkat)
            ->with('data_pending', $data_pending)
            ->with('search', $search)
            ->with('before_date', $before)
            ->with('after_date', $after)
        ;
    }

    public function getData(Request $request, $type){
        $logService = new LogService();
        $search = $request->input('search2') ? $request->input('search2') : NULL;
        $before = $request->input('before') ? $request->input('before') : NULL;
        $after = $request->input('after') ? $request->input('after') : NULL;

        switch ($type) {
            case '1': //Daftar Sidang QA
                $query = Sidang::orderBy('date', 'DESC');
                $before ? $query->where('date', '<=', $before) : '';
                $after ? $query->where('date', '>=', $after) : '';
                break;

            case '0': //Perangkat Belum Sidang   
            case '2': //Perangkat Pending
                $query = Examination::with('device')->with('company')->with('media')->with('examinationLab')->with('equipmentHistory')
                    ->where('examination_type_id', 1)
                    ->where('resume_status', 1)
                    ->where('qa_passed', $type)
                    ->whereNotIn('id', function ($q) {
                        $q->select('examination_id')
                            ->from('sidang_detail')
                            ->where('result', 0);
                    })
                ;
                if ($search){
                    $query->where(function($qry) use($search){
                        $qry->whereHas('device', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%')
                                    ->orWhere('mark', 'like', '%'.strtolower($search).'%')
                                ;
                            })
                        ->orWhereHas('company', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%');
                            })
                        ->orWhere('spk_code', 'like', '%'.strtolower($search).'%');
                    });
                }
                break;
                
            default: //Get Data Sidang by ID
                $query = Sidang_detail::with('sidang')
                    ->with('examination')
                    ->with('examination.company')
                    ->with('examination.media')
                    ->with('examination.examinationLab')
                    ->with('examination.equipmentHistory')
                    ->where('sidang_id', $type)
                ;
                if ($search){
                    $query->where(function($qry) use($search){
                        $qry->whereHas('examination.device', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%')
                                    ->orWhere('mark', 'like', '%'.strtolower($search).'%')
                                ;
                            })
                        ->orWhereHas('examination.company', function ($q) use ($search){
                                return $q->where('name', 'like', '%'.strtolower($search).'%');
                            })
                        ->orWhereHas('examination', function ($q) use ($search){
                                return $q->where('spk_code', 'like', '%'.strtolower($search).'%');
                            });
                    });
                }
                break;
        }
        
        return array(
            'data' => $query,
            'search' => $search,
            'before' => $before,
            'after' => $after,
        );
    }

    public function create(Request $request, $sidang_id = null)
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            //initial var
            $message = null;
            $paginate = 100;
            $tab = $sidang_id ? 'tab-draft' : $request->input('tab');

            $data_perangkat = $this->getData($request, 0)['data']->paginate($paginate, ['*'], 'pagePerangkat');
            $this->mergeOTR($data_perangkat->items(), 'perangkat');
            $data_pending = $this->getData($request, 2)['data']->paginate($paginate, ['*'], 'pagePending');
            $this->mergeOTR($data_pending->items(), 'perangkat');
            $data_draft = $this->getData($request, $sidang_id)['data']->paginate($paginate, ['*'], 'pageDraft');
            $this->mergeOTR($data_draft->items(), 'sidang');
            
            //return view to saves index with data
            return view('admin.sidang.create')
                ->with('sidang_id', $sidang_id)
                ->with('tab', $tab)
                ->with('data_perangkat', $data_perangkat)
                ->with('data_pending', $data_pending)
                ->with('data_draft', $data_draft)
            ;
        }else{
            return view('errors.401');
        }
    }


    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $logService = new LogService();
        $sidang_id = $request->has('sidang_id') ? $request->input('sidang_id') : null;
        $draft = $request->input('hidden_tab') == 'tab-draft' ? 1 : 0;
        if($sidang_id){ 
            $sidang = Sidang::find($sidang_id);
            if($draft){ //save draft
                $sidangDetail = Sidang_detail::where('sidang_id', $sidang_id)->delete();

                $sidang->date = $request->input('date');
                $sidang->audience = 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (POH Manager URel TTH), I Gede Astawa (Senior Manager TTH).';
                $sidang->jml_perangkat = count($request->input('chk-draft'));
                $sidang->status = 'DRAFT';
            }
        }else{ //save pratinjau
            $sidang = new Sidang;
            $sidang->id = Uuid::uuid4();
            $sidang->status = 'PRATINJAU';
        }
        $sidang->created_by = $currentUser->id;
        $sidang->updated_by = $currentUser->id;
        try{
            if($sidang->save()){
                if($request->has('hidden_tab')){
                    if($request->input('hidden_tab') == 'tab-perangkat'){$chk = $request->input('chk-perangkat');}
                    if($request->input('hidden_tab') == 'tab-pending'){$chk = $request->input('chk-pending');}
                    if($request->input('hidden_tab') == 'tab-draft'){$chk = $request->input('chk-draft');}
                }else{
                    if($request->has('chk-perangkat')){$chk = $request->input('chk-perangkat');}
                    if($request->has('chk-pending')){$chk = $request->input('chk-pending');}
                    if($request->has('chk-draft')){$chk = $request->input('chk-draft');}
                }
                foreach($chk as $examination_id){ 
                    $sidangDetail = new Sidang_detail;
                    $sidangDetail->id = Uuid::uuid4();
                    $sidangDetail->sidang_id = $sidang->id;
                    $sidangDetail->examination_id = $examination_id;
                    $sidangDetail->created_by = $currentUser->id;
                    $sidangDetail->updated_by = $currentUser->id;
                    $sidangDetail->save();
                }
            }
            $logService->createLog($draft ? 'Draft Sidang QA' : 'Pratinjau Draft Sidang QA');
            Session::flash(self::MESSAGE, 'Data successfully added');
            return $draft ? redirect(self::ADMIN_SIDANG) : redirect(self::ADMIN_SIDANG.'/create/'.$sidang->id);
        } catch(Exception $e){ return redirect('/admin/sidang/create'.$sidang->id)->with(self::ERROR, 'Save failed');
        }
    } 

    public function show(Request $request, $sidang_id)
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            //initial var
            $message = null;
            $data = $this->getData($request, $sidang_id)['data']->get();
            return view('admin.sidang.detail')
                ->with('data', $this->mergeOTR($data, 'sidang'))
            ;
        }else{
            return view('errors.401');
        }
    }   

    public function mergeOTR($data, $type){
        $examinationService = new ExaminationService();
        $client = new Client([
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            // Base URI is used with relative requests
            'base_uri' => config('app.url_api_bsp'),
            // You can set any number of default request options.
            // self::HTTP_ERRORS => false,
            'timeout' => 60.0,
        ]);

        for($i=0; $i<count($data); $i++){
            $data[$i]->finalResult = NULL;
            $data[$i]->startDate = NULL;
            $data[$i]->endDate = NULL;
            $data[$i]->targetDate = NULL;
            $spk_code = $type == 'sidang' ? $data[$i]->examination->spk_code : $data[$i]->spk_code;
            $res_exam_OTR = $client->get('spk/searchData?limit=1&spkNumber='.$spk_code)->getBody();
            $exam_OTR = json_decode($res_exam_OTR);
            if($exam_OTR->code != 'MSTD0059AERR' && $exam_OTR->code != 'MSTD0000AERR'){
                $data[$i]->finalResult = $exam_OTR->data[0]->reportFinalResultValue;
                $data[$i]->startDate = $exam_OTR->data[0]->actualStartTestDt;
                $data[$i]->endDate = $exam_OTR->data[0]->actualFinishTestDt;
                $data[$i]->targetDate = $exam_OTR->data[0]->targetDt;
            }
        }

        return $data;
    }

    public function excel(Request $request) 
    {
        $currentUser = Auth::user();
        if (!$currentUser){return redirect(self::LOGIN);}
        
        // initial service sales 
        $salesService = new SalesService();

        // gate Sales Data
        $data = $salesService->getDataByStatus($request, $request->input('payment_status'));

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'No',
            'Company Name',
            'Sales Date',
            'Invoice',
            'Total',
            'Status',
            'Payment Method',
            'Document Code'
        ];

         // Define payment status
        $paymentStatusList = array(
            '-1'=> 'Paid (decline)',
            '0' => 'Unpaid',
            '1' => 'Paid',
            '2' => 'Paid (waiting confirmation)',
            '3' => 'Delivered'
        );
        // Convert each ot the returned collection into an array, and append it to the payments array
        $no = 0;
        foreach ($data['data']->get() as $row) {
            $no ++;
            
            if ($paymentStatusList[$row->payment_status]){
                $payment_status = $paymentStatusList[$row->payment_status];
            }else{
                $payment_status = "Paid";
            }

            $examsArray[] = [
                $no,
                $row->company_name,
                $row->created_at,
                $row->invoice,
                number_format($row->cust_price_payment, 0, '.', ','),
                $payment_status,
                ($row->payment_method == 1)?'ATM':$row->VA_name,
                $row->stel_code
            ];
        }

        // Create log
        $logService = new LogService;
        $logService->createLog('download_excel', self::SALES,'');

        $excel = \App\Services\ExcelService::download($examsArray, 'Data Sales');
        return response($excel['file'], 200, $excel['headers']);
    }

    public function edit(Request $request, $sidang_id)
    {
        if(Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com'){
            //initial var
            $message = null;
            if($request->has('tag')){
                $sidang = Sidang::find($sidang_id);
                $sidang->status = 'ON GOING';
                $sidang->save();
            }
            $data = $this->getData($request, $sidang_id)['data']->get();
            return view('admin.sidang.edit')
                ->with('data', $this->mergeOTR($data, 'sidang'))
            ;
        }else{
            return view('errors.401');
        }
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $notificationService = new NotificationService();
        $logService = new LogService;

        $jml_comply = 0;$jml_not_comply = 0;$jml_pending = 0;
        for($i = 0; $i < count($request->input('id')); $i++){
            $sidang_detail = Sidang_detail::find($request->input('id')[$i]);
            $sidang_detail->result = $request->input('result')[$i];
            $sidang_detail->valid_range = $request->input('valid_range')[$i];
            $sidang_detail->valid_from = $request->input('date');
            $sidang_detail->valid_thru = date('Y-m-d', strtotime("+".$sidang_detail->valid_range." months", strtotime($sidang_detail->valid_from)));
            $sidang_detail->catatan = $request->input('catatan')[$i];
            $sidang_detail->save();

            switch ($sidang_detail->result) {
                case 1:
                    $jml_comply++;
                    break;
                case -1:
                    $jml_not_comply++;
                    break;
                case 2:
                    $jml_pending++;
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $sidang = Sidang::find($id);
        $oldData = clone $sidang;

        $sidang->date = $request->input('date');
        $sidang->audience = $request->input('audience');
        $sidang->jml_comply = $jml_comply;
        $sidang->jml_not_comply = $jml_not_comply;
        $sidang->jml_pending = $jml_pending;
        $sidang->status = $request->input('status');
        
        try{
            $sidang->save();
            $sidang->status = 'DONE' ? $this->updateExamination($sidang->id) : '';
            $logService->createLog($sidang->status = 'DONE' ? 'Update Sidang QA' : 'Selesai Sidang QA', 'Sidang QA',$oldData);
            Session::flash(self::MESSAGE, 'Data successfully updated');
            return redirect(self::ADMIN_SIDANG);
        } catch(Exception $e){ return redirect(self::ADMIN_SIDANG.'/'.$sidang->id.'/edit')->with(self::ERROR, 'Save failed');
        } 
    }

    public function updateExamination($sidang_id){
        $data = Sidang_detail::with('sidang')
            ->with('examination')
            ->with('examination.company')
            ->with('examination.media')
            ->with('examination.examinationLab')
            ->with('examination.equipmentHistory')
            ->where('sidang_id', $sidang_id)
            ->get()
        ;
        foreach($data as $item){
            // 1. update to Examination -> 
                // a. qa_passed (sidang_detail.result), 
                // b. qa_date (sidang.date), 
                // c. certificate_date (sidang.date)
                // d. qa_passed = 0 terhadap data pengujian yang pernah "tidak lulus"
            $exam = Examination::find($item->examination_id);
            $exam->qa_passed = $item->result;
            $exam->qa_date = $item->sidang->date;
            $exam->certificate_date = $item->sidang->date;
            if(strpos($exam->keterangan, 'qa_date') !== false){
                $data_ket = explode("qa_date", $exam->keterangan);
                $devnc_exam = Examination::find($data_ket[2]);

                if($devnc_exam){
                    $devnc_exam->qa_passed = 0;

                    try{
                        $devnc_exam->save();
                    } catch(Exception $e){
                        // DO NOTHING
                    }
                }
            }
            $exam->qa_status = 1;
            $exam->certificate_status = 1;
            $exam->save();
            
            // 2. generate Sertifikat()
            $cert_number = $item->result == 1 ? $this->generateNoSertifikat() : null;
            
            // 3. update Attachment -> name ("Sertifikat"), link (exam_attach.attachment), no (exam_attach.no) [SEPERTINYA TIDAK USAH]

            if($cert_number){
                // $approval = new Approval; -> Untuk masuk ke menu approval
                /* 
                    autentikasi_editor
                    1. id [aut_ed-1]
                    2. content [<p>]
                    3. sign_by -> user_id [1,2,3]

                    approval
                    1. id [xxx-1]
                    2. name [Sidang QA]
                    3. reference_id [examination_id]
                    4. autentikasi_editor_id -> autentikasi_editor.id [aut_ed-1]
                    5. status [0, 1]

                    approve_by
                    1. id [yyy-1]
                    2. approval_id -> approval.id [xxx-1]
                    3. user_id [1,2,3]
                    4. approved_date 
                */


                $pdfGenerated = $this->generateSertifikat($item, $cert_number, 'getStream');
				$fileService = new FileService();
				$fileProperties = array(
					'path' => 'device/'.$exam->device_id."/",
					'prefix' => "sertifikat_",
					'fileName' => $pdfGenerated['fileName'],
				);
				$fileService->uploadFromStream($pdfGenerated['stream'], $fileProperties);
				$name_file = $fileService->getFileName();
                // $name_file = null;
            // 4. update to Device ->
                //  a. certificate (exam_attach.attachment), 
                //  b. valid_from (sidang_detail.valid_from), 
                //  c. valid_thru (sidang_detail.valid_thru), 
                //  d. cert_number (exam_attach.no)
                //  e. status (sidang_detail.result)
                $device = Device::find($item->examination->device_id);
                $device->certificate = $name_file;
                $device->valid_from = $item->valid_from;
                $device->valid_thru = $item->valid_thru;
                $device->cert_number = $cert_number;
                $device->status = 1;
                $device->save();
            }

            // 5. lakukan seolah2 Step Sidang QA - Step Penerbitan Sertifikat Completed
                //  a. qa_status & certificate_status = 1 [Di Step 1]
                //  b. upload ke minio [Di Step 4]
                //  c. delivered ke digimon [Di Step 4]
                //  d. send_email, add_log, add_examination_history
            switch ($item->result) {
                case 1:
                    # Email Sidang QA Lulus dan Sertifikat dapat di-download
                    break;
                case -1:
                    # Email Sidang QA Tidak Lulus dan Sertifikat tidak dapat di-download
                    break;
                case 2:
                    # Email Sidang QA Pending dan Sertifikat tidak dapat di-download
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

    public function resetExamination($sidang_id){ // DELETE SOON
        $data = Sidang_detail::with('examination')
            ->where('sidang_id', $sidang_id)
            ->get()
        ;
        foreach($data as $item){
            $exam = Examination::find($item->examination_id);
            $exam->qa_passed = 0;
            $exam->qa_date = NULL;
            $exam->certificate_date = NULL;
            $exam->qa_status = 0;
            $exam->certificate_status = 0;
            $exam->save();
        
            $device = Device::find($item->examination->device_id);
            $device->certificate = NULL;
            $device->valid_from = NULL;
            $device->valid_thru = NULL;
            $device->cert_number = NULL;
            $device->status = 0;
            $device->save();
        }

        Session::flash('message', 'Successfully Reset Data');
        return redirect('/admin/sidang/');
    }

    private function generateNoSertifikat(){
        $thisYear = date('Y');
		$where = "SUBSTR(cert_number,'17',4) = '".$thisYear."'";
		$query = "
			SELECT 
			SUBSTR(cert_number,'6',3) + 1 AS last_numb
			FROM devices 
			WHERE 
			".$where."
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query); 
		if (!count($data)){
			$cert_number = 'Tel. 001/TTH-01/'.$thisYear;
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				$cert_number = 'Tel. 00'.$last_numb.'/TTH-01/'.$thisYear;
			}
			else if($last_numb < 100){
				$cert_number = 'Tel. 0'.$last_numb.'/TTH-01/'.$thisYear;
			}
			else{
                $cert_number = 'Tel. '.$last_numb.'/TTH-01/'.$thisYear;
			}
		}

		return $cert_number;
    }

    public function generateSertifikat($item, $cert_number, $method = '')
	{
		$month_list_lang_id = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];
		$signDate = date('d', strtotime($item->sidang->date)).' '.$month_list_lang_id[((int)date('m', strtotime($item->sidang->date)))-1].' '.date('Y', strtotime($item->sidang->date));
		$start_certificate_period = Carbon::parse($item->valid_from);
		$end_certificate_period = Carbon::parse($item->valid_thru);
		$interval = $start_certificate_period->diffInMonths($end_certificate_period);
		if ($interval % 12 == 0){
			$interval_year = (int)$interval/12;
			$period_id = "$interval_year tahun";
			$period_en = "$interval_year year".($interval_year > 1 ? 's': '');
		}else{
			$period_id = "$interval bulan";
			$period_en = "$interval month".($interval > 1 ? 's': '');
		}
		$signeeData = \App\GeneralSetting::whereIn('code', ['sm_urel', 'poh_sm_urel'])->where('is_active', '=', 1)->first();
		$certificateNumber = strval($cert_number);
		$telkomLogoSquarePath = '/app/Services/PDF/images/telkom-logo-square.png';
        // url('/approval/{{ approval_id }}') -> approval_id didapat ketika membuat data approval
		$qrCodeLink = url('/digitalSign/21003-132'); //todo daniel digitalSign page

		//dd($certificateNumber);

        $lap_uji = \App\ExaminationAttach::where('examination_id', $item->examination_id)->where('name', 'Laporan Uji')->first();
        $no_lap_uji = $lap_uji ? $lap_uji->no : '-';
        
		$PDFData = [
			'documentNumber' => $cert_number,
			'companyName' => $item->examination->company->name,
			'brand' => $item->examination->device->mark,
			'deviceName' => $item->examination->device->name,
			'deviceType' => $item->examination->device->model,
			'deviceCapacity' => $item->examination->device->capacity,
			'deviceSerialNumber' => $item->examination->device->serial_number,
			'examinationNumber' => $no_lap_uji,
			'examinationReference' => $item->examination->device->test_reference,
			'signDate' => $signDate,
			'period_id' => $period_id,
			'period_en' => $period_en,
			'signee' => $signeeData->value,
			'isSigneePoh' => $signeeData->code !== 'sm_urel',
			'signImagePath' => Storage::disk('minio')->url("generalsettings/$signeeData->id/$signeeData->attachment"),
			'method' => $method,
			'qrCode' => QrCode::format('png')->size(500)->merge($telkomLogoSquarePath)->errorCorrection('M')->generate($qrCodeLink)
		];
		$PDF = new \App\Services\PDF\PDFService();
		
		if ($method == 'getStream'){
			return [
				'stream' => $PDF->cetakSertifikatQA($PDFData),
				'fileName' => str_replace("/","",$certificateNumber).'.pdf'
			];
		}else{
			return $PDF->cetakSertifikatQA($PDFData);
		}
	}

    public function destroy($id, $reasonOfDeletion){
        //Get data
        $sidang = Sidang::find($id);
        
        // Filter and Feedback if no data found
        if (!$sidang){
            Session::flash('error', 'Undefined Data');
            return redirect('/admin/sidang/');
        }

        // Delete the record(s)
        $detail = Sidang_detail::where('sidang_id',$id)->delete();
        $sidang->delete();

        // Create Admin Log
        $logService = new LogService();
        $logService->createAdminLog('Hapus Data Sidang QA', 'Sidang', $sidang, urldecode($reasonOfDeletion) );

        // Feedback succeed
        Session::flash('message', 'Successfully Delete Data');
        return redirect('/admin/sidang/');
    }

}