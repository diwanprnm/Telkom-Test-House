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
use App\Api_logs;
use App\Logs;
use App\Logs_administrator;
use App\Income;
use App\Questioner;
use App\Equipment;
use App\EquipmentHistory;
use App\QuestionerDynamic;
use App\GeneralSetting;

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
use App\AdminRole;

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
			$query->where(function($q){
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
				;
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
					->orWhere('function_test_NO', 'like', '%'.strtolower($search).'%')
					->orWhere('spk_code', 'like', '%'.strtolower($search).'%');
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

            // if ($request->has('comp_stat')){
                // $comp_stat = $request->get('comp_stat');
                // if($request->input('comp_stat') != 'all'){
					// $query->where(function($q) use($request){
					// return $q->where('registration_status', '=', $request->get('comp_stat'))
						// ->orWhere('function_status', '=', $request->get('comp_stat'))
						// ->orWhere('contract_status', '=', $request->get('comp_stat'))
						// ->orWhere('spb_status', '=', $request->get('comp_stat'))
						// ->orWhere('payment_status', '=', $request->get('comp_stat'))
						// ->orWhere('spk_status', '=', $request->get('comp_stat'))
						// ->orWhere('examination_status', '=', $request->get('comp_stat'))
						// ->orWhere('resume_status', '=', $request->get('comp_stat'))
						// ->orWhere('qa_status', '=', $request->get('comp_stat'))
						// ->orWhere('certificate_status', '=', $request->get('comp_stat'))
						// ;
					// });
				// }
            // }

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
						/*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('registration_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('registration_status', '!=', 1);
							}
						}*/
						$query->where('registration_status', '!=', 1);
                        $status = 1;
                        break;
                    case 2:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('function_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('function_status', '!=', 1);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '!=', 1);
                        $status = 2;
                        break;
                    case 3:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('contract_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('contract_status', '!=', 1);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '!=', 1);
                        $status = 3;
                        break;
                    case 4:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('spb_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('spb_status', '!=', 1);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '!=', 1);
                        $status = 4;
                        break;
                    case 5:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('payment_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('payment_status', '!=', 1);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '=', 1);
						$query->where('payment_status', '!=', 1);
                        $status = 5;
                        break;
                    case 6:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('spk_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('spk_status', '!=', 1);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '=', 1);
						$query->where('payment_status', '=', 1);
						$query->where('spk_status', '!=', 1);
                        $status = 6;
                        break;
                    case 7:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('examination_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('examination_status', '!=', 0);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '=', 1);
						$query->where('payment_status', '=', 1);
						$query->where('spk_status', '=', 1);
						$query->where('examination_status', '!=', 1);
                        $status = 7;
                        break;
                    case 8:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('resume_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('resume_status', '!=', 0);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '=', 1);
						$query->where('payment_status', '=', 1);
						$query->where('spk_status', '=', 1);
						$query->where('examination_status', '=', 1);
						$query->where('resume_status', '!=', 1);
                        $status = 8;
                        break;
                    case 9:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('qa_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('qa_status', '!=', 0);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '=', 1);
						$query->where('payment_status', '=', 1);
						$query->where('spk_status', '=', 1);
						$query->where('examination_status', '=', 1);
						$query->where('resume_status', '=', 1);
						$query->where('qa_status', '!=', 1);
                        $status = 9;
                        break;
                    case 10:
                        /*if ($request->has('comp_stat')){
							$comp_stat = $request->get('comp_stat');
							if($request->input('comp_stat') != 'all'){
								$query->where('certificate_status', '=', $request->get('comp_stat'));
							}else{
								$query->where('certificate_status', '!=', 0);
							}
						}*/
						$query->where('registration_status', '=', 1);
						$query->where('function_status', '=', 1);
						$query->where('contract_status', '=', 1);
						$query->where('spb_status', '=', 1);
						$query->where('payment_status', '=', 1);
						$query->where('spk_status', '=', 1);
						$query->where('examination_status', '=', 1);
						$query->where('resume_status', '=', 1);
						$query->where('qa_status', '=', 1);
						$query->where('certificate_status', '!=', 1);
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

			$data = $query->orderBy('updated_at', 'desc')
                        ->paginate($paginate);
			
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
					->with('user')
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
    	$currentUser = Auth::user();
    	$admins = AdminRole::where('user_id', $currentUser->id)->get();
    	
        $exam = Examination::where('id', $id)
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('device')
                            ->with('media')
                            ->with('equipment')
                            ->first();

        $labs = ExaminationLab::all();
		// $gen_spk_code = $this->generateSPKCode($exam->examinationLab->lab_code,$exam->examinationType->name,date('Y'));
		
		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			// 'http_errors' => false,
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
			->with('exam_schedule', $exam_schedule)
			->with('admin_roles', $admins);
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
        /*if ($request->has('spk_code')){
            $exam->spk_code = $request->input('spk_code');
			if($this->checkSPKCode($request->input('spk_code')) > 0){
				Session::flash('error', 'SPK Number must be unique, please Re-Generate');
                return redirect('/admin/examination/'.$exam->id.'/edit');
			}
        }*/
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

            // ketika update status di uji fungsi -> baru di delete history nya ketika hasil tidak memenuhi
            if($exam->function_test_TE == 2){
                Equipment::where('examination_id', '=' ,''.$exam->id.'')->delete();
                EquipmentHistory::where('examination_id', '=' ,''.$exam->id.'')->delete();
                
                $exam->cust_test_date = NULL;
                $exam->deal_test_date = NULL;
                $exam->urel_test_date = NULL;
                $exam->function_date = NULL;
                $exam->function_test_reason = NULL;
                $exam->function_test_date_approval = 0;
                $exam->location = 0;
            }

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
                }else{
					$data= array( 
						"from"=>"admin",
						"to"=>$exam->created_by,
						"message"=>"Tahap Uji Fungsi Completed",
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
                    }else{
						$data= array( 
                            "from"=>"admin",
                            "to"=>$exam->created_by,
                            "message"=>"Tahap Uji Fungsi Not Completed",
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
        $spk_created = 0;
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

				$client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					'http_errors' => false,
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);

				$exam_forOTR = Examination::where('id', $exam->id)
				->with('examinationType')
				->with('examinationLab')
				->first();

				if ($exam->spk_code == null && $exam->company_id == '0fbf131c-32e3-4c9a-b6e0-a0f217cb2830'){
                    $spk_number_forOTR = $this->generateSPKCode($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
                    $exam->spk_code = $spk_number_forOTR;
                    $exam->spk_date = date('Y-m-d');
                    $spk_created = 1;
                    $exam->spb_status = $status;
                    $exam->payment_status = $status;
                }

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
				$exam->price = str_replace(".",'',$request->input('exam_price'));
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

				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.spb", "Penerbitan Surat Pemberitahuan Biaya (SPB) untuk ".$exam->function_test_NO,$path_file."/".$attach_name,$request->input('spb_number'),$exam->id);
			}else if($status == -1){
				$exam->price = str_replace(".",'',$request->input('exam_price'));
				// $exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","SPB",$request->input('keterangan'));
			}
        }
		
        if ($request->has('payment_status')){
			if ($request->has('cust_price_payment')){
				$exam->cust_price_payment = str_replace(".",'',$request->input('cust_price_payment'));
			}
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
			// $exam->spk_status = $status;
			if($status == 1){
				if($this->cekRefID($exam->id) == 0){
					$income = new Income;
					$income->id = Uuid::uuid4();
					$income->company_id = $exam->company_id;
					$income->inc_type = 1; 
					$income->reference_id = $exam->id; 
					$income->reference_number = $exam->spb_number;
					$income->tgl = $exam->spb_date;
					$income->created_by = $currentUser->id;

				}else{
					$income = Income::where('reference_id', $exam->id)->first();
				}
					// ($item->payment_method == 1)?'ATM':'Kartu Kredit'
					$income->price = ($request->input('cust_price_payment') != NULL) ? str_replace(".",'',$request->input('cust_price_payment')) : 0;
					// $income->price = $request->input('cust_price_payment');
					$income->updated_by = $currentUser->id;
					$income->save();
				/*$path_file = public_path().'/media/examination/'.$id;
				$attach = ExaminationAttach::where('name', 'Kuitansi')->where('examination_id', ''.$id.'')->first();
					$attach_name = $attach->attachment;
				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran",$path_file."/".$attach_name);*/
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran");
				
				$client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					'http_errors' => false,
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				$exam_forOTR = Examination::where('id', $exam->id)
				->with('examinationType')
				->with('examinationLab')
				->first();

                if ($exam->spk_code == null){
                    $spk_number_forOTR = $this->generateSPKCode($exam_forOTR->examinationLab->lab_code,$exam_forOTR->examinationType->name,date('Y'));
                    $exam->spk_code = $spk_number_forOTR;
                    $exam->spk_date = date('Y-m-d');
                    $spk_created = 1;
                }

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
			if ($request->has('lab_to_gudang_date')){
				$query_update = "UPDATE equipment_histories
					SET 
						action_date = '".$request->input('lab_to_gudang_date')."',
						updated_by = '".$currentUser->id."',
						updated_at = '".date('Y-m-d H:i:s')."'
					WHERE location = 2 AND examination_id = '".$id."'
				";
				$data_update = DB::update($query_update);
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
			if(!$request->hasFile('rev_lap_uji_file') && $request->has('hide_attachment_form-lap-uji') && $exam->resume_status == 0){
				/*TPN api_upload*/
	            if($exam->BILLING_ID != null){
	                $data_upload [] = array(
	                    'name'=>"delivered",
	                    'contents'=>json_encode(['by'=>$currentUser->name, "reference_id" => $currentUser->id]),
	                );

	                $upload = $this->api_upload($data_upload,$exam->BILLING_ID);
	            }
			}
			if ($request->hasFile('rev_lap_uji_file')) {
				$name_file = 'rev_lap_uji_'.$request->file('rev_lap_uji_file')->getClientOriginalName();
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('rev_lap_uji_file')->move($path_file,$name_file)){
                    /*TPN api_upload*/
		            if($exam->BILLING_ID != null){
		                $data_upload [] = array(
		                    'name'=>"delivered",
		                    'contents'=>json_encode(['by'=>$currentUser->name, "reference_id" => $currentUser->id]),
		                );

		                $upload = $this->api_upload($data_upload,$exam->BILLING_ID);
		            }
					
					$attach = new ExaminationAttach;
					$attach->id = Uuid::uuid4();
					$attach->examination_id = $exam->id; 
					$attach->name = 'Revisi Laporan Uji';
					$attach->attachment = $name_file;
					$attach->created_by = $currentUser->id;
					$attach->updated_by = $currentUser->id;

					$attach->save();
				}else{
					Session::flash('error', 'Save Revisi Laporan Uji to directory failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
			}
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
		if ($request->hasFile('tanda_terima_file')) {
			$name_file = 'form_tanda_terima_hasil_pengujian_'.$request->file('tanda_terima_file')->getClientOriginalName();
			$path_file = public_path().'/media/examination/'.$exam->id;
			if (!file_exists($path_file)) {
				mkdir($path_file, 0775);
			}
			if($request->file('tanda_terima_file')->move($path_file,$name_file)){
				$attach = ExaminationAttach::where('name', 'Tanda Terima Hasil Pengujian')->where('examination_id', ''.$exam->id.'')->first();
				if ($attach){
					$attach->attachment = $name_file;
					$attach->updated_by = $currentUser->id;

					$attach->save();
				} else{
					$attach = new ExaminationAttach;
					$attach->id = Uuid::uuid4();
					$attach->examination_id = $exam->id; 
					$attach->name = 'Tanda Terima Hasil Pengujian';
					$attach->attachment = $name_file;
					$attach->created_by = $currentUser->id;
					$attach->updated_by = $currentUser->id;

					$attach->save();
				}
				Session::flash('message', 'Success Save Tanda Terima Hasil Pengujian to directory');
				return redirect('/admin/examination/'.$exam->id.'/edit');
			}else{
				Session::flash('error', 'Save Tanda Terima Hasil Pengujian to directory failed');
				return redirect('/admin/examination/'.$exam->id.'/edit');
			}
		}
		if ($request->has('qa_status')){
            $status = $request->input('qa_status');
            $passed = $request->input('passed');
            $exam->qa_status = $status;
            $exam->qa_passed = $passed;
            if($exam->qa_passed == 1){  

            	if(strpos($exam->keterangan, 'qa_date') !== false){
            		$data_ket = explode("qa_date", $exam->keterangan);
            		$devnc_exam = Examination::find($data_ket[2]);

            		if($devnc_exam){
			        	$devnc_exam->qa_passed = 0;

			        	try{
			        		$devnc_exam->save();
			        	} catch(Exception $e){

					    }
			        }
            	}

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

                // $this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sidang_QA", "Hasil Sidang QA");
            }else{ 

            	$exam->certificate_status = 1;

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

                // $this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sidang_QA", "Hasil Sidang QA");
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
		if ($request->has('PO_ID')){
			if($exam->payment_status == 1){
				Session::flash('error', 'SPB Already Paid');
                return redirect('/admin/examination/'.$exam->id.'/edit');
			}
			if($exam->BILLING_ID){
				$data_cancel_billing = [
	            	"canceled" => [
						"message" => "-",
						"by" => $currentUser->name,
                    	"reference_id" => $currentUser->id
					]
	            ];
				$this->api_cancel_billing($exam->BILLING_ID, $data_cancel_billing);
			}
			/*$data_billing = [
                "draft_id" => $request->input('PO_ID'),
                "include_pph" => true,
                "created" => [
                    "by" => $currentUser->name,
                    "reference_id" => $currentUser->id
                ],
                "config" => [
                    "kode_wapu" => "01",
                    "afiliasi" => "non-telkom",
                    "tax_invoice_text" => $exam->device->name.', '.$exam->device->mark.', '.$exam->device->capacity.', '.$exam->device->model,
                    "payment_method" => "mps",
                ],
                "mps" => [
                    "gateway" => "020dc744-91a9-4668-8a54-f92e2a1c7957",
                    "product_code" => "finpay_vamandiri",
                    "product_type" => "VA",
                    "manual_expired" => 20160
                ]
            ];

            $billing = $this->api_billing($data_billing);

            $exam->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;

            if($exam->BILLING_ID && $billing->data->mps->va->number){
            	$exam->include_pph = 1;
            	$exam->payment_method = 2;
            	$exam->VA_number = $billing && $billing->status == true ? $billing->data->mps->va->number : null;
	            $exam->VA_amount = $billing && $billing->status == true ? $billing->data->mps->va->amount : null;
	            $exam->VA_expired = $billing && $billing->status == true ? $billing->data->mps->va->expired : null;
            }else{
            	Session::flash('error', 'Failed To Generate VA, please try again');
                return redirect('/admin/examination/'.$exam->id.'/edit');
            }*/
            $exam->PO_ID = $request->input('PO_ID');
            $exam->BILLING_ID = null;
			$exam->include_pph = 0;
			$exam->payment_method = 0;
			$exam->VA_name = null;
			$exam->VA_image_url = null;
			$exam->VA_number = null;
			$exam->VA_amount = null;
			$exam->VA_expired = null;
            // $exam->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;
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
                    $device->cert_number = $request->input('cert_number');

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
				$exam_schedule = $res_exam_schedule->getStatusCode() == '200' ? json_decode($res_exam_schedule->getBody()) : null;
				if($exam_schedule && $exam_schedule->status == false){
					$api_logs = new Api_logs;
					$api_logs->send_to = "OTR";
					$api_logs->route = 'spk/addNotif?id='.$exam->id.'&spkNumber='.$spk_number_forOTR;
					$api_logs->status = $exam_schedule->status;
					$api_logs->data = json_encode($exam_schedule);
					$api_logs->reference_id = $exam->id;
					$api_logs->reference_table = "examinations";
					$api_logs->created_by = $currentUser->id;
					$api_logs->updated_by = $currentUser->id;

					$api_logs->save();
				}elseif ($exam_schedule == null) {
					$api_logs = new Api_logs;
					$api_logs->send_to = "OTR";
					$api_logs->route = 'spk/addNotif?id='.$exam->id.'&spkNumber='.$spk_number_forOTR;
					$api_logs->status = 0;
					$api_logs->data = "-";
					$api_logs->reference_id = $exam->id;
					$api_logs->reference_table = "examinations";
					$api_logs->created_by = $currentUser->id;
					$api_logs->updated_by = $currentUser->id;

					$api_logs->save();
				}
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

    public function api_cancel_billing($BILLING_ID,$data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_2")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $params['json'] = $data;
            $res_cancel_billing = $client->put("v1/billings/".$BILLING_ID."/cancel", $params)->getBody();
            $cancel_billing = json_decode($res_cancel_billing);

            return $cancel_billing;
        } catch(Exception $e){
            return null;
        }
    }

    public function api_billing($data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_2")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $params['json'] = $data;
            $res_billing = $client->post("v1/billings", $params)->getBody();
            $billing = json_decode($res_billing);

            return $billing;
        } catch(Exception $e){
            return null;
        }
    }

    public function generateKuitansi(Request $request) {
    	$client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn_2")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $id = $request->input('id');
        
        $exam = Examination::where("id", $id)->first();
        if($exam){
            try {
                $INVOICE_ID = $exam->INVOICE_ID;
                $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
                $invoice = json_decode($res_invoice->getBody());
                
                if($INVOICE_ID && $invoice && $invoice->status == true){
                    $status_invoice = $invoice->data->status_invoice;
                    if($status_invoice == "approved"){
                        $status_faktur = $invoice->data->status_faktur;
                        if($status_faktur == "received"){
                            /*SAVE KUITANSI*/
                            $name_file = 'kuitansi_spb_'.$INVOICE_ID.'.pdf';
							$path_file = public_path().'/media/examination/'.$id;
							if (!file_exists($path_file)) {
								mkdir($path_file, 0775);
							}
							$response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/exportpdf');
                            $stream = (String)$response->getBody();

                            if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                                $attach = ExaminationAttach::where('name', 'Kuitansi')->where('examination_id', ''.$id.'')->first();
                                $currentUser = Auth::user();

								if ($attach){
									$attach->attachment = $name_file;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								} else{
									$attach = new ExaminationAttach;
									$attach->id = Uuid::uuid4();
									$attach->examination_id = $id; 
									$attach->name = 'Kuitansi';
									$attach->attachment = $name_file;
									$attach->created_by = $currentUser->id;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								}
                                return "Kuitansi Berhasil Disimpan.";
                            }else{
                                return "Gagal Menyimpan Kuitansi!";
                            }
                        }else{
                            return $invoice->data->status_faktur;
                        }
                    }else{
                        switch ($status_invoice) {
                            case 'invoiced':
                                return "Invoice Baru Dibuat.";
                                break;
                            
                            case 'returned':
                                return $invoice->data->$status_invoice->message;
                                break;
                            
                            default:
                                return "Invoice sudah dikirim ke DJP.";
                                break;
                        }
                    }
                }else{
                    return "Data Invoice Tidak Ditemukan!";        
                }
            } catch(Exception $e){
                return null;
            }
        }else{
            return "Data Pembelian Tidak Ditemukan!";
        }
    }

    public function generateTaxInvoice(Request $request) {
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn_2")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $id = $request->input('id');

        $exam = Examination::select(DB::raw('companies.name as company_name, examination_attachments.tgl as payment_date, examinations.*, devices.name, devices.mark, devices.capacity, devices.model'))
    	->where('examinations.id', $id)
    	->whereNotExists(function ($query) {
           	$query->select(DB::raw(1))
                 ->from('examination_attachments')
                 ->whereRaw('examination_attachments.examination_id = examinations.id')
                 ->whereRaw('examination_attachments.name = "Faktur Pajak"')
            ;
       	})->orWhereExists(function ($query) {
           	$query->select(DB::raw(1))
                 ->from('examination_attachments')
                 ->whereRaw('examination_attachments.examination_id = examinations.id')
                 ->whereRaw('examination_attachments.name = "Faktur Pajak"')
                 ->whereRaw('examination_attachments.attachment = ""')
            ;
       	})
       	->join('companies', 'examinations.company_id', '=', 'companies.id')
        ->join('devices', 'examinations.device_id', '=', 'devices.id')
        ->leftJoin('examination_attachments', function($leftJoin){
            $leftJoin->on('examinations.id', '=', 'examination_attachments.examination_id');
            $leftJoin->on(DB::raw('examination_attachments.name'), DB::raw('='),DB::raw("'File Pembayaran'"));
        })
        ->first();

        if($exam){
        	$payment_date = $exam->payment_date != '0000-00-00' ? $exam->payment_date : null;
            /* GENERATE NAMA FILE FAKTUR */
                $filename = $exam ? $payment_date.'_'.$exam->company_name.'_'.$exam->name.'_'.$exam->mark.'_'.$exam->capacity.'_'.$exam->model : $exam->INVOICE_ID;
            /* END GENERATE NAMA FILE FAKTUR */
            try {
                $INVOICE_ID = $exam->INVOICE_ID;
                $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
                $invoice = json_decode($res_invoice->getBody());
                if($INVOICE_ID && $invoice && $invoice->status == true){
    			    $status_invoice = $invoice->data->status_invoice;
                    if($status_invoice == "approved"){
                        $status_faktur = $invoice->data->status_faktur;
                        if($status_faktur == "received"){
                            /*SAVE FAKTUR PAJAK*/
                            $name_file = 'faktur_spb_'.$filename.'.pdf';
                            $path_file = public_path().'/media/examination/'.$id;
                            if (!file_exists($path_file)) {
                                mkdir($path_file, 0775);
                            }
                            $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                            $stream = (String)$response->getBody();

                            if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                                $attach = ExaminationAttach::where('name', 'Faktur Pajak')->where('examination_id', ''.$id.'')->first();
                                $currentUser = Auth::user();

								if ($attach){
									$attach->attachment = $name_file;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								} else{
									$attach = new ExaminationAttach;
									$attach->id = Uuid::uuid4();
									$attach->examination_id = $id; 
									$attach->name = 'Faktur Pajak';
									$attach->attachment = $name_file;
									$attach->created_by = $currentUser->id;
									$attach->updated_by = $currentUser->id;

									$attach->save();
								}
                                return "Faktur Pajak Berhasil Disimpan.";
                            }else{
                                return "Gagal Menyimpan Faktur Pajak!";
                            }
                        }else{
                            return $invoice->data->status_faktur;
                        }
                    }else{
                        switch ($status_invoice) {
                            case 'invoiced':
                                return "Faktur Pajak belum Tersedia, karena Invoice Baru Dibuat.";
                                break;
                            
                            case 'returned':
                                return $invoice->data->$status_invoice->message;
                                break;
                            
                            default:
                                return "Faktur Pajak belum Tersedia. Invoice sudah dikirim ke DJP.";
                                break;
                        }
                    }
                }else{
                    return "Data Invoice Tidak Ditemukan!";        
                }
            } catch(Exception $e){
                return null;
            }
        }else{
            return "Data Pembelian Tidak Ditemukan!";
        }
    }

    public function api_upload($data, $BILLING_ID){
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn_2")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $params['multipart'] = $data;
            $res_upload = $client->post("v1/billings/".$BILLING_ID."/deliver", $params)->getBody(); //BILLING_ID
            $upload = json_decode($res_upload);

            /*get
                $upload->status; //if true lanjut, else panggil lagi API nya, dan jalankan API invoices
                $upload->data->_id;
            */

            return $upload;
        } catch(Exception $e){
            return null;
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

    public function downloadRefUjiFile($id)
    {
        $data = ExaminationAttach::find($id);

        if ($data){
            $file = public_path().'/media/examination/'.$data->examination_id.'/'.$data->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::download($file, $data->attachment, $headers);
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
	
	public function sendEmailNotification_wAttach($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $attach, $spb_number = null, $exam_id = null)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'dev_name' => $dev_name,
			'exam_type' => $exam_type,
			'exam_type_desc' => $exam_type_desc,
			'spb_number' => $spb_number,
			'id' => $exam_id,
			'payment_method' => $this->api_get_payment_methods()
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

        $search = trim($request->input('search'));
        $comp_stat = '';
        $type = '';
        $status = '';
		$before = null;
        $after = null;

        $query = Examination::whereNotNull('created_at')
                            ->with('user')
                            ->with('company')
                            ->with('examinationType')
                            ->with('examinationLab')
                            ->with('media')
                            ->with('device');
		$query->where(function($q){
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
			;
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
        }

        // if ($request->has('comp_stat')){
            // $comp_stat = $request->get('comp_stat');
            // if($request->input('comp_stat') != 'all'){
				// $query->where(function($q) use($request){
				// return $q->where('registration_status', '=', $request->get('comp_stat'))
					// ->orWhere('function_status', '=', $request->get('comp_stat'))
					// ->orWhere('contract_status', '=', $request->get('comp_stat'))
					// ->orWhere('spb_status', '=', $request->get('comp_stat'))
					// ->orWhere('payment_status', '=', $request->get('comp_stat'))
					// ->orWhere('spk_status', '=', $request->get('comp_stat'))
					// ->orWhere('examination_status', '=', $request->get('comp_stat'))
					// ->orWhere('resume_status', '=', $request->get('comp_stat'))
					// ->orWhere('qa_status', '=', $request->get('comp_stat'))
					// ->orWhere('certificate_status', '=', $request->get('comp_stat'))
					// ;
				// });
			// }
        // }

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
					/*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('registration_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('registration_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '!=', 1);
                    $status = 1;
                    break;
                case 2:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('function_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('function_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '!=', 1);
                    $status = 2;
                    break;
                case 3:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('contract_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('contract_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '!=', 1);
                    $status = 3;
                    break;
                case 4:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('spb_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('spb_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '!=', 1);
                    $status = 4;
                    break;
                case 5:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('payment_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('payment_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '=', 1);
					$query->where('payment_status', '!=', 1);
                    $status = 5;
                    break;
                case 6:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('spk_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('spk_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '=', 1);
					$query->where('payment_status', '=', 1);
					$query->where('spk_status', '!=', 1);
                    $status = 6;
                    break;
                case 7:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('examination_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('examination_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '=', 1);
					$query->where('payment_status', '=', 1);
					$query->where('spk_status', '=', 1);
					$query->where('examination_status', '!=', 1);
                    $status = 7;
                    break;
                case 8:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('resume_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('resume_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '=', 1);
					$query->where('payment_status', '=', 1);
					$query->where('spk_status', '=', 1);
					$query->where('examination_status', '=', 1);
					$query->where('resume_status', '!=', 1);
                    $status = 8;
                    break;
                case 9:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('qa_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('qa_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '=', 1);
					$query->where('payment_status', '=', 1);
					$query->where('spk_status', '=', 1);
					$query->where('examination_status', '=', 1);
					$query->where('resume_status', '=', 1);
					$query->where('qa_status', '!=', 1);
                    $status = 9;
                    break;
                case 10:
                    /*if ($request->has('comp_stat')){
						$comp_stat = $request->get('comp_stat');
						if($request->input('comp_stat') != 'all'){
							$query->where('certificate_status', '=', $request->get('comp_stat'));
						}else{
							$query->where('certificate_status', '!=', 0);
						}
					}*/
					$query->where('registration_status', '=', 1);
					$query->where('function_status', '=', 1);
					$query->where('contract_status', '=', 1);
					$query->where('spb_status', '=', 1);
					$query->where('payment_status', '=', 1);
					$query->where('spk_status', '=', 1);
					$query->where('examination_status', '=', 1);
					$query->where('resume_status', '=', 1);
					$query->where('qa_status', '=', 1);
					$query->where('certificate_status', '!=', 1);
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

		$data = $query->orderBy('updated_at', 'desc')->get();

		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'Tipe Pengujian',
			'Tahap Pengujian',
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
			if( $row->registration_status == 1) {
				$status_reg = 'Completed';
			}else
				if( $row->registration_status == -1) {
					$status_reg = 'Not Completed';
				}else{
					$status_reg = 'On Progress';
				}
				
			if( $row->function_status == 1) {
				$status_func = 'Completed';
			}else
				if( $row->function_status == -1) {
					$status_func = 'Not Completed';
				}else{
					$status_func = 'On Progress';
				}
				
			if( $row->contract_status == 1) {
				$status_cont = 'Completed';
			}else
				if( $row->contract_status == -1) {
					$status_cont = 'Not Completed';
				}else{
					$status_cont = 'On Progress';
				}
				
			if( $row->spb_status == 1) {
				$status_spb = 'Completed';
			}else
				if( $row->spb_status == -1) {
					$status_spb = 'Not Completed';
				}else{
					$status_spb = 'On Progress';
				}
			
			if( $row->payment_status == 1) {
				$status_pay = 'Completed';
			}else
				if( $row->payment_status == -1) {
					$status_pay = 'Not Completed';
				}else{
					$status_pay = 'On Progress';
				}
				
			if( $row->spk_status == 1) {
				$status_spk = 'Completed';
			}else
				if( $row->spk_status == -1) {
					$status_spk = 'Not Completed';
				}else{
					$status_spk = 'On Progress';
				}
				
			if( $row->examination_status == 1) {
				$status_exam = 'Completed';
			}else
				if( $row->examination_status == -1) {
					$status_exam = 'Not Completed';
				}else{
					$status_exam = 'On Progress';
				}
				
			if( $row->resume_status == 1) {
				$status_resu = 'Completed';
			}else
				if( $row->resume_status == -1) {
					$status_resu = 'Not Completed';
				}else{
					$status_resu = 'On Progress';
				}
				
			if( $row->qa_status == 1) {
				$status_qa = 'Completed';
			}else
				if( $row->qa_status == -1) {
					$status_qa = 'Not Completed';
				}else{
					$status_qa = 'On Progress';
				}
				
			if( $row->certificate_status == 1) {
				$status_cert = 'Completed';
			}else
				if( $row->certificate_status == -1) {
					$status_cert = 'Not Completed';
				}else{
					$status_cert = 'On Progress';
				}

			/*Tahap Pengujian*/

			if($row->registration_status != 1){
				$tahap = 'Registrasi';
			}
			if($row->registration_status == 1 && $row->function_status != 1){
				$tahap = 'Uji Fungsi';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status != 1){
				$tahap = 'Tinjauan Kontrak';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status != 1){
				$tahap = 'SPB';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status != 1){
				$tahap = 'Pembayaran';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status != 1){
				$tahap = 'Pembuatan SPK';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status != 1){
				$tahap = 'Pelaksanaan Uji';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status == 1 && $row->resume_status != 1){
				$tahap = 'Laporan Uji';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status == 1 && $row->resume_status == 1 && $row->qa_status != 1){
				$tahap = 'Sidang QA';
			}
			if($row->registration_status == 1 && $row->function_status == 1 && $row->contract_status == 1 && $row->spb_status == 1 && $row->payment_status == 1 && $row->spk_status == 1 && $row->examination_status == 1 && $row->resume_status == 1 && $row->qa_status == 1 && $row->certificate_status != 1){
				$tahap = 'Penerbitan Sertifikat';
			}

			/*End Tahap Pengujian*/

			/*ExaminationType*/
			if(!isset($row->examinationType->name)){
				$examType_name = '';
			}else{
				$examType_name = $row->examinationType->name;
			}
			if(!isset($row->examinationType->description)){
				$examType_desc = '';
			}else{
				$examType_desc = $row->examinationType->description;
			}
			/*EndExaminationType*/
			
			/*User*/
			if(!isset($row->user->name)){
				$user_name = '';
			}else{
				$user_name = $row->user->name;
			}
			if(!isset($row->user->email)){
				$user_email = '';
			}else{
				$user_email = $row->user->email;
			}
			if(!isset($row->user->address)){
				$user_address = '';
			}else{
				$user_address = $row->user->address;
			}
			if(!isset($row->user->phone_number)){
				$user_phone_number = '';
			}else{
				$user_phone_number = $row->user->phone_number;
			}
			if(!isset($row->user->fax)){
				$user_fax = '';
			}else{
				$user_fax = $row->user->fax;
			}
			/*EndUser*/
			
			/*Company*/
			if(!isset($row->company->siup_date)){
				$siup_date = '';
			}else{
				$siup_date = date("d-m-Y", strtotime($row->company->siup_date));
			}
			if(!isset($row->company->qs_certificate_date)){
				$qs_certificate_date = '';
			}else{
				$qs_certificate_date = date("d-m-Y", strtotime($row->company->qs_certificate_date));
			}
			if(!isset($row->company->name)){
				$company_name = '';
			}else{
				$company_name = $row->company->name;
			}
			if(!isset($row->company->address)){
				$company_address = '';
			}else{
				$company_address = $row->company->address;
			}
			if(!isset($row->company->city)){
				$company_city = '';
			}else{
				$company_city = $row->company->city;
			}
			if(!isset($row->company->postal_code)){
				$company_postal_code = '';
			}else{
				$company_postal_code = $row->company->postal_code;
			}
			if(!isset($row->company->email)){
				$company_email = '';
			}else{
				$company_email = $row->company->email;
			}
			if(!isset($row->company->phone_number)){
				$company_phone_number = '';
			}else{
				$company_phone_number = $row->company->phone_number;
			}
			if(!isset($row->company->fax)){
				$company_fax = '';
			}else{
				$company_fax = $row->company->fax;
			}
			if(!isset($row->company->npwp_number)){
				$company_npwp_number = '';
			}else{
				$company_npwp_number = $row->company->npwp_number;
			}
			if(!isset($row->company->siup_number)){
				$company_siup_number = '';
			}else{
				$company_siup_number = $row->company->siup_number;
			}
			if(!isset($row->company->qs_certificate_number)){
				$company_qs_certificate_number = '';
			}else{
				$company_qs_certificate_number = $row->company->qs_certificate_number;
			}
			/*EndCompany*/
			
			/*Device*/
			if(!isset($row->device->valid_from)){
				$valid_from = '';
			}else{
				$valid_from = date("d-m-Y", strtotime($row->device->valid_from));
			}
			if(!isset($row->device->valid_thru)){
				$valid_thru = '';
			}else{
				$valid_thru = date("d-m-Y", strtotime($row->device->valid_thru));
			}
			if(!isset($row->device->name)){
				$device_name = '';
			}else{
				$device_name = $row->device->name;
			}
			if(!isset($row->device->mark)){
				$device_mark = '';
			}else{
				$device_mark = $row->device->mark;
			}
			if(!isset($row->device->capacity)){
				$device_capacity = '';
			}else{
				$device_capacity = $row->device->capacity;
			}
			if(!isset($row->device->manufactured_by)){
				$device_manufactured_by = '';
			}else{
				$device_manufactured_by = $row->device->manufactured_by;
			}
			if(!isset($row->device->serial_number)){
				$device_serial_number = '';
			}else{
				$device_serial_number = $row->device->serial_number;
			}
			if(!isset($row->device->model)){
				$device_model = '';
			}else{
				$device_model = $row->device->model;
			}
			if(!isset($row->device->test_reference)){
				$device_test_reference = '';
			}else{
				$device_test_reference = $row->device->test_reference;
			}
			/*EndDevice*/
			
			if($row->spk_date==''){
				$spk_date = '';
			}else{
				$spk_date = date("d-m-Y", strtotime($row->spk_date));
			}
			$examsArray[] = [
				"".$examType_name." (".$examType_desc.")",
				$tahap,
				$user_name,
				$user_email,
				$user_address,
				$user_phone_number,
				$user_fax,
				$row->jns_perusahaan,
				$company_name,
				$company_address.", kota".$company_city.", kode pos".$company_postal_code,
				$company_email,
				$company_phone_number,
				$company_fax,
				$company_npwp_number,
				$company_siup_number,
				$siup_date,
				$company_qs_certificate_number,
				$qs_certificate_date,
				$device_name,
				$device_mark,
				$device_capacity,
				$device_manufactured_by,
				$device_serial_number,
				$device_model,
				$device_test_reference,
				$valid_from." s.d. ".$valid_thru,
				$row->spk_code,
				$spk_date,
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
			$contract_date_ina = date('j F Y', $contract_date_ina_temp);
		
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with('company')
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
				
				$res_manager_urel = $client->get('user/getManagerLabInfo?groupId=MU')->getBody();
				$manager_urel = json_decode($res_manager_urel);
				
				if(count($manager_lab->data) == 1){
					if( strpos( $manager_lab->data[0]->name, "/" ) !== false ) {$manager_labs = urlencode(urlencode($manager_lab->data[0]->name));}
						else{$manager_labs = $manager_lab->data[0]->name?: '-';}
				}else{
					$manager_labs = '...............................';
				}
				
				if(count($manager_urel->data) == 1){
					if( strpos( $manager_urel->data[0]->name, "/" ) !== false ) {$manager_urels = urlencode(urlencode($manager_urel->data[0]->name));}
						else{$manager_urels = $manager_urel->data[0]->name?: '-';}
				}else{
					$manager_urels = '...............................';
				}

				$is_poh = 0;
				$general_setting_poh = GeneralSetting::where('code', 'poh_manager_urel')->first();
				if($general_setting_poh){
					if($general_setting_poh->is_active){
						$is_poh = 1;
						if( strpos( $general_setting_poh->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting_poh->value));}
							else{$manager_urels = $general_setting_poh->value?: '-';}
					}else{
						$general_setting = GeneralSetting::where('code', 'manager_urel')->first();
						if($general_setting){
							if( strpos( $general_setting->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting->value));}
								else{$manager_urels = $general_setting->value?: '-';}
						}else{
							$manager_urels = '...............................';
						}	
					}
				}else{
					$general_setting = GeneralSetting::where('code', 'manager_urel')->first();
					if($general_setting){
						if( strpos( $general_setting->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting->value));}
							else{$manager_urels = $general_setting->value?: '-';}
					}else{
						$manager_urels = '...............................';
					}
				}
				
				if(count($exam->equipment)>0){
					if( strpos( $exam->equipment[0]->pic, "/" ) !== false ) {$pic = urlencode(urlencode($exam->equipment[0]->pic));}
						else{$pic = $exam->equipment[0]->pic?: '-';}
				}else{
					$pic = '...............................';
				}
				
				$data = Array([
					'no_reg' => $exam->function_test_NO,
					'jns_pengujian' => $exam->examination_type_id,
					'nama_pemohon' => $exam->user->name,
					'alamat_pemohon' => $exam->user->address,
					'nama_perusahaan' => $exam->company->name,
					'alamat_perusahaan' => $exam->company->address,
					'plg_id' => $exam->company->plg_id,
					'nib' => $exam->company->nib,
					'nama_perangkat' => $exam->device->name,
					'merek_perangkat' => $exam->device->mark,
					'model_perangkat' => $exam->device->model,
					'kapasitas_perangkat' => $exam->device->capacity,
					'referensi_perangkat' => $exam->device->test_reference,
					'pembuat_perangkat' => $exam->device->manufactured_by,
					'is_loc_test' => $exam->is_loc_test,
					'contract_date' => $contract_date_ina,
					'manager_lab' => $manager_labs,
					'manager_urel' => $manager_urels,
					'pic' => $pic,
					'is_poh' => $is_poh
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
	
	public function tandaterima(Request $request)
    {
		$exam_id = $request->input('hide_id_exam');
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with('device')
				->with('media')
				->first();
			$no_laporan = '-';
			foreach ($exam->media as $item) {
				if($item->name == 'Laporan Uji' && $item->no != ''){
					$no_laporan = $item->no;
				}
			}
			try{
				
				$data = Array([
					'nama_pemohon' => $exam->user->name,
					'alamat_pemohon' => $exam->user->address,
					'nama_perangkat' => $exam->device->name,
					'merek_perangkat' => $exam->device->mark,
					'model_perangkat' => $exam->device->model,
					'kapasitas_perangkat' => $exam->device->capacity,
					'referensi_perangkat' => $exam->device->test_reference,
					'pembuat_perangkat' => $exam->device->manufactured_by,
					'cert_number' => $exam->device->cert_number,
					'no_laporan' => $no_laporan
				]);
				
				$request->session()->put('key_tanda_terima', $data);
				
				Session::flash('message', 'Tanda Terima successfully created');
				// $this->sendProgressEmail("Pengujian atas nama ".$user_name." dengan alamat email ".$user_email.", telah melakukan proses Upload Bukti Pembayaran");
				// return back();
				echo 1;
			} catch(Exception $e){
				Session::flash('error', 'Tanda Terima failed');
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
	
	public function destroy($id,$page,$reason = null)
	{
		$currentUser = Auth::user();
		$logs_a_exam = NULL;
		$logs_a_device = NULL;
		
		$exam_attach = ExaminationAttach::where('examination_id', '=' ,''.$id.'')->get();
		$exam = Examination::find($id);
			$device_id = $exam['device_id'];
		$device = Device::find($device_id);
		if ($exam_attach && $exam && $device){
			/* DELETE SPK FROM OTR */
			if($exam->spk_code){
				$client = new Client([
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					// Base URI is used with relative requests
					// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					'base_uri' => config("app.url_api_bsp"),
					// You can set any number of default request options.
					'timeout'  => 60.0,
				]);
				
				$res_delete_spk = $client->get('spk/delete?examId='.$exam->id.'&spkNumber='.$exam->spk_code)->getBody();
				$delete_spk = json_decode($res_delete_spk);
				if($delete_spk->status == false){
					Session::flash('error', $delete_spk->message.' (message from OTR)');
					return redirect('/admin/examination');
				}
			}
			/* END DELETE SPK FROM OTR */
			try{
				$logs_a_exam = $exam;
				$logs_a_device = $device;
				Income::where('reference_id', '=' ,''.$id.'')->delete();
				Questioner::where('examination_id', '=' ,''.$id.'')->delete();
				Equipment::where('examination_id', '=' ,''.$id.'')->delete();
				EquipmentHistory::where('examination_id', '=' ,''.$id.'')->delete();
				ExaminationHistory::where('examination_id', '=' ,''.$id.'')->delete();
				ExaminationAttach::where('examination_id', '=' ,''.$id.'')->delete();
				QuestionerDynamic::where('examination_id', '=' ,''.$id.'')->delete();
				$exam->delete();
				$device->delete();
				
				if (File::exists(public_path().'\media\\examination\\'.$id)){
					File::deleteDirectory(public_path().'\media\\examination\\'.$id);
				}

				$logs = new Logs_administrator;
				$logs->id = Uuid::uuid4();
				$logs->user_id = $currentUser->id;
				$logs->action = "Hapus Data Pengujian";
				$logs->page = $page;
				$logs->reason = urldecode($reason);
				$logs->data = $logs_a_exam.$logs_a_device;
				$logs->save();

				Session::flash('message', 'Examination successfully deleted');
				return redirect('/admin/examination');
			}catch (Exception $e){
				Session::flash('error', 'Delete failed');
				return redirect('/admin/examination');
			}
		}
	}
	
	public function resetUjiFungsi($id,$reason = null)
	{
		$currentUser = Auth::user();
		$logs_a_exam = NULL;

        if ($currentUser){
			$exam = Examination::find($id);
			if ($exam){
				try{
					$logs_a_exam = $exam;
					Equipment::where('examination_id', '=' ,''.$exam->id.'')->delete();
					EquipmentHistory::where('examination_id', '=' ,''.$exam->id.'')->delete();
					
					$exam->cust_test_date = NULL;
					$exam->deal_test_date = NULL;
					$exam->urel_test_date = NULL;
					$exam->function_date = NULL;
					$exam->function_test_reason = NULL;
					$exam->function_test_TE = 0;
					$exam->function_test_date_approval = 0;
					$exam->function_test_status_detail = NULL;
					$exam->updated_by = $currentUser->id;
					$exam->updated_at = date('Y-m-d H:i:s');
					$exam->location = 0;
					
					$exam->save();
					
					$exam_hist = new ExaminationHistory;
					$exam_hist->examination_id = $exam->id;
					$exam_hist->date_action = date('Y-m-d H:i:s');
					$exam_hist->tahap = 'Uji Fungsi';
					$exam_hist->status = 'Not Completed';
					$exam_hist->keterangan = 'Data Uji Fungsi direset oleh Super Admin URel';
					$exam_hist->created_by = $currentUser->id;
					$exam_hist->created_at = date('Y-m-d H:i:s');
					$exam_hist->save();
					
					// $data= array( 
						// "from"=>"admin",
						// "to"=>$exam->created_by,
						// "message"=>"Hasil Uji Fungsi lain-lain",
						// "url"=>"pengujian/".$exam->id."/detail",
						// "is_read"=>0,
						// "created_at"=>date("Y-m-d H:i:s"),
						// "updated_at"=>date("Y-m-d H:i:s")
					// );

					$logs = new Logs;
					$logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
					$logs->action = "Reset Uji Fungsi";
					$logs->data = $exam;
					$logs->created_by = $currentUser->id;
					$logs->page = "EXAMINATION";
					$logs->save();

					$logs_a = new Logs_administrator;
					$logs_a->id = Uuid::uuid4();
					$logs_a->user_id = $currentUser->id;
					$logs_a->action = "Reset Uji Fungsi";
					$logs_a->page = "Pengujian -> Change Status";
					$logs_a->reason = urldecode($reason);
					$logs_a->data = $logs_a_exam;
					$logs_a->save();
					
					Session::flash('message', 'Function Test successfully reset');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}catch (Exception $e){
					Session::flash('error', 'Reset failed');
					return redirect('/admin/examination/'.$exam->id.'/edit');
				}
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
		$gen_spk_code = $this->generateSPKCode($request->input('lab_code'),$request->input('exam_type'),$request->input('year'));
		return $gen_spk_code;
    }
	
	public function generateSPKCode($a,$b,$c) {
		// $query = "
			// SELECT 
			// SUBSTRING_INDEX(SUBSTRING_INDEX(spk_code,'/',2),'/',-1) + 1 AS last_numb
			// FROM examinations WHERE 
			// SUBSTRING_INDEX(spk_code,'/',1) = '".$a."' AND
			// SUBSTRING_INDEX(SUBSTRING_INDEX(spk_code,'/',-2),'/',1) = '".$b."' AND
			// SUBSTRING_INDEX(spk_code,'/',-1) = '".$c."'
			// ORDER BY last_numb DESC LIMIT 1
		// ";
		$query = "
			SELECT 
			SUBSTRING_INDEX(SUBSTRING_INDEX(spk_code,'/',2),'/',-1) + 1 AS last_numb
			FROM examinations WHERE 
			SUBSTRING_INDEX(spk_code,'/',1) = '".$a."' AND
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
			FROM examinations WHERE SUBSTRING_INDEX(spb_number,'/',-1) = ".$thisYear." AND spb_number LIKE '%TTH-02%'
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			return '001/TTH-02/'.$thisYear.'';
		}
		else{
			$last_numb = $data[0]->last_numb;
			if($last_numb < 10){
				return '00'.$last_numb.'/TTH-02/'.$thisYear.'';
			}
			else if($last_numb < 100){
				return '0'.$last_numb.'/TTH-02/'.$thisYear.'';
			}
			else{
				return ''.$last_numb.'/TTH-02/'.$thisYear.'';
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
		$request->session()->put('key_for_for_kuitansi', "Pengujian Perangkat ".$exam->device->name." (".$kode.")");
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
		$exam_id = $request->session()->get('key_exam_id_for_generate_spb');
		$spb_number = $request->session()->get('key_spb_number_for_generate_spb');
		if($spb_number == "" or $spb_number == null){
			$spb_number = $this->generateSPBNumber();
		}
		$spb_date = $request->session()->get('key_spb_date_for_generate_spb');
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
		/*$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
			// Base URI is used with relative requests
			// 'base_uri' => 'http://37.72.172.144/telkomtesthouse/public/v1/',
			'base_uri' => config("app.url_api_bsp"),
			// You can set any number of default request options.
			'timeout'  => 60.0,
		]);
		*/
		/*$res_manager_urel = $client->get('user/getManagerLabInfo?groupId=MU')->getBody();
		$manager_urel = json_decode($res_manager_urel);

		if(count($manager_urel->data) == 1){
			if( strpos( $manager_urel->data[0]->name, "/" ) !== false ) {$manager_urels = urlencode(urlencode($manager_urel->data[0]->name));}
				else{$manager_urels = $manager_urel->data[0]->name?: '-';}
		}else{
			$manager_urels = '...............................';
		}*/
		$is_poh = 0;
		$general_setting_poh = GeneralSetting::where('code', 'poh_manager_urel')->first();
		if($general_setting_poh){
			if($general_setting_poh->is_active){
				$is_poh = 1;
				if( strpos( $general_setting_poh->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting_poh->value));}
					else{$manager_urels = $general_setting_poh->value?: '-';}
			}else{
				$general_setting = GeneralSetting::where('code', 'manager_urel')->first();
				if($general_setting){
					if( strpos( $general_setting->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting->value));}
						else{$manager_urels = $general_setting->value?: '-';}
				}else{
					$manager_urels = '...............................';
				}	
			}
		}else{
			$general_setting = GeneralSetting::where('code', 'manager_urel')->first();
			if($general_setting){
				if( strpos( $general_setting->value, "/" ) !== false ) {$manager_urels = urlencode(urlencode($general_setting->value));}
					else{$manager_urels = $general_setting->value?: '-';}
			}else{
				$manager_urels = '...............................';
			}
		}
		
		if($this->cekSPBNumber($request->input('spb_number')) > 0){
			echo 2; //SPB Number Exists
		}else{
			$exam_id = $request->input('exam_id');
			$spb_number = $request->input('spb_number');
			$spb_date = $request->input('spb_date');
			$arr_nama_perangkat = $request->input('arr_nama_perangkat');
			$arr_biaya = $request->input('arr_biaya');
			$exam = Examination::where('id', $exam_id)
						->with('user')
						->with('company')
						->with('device')
						->with('examinationType')
						->first()
			;
/* Kirim Draft ke TPN */
			$biaya = 0;
			for($i=0;$i<count($arr_biaya);$i++){
				$biaya = $biaya + $arr_biaya[$i];
			}
			$ppn = 0.1*$biaya;
			$total_biaya = $biaya + $ppn;
			$details [] = 
	            [
	                "item" => 'Biaya Uji '.$exam->examinationType->name.' ('.$exam->examinationType->description.')',
	                "description" => $exam->device->name.', '.$exam->device->mark.', '.$exam->device->capacity.', '.$exam->device->model,
	                "quantity" => 1,
	                "price" => $biaya,
	                "total" => $biaya
	            ]
	        ;

			$data_draft = [
	            "from" => [
	                "name" => "PT TELEKOMUNIKASI INDONESIA, TBK.",
	                "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
	                "phone" => "(+62) 812-2483-7500",
	                "email" => "urelddstelkom@gmail.com",
	                "npwp" => "01.000.013.1-093.000"
	            ],
	            "to" => [
	                "name" => $exam->company->name ? $exam->company->name : "-",
	                "address" => $exam->company->address ? $exam->company->address : "-",
	                "phone" => $exam->company->phone_number ? $exam->company->phone_number : "-",
	                "email" => $exam->company->email ? $exam->company->email : "-",
	                "npwp" => $exam->company->npwp_number ? $exam->company->npwp_number : "-"
	            ],
	            "product_id" => config("app.product_id_tth_2"), //product_id TTH untuk Pengujian
	            "details" => $details,
	            "created" => [
	                "by" => $exam->user->name,
	                "reference_id" => $exam->user->id
	            ],
	            "include_tax_invoice" => true,
	            "bank" => [
	                "owner" => "Divisi RisTI TELKOM",
	                "account_number" => "131-0096022712",
	                "bank_name" => "BANK MANDIRI",
	                "branch_office" => "KCP KAMPUS TELKOM BANDUNG"         
	            ]
	        ];
	        $purchase = $this->api_purchase($data_draft);

	        /*$PO_ID = $request->session()->get('PO_ID_from_TPN') ? $request->session()->get('PO_ID_from_TPN') : ($purchase && $purchase->status ? $purchase->data->_id : null);
            $request->session()->put('PO_ID_from_TPN', $PO_ID);*/
	        $total_price = $biaya;
            $PO_ID = $purchase && $purchase->status ? $purchase->data->_id : null;
            $tax = floor(0.1*$total_price);
            $final_price = $total_price + $tax;
/* END Kirim Draft ke TPN */
			$data = []; 
			$data[] = [
				'spb_number' => $spb_number,
				'spb_date' => $spb_date,
				'arr_nama_perangkat' => $arr_nama_perangkat,
				'arr_biaya' => $arr_biaya,
				'exam' => $exam,
				'manager_urel' => $manager_urels,
				'is_poh' => $is_poh,
				'payment_method' => $this->api_get_payment_methods()
			];

			$request->session()->put('key_exam_for_spb', $data);
			echo $PO_ID.'myToken'.$final_price;
		}
    }

    public function api_purchase($data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_2")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false,
            'verify' => false
        ]);
        try {
            
            $params['json'] = $data;
            $res_purchase = $client->post("v1/draftbillings", $params)->getBody();
            $purchase = json_decode($res_purchase);

            return $purchase;
        } catch(Exception $e){
            return null;
        }
    }

    public function api_get_payment_methods(){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false,
            'verify' => false
        ]);
        try {
            $res_payment_method = $client->get("v1/products/".config("app.product_id_tth_2")."/paymentmethods")->getBody();
            $payment_method = json_decode($res_payment_method);

            return $payment_method;
        } catch(Exception $e){
            return null;
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
		if( strpos( $data[0]->function_test_NO, "/" ) !== false ) {$no_reg = urlencode(urlencode($data[0]->function_test_NO));}
			else{$no_reg = $data[0]->function_test_NO?: '-';}
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
				$name_te = "-";
			}
		if(count($data[0]->equipment)>0){
			if( strpos( $data[0]->equipment[0]->pic, "/" ) !== false ) {$pic = urlencode(urlencode($data[0]->equipment[0]->pic));}
				else{$pic = $data[0]->equipment[0]->pic?: '-';}
		}else{
			$pic = '-';
		}
		if($data[0]->function_test_date_approval == 1){
			if($data[0]->function_date != null){
				if( strpos( $data[0]->function_date, "/" ) !== false ) {$tgl_uji_fungsi = urlencode(urlencode(date("j F Y", strtotime($data[0]->function_date))));}
					else{$tgl_uji_fungsi = date("j F Y", strtotime($data[0]->function_date))?: '-';}
			}else{
				if( strpos( $data[0]->deal_test_date, "/" ) !== false ) {$tgl_uji_fungsi = urlencode(urlencode(date("j F Y", strtotime($data[0]->deal_test_date))));}
					else{$tgl_uji_fungsi = date("j F Y", strtotime($data[0]->deal_test_date))?: '-';}
			}
		}else{
			$tgl_uji_fungsi = '-';
		}
		return \Redirect::route('cetakHasilUjiFungsi', [
			'no_reg' => $no_reg,
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
			'tgl_uji_fungsi' => $tgl_uji_fungsi,
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
		->with('User')
		->with('Device')
		->with('Equipment')
		->get();
		if(isset($data[0]->equipment[0]->no)){
			if ($data[0]->equipment[0]->no) {
				$kode_barang = $data[0]->equipment[0]->no;
			}else{
				$kode_barang = $this->generateKodeBarang($data[0]->ExaminationLab->lab_init,$this->romawi(date('n')),date('Y'));
				$query_update = "UPDATE equipments
					SET no = '".$kode_barang."'
					WHERE examination_id = '".$id."'
				";
				$data_update = DB::update($query_update);
			}
		}else{
			$kode_barang = '';
			Session::flash('error', 'Undefined Equipment(s), please put equipment(s) first');
            return redirect('/admin/examination/'.$id.'/edit');
		}
		$kode_barang = urlencode(urlencode($kode_barang));
		if( strpos( $data[0]->company->name, "/" ) !== false ) {$company_name = urlencode(urlencode($data[0]->company->name));}
			else{$company_name = $data[0]->company->name?: '-';}
		if( strpos( $data[0]->company->address, "/" ) !== false ) {$company_address = urlencode(urlencode($data[0]->company->address));}
			else{$company_address = $data[0]->company->address?: '-';}
		if( strpos( $data[0]->company->phone, "/" ) !== false ) {$company_phone = urlencode(urlencode($data[0]->company->phone));}
			else{$company_phone = $data[0]->company->phone?: '-';}
		if( strpos( $data[0]->company->fax, "/" ) !== false ) {$company_fax = urlencode(urlencode($data[0]->company->fax));}
			else{$company_fax = $data[0]->company->fax?: '-';}
		if( strpos( $data[0]->user->phone_number, "/" ) !== false ) {$user_phone = urlencode(urlencode($data[0]->user->phone_number));}
			else{$user_phone = $data[0]->user->phone_number?: '-';}
		if( strpos( $data[0]->user->fax, "/" ) !== false ) {$user_fax = urlencode(urlencode($data[0]->user->fax));}
			else{$user_fax = $data[0]->user->fax?: '-';}
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
			'user_phone' => $user_phone,
			'user_fax' => $user_fax,
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
		$exam_id = $request->session()->get('key_exam_id_for_generate_equip_masuk');
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
		/*$query = "
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
		";*/
		$query = "
			SELECT
				SUBSTRING_INDEX(no, '/', 1) + 1 AS last_numb
			FROM
				equipments
			WHERE
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

	public function deleteRevLapUji($id)
    {
        $currentUser = Auth::user();
        $examination_attachment = ExaminationAttach::find($id);
        if($examination_attachment){
            
            // unlink stels_sales_detail.attachment
            if (File::exists(public_path().'\media\examination\\'.$examination_attachment->examination_id.'\\'.$examination_attachment->attachment)){
                File::delete(public_path().'\media\examination\\'.$examination_attachment->examination_id.'\\'.$examination_attachment->attachment);
            }

            // delete stels_sales_detail
            $examination_attachment->delete();

            Session::flash('message', 'Successfully Delete Revision File');
        }else{
            Session::flash('error', 'Undefined Data');
        }
            return redirect('/admin/examination/'.$examination_attachment->examination_id.'/edit');

    }
}
