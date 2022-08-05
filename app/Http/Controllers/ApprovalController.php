<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


use App\User;
use Auth;
use Session;
use App\GeneralSetting;

use App\Approval;
use App\ApproveBy;
use App\AuthentikasiEditor;

use App\Examination;
use App\ExaminationType;
use App\ExaminationLab;
use App\Company;
use App\Logs;
use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;
use App\Services\EmailEditorService;


use Storage;
use File;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ApprovalController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input('search','')));
        // $before = null;
        // $after = null;
        // $type = '';
        // $filterCompany = '';
        // $lab = '';
        // $sort_by = 'questioner_date';
        // $sort_type = 'desc';
        $user_id = Auth::user()->id;
        // $query = Approval::with('approveBy')
        //     ->with('authentikasi')
        //     ->where(function($qry) use($user_id){
        //         $qry->whereHas('approveBy', function ($q) use ($user_id){
        //             return $q->where('user_id', $user_id);
        //         });
        //     })
        // ;
        $query = ApproveBy::with('approval')
            ->with('approval.authentikasi')
            ->with('approval.approveBy')
        ;
        Auth::user()->id != '1' && Auth::user()->email != 'admin@mail.com' ? $query->where('user_id', $user_id) : '';
        $logService = new LogService();
        
        if ($search){
            $query->whereHas('approval.authentikasi', function ($q) use ($search) {
                $q->where('name', 'like', '%'.strtolower($search).'%');
            })
            // ->orWhereHas('approval.authentikasi', function ($q) use ($search) {
            //     $q->orWhere('name', 'like', '%'.strtolower($search).'%');
            // })
            ;
            $logService->createLog('search', 'Approval', json_encode(array('search' => $search)) );
        }

        $data = $query->orderBy('created_at', 'DESC')->paginate($paginate);
        if (count($data) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.approval.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with('search', $search)
            // ->with('before_date', $before)
            // ->with('after_date', $after)
            // ->with('type', $examType)
            // ->with('filterType', $type)
            // ->with('company', $companies)
            // ->with('filterCompany', $filterCompany)
            // ->with('lab', $examLab)
            // ->with('filterLab', $lab)
            // ->with(self::SORT_BY, $sort_by)
            // ->with(self::SORT_TYPE, $sort_type)
        ;
    }


    public function sendOtp(){
        $email_editors = new EmailEditorService();
        $emails = $email_editors->selectBy('emails.verifikasiAkun');
        $user=Auth::user();
        $otp = strval(mt_rand(100000,999999));
        $content=$this->parseOtp($emails->content,$user->name, $otp);
        $user->email3 = $otp;
        $user->save();
        
        if(GeneralSetting::where('code', 'send_email')->first()['is_active']){
            Mail::send('emails.editor', array(
                'content' => $content,
                'logo' => $emails->logo,
                'signature' => $emails->signature
                ), function ($m) use ($user) {
                $subject='Verifikasi Akun';
                $m->to($user->email)->subject($subject);
            });
        }

        return response()->json(['success'=>'The OTP code has been successfully sent to your email']);

    }


    public function parseOtp($content, $user_name, $otp){
		$content = str_replace('@user_name', $user_name, $content);
		$content = str_replace('@token_verification', $otp, $content);
		return $content;
	}


    public function assign($id,$otp)
	{
        $user= Auth::user();
        if ($otp!=$user->email3){
                
                $user->email3=null;
                $user->save();
                
                return redirect('admin/approval')->with('error', 'OTP Salah!');
            }
        $logService = new LogService();
		$data = ApproveBy::find($id);
        
		if ($data){
			try{
                $data->approve_date = date("Y-m-d H:i:s");
                $data->updated_by = $user->id;
                $data->save();

                $approveBy = ApproveBy::where('approval_id', $data->approval_id)->whereNull('approve_date')->get();
                if(count($approveBy) == 0){
                    $approval = Approval::find($data->approval_id);
                    $approval->status = 1;
                    $approval->updated_by = $user->id;
                    $approval->save();

                    $examination = Examination::where('device_id', $approval->reference_id)->first();
                    $examination->certificate_status = 1;
                    $examination->updated_by = $user->id;
                    $examination->save();
                    $user->email3=null;
                    $user->save();
                }
                    //EMAIL KE SM
                $logService->createLog('assign', 'Approval', $data );
				Session::flash('message', 'Document successfully approved');
				return redirect('admin/approval');
			}catch (Exception $e){ return redirect('admin/approval')->with('error', 'Approve failed'); }
		}
	}

    public function show($id){
        $approval = Approval::where('id', $id)->with('approveBy')->with('approveBy.user')->with('approveBy.user.role')->with('authentikasi')->first();
        dd($approval);
        $examination = Examination::where('device_id', $approval->reference_id)->with('device')->with('company')->first();
        $data['name'] = $approval->authentikasi->name;
        $data['attachment'] = $approval->attachment;
        $data['document_code'] = $examination->device->cert_number;
        $data['company_name'] = $examination->company->name;
        $data['device_name'] = $examination->device->name;
        $data['mark'] = $examination->device->mark;
        $data['model'] = $examination->device->model;
        $data['capacity'] = $examination->device->capacity;
        $data['serial_number'] = $examination->device->serial_number;
        $data['valid_thru'] = $examination->device->valid_thru;
        $data['approveBy'] = $approval->approveBy;
        
        // switch ($approval->authentikasi->dir_name) {
		// 	case 'authentikasi.registrasi':
		// 		$content = $this->parsingSertifikat($approval->content);
		// 		break;
		// 	case 'authentikasi.pembayaran':
		// 		$content = $this->parsingEmailPembayaran($email->content, $user->name);
		// 		break;
		// 	case 'authentikasi.sertifikat':
		// 		$content = $this->parsingEmailSertifikat($email->content, $user->name, $exam->is_loc_test);
		// 		break;
		// 	default:
		// 		$content = null;
		// 		$subject = null;
		// 		break;
		// }

        return view($approval->authentikasi->dir_name)->with('data', $data);
    }

}