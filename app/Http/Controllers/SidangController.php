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
use App\Approval;
use App\ApproveBy;
use App\AuthentikasiEditor;
use App\ExaminationType;
use App\GeneralSetting;

use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;
use App\Services\NotificationService;
use App\Services\SalesService;
use App\Services\FileService;
use App\Services\ExaminationService;
use App\Services\EmailEditorService;

use Auth;
use Session;
use Validator;
use Excel;
use Response;
use Storage;
use File;
use Mail;

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
            ->with('data_sidang', $this->getData($request, 1)['data']->paginate($paginate, ['*'], 'pageSidang'))
            ->with('data_perangkat', $data_perangkat)
            ->with('data_pending', $data_pending)
            ->with('search', $search)
            ->with('before_date', $before)
            ->with('after_date', $after);
    }

    public function getData(Request $request, $type)
    {
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
                    });
                if ($search) {
                    $query->where(function ($qry) use ($search) {
                        $qry->whereHas('device', function ($q) use ($search) {
                            return $q->where('name', 'like', '%' . strtolower($search) . '%')
                                ->orWhere('mark', 'like', '%' . strtolower($search) . '%');
                        })
                            ->orWhereHas('company', function ($q) use ($search) {
                                return $q->where('name', 'like', '%' . strtolower($search) . '%');
                            })
                            ->orWhere('spk_code', 'like', '%' . strtolower($search) . '%');
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
                    ->where('sidang_id', $type);
                if ($search) {
                    $query->where(function ($qry) use ($search) {
                        $qry->whereHas('examination.device', function ($q) use ($search) {
                            return $q->where('name', 'like', '%' . strtolower($search) . '%')
                                ->orWhere('mark', 'like', '%' . strtolower($search) . '%');
                        })
                            ->orWhereHas('examination.company', function ($q) use ($search) {
                                return $q->where('name', 'like', '%' . strtolower($search) . '%');
                            })
                            ->orWhereHas('examination', function ($q) use ($search) {
                                return $q->where('spk_code', 'like', '%' . strtolower($search) . '%');
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
        if (Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com') {
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
                ->with('data_draft', $data_draft);
        } else {
            return view('errors.401');
        }
    }


    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $logService = new LogService();
        $sidang_id = $request->has('sidang_id') ? $request->input('sidang_id') : null;
        $draft = $request->input('hidden_tab') == 'tab-draft' ? 1 : 0;
        if ($sidang_id) {
            $sidang = Sidang::find($sidang_id);
            if ($draft) { //save draft
                $sidangDetail = Sidang_detail::where('sidang_id', $sidang_id)->delete();

                $sidang->date = $request->input('date');
                $sidang->audience = 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (POH Manager URel TTH), I Gede Astawa (Senior Manager TTH).';
                $sidang->jml_perangkat = count($request->input('chk-draft'));
                $sidang->status = 'DRAFT';
            }
        } else { //save pratinjau
            $sidang = new Sidang;
            $sidang->id = Uuid::uuid4();
            $sidang->status = 'PRATINJAU';
        }
        $sidang->created_by = $currentUser->id;
        $sidang->updated_by = $currentUser->id;
        try {
            if ($sidang->save()) {
                if ($request->has('hidden_tab')) {
                    if ($request->input('hidden_tab') == 'tab-perangkat') {
                        $chk = $request->input('chk-perangkat');
                    }
                    if ($request->input('hidden_tab') == 'tab-pending') {
                        $chk = $request->input('chk-pending');
                    }
                    if ($request->input('hidden_tab') == 'tab-draft') {
                        $chk = $request->input('chk-draft');
                    }
                } else {
                    if ($request->has('chk-perangkat')) {
                        $chk = $request->input('chk-perangkat');
                    }
                    if ($request->has('chk-pending')) {
                        $chk = $request->input('chk-pending');
                    }
                    if ($request->has('chk-draft')) {
                        $chk = $request->input('chk-draft');
                    }
                }
                foreach ($chk as $examination_id) {
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
            return $draft ? redirect(self::ADMIN_SIDANG) : redirect(self::ADMIN_SIDANG . '/create/' . $sidang->id);
        } catch (Exception $e) {
            return redirect('/admin/sidang/create' . $sidang->id)->with(self::ERROR, 'Save failed');
        }
    }

    public function show(Request $request, $sidang_id)
    {
        if (Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com') {
            //initial var
            $message = null;
            $data = $this->getData($request, $sidang_id)['data']->get();
            return view('admin.sidang.detail')
                ->with('data', $this->mergeOTR($data, 'sidang'));
        } else {
            return view('errors.401');
        }
    }

    public function mergeOTR($data, $type)
    {
        $examinationService = new ExaminationService();
        $client = new Client([
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            // Base URI is used with relative requests
            'base_uri' => config('app.url_api_bsp'),
            // You can set any number of default request options.
            // self::HTTP_ERRORS => false,
            'timeout' => 60.0,
        ]);

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->finalResult = NULL;
            $data[$i]->startDate = NULL;
            $data[$i]->endDate = NULL;
            $data[$i]->targetDate = NULL;
            $spk_code = $type == 'sidang' ? $data[$i]->examination->spk_code : $data[$i]->spk_code;
            $res_exam_OTR = $client->get('spk/searchData?limit=1&spkNumber=' . $spk_code)->getBody();
            $exam_OTR = json_decode($res_exam_OTR);
            if ($exam_OTR->code != 'MSTD0059AERR' && $exam_OTR->code != 'MSTD0000AERR') {
                $data[$i]->finalResult = $exam_OTR->data[0]->reportFinalResultValue;
                $data[$i]->startDate = $exam_OTR->data[0]->actualStartTestDt;
                $data[$i]->endDate = $exam_OTR->data[0]->actualFinishTestDt;
                $data[$i]->targetDate = $exam_OTR->data[0]->targetDt;
            }
        }

        return $data;
    }

    public function excel($sidang_id)
    {
        $currentUser = Auth::user();
        if (!$currentUser) {
            return redirect(self::LOGIN);
        }
        $logService = new LogService();

        $data = Sidang_detail::with('sidang')
            ->with('examination')
            ->with('examination.company')
            ->with('examination.media')
            ->with('examination.examinationLab')
            ->with('examination.equipmentHistory')
            ->with('examination.examinationAttach')
            ->where('sidang_id', $sidang_id)
            ->get();

        $data_draft = $this->mergeOTR($data, 'sidang');

        // Define the Excel spreadsheet headers
        $examsArray = [];
        $examsArray[] = [
            'No.',
            'Perusahaan',
            'Perangkat',
            'Merek',
            'Tipe',
            'Kapasitas',
            'Negara Pembuat',
            'Hasil Uji',
            'Status',
        ];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.

        $no = 1;
        foreach ($data_draft as $row) {
            $examsArray[] = [
                $no,
                $row->examination->company->name,
                $row->examination->device->name,
                $row->examination->device->mark,
                $row->examination->device->model,
                $row->examination->device->capacity,
                $row->examination->device->manufactured_by,
                $row->finalResult ? $row->finalResult : '-',
                $row->examination->company->qs_certificate_date > date('Y-m-d') ? 'SM Eligible' : 'SM Not Eligible',
            ];
            $no++;
        }

        $logService->createLog('download_excel', 'Draft Sidang QA', '');
        $excel = \App\Services\ExcelService::download($examsArray, 'Draft Sidang QA ' . $data_draft[0]->sidang->date);
        return response($excel['file'], 200, $excel['headers']);
    }

    public function edit(Request $request, $sidang_id)
    {
        if (Auth::user()->id == 1 || Auth::user()->email == 'admin@mail.com') {
            //initial var
            $message = null;
            if ($request->has('tag')) {
                $sidang = Sidang::find($sidang_id);
                $sidang->status = 'ON GOING';
                $sidang->save();
            }
            $data = $this->getData($request, $sidang_id)['data']->get();
            return view('admin.sidang.edit')
                ->with('data', $this->mergeOTR($data, 'sidang'));
        } else {
            return view('errors.401');
        }
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $notificationService = new NotificationService();
        $logService = new LogService;

        $jml_comply = 0;
        $jml_not_comply = 0;
        $jml_pending = 0;
        for ($i = 0; $i < count($request->input('id')); $i++) {
            $sidang_detail = Sidang_detail::find($request->input('id')[$i]);
            $sidang_detail->result = $request->input('result')[$i];
            $sidang_detail->valid_range = $request->input('valid_range')[$i];
            $sidang_detail->valid_from = $request->input('date');
            $sidang_detail->valid_thru = date('Y-m-d', strtotime("+" . $sidang_detail->valid_range . " months", strtotime($sidang_detail->valid_from)));
            $sidang_detail->catatan = $request->input('catatan')[$i];
            $sidang_detail->updated_by = $currentUser->id;
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

        try {
            $sidang->save();
            if ($request->has('device_id') && $sidang->status != 'DONE') {
                $this->updateDevice($request, $id);
                Session::flash(self::MESSAGE, 'Data successfully updated');
                return redirect(self::ADMIN_SIDANG . '/' . $id . '/edit');
            } else {
                $sidang->status == 'DONE' ? $this->updateExamination($sidang->id) : '';
                $logService->createLog($sidang->status = 'DONE' ? 'Update Sidang QA' : 'Selesai Sidang QA', 'Sidang QA', $oldData);
                Session::flash(self::MESSAGE, 'Data successfully updated');
                return redirect(self::ADMIN_SIDANG);
            }
        } catch (Exception $e) {
            return redirect(self::ADMIN_SIDANG . '/' . $sidang->id . '/edit')->with(self::ERROR, 'Save failed');
        }
    }

    public function updateDevice($request, $id)
    {
        $currentUser = Auth::user();
        $logService = new LogService();

        $device = Device::findOrFail($request->input('device_id'));
        $device->test_reference = $request->input('test_reference');
        $device->name = $request->input('name');
        $device->mark = $request->input('mark');
        $device->model = $request->input('model');
        $device->capacity = $request->input('capacity');
        $device->manufactured_by = $request->input('manufactured_by');
        $device->serial_number = $request->input('serial_number');

        $device->updated_by = $currentUser->id;
        $device->updated_at = date('Y-m-d H:i:s');

        try {
            $device->save();
            $logService->createLog("update", "REVISI", $device);
        } catch (Exception $e) {
            return redirect(self::ADMIN_SIDANG . '/' . $id . '/edit')->with(self::ERROR, 'Save failed');
        }
    }

    public function updateExamination($sidang_id)
    {
        $currentUser = Auth::user();
        $data = Sidang_detail::with('sidang')
            ->with('examination')
            ->with('examination.user')
            ->with('examination.company')
            ->with('examination.media')
            ->with('examination.examinationType')
            ->with('examination.examinationLab')
            ->with('examination.equipmentHistory')
            ->with('examination.examinationAttach')
            ->where('sidang_id', $sidang_id)
            ->get();

        // $devicesList = [];
        // $noLaporanUji = [];
        // $certificateNumberList = [];
        // $sidang_detail = $this->mergeOTR($data, 'sidang');

        foreach ($data as $item) {
            // 1. update to Examination -> 
            // a. qa_passed (sidang_detail.result), 
            // b. qa_date (sidang.date), 
            // c. certificate_date (sidang.date)
            // d. qa_passed = 0 terhadap data pengujian yang pernah "tidak lulus"
            $exam = Examination::find($item->examination_id);
            $exam->qa_passed = $item->result;
            $exam->qa_date = $item->sidang->date;
            $exam->certificate_date = $item->sidang->date;
            if (strpos($exam->keterangan, 'qa_date') !== false) {
                $data_ket = explode("qa_date", $exam->keterangan);
                $devnc_exam = Examination::find($data_ket[2]);

                if ($devnc_exam) {
                    $devnc_exam->qa_passed = 0;

                    try {
                        $devnc_exam->save();
                    } catch (Exception $e) {
                        // DO NOTHING
                    }
                }
            }
            $exam->qa_status = 1;
            $exam->certificate_status = 1;
            $exam->save();

            // 2. generate Sertifikat()
            $cert_number = $item->result == 1 ? $this->generateNoSertifikat() : null;
            $certificateNumberList[] = $cert_number;

            // 3. update Attachment -> name ("Sertifikat"), link (exam_attach.attachment), no (exam_attach.no) [SEPERTINYA TIDAK USAH]

            if ($cert_number) {
                $name_file = str_replace("/", "", strval($cert_number)) . '.pdf';
                $auth = AuthentikasiEditor::where('dir_name', 'authentikasi.sertifikat')->first();
                $approval = new Approval;
                $approval->id = Uuid::uuid4();
                $approval->reference_table = 'device';
                $approval->reference_id = $item->examination->device_id;
                $approval->attachment = $name_file;
                $approval->status = 1;
                $approval->autentikasi_editor_id = $auth->id;
                $approval->created_by = $currentUser->id;
                $approval->updated_by = $currentUser->id;
                if ($approval->save()) {
                    foreach (json_decode($auth->sign_by) as $sign_by) {
                        $approveBy = new ApproveBy;
                        $approveBy->id = Uuid::uuid4();
                        $approveBy->approval_id = $approval->id;
                        $approveBy->user_id = $sign_by;
                        $approveBy->approve_date = date('Y-m-d H:i:s');
                        $approveBy->created_by = $currentUser->id;
                        $approveBy->updated_by = $currentUser->id;
                        $approveBy->save();
                    }
                }
                $pdfGenerated = $this->generateSertifikat($item, $cert_number, 'getStream', $approval->id);
                $fileService = new FileService();
                $fileProperties = array(
                    'path' => 'device/' . $exam->device_id . "/",
                    'prefix' => "sertifikat_",
                    'fileName' => $pdfGenerated['fileName'],
                );
                $fileService->uploadFromStream($pdfGenerated['stream'], $fileProperties);
                $name_file = $fileService->getFileName();
                // $name_file = $cert_number;
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
                $devicesList[] = $device;
            } else {
                $devicesList[] = $item->examination->device;
            }

            // 5. lakukan seolah2 Step Sidang QA - Step Penerbitan Sertifikat Completed
            //  a. qa_status & certificate_status = 1 [Di Step 1]
            //  b. upload ke minio [Di Step 4]
            //  c. delivered ke digimon [Di Step 4]
            //  d. send_email ke PIC, add_log, add_examination_history
            $this->sendEmail($item);
        }

        // 6. Save Sidang QA to Approval
        // upload Sidang QA ke minio
        // generate Sidang QA -> [Chris]
        // $approval = new Approval; -> Untuk masuk ke menu approval [Chandra]
        /* 
            autentikasi_editor (nanti di menu authentikasi editor ada fitur manage user yang ttd ini)
            1. id [aut_ed-1]
            2. content [<p>]
            3. sign_by -> user_id [1,2,3]

            approval
            1. id [xxx-1]
            2. reference_id [examination_id]
            3. autentikasi_editor_id -> autentikasi_editor.id [aut_ed-1]
            4. status [0, 1]

            approve_by
            1. id [yyy-1]
            2. approval_id -> approval.id [xxx-1]
            3. user_id [1,2,3]
            4. approve_date 
        */
        $sidang_detail = $this->mergeOTR($data, 'sidang');
        $PDFData = [];
        $PDFData['devices'] = $devicesList;
        $PDFData['sidang_detail'] = $sidang_detail;
        $PDFData['sidang'] = Sidang::find($sidang_id);
        $PDFData['certificateNumber'] = $certificateNumberList;
        $pdfGenerated = $this->generateSidangQA($PDFData, 'getStream');
        $fileService = new FileService();
        $fileProperties = array(
            'path' => 'sidang/' . $sidang_id . "/",
            'prefix' => "sidang_",
            'fileName' => $pdfGenerated['fileName'],
        );
        $fileService->uploadFromStream($pdfGenerated['stream'], $fileProperties);

        $auth = AuthentikasiEditor::where('dir_name', 'authentikasi.sidang')->first();
        $approval = new Approval;
        $approval->id = Uuid::uuid4();
        $approval->reference_table = 'sidang';
        $approval->reference_id = $sidang_id;
        $approval->attachment = 'sidang ' . $PDFData['sidang']->date . '.pdf';
        $approval->status = 1;
        $approval->autentikasi_editor_id = $auth->id;
        $approval->created_by = $currentUser->id;
        $approval->updated_by = $currentUser->id;
        if ($approval->save()) {
            foreach (json_decode($auth->sign_by) as $sign_by) {
                $approveBy = new ApproveBy;
                $approveBy->id = Uuid::uuid4();
                $approveBy->approval_id = $approval->id;
                $approveBy->user_id = $sign_by;
                $approveBy->approve_date = date('Y-m-d H:i:s');
                $approveBy->created_by = $currentUser->id;
                $approveBy->updated_by = $currentUser->id;
                $approveBy->save();
            }
        }
    }

    public function sendEmail($item)
    {
        $email_editors = new EmailEditorService();

        $email = $item->result == 2 ? $email_editors->selectBy('emails.qaPending') : $email_editors->selectBy('emails.qa');
        $content = $this->parsingEmailSidangQA($email->content, $item->examination->user->name, $item->examination->company->name, $item->examination->qa_passed, $item->examination->device, $item->catatan);
        $subject = 'Pemberitahuan Hasil Pengujian Perangkat ' . $item->examination->device->name . ' | ' . $item->examination->device->mark . ' | ' . $item->examination->device->model . ' | ' . $item->examination->device->capacity;

        // $user_email = $item->examination->user->email;
        $user_email = 'arifchandrasimanjuntak@yahoo.co.id';
        if (GeneralSetting::where('code', 'send_email')->first()['is_active']) {
            Mail::send('emails.editor', array(
                'logo' => $email->logo,
                'content' => $content,
                'signature' => $email->signature
            ), function ($m) use ($user_email, $subject) {
                $m->to($user_email)->subject($subject);
            });
        }
    }

    public function parsingEmailSertifikat($content, $user_name, $is_loc_test)
    {
        $content = str_replace('@user_name', $user_name, $content);
        $text1 = $is_loc_test ? 'Anda dapat' : 'Perangkat sampel uji agar segera diambil kembali sebagai syarat untuk';
        $text2 = $is_loc_test == 0 ? ' .<br>Dokumen tersebut nanti dapat anda unduh' : '';
        $content = str_replace('@text1', $text1, $content);
        $content = str_replace('@text2', $text2, $content);
        return $content;
    }

    public function parsingEmailSidangQA($content, $user_name, $company_name, $qa_passed, $device, $catatan)
    {
        $content = str_replace('@user_name', $user_name, $content);
        $content = str_replace('@company_name', $company_name, $content);
        $content = str_replace('@device_name', $device->name, $content);
        $content = str_replace('@device_mark', $device->mark, $content);
        $content = str_replace('@device_model', $device->model, $content);
        $content = str_replace('@device_capacity', $device->capacity, $content);
        $content = str_replace('@test_reference', $device->test_reference, $content);
        switch ($qa_passed) {
            case '1':
                $content = str_replace('@qa_passed', '<strong>LULUS</strong>', $content);
                $content = str_replace('@cert1', ' dan sertifikat QA', $content);
                $content = str_replace('@cert2', ', unduh sertifikat/ download certificate', $content);
                break;
            case '-1':
                $content = str_replace('@qa_passed', '<strong>TIDAK LULUS</strong>', $content);
                $content = str_replace('@cert1', '', $content);
                $content = str_replace('@cert2', '', $content);
                break;
            case '2':
                $content = $catatan ? str_replace('@catatan', ' dengan catatan ' . $catatan, $content) : str_replace('@catatan', '', $content);
                break;
        }
        return $content;
    }

    public function resetExamination($sidang_id)
    { // DELETE SOON
        $data = Sidang_detail::with('examination')
            ->where('sidang_id', $sidang_id)
            ->get();
        foreach ($data as $item) {
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

    private function generateNoSertifikat()
    {
        $thisYear = date('Y');
        $where = "SUBSTR(cert_number,'17',4) = '" . $thisYear . "'";
        $query = "
			SELECT 
			SUBSTR(cert_number,'6',3) + 1 AS last_numb
			FROM devices 
			WHERE 
			" . $where . "
			ORDER BY last_numb DESC LIMIT 1
		";
        $data = DB::select($query);
        if (!count($data)) {
            $cert_number = 'Tel. 001/TTH-01/' . $thisYear;
        } else {
            $last_numb = $data[0]->last_numb;
            if ($last_numb < 10) {
                $cert_number = 'Tel. 00' . $last_numb . '/TTH-01/' . $thisYear;
            } else if ($last_numb < 100) {
                $cert_number = 'Tel. 0' . $last_numb . '/TTH-01/' . $thisYear;
            } else {
                $cert_number = 'Tel. ' . $last_numb . '/TTH-01/' . $thisYear;
            }
        }

        return $cert_number;
    }

    public function generateSertifikat($item, $cert_number, $method = '', $approval_id)
    {
        $signDate = \App\Services\MyHelper::tanggalIndonesia($item->sidang->date);
        $start_certificate_period = Carbon::parse($item->valid_from);
        $end_certificate_period = Carbon::parse($item->valid_thru);
        $interval = $start_certificate_period->diffInMonths($end_certificate_period);
        if ($interval % 12 == 0) {
            $interval_year = (int)$interval / 12;
            $period_id = "$interval_year tahun";
            $period_en = "$interval_year year" . ($interval_year > 1 ? 's' : '');
        } else {
            $period_id = "$interval bulan";
            $period_en = "$interval month" . ($interval > 1 ? 's' : '');
        }
        $signeeData = \App\GeneralSetting::whereIn('code', ['sm_urel', 'poh_sm_urel'])->where('is_active', '=', 1)->first();
        $certificateNumber = strval($cert_number);
        $telkomLogoSquarePath = '/app/Services/PDF/images/telkom-logo-square.png';
        $qrCodeLink = url('/approval/' . $approval_id); //approval_id didapat ketika membuat data approval
        // $qrCodeLink = url('/digitalSign/21003-132'); //todo daniel digitalSign page

        // dd($certificateNumber);

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

        if ($method == 'getStream') {
            return [
                'stream' => $PDF->cetakSertifikatQA($PDFData),
                'fileName' => str_replace("/", "", $certificateNumber) . '.pdf'
            ];
        } else {
            return $PDF->cetakSertifikatQA($PDFData);
        }
    }

    public function print($sidang_id)
    {
        $data = Sidang_detail::with('sidang')
            ->with('examination')
            ->with('examination.company')
            ->with('examination.media')
            ->with('examination.examinationLab')
            ->with('examination.equipmentHistory')
            ->with('examination.examinationAttach')
            ->where('sidang_id', $sidang_id)
            ->get();

        $devicesList = [];
        $certificateNumberList = [];
        $sidang_detail = $this->mergeOTR($data, 'sidang');

        foreach ($sidang_detail as $item) {
            $device = Device::find($item->examination->device_id);
            $devicesList[] = $device;
            $certificateNumberList[] = $item->result == 1 ? $device->cert_number : 1;
        }

        $PDFData = [];
        $PDFData['devices'] = $devicesList;
        $PDFData['sidang_detail'] = $sidang_detail;
        $PDFData['sidang'] = Sidang::find($sidang_id);
        $PDFData['certificateNumber'] = $certificateNumberList;
        $this->generateSidangQA($PDFData, '');
    }

    public function download($sidang_id)
    {
        $sidang = Sidang::find($sidang_id);
        if ($sidang) {
            $fileName = 'sidang ' . $sidang->date . '.pdf';
            $file = Storage::disk('minio')->get('sidang/' . $sidang_id . "/" . $fileName);
            return response($file, 200, \App\Services\MyHelper::getHeaderOctet($fileName));
        } else {
            return false;
        }
    }

    public function destroy($id, $reasonOfDeletion)
    {
        //Get data
        $sidang = Sidang::find($id);

        // Filter and Feedback if no data found
        if (!$sidang) {
            Session::flash('error', 'Undefined Data');
            return redirect('/admin/sidang/');
        }

        // Delete the record(s)
        $detail = Sidang_detail::where('sidang_id', $id)->delete();
        $sidang->delete();

        // Create Admin Log
        $logService = new LogService();
        $logService->createAdminLog('Hapus Data Sidang QA', 'Sidang', $sidang, urldecode($reasonOfDeletion));

        // Feedback succeed
        Session::flash('message', 'Successfully Delete Data');
        return redirect('/admin/sidang/');
    }

    public function generateSidangQA($PDFData, $method = '')
    {
        $PDF = new \App\Services\PDF\PDFService();
        $officer = \App\Services\MyHelper::getOfficer();
        $telkomLogoSquarePath = '/app/Services/PDF/images/telkom-logo-square.png';
        // $qrCodeLink = url('/digitalSign/21003-132'); //todo @arif digitalSign page

        // $PDFData['qrCode'] = QrCode::format('png')->size(500)->merge($telkomLogoSquarePath)->errorCorrection('M')->generate($qrCodeLink);
        $PDFData['method'] = $method;
        $PDFData['signees'] = [
            [
                'name' => strtoupper($officer['seniorManager']),
                'title' => $officer['isSeniorManagerPOH'] ? "POH SM INFRASTRUCTURE ASSURANCE" : "SM INFRASTRUCTURE ASSURANCE",
                'tandaTanganSeniorManager' => $officer['tandaTanganSeniorManager']
            ],
            [
                'name' => strtoupper($officer['manager']),
                'title' => $officer['isManagerPOH'] ? "POH SEKRETARIS" : "SEKRETARIS",
                'tandaTanganManager' => $officer['tandaTanganManager']
            ]
        ];

        if ($method == 'getStream') {
            return [
                'stream' => $PDF->cetakSidangQA($PDFData),
                'fileName' => 'sidang ' . $PDFData['sidang']->date . '.pdf' //todo @arif nama pdf kalau perlu diupload
            ];
        } else {
            return $PDF->cetakSidangQA($PDFData);
        }
    }
}
