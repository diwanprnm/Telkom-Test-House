<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Company;
use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\ExaminationHistory;
use App\User;
use App\Logs;
use App\Income;
use App\Questioner;
use App\Equipment;
use App\EquipmentHistory;

use Auth;
use File;
use Mail;
use Session;
use Response;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Events\Notification;
use App\NotificationTable;

class ExaminationController extends Controller
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
        $currentUser = Auth::user();

        if ($currentUser){
            $message = null;
            $paginate = 5;
            $search = trim($request->input('search'));
            $comp_stat = '';
            $type = '';
            $status = '';
			$before = null;
            $after = null;

            $examType = ExaminationType::all();

            $query = Examination::whereNotNull('created_at')
                                ->with('user')
                                ->with('company')
                                ->with('examinationType')
                                ->with('examinationLab')
                                ->with('media')
                                ->with('device');
			$query->where(function($qry){
				$qry->where(function($q){
					return $q->where('registration_status', '!=', '1')
						->orWhere('function_status', '!=', '1')
						->orWhere('contract_status', '!=', '1')
						->orWhere('spb_status', '!=', '1')
						->orWhere('payment_status', '!=', '1')
						->orWhere('spk_status', '!=', '1')
						->orWhere('examination_status', '!=', '1')
						->orWhere('resume_status', '!=', '1')
						->orWhere('qa_status', '!=', '1')
						->orWhere('certificate_status', '!=', '1')
						->orWhere('location', '!=', '1')
						;
					})
					->where('examination_type_id', '=', '1')
				->orWhere(function($q){
					return $q->where('registration_status', '!=', '1')
						->orWhere('function_status', '!=', '1')
						->orWhere('contract_status', '!=', '1')
						->orWhere('spb_status', '!=', '1')
						->orWhere('payment_status', '!=', '1')
						->orWhere('spk_status', '!=', '1')
						->orWhere('examination_status', '!=', '1')
						->orWhere('resume_status', '!=', '1')
						->orWhere('location', '!=', '1')
						;
					})->where('examination_type_id', '!=', '1')
					;
			});
			if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->whereHas('device', function ($q) use ($search){
							return $q->where('name', 'like', '%'.strtolower($search).'%');
						})
					->orWhereHas('company', function ($q) use ($search){
							return $q->where('name', 'like', '%'.strtolower($search).'%');
						});
                });

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "EXAMINATION";
                $logs->save();
            }

            if ($request->has('comp_stat')){
                $comp_stat = $request->get('comp_stat');
                if($request->input('comp_stat') != 'all'){
					$query->where(function($q) use($request){
					return $q->where('registration_status', '=', $request->get('comp_stat'))
						->orWhere('function_status', '=', $request->get('comp_stat'))
						->orWhere('contract_status', '=', $request->get('comp_stat'))
						->orWhere('spb_status', '=', $request->get('comp_stat'))
						->orWhere('payment_status', '=', $request->get('comp_stat'))
						->orWhere('spk_status', '=', $request->get('comp_stat'))
						->orWhere('examination_status', '=', $request->get('comp_stat'))
						->orWhere('resume_status', '=', $request->get('comp_stat'))
						->orWhere('qa_status', '=', $request->get('comp_stat'))
						->orWhere('certificate_status', '=', $request->get('comp_stat'))
						;
					});
				}
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
                        $query->where('registration_status', 1);
                        $status = 1;
                        break;
                    case 2:
                        $query->where('function_status', 1);
                        $status = 2;
                        break;
                    case 3:
                        $query->where('contract_status', 1);
                        $status = 3;
                        break;
                    case 4:
                        $query->where('spb_status', 1);
                        $status = 4;
                        break;
                    case 5:
                        $query->where('payment_status', 1);
                        $status = 5;
                        break;
                    case 6:
                        $query->where('spk_status', 1);
                        $status = 6;
                        break;
                    case 7:
                        $query->where('examination_status', 1);
                        $status = 7;
                        break;
                    case 8:
                        $query->where('resume_status', 1);
                        $status = 8;
                        break;
                    case 9:
                        $query->where('qa_status', 1);
                        $status = 9;
                        break;
                    case 10:
                        $query->where('certificate_status', 1);
                        $status = 10;
                        break;
                    
                    default:
						$status = 'all';
                        break;
                }
            }
			
			if ($request->has('before_date')){
				$query->where('spk_date', '<=', $request->get('before_date'));
				$before = $request->get('before_date');
			}

			if ($request->has('after_date')){
				$query->where('spk_date', '>=', $request->get('after_date'));
				$after = $request->get('after_date');
			}

			$data_excel = $query->orderBy('updated_at', 'desc')->get();
            $data = $query->orderBy('updated_at', 'desc')
                        ->paginate($paginate);
						
			$request->session()->put('excel_pengujian', $data_excel);

            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.examination.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('comp_stat', $comp_stat)
                ->with('type', $examType)
                ->with('search', $search)
                ->with('filterType', $type)
                ->with('status', $status)
				->with('before_date', $before)
                ->with('after_date', $after);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = Examination::where('id', $id)
                            ->with('user')
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('device')
                            ->with('media')
                            ->first();
							
		$exam_history = ExaminationHistory::whereNotNull('created_at')
                    ->where('examination_id', $id)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        return view('admin.examination.show')
            ->with('exam_history', $exam_history)
            ->with('data', $exam);
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = Examination::where('id', $id)
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('device')
                            ->with('media')
                            ->with('equipment')
                            ->first();

        $labs = ExaminationLab::all();
		// $gen_spk_code = $this->generateSPKCOde($exam->examinationLab->lab_code,$exam->examinationType->name,date('Y'));
		
		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		
		$query_lab = "SELECT action_date FROM equipment_histories WHERE location = 3 AND examination_id = '".$id."' ORDER BY created_at DESC LIMIT 1";
		$data_lab = DB::select($query_lab);
		
		$query_gudang = "SELECT action_date FROM equipment_histories WHERE location = 2 AND examination_id = '".$id."' ORDER BY created_at DESC LIMIT 2";
		$data_gudang = DB::select($query_gudang);
		
		// $res_exam_schedule = $client->post('notification/notifToTE?lab='.$exam->examinationLab->lab_code)->getBody();
		$res_exam_schedule = $client->get('spk/searchData?spkNumber='.$exam->spk_code)->getBody();
		$exam_schedule = json_decode($res_exam_schedule);
		
		$res_exam_approve_date = $client->get('spk/searchHistoryData?spkNumber='.$exam->spk_code)->getBody();
		$exam_approve_date = json_decode($res_exam_approve_date);

        return view('admin.examination.edit')
            ->with('data', $exam)
            // ->with('gen_spk_code', $gen_spk_code)
            ->with('labs', $labs)
            ->with('data_lab', $data_lab)
            ->with('data_gudang', $data_gudang)
			->with('exam_approve_date', $exam_approve_date)
			->with('exam_schedule', $exam_schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$currentUser = Auth::user();

        $exam = Examination::find($id);
			$device = Device::findOrFail($exam->device_id);
			$exam_type = ExaminationType::findOrFail($exam->examination_type_id);

        if ($request->has('examination_lab_id')){
            $exam->examination_lab_id = $request->input('examination_lab_id');
        }
        if ($request->has('spk_code')){
            $exam->spk_code = $request->input('spk_code');
			if($this->checkSPKCode($request->input('spk_code')) > 0){
				Session::flash('error', 'SPK Number must be unique, please Re-Generate');
                return redirect('/admin/examination/'.$exam->id.'/edit');
			}
        }
        if ($request->has('registration_status')){
			$status = $request->input('registration_status');
            $exam->registration_status = $status;
			$exam->is_loc_test = $request->input('is_loc_test');
			if($status == 1){
				/* push notif*/ 
				$data= array( 
	                "from"=>"admin",
	                "to"=>$exam->created_by,
	                "message"=>"Registrasi Completed",
	                "url"=>"pengujian/".$exam->id."/detail",
	                "is_read"=>0,
	                "created_at"=>date("Y-m-d H:i:s"),
	                "updated_at"=>date("Y-m-d H:i:s")
                );
				$notification = new NotificationTable();
                $notification->id = Uuid::uuid4();
			    $notification->from = $data['from'];
			      $notification->to = $data['to'];
			      $notification->message = $data['message'];
			      $notification->url = $data['url'];
			      $notification->is_read = $data['is_read'];
			      $notification->created_at = $data['created_at'];
			      $notification->updated_at = $data['updated_at'];
			      $notification->save();

			     	$data['id'] = $notification->id;

			    event(new Notification($data));
				
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.registrasi", "Acc Registrasi");
			}else if($status == -1){
				// $exam->keterangan = $request->input('keterangan');
				/* push notif*/
				
			     $data= array( 
                "from"=>"admin",
                "to"=>$exam->created_by,
                "message"=>"Registrasi Not Completed",
                "url"=>"pengujian/".$exam->id."/detail",
                "is_read"=>0,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
                );
				  $notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      $notification->from = $data['from'];
			      $notification->to = $data['to'];
			      $notification->message = $data['message'];
			      $notification->url = $data['url'];
			      $notification->is_read = $data['is_read'];
			      $notification->created_at = $data['created_at'];
			      $notification->updated_at = $data['updated_at'];
			      $notification->save(); 

			      $data['id'] = $notification->id;
			      event(new Notification($data));
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Registrasi",$request->input('keterangan'));
			}
        }
		if ($request->has('function_status')){
			if ($request->hasFile('barang_file')) {
				$name_file = 'form_penerimaan_barang_'.$request->file('barang_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('barang_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'Bukti Penerimaan & Pengeluaran Perangkat Uji1')->where('examination_id', ''.$exam->id.'')->first();
					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'Bukti Penerimaan & Pengeluaran Perangkat Uji1';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}
					
					 return redirect('/admin/examination/'.$exam->id.'/edit');
				}else{
					Session::flash('error', 'Save Bukti Penerimaan & Pengeluaran Perangkat Uji to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
			if ($request->hasFile('function_file')) {
				/*$ext_file = $request->file('function_file')->getClientOriginalExtension();
				$name_file = uniqid().'_function_'.$exam->id.'.'.$ext_file;*/
				$name_file = 'function_'.$request->file('function_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('function_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'Laporan Hasil Uji Fungsi')->where('examination_id', ''.$exam->id.'')->first();
					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'Laporan Hasil Uji Fungsi';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}

				}else{
					Session::flash('error', 'Save Function Test Report to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
			$status = $request->input('function_status');
			if($exam->function_date != null){
				$exam->contract_date = $exam->function_date;
			}
			else if($exam->deal_test_date != null){
				$exam->contract_date = $exam->deal_test_date;
			}else{
				$exam->contract_date = date('Y-m-d');				
			}
			$exam->function_status = $status;
			if($status == 1){
				/* push notif*/
	            
                if ($exam->function_test_TE == 1){
                    $data= array( 
                        "from"=>"admin",
                        "to"=>$exam->created_by,
                        "message"=>"Hasil Uji Fungsi Memenuhi",
                        "url"=>"pengujian/".$exam->id."/detail",
                        "is_read"=>0,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "updated_at"=>date("Y-m-d H:i:s")
                    );
                }
                else if ($exam->function_test_TE == 2){
                    $data= array( 
                        "from"=>"admin",
                        "to"=>$exam->created_by,
                        "message"=>"Hasil Uji Fungsi Tidak Memenuhi",
                        "url"=>"pengujian/".$exam->id."/detail",
                        "is_read"=>0,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "updated_at"=>date("Y-m-d H:i:s")
                    );   
                }
                else if ($exam->function_test_TE == 3){
                    $data= array( 
                        "from"=>"admin",
                        "to"=>$exam->created_by,
                        "message"=>"Hasil Uji Fungsi lain-lain",
                        "url"=>"pengujian/".$exam->id."/detail",
                        "is_read"=>0,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "updated_at"=>date("Y-m-d H:i:s")
                    );
                }
	              $notification = new NotificationTable();
                  $notification->id = Uuid::uuid4();
	              $notification->from = $data['from'];
	              $notification->to = $data['to'];
	              $notification->message = $data['message'];
	              $notification->url = $data['url'];
	              $notification->is_read = $data['is_read'];
	              $notification->created_at = $data['created_at'];
	              $notification->updated_at = $data['updated_at'];
	              $notification->save();
	              $data['id'] = $notification->id;
	              	
	               event(new Notification($data));

				// $this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.function", "Acc Uji Fungsi");
			}else if($status == -1){
				/* push notif*/
		            
					if ($exam->function_test_TE == 1){
                        $data= array( 
                            "from"=>"admin",
                            "to"=>$exam->created_by,
                            "message"=>"Hasil Uji Fungsi Memenuhi",
                            "url"=>"pengujian/".$exam->id."/detail",
                            "is_read"=>0,
                            "created_at"=>date("Y-m-d H:i:s"),
                            "updated_at"=>date("Y-m-d H:i:s")
                        );
                    }
                    else if ($exam->function_test_TE == 2){
                        $data= array( 
                            "from"=>"admin",
                            "to"=>$exam->created_by,
                            "message"=>"Hasil Uji Fungsi Tidak Memenuhi",
                            "url"=>"pengujian/".$exam->id."/detail",
                            "is_read"=>0,
                            "created_at"=>date("Y-m-d H:i:s"),
                            "updated_at"=>date("Y-m-d H:i:s")
                        );   
                    }
                    else if ($exam->function_test_TE == 3){
                        $data= array( 
                            "from"=>"admin",
                            "to"=>$exam->created_by,
                            "message"=>"Hasil Uji Fungsi lain-lain",
                            "url"=>"pengujian/".$exam->id."/detail",
                            "is_read"=>0,
                            "created_at"=>date("Y-m-d H:i:s"),
                            "updated_at"=>date("Y-m-d H:i:s")
                        );
                    }
		              $notification = new NotificationTable();
                      $notification->id = Uuid::uuid4();
		              $notification->from = $data['from'];
		              $notification->to = $data['to'];
		              $notification->message = $data['message'];
		              $notification->url = $data['url'];
		              $notification->is_read = $data['is_read'];
		              $notification->created_at = $data['created_at'];
		              $notification->updated_at = $data['updated_at'];
		              $notification->save();

		              $data['id'] = $notification->id;

		                event(new Notification($data));
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Uji Fungsi",$request->input('keterangan'));
			}
        }
        if ($request->has('contract_status')){
			if ($request->hasFile('contract_file')) {
				/*$ext_file = $request->file('contract_file')->getClientOriginalExtension();
				$name_file = uniqid().'_contract_'.$exam->id.'.'.$ext_file;*/
				$name_file = 'contract_'.$request->file('contract_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('contract_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'Tinjauan Kontrak')->where('examination_id', ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'Tinjauan Kontrak';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}
				}else{
					Session::flash('error', 'Save contract review to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
			$status = $request->input('contract_status');
            $exam->contract_status = $status;
			if($status == 1){
				$path_file = public_path().'/media/examination/'.$id;
				$attach = ExaminationAttach::where('name', 'Tinjauan Kontrak')->where('examination_id', ''.$id.'')->first();
					$attach_name = $attach->attachment;
				// $this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.contract", "Upload Tinjauan Pustaka",$path_file."/".$attach_name);
				if($exam->contract_status){
					/* push notif*/
		            

		              $data= array( 
		                    "from"=>"admin",
		                    "to"=>$exam->created_by,
		                    "message"=>"Tinjauan Kontrak Completed",
		                    "url"=>"pengujian/".$id."/detail",
		                    "is_read"=>0,
		                    "created_at"=>date("Y-m-d H:i:s"),
		                    "updated_at"=>date("Y-m-d H:i:s")
		                    );
		              $notification = new NotificationTable();
$notification->id = Uuid::uuid4();
		              $notification->from = $data['from'];
		              $notification->to = $data['to'];
		              $notification->message = $data['message'];
		              $notification->url = $data['url'];
		              $notification->is_read = $data['is_read'];
		              $notification->created_at = $data['created_at'];
		              $notification->updated_at = $data['updated_at'];
		              $notification->save();

		              $data['id'] = $notification->id;
		              event(new Notification($data));
				}else{
					/* push notif*/
		            $data= array( 
		                    "from"=>"admin",
		                    "to"=>$exam->created_by,
		                    "message"=>"Tinjauan Kontrak Not Completed",
		                    "url"=>"pengujian/".$id."/detail",
		                    "is_read"=>0,
		                    "created_at"=>date("Y-m-d H:i:s"),
		                    "updated_at"=>date("Y-m-d H:i:s")
		                    );

		              $notification = new NotificationTable();
$notification->id = Uuid::uuid4();
		              $notification->from = $data['from'];
		              $notification->to = $data['to'];
		              $notification->message = $data['message'];
		              $notification->url = $data['url'];
		              $notification->is_read = $data['is_read'];
		              $notification->created_at = $data['created_at'];
		              $notification->updated_at = $data['updated_at'];
		              $notification->save();

		              $data['id'] = $notification->id;

		              event(new Notification($data));
				}
				
			}else if($status == -1){
				// $exam->keterangan = $request->input('keterangan');
				
				
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Tinjauan Pustaka",$request->input('keterangan'));
			}
        }
		if ($request->has('spb_status')){
			if ($request->hasFile('spb_file')) {
				/*$ext_file = $request->file('spb_file')->getClientOriginalExtension();
				$name_file = uniqid().'_spb_'.$exam->id.'.'.$ext_file;*/
				$name_file = 'spb_'.$request->file('spb_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('spb_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'SPB')->where('examination_id', ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'SPB';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}					
				}else{
					Session::flash('error', 'Save spb to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
			$status = $request->input('spb_status');
            $exam->spb_status = $status;
			if($status == 1){
				$exam->price = $request->input('exam_price');
				$path_file = public_path().'/media/examination/'.$id;
				$attach = ExaminationAttach::where('name', 'SPB')->where('examination_id', ''.$id.'')->first();
					$attach_name = $attach->attachment;

				/* push notif*/
	           	$data= array( 
	                    "from"=>"admin",
	                    "to"=>$exam->created_by,
	                    "message"=>"URel mengirimkan SPB untuk dibayar",
	                    "url"=>"pengujian/".$exam->id."/pembayaran",
	                    "is_read"=>0,
	                    "created_at"=>date("Y-m-d H:i:s"),
	                    "updated_at"=>date("Y-m-d H:i:s")
	                    );

	              $notification = new NotificationTable();
$notification->id = Uuid::uuid4();
	              $notification->from = $data['from'];
	              $notification->to = $data['to'];
	              $notification->message = $data['message'];
	              $notification->url = $data['url'];
	              $notification->is_read = $data['is_read'];
	              $notification->created_at = $data['created_at'];
	              $notification->updated_at = $data['updated_at'];
	              $notification->save();
	               	$data['id'] = $notification->id;
	               event(new Notification($data));

				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.spb", "Upload SPB",$path_file."/".$attach_name);
			}else if($status == -1){
				$exam->price = $request->input('exam_price');
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","SPB",$request->input('keterangan'));
			}
        }
		$spk_created = 0;
        if ($request->has('payment_status')){
			if ($request->hasFile('kuitansi_file')) {
				$name_file = 'kuitansi_'.$request->file('kuitansi_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('kuitansi_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'Kuitansi')->where('examination_id', ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'Kuitansi';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}					
				}else{
					Session::flash('error', 'Save kuitansi to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
			if ($request->hasFile('faktur_file')) {
				$name_file = 'faktur_'.$request->file('faktur_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('faktur_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'Faktur Pajak')->where('examination_id', ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'Faktur Pajak';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}					
				}else{
					Session::flash('error', 'Save Faktur Pajak to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
            $status = $request->input('payment_status');
            $exam->payment_status = $status;
			$exam->spk_status = $status;
			if($status == 1){
				if($this->cekRefID($exam->id) == 0){
					$income = new Income;
					$income->id = Uuid::uuid4();
					$income->company_id = $exam->company_id;
					$income->inc_type = 1; 
					$income->reference_id = $exam->id; 
					$income->reference_number = $request->input('spb_number');
					$income->tgl = $request->input('spb_date');
					$income->created_by = $currentUser->id;

				}else{
					$income = Income::where('reference_id', $exam->id)->first();
				}
					$income->price = $request->input('exam_price');
					$income->updated_by = $currentUser->id;
					$income->save();
				$path_file = public_path().'/media/examination/'.$id;
				$attach = ExaminationAttach::where('name', 'Kuitansi')->where('examination_id', ''.$id.'')->first();
					$attach_name = $attach->attachment;
				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran",$path_file."/".$attach_name);
				
				$client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				$exam_forOTR = Examination::where('id', $exam->id)
				->with('examinationType')
				->with('examinationLab')
				->first()
				;
				$spk_number_forOTR = $this->generateSPKCOde($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
				$exam->spk_code = $spk_number_forOTR;
				$exam->spk_date = date('Y-m-d');
					$spk_created = 1;
				// $res_exam_schedule = $client->post('notification/notifToTE?lab=?'.$exam->examinationLab->lab_code)->getBody();
				// $res_exam_schedule = $client->get('spk/addNotif?id='.$exam->id.'&spkNumber='.$spk_number_forOTR);
				if($exam->payment_status){
					
						$data= array( 
		                "from"=>"admin",
		                "to"=>$exam->created_by,
		                "message"=>"Pembayaran Completed",
		                "url"=>"pengujian/".$exam->id."/detail",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
		                );
				  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      	$notification->from = $data['from'];
			      	$notification->to = $data['to'];
			      	$notification->message = $data['message'];
			      	$notification->url = $data['url'];
			      	$notification->is_read = $data['is_read'];
			      	$notification->created_at = $data['created_at'];
			      	$notification->updated_at = $data['updated_at'];
			      	$notification->save();
			      	$data['id'] = $notification->id;
			      
			        event(new Notification($data));
				}else{
						$data= array( 
		                "from"=>"admin",
		                "to"=>$exam->created_by,
		                "message"=>"Pembayaran Not Completed",
		                "url"=>"pengujian/".$exam->id."/detail",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
	                );
				  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      	$notification->from = $data['from'];
			      	$notification->to = $data['to'];
			      	$notification->message = $data['message'];
			      	$notification->url = $data['url'];
			      	$notification->is_read = $data['is_read'];
			      	$notification->created_at = $data['created_at'];
			      	$notification->updated_at = $data['updated_at'];
			      	$notification->save();

			      	$data['id'] = $notification->id;


			        event(new Notification($data));
				}
				
				// $exam_schedule = json_decode($res_exam_schedule);
			}else if($status == -1){
				Income::where('reference_id', '=' ,''.$exam->id.'')->delete();
				// $exam->keterangan = $request->input('keterangan');
				
				
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pembayaran",$request->input('keterangan'));
			}
        }
        if ($request->has('spk_status')){
            $status = $request->input('spk_status');
            $exam->spk_status = $status;
			if($status == -1){
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pembuatan SPK",$request->input('keterangan'));
			}
        }
        if ($request->has('examination_status')){
            $status = $request->input('examination_status');
            $exam->examination_status = $status;
			if($status == -1){
			
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pelaksanaan Uji",$request->input('keterangan'));
			}else{
				if($status ){
					
					$data= array( 
		                "from"=>"admin",
		                "to"=>$exam->created_by,
		                "message"=>"Pelaksanaan Uji Completed",
		                "url"=>"pengujian/".$exam->id."/detail",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
	                );
				  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      	$notification->from = $data['from'];
			      	$notification->to = $data['to'];
			      	$notification->message = $data['message'];
			      	$notification->url = $data['url'];
			      	$notification->is_read = $data['is_read'];
			      	$notification->created_at = $data['created_at'];
			      	$notification->updated_at = $data['updated_at'];  
			      	$notification->save();

			      	$data['id'] = $notification->id;

			      	event(new Notification($data));
				}else{ 
					$data= array( 
		                "from"=>"admin",
		                "to"=>$exam->created_by,
		                "message"=>"Pelaksanaan Uji Not Completed",
		                "url"=>"pengujian/".$exam->id."/detail",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
	                );
				  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      	$notification->from = $data['from'];
			      	$notification->to = $data['to'];
			      	$notification->message = $data['message'];
			      	$notification->url = $data['url'];
			      	$notification->is_read = $data['is_read'];
			      	$notification->created_at = $data['created_at'];
			      	$notification->updated_at = $data['updated_at'];
			      	$notification->save();
			      	$data['id'] = $notification->id;
			      	event(new Notification($data));
				}
				
			}
        }
        if ($request->has('resume_status')){
			// if ($request->hasFile('lap_uji_file')) {
				// $ext_file = $request->file('lap_uji_file')->getClientOriginalExtension();
				// $name_file = uniqid().'_resume_'.$exam->id.'.'.$ext_file;
				// $path_file = public_path().'/media/examination/'.$exam->id;
				// if (!file_exists($path_file)) {
					// mkdir($path_file, 0775);
				// }
				// if($request->file('lap_uji_file')->move($path_file,$name_file)){
					// $attach = ExaminationAttach::where('name', 'Laporan Uji')->where('examination_id', ''.$exam->id.'')->first();

					// if ($attach){
						// $attach->attachment = $name_file;
						// $attach->updated_by = $currentUser->id;

						// $attach->save();
					// } else{
						// $attach = new ExaminationAttach;
						// $attach->id = Uuid::uuid4();
						// $attach->examination_id = $exam->id; 
						// $attach->name = 'Laporan Uji';
						// $attach->attachment = $name_file;
						// $attach->created_by = $currentUser->id;
						// $attach->updated_by = $currentUser->id;

						// $attach->save();
					// }
				// }else{
					// Session::flash('error', 'Save resume to directory failed');
					// return redirect('/admin/examination/'.$exam->id.'/edit');
				// }
			// }
            $status = $request->input('resume_status');
            $exam->resume_status = $status;
			
			if($status == -1){
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Laporan Uji",$request->input('keterangan'));
			
			}else{
				if($status ){
					
					$data= array( 
		                "from"=>"admin",
		                "to"=>$exam->created_by,
		                "message"=>"Laporan Uji Completed",
		                "url"=>"pengujian/".$exam->id."/detail",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
	                	"updated_at"=>date("Y-m-d H:i:s")
	                );

				  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      	$notification->from = $data['from'];
			      	$notification->to = $data['to'];
			      	$notification->message = $data['message'];
			      	$notification->url = $data['url'];
			      	$notification->is_read = $data['is_read'];
			      	$notification->created_at = $data['created_at'];
			      	$notification->updated_at = $data['updated_at'];
			      	$notification->save();

			      	$data['id'] = $notification->id;

			      	event(new Notification($data));
				}else{ 
					$data= array( 
		                "from"=>"admin",
		                "to"=>$exam->created_by,
		                "message"=>"Laporan Uji Not Completed",
		                "url"=>"pengujian/".$exam->id."/detail",
		                "is_read"=>0,
		                "created_at"=>date("Y-m-d H:i:s"),
		                "updated_at"=>date("Y-m-d H:i:s")
	                );
				  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
			      	$notification->from = $data['from'];
			      	$notification->to = $data['to'];
			      	$notification->message = $data['message'];
			      	$notification->url = $data['url'];
			      	$notification->is_read = $data['is_read'];
			      	$notification->created_at = $data['created_at'];
			      	$notification->updated_at = $data['updated_at'];
			      	$notification->save();

			      	
			      	$data['id'] = $notification->id;
			      	event(new Notification($data));
				}
				
			}
        }
		if ($request->hasFile('barang_file2')) {
			$name_file = 'form_penerimaan_barang2_'.$request->file('barang_file2')->getClientOriginalName();
			$path_file = public_path().'/media/examination/'.$exam->id;
			if (!file_exists($path_file)) {
				mkdir($path_file, 0775);
			}
			if($request->file('barang_file2')->move($path_file,$name_file)){
				$attach = ExaminationAttach::where('name', 'Bukti Penerimaan & Pengeluaran Perangkat Uji2')->where('examination_id', ''.$exam->id.'')->first();
				if ($attach){
					$attach->attachment = $name_file;
					$attach->updated_by = $currentUser->id;

					$attach->save();
				} else{
					$attach = new ExaminationAttach;
					$attach->id = Uuid::uuid4();
					$attach->examination_id = $exam->id; 
					$attach->name = 'Bukti Penerimaan & Pengeluaran Perangkat Uji2';
					$attach->attachment = $name_file;
					$attach->created_by = $currentUser->id;
					$attach->updated_by = $currentUser->id;

					$attach->save();
				}
				return redirect('/admin/examination/'.$exam->id.'/edit');
			}else{
				Session::flash('error', 'Save Bukti Penerimaan & Pengeluaran Perangkat Uji to directory failed');
				return redirect('/admin/examination/'.$exam->id.'/edit');
			}
		}
        if ($request->has('qa_status')){
            $status = $request->input('qa_status');
            $passed = $request->input('passed');
            $exam->qa_status = $status;
            $exam->qa_passed = $passed;
            if($exam->qa_passed == 1){  
            	$data= array( 
	                "from"=>"admin",
	                "to"=>$exam->created_by,
	                "message"=>"Perangkat Lulus Sidang QA",
	                "url"=>"pengujian/".$exam->id."/detail",
	                "is_read"=>0,
	                "created_at"=>date("Y-m-d H:i:s"),
	                "updated_at"=>date("Y-m-d H:i:s")
	            );

			  	$notification = new NotificationTable();
                $notification->id = Uuid::uuid4();
		      	$notification->from = $data['from'];
		      	$notification->to = $data['to'];
		      	$notification->message = $data['message'];
		      	$notification->url = $data['url'];
		      	$notification->is_read = $data['is_read'];
		      	$notification->created_at = $data['created_at'];
		      	$notification->updated_at = $data['updated_at'];
		      	$notification->save(); 
		      	$data['id'] = $notification->id;
	            event(new Notification($data));
            }else{ 

		      	$data= array( 
	                "from"=>"admin",
	                "to"=>$exam->created_by,
	                "message"=>"Perangkat tidak lulus Sidang QA",
	                "url"=>"pengujian/".$exam->id."/detail",
	                "is_read"=>0,
	                "created_at"=>date("Y-m-d H:i:s"),
	                "updated_at"=>date("Y-m-d H:i:s")
                );
			  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
		      	$notification->from = $data['from'];
		      	$notification->to = $data['to'];
		      	$notification->message = $data['message'];
		      	$notification->url = $data['url'];
		      	$notification->is_read = $data['is_read'];
		      	$notification->created_at = $data['created_at'];
		      	$notification->updated_at = $data['updated_at'];
		      	$notification->save();

		      	$data['id'] = $notification->id;
                event(new Notification($data));
            }
           
			if($status == -1){
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Sidang QA",$request->input('keterangan'));
			}
        }
        if ($request->has('certificate_status')){
            $status = $request->input('certificate_status');
            $exam->certificate_status = $status;
            if($exam->certificate_status){ 
            	$data= array(  
                "from"=>"admin",
                "to"=>$exam->created_by,
                "message"=>"Sertifikat Completed",
                "url"=>"pengujian/".$exam->id."/detail",
                "is_read"=>0,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
                );
			  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
		      	$notification->from = $data['from'];
		      	$notification->to = $data['to'];
		      	$notification->message = $data['message'];
		      	$notification->url = $data['url'];
		      	$notification->is_read = $data['is_read'];
		      	$notification->created_at = $data['created_at'];
		      	$notification->updated_at = $data['updated_at'];
		      	$notification->save();  
		     	$data['id'] = $notification->id;

                event(new Notification($data));
            }else{  

            	$data= array( 
                "from"=>"admin",
                "to"=>$exam->created_by,
                "message"=>"Sertifikat Not Completed",
                "url"=>"pengujian/".$exam->id."/detail",
                "is_read"=>0,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
                );
			  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
		      	$notification->from = $data['from'];
		      	$notification->to = $data['to'];
		      	$notification->message = $data['message'];
		      	$notification->url = $data['url'];
		      	$notification->is_read = $data['is_read'];
		      	$notification->created_at = $data['created_at'];
		      	$notification->updated_at = $data['updated_at'];
		      	$notification->save();

		      	$data['id'] = $notification->id;

                event(new Notification($data));
            }
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sertifikat", "Penerbitan Sertfikat");
			}else if($status == -1){
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pembuatan Sertifikat",$request->input('keterangan'));
			}
        }
        // if ($request->has('examination_date')){
            // $exam->examination_date = $request->input('examination_date');
        // }
        if ($request->has('resume_date')){
            $exam->resume_date = $request->input('resume_date');
        }
        if ($request->has('qa_date')){
            $exam->qa_date = $request->input('qa_date');
        }
        if ($request->has('certificate_date')){
            $exam->certificate_date = $request->input('certificate_date');
        }
		// if ($request->has('spk_date')){
            // $exam->spk_date = $request->input('spk_date');
        // }
		// if ($request->has('function_date')){
            // $exam->function_date = $request->input('function_date');
        // }
		if ($request->has('catatan')){
            $exam->catatan = $request->input('catatan');
        }
		if ($request->has('contract_date')){
            $exam->contract_date = $request->input('contract_date');
        }
		if ($request->has('testing_start')){
            $exam->testing_start = $request->input('testing_start');
        }
		if ($request->has('testing_end')){
            $exam->testing_end = $request->input('testing_end');
        }
		// if ($request->has('urel_test_date')){
            // $exam->urel_test_date = $request->input('urel_test_date');
        // }
		// if ($request->has('deal_test_date')){
            // $exam->deal_test_date = $request->input('deal_test_date');
        // }
		if ($request->has('spb_number')){
            $exam->spb_number = $request->input('spb_number');
        }
		if ($request->has('spb_date')){
            $exam->spb_date = $request->input('spb_date');
        }

        // if ($request->hasFile('resume_file')) {
            // $ext_file = $request->file('resume_file')->getClientOriginalExtension();
            // $name_file = uniqid().'_resume_'.$exam->id.'.'.$ext_file;
            // $path_file = public_path().'/media/examination/'.$exam->id;
            // if (!file_exists($path_file)) {
                // mkdir($path_file, 0775);
            // }
            // if($request->file('resume_file')->move($path_file,$name_file)){
                // $attach = ExaminationAttach::where('name', 'Laporan Uji')->first();

                // if ($attach){
                    // $attach->attachment = $name_file;
                    // $attach->updated_by = $currentUser->id;

                    // $attach->save();
                // } else{
                    // $attach = new ExaminationAttach;
                    // $attach->id = Uuid::uuid4();
                    // $attach->examination_id = $exam->id; 
                    // $attach->name = 'Laporan Uji';
                    // $attach->attachment = $name_file;
                    // $attach->created_by = $currentUser->id;
                    // $attach->updated_by = $currentUser->id;

                    // $attach->save();
                // }
            // }else{
                // Session::flash('error', 'Save spb to directory failed');
                // return redirect('/admin/examination/'.$exam->id.'/edit');
            // }
        // }

        if ($request->hasFile('certificate_file')) {
			$name_file = 'sertifikat_'.$request->file('certificate_file')->getClientOriginalName();
			$path_file = public_path().'/media/device/'.$exam->device_id;
            // $ext_file = $request->file('certificate_file')->getClientOriginalExtension();
            // $name_file = uniqid().'_certificate_'.$exam->device_id.'.'.$ext_file;
            // $path_file = public_path().'/media/device/'.$exam->device_id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('certificate_file')->move($path_file,$name_file)){
                $device = Device::findOrFail($exam->device_id);
                if ($device){
                    $device->certificate = $name_file;
                    $device->status = $request->input('certificate_status');
                    $device->valid_from = $request->input('valid_from');
                    $device->valid_thru = $request->input('valid_thru');

                    $device->save();
                }
            }else{
                Session::flash('error', 'Save spb to directory failed');
                return redirect('/admin/examination/'.$exam->id.'/edit');
            }
        }
		
			$device = Device::findOrFail($exam->device_id);
			$exam_type = ExaminationType::findOrFail($exam->examination_type_id);
		
        $exam->updated_by = $currentUser->id;

        try{
            $exam->save();
			if($spk_created == 1){
				$res_exam_schedule = $client->get('spk/addNotif?id='.$exam->id.'&spkNumber='.$spk_number_forOTR);				
			}
             
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $exam->id;
				$exam_hist->date_action = date('Y-m-d H:i:s');
				$exam_hist->tahap = $request->input('status');
					if ($request->has('registration_status')){$exam_hist->status = $request->input('registration_status');}
					if ($request->has('function_status')){$exam_hist->status = $request->input('function_status');}
					if ($request->has('contract_status')){$exam_hist->status = $request->input('contract_status');}
					if ($request->has('spb_status')){$exam_hist->status = $request->input('spb_status');}
					if ($request->has('payment_status')){$exam_hist->status = $request->input('payment_status');}
					if ($request->has('spk_status')){$exam_hist->status = $request->input('spk_status');}
					if ($request->has('examination_status')){$exam_hist->status = $request->input('examination_status');}
					if ($request->has('resume_status')){$exam_hist->status = $request->input('resume_status');}
					if ($request->has('qa_status')){$exam_hist->status = $request->input('qa_status');}
					if ($request->has('certificate_status')){$exam_hist->status = $request->input('certificate_status');}
				$exam_hist->keterangan = $request->input('keterangan');
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date('Y-m-d H:i:s');
				$exam_hist->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Update ".$request->input('status');   
                $logs->data = $exam;
                $logs->created_by = $currentUser->id;
                $logs->page = "EXAMINATION";
                $logs->save();

            Session::flash('message', 'Examination successfully updated');
            return redirect('/admin/examination');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/examination/'.$exam->id.'/edit');
        }
    }

    public function downloadForm($id)
    {
        $exam = Examination::find($id);

        if ($exam){
            $file = public_path().'/media/examination/'.$exam->id.'/'.$exam->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::download($file, $exam->attachment, $headers);
        }
    }

    public function printForm($id)
    {
        $exam = Examination::find($id);

        if ($exam){
            $file = public_path().'/media/examination/'.$exam->id.'/'.$exam->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file);
        }
    }

    public function downloadMedia($id, $name)
    {
        if (strcmp($name, 'certificate') == 0){
            $device = Device::findOrFail($id);

            if ($device){
                $file = public_path().'/media/device/'.$device->id.'/'.$device->certificate;
                $headers = array(
                  'Content-Type: application/octet-stream',
                );

                return Response::download($file, $device->attachment, $headers);
            }
        } else{
            $exam = ExaminationAttach::where('examination_id', $id)
                                ->where('name','like','%'.$name.'%')
                                ->first();

            if ($exam){
                $file = public_path().'/media/examination/'.$exam->examination_id.'/'.$exam->attachment;
                $headers = array(
                  'Content-Type: application/octet-stream',
                );

                return Response::download($file, $exam->attachment, $headers);
            }
        }
    }

    public function printMedia($id, $name)
    {
        if (strcmp($name, 'certificate') == 0){
            $device = Device::findOrFail($id);

            if ($device){
                $file = public_path().'/media/device/'.$device->id.'/'.$device->certificate;
                $headers = array(
                  'Content-Type: application/pdf',
                );

                return Response::file($file);
            }
        } else{
            $exam = ExaminationAttach::where('examination_id', $id)
                                ->where('name','like','%'.$name.'%')
                                ->first();

            if ($exam){
                $file = public_path().'/media/examination/'.$exam->examination_id.'/'.$exam->attachment;
                $headers = array(
                  'Content-Type: application/pdf',
                );

                return Response::file($file);
            }
        }
    }

    /**
     * Send an e-mail notification to the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function sendEmailNotification($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'dev_name' => $dev_name,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }
	
	public function sendEmailNotification_wAttach($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $attach)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'dev_name' => $dev_name,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc
			), function ($m) use ($data,$subject,$attach) {
            $m->to($data->email)->subject($subject);
			$m->attach($attach);
        });

        return true;
    }
	
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		
		$data = $request->session()->get('excel_pengujian');
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'Tipe Pengujian',
			'Nama Pemohon',
			'Email Pemohon',
			'Alamat Pemohon',
			'Telepon Pemohon',
			'Faksimil Pemohon',
			'Jenis Perusahaan',
			'Nama Perusahaan',
			'Alamat Perusahaan',
			'Email Perusahaan',
			'Telepon Perusahaan',
			'Faksimil Perusahaan',
			'NPWP Perusahaan',
			'SIUPP Perusahaan',
			'Tgl. SIUPP Perusahaan',
			'Sertifikat Perusahaan',
			'Tgl. Sertifikat Perusahaan',
			'Nama Perangkat',
			'Merk/Pabrik Perangkat',
			'Kapasitas/Kecepatan Perangkat',
			'Pembuat Perangkat',
			'Nomor Seri Perangkat',
			'Model/Tipe Perangkat',
			'Referensi Uji Perangkat',
			'Tanggal Berlaku Perangkat',
			'Nomor SPK',
			'Tanggal SPK',
			'Status Pengujian',
			'Registrasi',
			'Uji Fungsi',
			'Tinjauan Kontrak',
			'SPB',
			'Pembayaran',
			'Pembuatan SPK',
			'Pelaksanaan Uji',
			'Laporan Uji',
			'Sidang QA',
			'Penerbitan Sertifikat',
			'Total Biaya'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
		foreach ($data as $row) {
			if($row->registration_status < 1){
				$status_pengujian = 'Belum Terdaftar';
				$status_reg = 'Tidak';
				$status_func = 'Tidak';
				$status_cont = 'Tidak';
				$status_spb = 'Tidak';
				$status_pay = 'Tidak';
				$status_spk = 'Tidak';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->registration_status == 1 and $row->function_status < 1){
				$status_pengujian = 'Pendaftaran';
				$status_reg = 'Ya';
				$status_func = 'Tidak';
				$status_cont = 'Tidak';
				$status_spb = 'Tidak';
				$status_pay = 'Tidak';
				$status_spk = 'Tidak';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->function_status == 1 and $row->contract_status < 1){
				$status_pengujian = 'Uji Fungsi';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Tidak';
				$status_spb = 'Tidak';
				$status_pay = 'Tidak';
				$status_spk = 'Tidak';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->contract_status == 1 and $row->spb_status < 1){
				$status_pengujian = 'Tinjauan Kontrak';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Tidak';
				$status_pay = 'Tidak';
				$status_spk = 'Tidak';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->spb_status == 1 and $row->payment_status < 1){
				$status_pengujian = 'SPB';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Tidak';
				$status_spk = 'Tidak';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->payment_status == 1 and $row->spk_status < 1){
				$status_pengujian = 'Pembayaran';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Ya';
				$status_spk = 'Tidak';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->spk_status == 1 and $row->examination_status < 1){
				$status_pengujian = 'Pembuatan SPK';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Ya';
				$status_spk = 'Ya';
				$status_exam = 'Tidak';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->examination_status == 1 and $row->resume_status < 1){
				$status_pengujian = 'Pelaksanaan Uji';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Ya';
				$status_spk = 'Ya';
				$status_exam = 'Ya';
				$status_resu = 'Tidak';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->resume_status == 1 and $row->qa_status < 1){
				$status_pengujian = 'Laporan Uji';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Ya';
				$status_spk = 'Ya';
				$status_exam = 'Ya';
				$status_resu = 'Ya';
				$status_qa = 'Tidak';
				$status_cert = 'Tidak';
			}
			else if($row->qa_status == 1 and $row->certificate_status < 1){
				$status_pengujian = 'Sidang QA';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Ya';
				$status_spk = 'Ya';
				$status_exam = 'Ya';
				$status_resu = 'Ya';
				$status_qa = 'Ya';
				$status_cert = 'Tidak';
			}
			else if($row->certificate_status == 1){
				$status_pengujian = 'Penerbitan Sertifikat';
				$status_reg = 'Ya';
				$status_func = 'Ya';
				$status_cont = 'Ya';
				$status_spb = 'Ya';
				$status_pay = 'Ya';
				$status_spk = 'Ya';
				$status_exam = 'Ya';
				$status_resu = 'Ya';
				$status_qa = 'Ya';
				$status_cert = 'Ya';
			}
			if($row->company->siup_date==''){
				$siup_date = '';
			}else{
				$siup_date = date("d-m-Y", strtotime($row->company->siup_date));
			}
			if($row->company->qs_certificate_date==''){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date("d-m-Y", strtotime($row->company->qs_certificate_date));
			}
			if($row->device->valid_from==''){
				$valid_from = '';
			}else{
				$valid_from = date("d-m-Y", strtotime($row->device->valid_from));
			}
			if($row->device->valid_thru==''){
				$valid_thru = '';
			}else{
				$valid_thru = date("d-m-Y", strtotime($row->device->valid_thru));
			}
			if($row->spk_date==''){
				$spk_date = '';
			}else{
				$spk_date = date("d-m-Y", strtotime($row->spk_date));
			}
			$examsArray[] = [
				"".$row->examinationType->name." (".$row->examinationType->description.")",
				$row->user->name,
				$row->user->email,
				$row->user->address,
				$row->user->phone_number,
				$row->user->fax,
				$row->jns_perusahaan,
				$row->company->name,
				$row->company->address.", kota".$row->company->city.", kode pos".$row->company->postal_code,
				$row->company->email,
				$row->company->phone_number,
				$row->company->fax,
				$row->company->npwp_number,
				$row->company->siup_number,
				$siup_date,
				$row->company->qs_certificate_number,
				$qs_certificate_date,
				$row->device->name,
				$row->device->mark,
				$row->device->capacity,
				$row->device->manufactured_by,
				$row->device->serial_number,
				$row->device->model,
				$row->device->test_reference,
				$valid_from." s.d. ".$valid_thru,
				$row->spk_code,
				$spk_date,
				$status_pengujian,
				$status_reg,
				$status_func,
				$status_cont,
				$status_spb,
				$status_pay,
				$status_spk,
				$status_exam,
				$status_resu,
				$status_qa,
				$status_cert,
				$row->price
			];
		}
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "EXAMINATION";
        $logs->save();

		// Generate and return the spreadsheet
		Excel::create('Data Pengujian', function($excel) use ($examsArray) {

			// Set the spreadsheet title, creator, and description
			// $excel->setTitle('Payments');
			// $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
			// $excel->setDescription('payments file');

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx'); 
	}
	
	public function revisi($id)
    {
        $exam = Examination::where('id', $id)
                            ->with('user')
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('device')
                            ->with('media')
                            ->first();
		$query_stels = "SELECT * FROM stels WHERE is_active = 1";
		$data_stels = DB::select($query_stels);
		
        return view('admin.examination.revisi')
            ->with('data', $exam)
			->with('data_stels', $data_stels);
    }
	
	public function updaterevisi(Request $request)
    {
		$currentUser = Auth::user();

			$device = Device::findOrFail($request->input('id_perangkat'));

        if ($request->has('nama_perangkat')){
            $device->name = $request->input('nama_perangkat');
        }
			
        if ($request->has('merk_perangkat')){
            $device->mark = $request->input('merk_perangkat');
        }
			
        if ($request->has('kapasitas_perangkat')){
            $device->capacity = $request->input('kapasitas_perangkat');
        }
			
        if ($request->has('pembuat_perangkat')){
            $device->manufactured_by = $request->input('pembuat_perangkat');
        }
			
        if ($request->has('model_perangkat')){
            $device->model = $request->input('model_perangkat');
        }
			
        if ($request->has('cmb-ref-perangkat')){
            $device->test_reference = $request->input('cmb-ref-perangkat');
			$ref_perangkat = $request->input('cmb-ref-perangkat');
        }
			
        if ($request->has('ref_perangkat')){
            $device->test_reference = $request->input('ref_perangkat');
			$ref_perangkat = $request->input('ref_perangkat');
        }
			
        if ($request->has('sn_perangkat')){
            $device->serial_number = $request->input('sn_perangkat');
        }
			
		$device->updated_by = '".$currentUser->id."';
		$device->updated_at = date("Y-m-d H:i:s");
        
        try{
            $device->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "update";   
            $logs->data = $device;
            $logs->created_by = $currentUser->id;
            $logs->page = "REVISI";
            $logs->save();

            /* push notif*/
			$data= array( 
            	"from"=>$currentUser->id,
            	"to"=>$device->created_by,
            	"message"=>"Urel mengedit data pengujian",
            	"url"=>"pengujian/".$request->input('id_exam')."/detail",
            	"is_read"=>0,
            	"created_at"=>date("Y-m-d H:i:s"),
            	"updated_at"=>date("Y-m-d H:i:s")
            );

		  	$notification = new NotificationTable();
$notification->id = Uuid::uuid4();
	      	$notification->from = $data['from'];
	      	$notification->to = $data['to'];
	      	$notification->message = $data['message'];
	      	$notification->url = $data['url'];
	      	$notification->is_read = $data['is_read'];
	      	$notification->created_at = $data['created_at'];
	      	$notification->updated_at = $data['updated_at'];
	      	$notification->save();

	      	$data['id'] = $notification->id;
	      	event(new Notification($data));


            Session::flash('message', 'Examination successfully updated');
			$this->sendEmailRevisi(
				$request->input('exam_created'),
				$request->input('exam_type'),
				$request->input('exam_desc'),
				$request->input('hidden_nama_perangkat'),
				$request->input('nama_perangkat'),
				$request->input('hidden_merk_perangkat'),
				$request->input('merk_perangkat'),
				$request->input('hidden_kapasitas_perangkat'),
				$request->input('kapasitas_perangkat'),
				$request->input('hidden_pembuat_perangkat'),
				$request->input('pembuat_perangkat'),
				$request->input('hidden_model_perangkat'),
				$request->input('model_perangkat'),
				$request->input('hidden_ref_perangkat'),
				$ref_perangkat,
				$request->input('hidden_sn_perangkat'),
				$request->input('sn_perangkat'),
				"emails.revisi", 
				"Revisi Data Permohonan Uji"
			);
            return redirect('/admin/examination/'.$request->input('id_exam').'');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/examination/revisi/'.$request->input('id_exam').'');
        }
    }
	
	public function tanggalkontrak(Request $request)
    {
		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		
		$currentUser = Auth::user();
		
		$exam_id = $request->input('hide_id_exam');
		// $testing_start = $request->input('testing_start');
        // if($request->input('testing_start') == '' or $request->input('testing_start') == '0000-00-00' or $request->input('testing_start') == NULL){
            // $testing_start_ina = '_ _ - _ _ - _ _ _ _';
        // }else{
            // $testing_start_ina = date('d-m-Y', strtotime($request->input('testing_start')));
        // }
		// $testing_end = $request->input('testing_end');
        // if($request->input('testing_end') == '' or $request->input('testing_end') == '0000-00-00' or $request->input('testing_start') == NULL){
            // $testing_end_ina = '_ _ - _ _ - _ _ _ _';
        // }else{
            // $testing_end_ina = date('d-m-Y', strtotime($request->input('testing_end')));
        // }
		$contract_date = $request->input('contract_date');
			$contract_date_ina_temp = strtotime($contract_date);
			$contract_date_ina = date('d-m-Y', $contract_date_ina_temp);
		
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with('device')
				->with('examinationLab')
				->with('Equipment')
				->first();
			
			try{
				$query_update = "UPDATE examinations
					SET 
						contract_date = '".$contract_date."',
						updated_by = '".$currentUser['attributes']['id']."',
						updated_at = '".date('Y-m-d H:i:s')."'
					WHERE id = '".$exam_id."'
				";
				$data_update = DB::update($query_update);
				
				$res_manager_lab = $client->get('user/getManagerLabInfo?labCode='.$exam->examinationLab->lab_code)->getBody();
				$manager_lab = json_decode($res_manager_lab);
				
				if(count($manager_lab->data) == 1){
					if( strpos( $manager_lab->data[0]->name, "/" ) !== false ) {$manager_labs = urlencode(urlencode($manager_lab->data[0]->name));}
						else{$manager_labs = $manager_lab->data[0]->name?: '-';}
				}else{
					$manager_labs = '...............................';
				}
				
				if(count($exam->equipment)>0){
					if( strpos( $exam->equipment[0]->pic, "/" ) !== false ) {$pic = urlencode(urlencode($exam->equipment[0]->pic));}
						else{$pic = $exam->equipment[0]->pic?: '-';}
				}else{
					$pic = '...............................';
				}
				
				$data = Array([
					'nama_pemohon' => $exam->user->name,
					'alamat_pemohon' => $exam->user->address,
					'nama_perangkat' => $exam->device->name,
					'merek_perangkat' => $exam->device->mark,
					'model_perangkat' => $exam->device->model,
					'kapasitas_perangkat' => $exam->device->capacity,
					'referensi_perangkat' => $exam->device->test_reference,
					'pembuat_perangkat' => $exam->device->manufactured_by,
					'contract_date' => $contract_date_ina,
					'manager_lab' => $manager_labs,
					'pic' => $pic
				]);
				
				$request->session()->put('key_contract', $data);
				
				Session::flash('message', 'Contract successfully created');
				// $this->sendProgressEmail("Pengujian atas nama ".$user_name." dengan alamat email ".$user_email.", telah melakukan proses Upload Bukti Pembayaran");
				// return back();
				echo 1;
			} catch(Exception $e){
				Session::flash('error', 'Contract failed');
				// return back();
				echo 0;
			}
    }
	
	public function sendEmailRevisi(
		$user, 
		$exam_type, 
		$exam_type_desc, 
		$perangkat1, 
		$perangkat2, 
		$merk_perangkat1, 
		$merk_perangkat2, 
		$kapasitas_perangkat1, 
		$kapasitas_perangkat2, 
		$pembuat_perangkat1, 
		$pembuat_perangkat2, 
		$model_perangkat1, 
		$model_perangkat2, 
		$ref_perangkat1, 
		$ref_perangkat2, 
		$sn_perangkat1, 
		$sn_perangkat2, 
		$message,
		$subject
	)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc,
			'perangkat1' => $perangkat1,
			'perangkat2' => $perangkat2,
			'merk_perangkat1' => $merk_perangkat1,
			'merk_perangkat2' => $merk_perangkat2,
			'kapasitas_perangkat1' => $kapasitas_perangkat1,
			'kapasitas_perangkat2' => $kapasitas_perangkat2,
			'pembuat_perangkat1' => $pembuat_perangkat1,
			'pembuat_perangkat2' => $pembuat_perangkat2,
			'model_perangkat1' => $model_perangkat1,
			'model_perangkat2' => $model_perangkat2,
			'ref_perangkat1' => $ref_perangkat1,
			'ref_perangkat2' => $ref_perangkat2,
			'sn_perangkat1' => $sn_perangkat1,
			'sn_perangkat2' => $sn_perangkat2
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }
	
	public function sendEmailFailure($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $tahap, $keterangan)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'dev_name' => $dev_name,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc,
			'tahap' => $tahap,
			'keterangan' => $keterangan
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }
	
	public function destroy($id)
	{
		$exam_attach = ExaminationAttach::where('examination_id', '=' ,''.$id.'')->get();
		$exam = Examination::find($id);
			$device_id = $exam['device_id'];
		$device = Device::find($device_id);
		if ($exam_attach && $exam && $device){
			try{
				Income::where('reference_id', '=' ,''.$id.'')->delete();
				Questioner::where('examination_id', '=' ,''.$id.'')->delete();
				Equipment::where('examination_id', '=' ,''.$id.'')->delete();
				EquipmentHistory::where('examination_id', '=' ,''.$id.'')->delete();
				ExaminationHistory::where('examination_id', '=' ,''.$id.'')->delete();
				ExaminationAttach::where('examination_id', '=' ,''.$id.'')->delete();
				$exam->delete();
				$device->delete();
				
				if (File::exists(public_path().'\media\\examination\\'.$id)){
					File::deleteDirectory(public_path().'\media\\examination\\'.$id);
				}
				Session::flash('message', 'Examination successfully deleted');
				return redirect('/admin/examination');
			}catch (Exception $e){
				Session::flash('error', 'Delete failed');
				return redirect('/admin/examination');
			}
		}
	}
	
	public function autocomplete($query) {
        $respons_result = Examination::adm_exam_autocomplet($query);
        return response($respons_result);
    }
	
	public function checkSPKCode($a) {
        $query_exam = "SELECT * FROM examinations WHERE spk_code = '".$a."'";
		$data_exam = DB::select($query_exam);
		return count($data_exam);
    }
	
	public function generateSPKCodeManual(Request $request) {
		$gen_spk_code = $this->generateSPKCOde($request->input('lab_code'),$request->input('exam_type'),$request->input('year'));
		return $gen_spk_code;
    }
	
	public function generateSPKCode($a,$b,$c) {
		$query = "
			SELECT 
			SUBSTRING_INDEX(SUBSTRING_INDEX(spk_code,'/',2),'/',-1) + 1 AS last_numb
			FROM examinations WHERE 
			SUBSTRING_INDEX(spk_code,'/',1) = '".$a."' AND
			SUBSTRING_INDEX(SUBSTRING_INDEX(spk_code,'/',-2),'/',1) = '".$b."' AND
			SUBSTRING_INDEX(spk_code,'/',-1) = '".$c."'
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			return ''.$a.'/001/'.$b.'/'.$c.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return ''.$a.'/00'.$last_numb.'/'.$b.'/'.$c.'';
			}
			else if($last_numb < 100){
				return ''.$a.'/0'.$last_numb.'/'.$b.'/'.$c.'';
			}
			else{
				return ''.$a.'/'.$last_numb.'/'.$b.'/'.$c.'';
			}
		}
    }
	
	public function generateSPBNumber() {
		$thisYear = date('Y');
		$query = "
			SELECT SUBSTRING_INDEX(spb_number,'/',1) + 1 AS last_numb
			FROM examinations WHERE SUBSTRING_INDEX(spb_number,'/',-1) = ".$thisYear."
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			return '001/KU000/DDS-73/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.'/KU000/DDS-73/'.$thisYear.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.'/KU000/DDS-73/'.$thisYear.'';
			}
			else{
				return ''.$last_numb.'/KU000/DDS-73/'.$thisYear.'';
			}
		}
    }
	
	public function generateSPBParam(Request $request) {
		$request->session()->put('key_exam_id_for_generate_spb', $request->input('exam_id'));
		$request->session()->put('key_spb_number_for_generate_spb', $request->input('spb_number'));
		$request->session()->put('key_spb_date_for_generate_spb', $request->input('spb_date'));
		echo 1;
    }
	
	public function generateEquipParam(Request $request) {
		$request->session()->put('key_exam_id_for_generate_equip_masuk', $request->input('exam_id'));
		$request->session()->put('key_in_equip_date_for_generate_equip_masuk', $request->input('in_equip_date'));
		echo 1;
    }
	
	public function generateKuitansiParam(Request $request) {
		$kode = $this->generateKuitansiManual();
		$exam_id = $request->input('exam_id');
		$exam = Examination::where('id', $exam_id)
					->with('device')
					->with('company')
					->first();
		$request->session()->put('key_jenis_for_kuitansi', 1);
		$request->session()->put('key_id_for_kuitansi', $exam_id);
		$request->session()->put('key_kode_for_kuitansi', $kode);
		$request->session()->put('key_from_for_kuitansi', $exam->company->name);
		$request->session()->put('key_price_for_kuitansi', $exam->cust_price_payment?: '0');
		$request->session()->put('key_for_for_kuitansi', "Pengujian Perangkat ".$exam->device->name);
		echo 1;
    }
	
	public function generateKuitansiParamSTEL(Request $request) {
		$kode = $this->generateKuitansiManual();
		$exam_id = $request->input('exam_id');
		$query_stels = "SELECT DISTINCT
				s. code AS stel,
				ss. cust_price_payment,
				c. name
			FROM
				stels s,
				stels_sales ss,
				stels_sales_detail ssd,
				companies c,
				users u
			WHERE
				s.id = ssd.stels_id
			AND ss.id = ssd.stels_sales_id
			AND ss.user_id = u.id
			AND u.company_id = c.id
			AND ss.id = '".$exam_id."'
			ORDER BY s.code";
		$data_stels = DB::select($query_stels);
			$stel = $data_stels[0]->stel;
		for ($i=1;$i<count($data_stels);$i++) {
			$stel = $stel.", ".$data_stels[$i]->stel;
		}
			$stel = $stel.".";
		$request->session()->put('key_jenis_for_kuitansi', 2);
		$request->session()->put('key_id_for_kuitansi', $exam_id);
		$request->session()->put('key_kode_for_kuitansi', $kode);
		$request->session()->put('key_from_for_kuitansi', $data_stels[0]->name);
		$request->session()->put('key_price_for_kuitansi', $data_stels[0]->cust_price_payment?: '0');
		$request->session()->put('key_for_for_kuitansi', $stel);
		echo 1;
    }
	
	public function generateSPB(Request $request) {
		$exam_id = $request->session()->pull('key_exam_id_for_generate_spb');
		$spb_number = $request->session()->pull('key_spb_number_for_generate_spb');
		if($spb_number == "" or $spb_number == null){
			$spb_number = $this->generateSPBNumber();
		}
		$spb_date = $request->session()->pull('key_spb_date_for_generate_spb');
		$exam = Examination::where('id', $exam_id)
					->with('device')
					->first()
		;
		if($exam->examination_type_id == 2){
			$query_price = "SELECT ta_price as price FROM examination_charges WHERE stel LIKE '%".$exam->device->test_reference."%'";
		}
		else if($exam->examination_type_id == 3){
			$query_price = "SELECT vt_price as price FROM examination_charges WHERE stel LIKE '%".$exam->device->test_reference."%'";
		}else{
			$query_price = "SELECT price FROM examination_charges WHERE stel LIKE '%".$exam->device->test_reference."%'";
		}
		$price = DB::select($query_price);
		if(count($price) == 0){
			$price = 0;
		}else{
			$price = $price[0]->price;
		}
		
		$query_stels = "SELECT * FROM examination_charges ORDER BY device_name";
		$data_stels = DB::select($query_stels);
			
		// setlocale(LC_MONETARY, 'it_IT');
		// $price = number_format($price[0]->price, 0, ',', '.');
		return view('admin.examination.spb')
					->with('exam_id', $exam_id)
					->with('spb_number', $spb_number)
					->with('spb_date', $spb_date)
					->with('data', $exam)
					->with('price', $price)
					->with('data_stels', $data_stels)
		;
    }
	
	public function generateSPBData(Request $request) {
		if($this->cekSPBNumber($request->input('spb_number')) == 0){
			$exam_id = $request->input('exam_id');
			$spb_number = $request->input('spb_number');
			$spb_date = $request->input('spb_date');
			$arr_nama_perangkat = $request->input('arr_nama_perangkat');
			$arr_biaya = $request->input('arr_biaya');
			$arr_nama_perangkat2 = $request->input('arr_nama_perangkat2');
			$arr_biaya2 = $request->input('arr_biaya2');
			$exam = Examination::where('id', $exam_id)
						->with('user')
						->with('company')
						->with('examinationType')
						->first()
			;
			$data = []; 
			$data[] = [
				'spb_number' => $spb_number,
				'spb_date' => $spb_date,
				'arr_nama_perangkat' => $arr_nama_perangkat,
				'arr_biaya' => $arr_biaya,
				'arr_nama_perangkat2' => $arr_nama_perangkat2,
				'arr_biaya2' => $arr_biaya2,
				'exam' => $exam
			];
			$request->session()->put('key_exam_for_spb', $data);
			echo 1;			
		}else{
			echo 2; //SPB Number Exists
		}
    }
	
	function cekSPBNumber($spb_number)
    {
		$exam = Examination::where('spb_number','=',''.$spb_number.'')->get();
		return count($exam);
    }
	
	function cekRefID($exam_id)
    {
		$income = Income::where('reference_id','=',''.$exam_id.'')->get();
		return count($income);
    }
	
	function cetakUjiFungsi($id)
    {
		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		// $res_function_test = $client->get('functionTest/getResultData?id='.$id)->getBody();
		$res_function_test = $client->get('functionTest/getFunctionTestInfo?id='.$id)->getBody();
		$function_test = json_decode($res_function_test);
		
		$data = Examination::where('id','=',$id)
		->with('Company')
		->with('Device')
		->with('Equipment')
		->get();
		if( strpos( $data[0]->company->name, "/" ) !== false ) {$company_name = urlencode(urlencode($data[0]->company->name));}
			else{$company_name = $data[0]->company->name?: '-';}
		if( strpos( $data[0]->company->address, "/" ) !== false ) {$company_address = urlencode(urlencode($data[0]->company->address));}
			else{$company_address = $data[0]->company->address?: '-';}
		if( strpos( $data[0]->company->phone, "/" ) !== false ) {$company_phone = urlencode(urlencode($data[0]->company->phone));}
			else{$company_phone = $data[0]->company->phone?: '-';}
		if( strpos( $data[0]->company->fax, "/" ) !== false ) {$company_fax = urlencode(urlencode($data[0]->company->fax));}
			else{$company_fax = $data[0]->company->fax?: '-';}
		if( strpos( $data[0]->device->name, "/" ) !== false ) {$device_name = urlencode(urlencode($data[0]->device->name));}
			else{$device_name = $data[0]->device->name?: '-';}
		if( strpos( $data[0]->device->mark, "/" ) !== false ) {$device_mark = urlencode(urlencode($data[0]->device->mark));}
			else{$device_mark = $data[0]->device->mark?: '-';}
		if( strpos( $data[0]->device->manufactured_by, "/" ) !== false ) {$device_manufactured_by = urlencode(urlencode($data[0]->device->manufactured_by));}
			else{$device_manufactured_by = $data[0]->device->manufactured_by?: '-';}
		if( strpos( $data[0]->device->model, "/" ) !== false ) {$device_model = urlencode(urlencode($data[0]->device->model));}
			else{$device_model = $data[0]->device->model?: '-';}
		if( strpos( $data[0]->device->serial_number, "/" ) !== false ) {$device_serial_number = urlencode(urlencode($data[0]->device->serial_number));}
			else{$device_serial_number = $data[0]->device->serial_number?: '-';}
		if( strpos( $data[0]->function_test_TE, "/" ) !== false ) {$function_test_TE = urlencode(urlencode($data[0]->function_test_TE));}
			else{$function_test_TE = $data[0]->function_test_TE?: '-';}
		if( strpos( $data[0]->catatan, "/" ) !== false ) {$catatan = urlencode(urlencode($data[0]->catatan));}
			else{$catatan = $data[0]->catatan?: '-';}
			if($function_test->code != 'MSTD0059AERR' && $function_test->code != 'MSTD0000AERR'){
				if( strpos( $function_test->data[0]->nik, "/" ) !== false ) {$nik_te = urlencode(urlencode($function_test->data[0]->nik));}
					else{$nik_te = $function_test->data[0]->nik?: '-';}
				if( strpos( $function_test->data[0]->name, "/" ) !== false ) {$name_te = urlencode(urlencode($function_test->data[0]->name));}
					else{$name_te = $function_test->data[0]->name?: '-';}
			}else{
				$nik_te = "-";
				$name_te = "____________________________";
			}
		if(count($data[0]->equipment)>0){
			if( strpos( $data[0]->equipment[0]->pic, "/" ) !== false ) {$pic = urlencode(urlencode($data[0]->equipment[0]->pic));}
				else{$pic = $data[0]->equipment[0]->pic?: '-';}
		}else{
			$pic = '____________________________';
		}
		return \Redirect::route('cetakHasilUjiFungsi', [
			'company_name' => $company_name,
			'company_address' => $company_address,
			'company_phone' => $company_phone,
			'company_fax' => $company_fax,
			'device_name' => $device_name,
			'device_mark' => $device_mark,
			'device_manufactured_by' => $device_manufactured_by,
			'device_model' => $device_model,
			'device_serial_number' => $device_serial_number,
			'status' => $function_test_TE,
			'catatan' => $catatan,
			'nik_te' => $nik_te,
			'name_te' => $name_te,
			'pic' => $pic
		]);
    }
	
	function cetakFormBarang($id, Request $request)
    {
		/* $client = new Client([
			// Base URI is used with relative requests
			'base_uri' => 'http://ptbsp.ddns.net:13280/RevitalisasiOTR/api/',
			// 'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		// $res_function_test = $client->get('functionTest/getResultData?id='.$id)->getBody();
		$res_function_test = $client->get('functionTest/getResultData?id=3babffdd-6af1-4be7-a7bb-07da626c1351')->getBody();
		$function_test = json_decode($res_function_test);
		*/
		$data = Examination::where('id','=',$id)
		->with('ExaminationType')
		->with('ExaminationLab')
		->with('Company')
		->with('Device')
		->with('Equipment')
		->get();
		$kode_barang = $this->generateKodeBarang($data[0]->ExaminationLab->lab_init,$this->romawi(date('n')),date('Y'));
		$kode_barang = urlencode(urlencode($kode_barang));
		if( strpos( $data[0]->company->name, "/" ) !== false ) {$company_name = urlencode(urlencode($data[0]->company->name));}
			else{$company_name = $data[0]->company->name?: '-';}
		if( strpos( $data[0]->company->address, "/" ) !== false ) {$company_address = urlencode(urlencode($data[0]->company->address));}
			else{$company_address = $data[0]->company->address?: '-';}
		if( strpos( $data[0]->company->phone, "/" ) !== false ) {$company_phone = urlencode(urlencode($data[0]->company->phone));}
			else{$company_phone = $data[0]->company->phone?: '-';}
		if( strpos( $data[0]->company->fax, "/" ) !== false ) {$company_fax = urlencode(urlencode($data[0]->company->fax));}
			else{$company_fax = $data[0]->company->fax?: '-';}
		if( strpos( $data[0]->device->name, "/" ) !== false ) {$device_name = urlencode(urlencode($data[0]->device->name));}
			else{$device_name = $data[0]->device->name?: '-';}
		if( strpos( $data[0]->device->mark, "/" ) !== false ) {$device_mark = urlencode(urlencode($data[0]->device->mark));}
			else{$device_mark = $data[0]->device->mark?: '-';}
		if( strpos( $data[0]->device->manufactured_by, "/" ) !== false ) {$device_manufactured_by = urlencode(urlencode($data[0]->device->manufactured_by));}
			else{$device_manufactured_by = $data[0]->device->manufactured_by?: '-';}
		if( strpos( $data[0]->device->model, "/" ) !== false ) {$device_model = urlencode(urlencode($data[0]->device->model));}
			else{$device_model = $data[0]->device->model?: '-';}
		if( strpos( $data[0]->device->serial_number, "/" ) !== false ) {$device_serial_number = urlencode(urlencode($data[0]->device->serial_number));}
			else{$device_serial_number = $data[0]->device->serial_number?: '-';}
		if( strpos( $data[0]->ExaminationType->name, "/" ) !== false ) {$exam_type = urlencode(urlencode($data[0]->ExaminationType->name));}
			else{$exam_type = $data[0]->ExaminationType->name?: '-';}
		if( strpos( $data[0]->ExaminationType->description, "/" ) !== false ) {$exam_type_desc = urlencode(urlencode($data[0]->ExaminationType->description));}
			else{$exam_type_desc = $data[0]->ExaminationType->description?: '-';}
		if( strpos( $data[0]->contract_date, "/" ) !== false ) {$timestamp = strtotime($data[0]->contract_date);$contract_date = urlencode(urlencode(date('d-m-Y', $timestamp)));}
			else{$timestamp = strtotime($data[0]->contract_date);$contract_date = date('d-m-Y', $timestamp)?: '-';}
		
		$request->session()->put('key_exam_for_equipment', $data[0]->equipment);
		return \Redirect::route('cetakBuktiPenerimaanPerangkat', [
			'kode_barang' => $kode_barang,
			'company_name' => $company_name,
			'company_address' => $company_address,
			'company_phone' => $company_phone,
			'company_fax' => $company_fax,
			'device_name' => $device_name,
			'device_mark' => $device_mark,
			'device_manufactured_by' => $device_manufactured_by,
			'device_model' => $device_model,
			'device_serial_number' => $device_serial_number,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc,
			'contract_date' => $contract_date
		]);
    }
	
	public function generateEquip(Request $request)
    {
		$exam_id = $request->session()->pull('key_exam_id_for_generate_equip_masuk');
		$equipment = Equipment::where('examination_id', $exam_id)->get();
        $location = Equipment::where('examination_id', $exam_id)->first();
        $examination = DB::table('examinations')
			->join('devices', 'examinations.device_id', '=', 'devices.id')
			->select(
					'examinations.id',
					'devices.name',
					'devices.model'
					)
            ->where('examinations.id', $exam_id)
			->orderBy('devices.name')
			->first();

        return view('admin.equipment.edit')
            ->with('item', $examination)
            ->with('location', $location)
            ->with('data', $equipment);
    }
	
	public function generateKuitansiManual() {
		$thisYear = date('Y');
		$query = "
			SELECT SUBSTRING_INDEX(number,'/',1) + 1 AS last_numb
			FROM kuitansi WHERE SUBSTRING_INDEX(number,'/',-1) = ".$thisYear."
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			return '001/DDS-73/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.'/DDS-73/'.$thisYear.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.'/DDS-73/'.$thisYear.'';
			}
			else{
				return ''.$last_numb.'/DDS-73/'.$thisYear.'';
			}
		}
    }
	
	public function generateKodeBarang($a,$b,$c) {
		$query = "
			SELECT
				SUBSTRING_INDEX(no, '/', 1) + 1 AS last_numb
			FROM
				equipments
			WHERE
			SUBSTRING_INDEX(
				SUBSTRING_INDEX(no, '/', 2),
				'/' ,- 1
			) = '".$a."' AND
			SUBSTRING_INDEX(
				SUBSTRING_INDEX(no, '/', 3),
				'/' ,- 1
			) = '".$b."' AND
			SUBSTRING_INDEX(no, '/', -1) = ".$c."
			ORDER BY
				last_numb DESC
			LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			return '001/'.$a.'/'.$b.'/'.$c.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.'/'.$a.'/'.$b.'/'.$c.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.'/'.$a.'/'.$b.'/'.$c.'';
			}
			else{
				return ''.$last_numb.'/'.$a.'/'.$b.'/'.$c.'';
			}
		}
    }
	
	function romawi($bln){
		$array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
		$bulan = $array_bulan[$bln];
		return $bulan;
	}
}
