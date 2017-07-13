<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\ChangeLocale;

use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\ExaminationHistory;
use App\AdminRole;
use App\User;
use App\Logs;
use App\Income;

use Auth;
use Mail;
use Session;
use Response;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class MyExaminationController extends Controller
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
        $currentUser = Auth::user();
		$adminRole = AdminRole::find($currentUser->id);
		$message = null;
		$paginate = 5;
		$search = trim($request->input('search'));
		$type = '';
		$status = '';
		$before = null;
		$after = null;
		$examType = ExaminationType::all();
		$data = ExaminationType::where("id", "=", "")
							->paginate($paginate);
		
		if(!$adminRole){
			$message = "Data Not Found";
			return view('admin.myexam.index')
					->with('message', $message)
					->with('data', $data)
					->with('type', $examType)
					->with('search', $search)
					->with('filterType', $type)
					->with('status', $status)
					->with('before_date', $before)
					->with('after_date', $after);
					;
		}else{
			if ($currentUser){
				$query = Examination::whereNotNull('created_at')
									->with('user')
									->with('company')
									->with('examinationType')
									->with('media')
									->with('device');

				if ($adminRole->registration_status){
					$query->where(function($q){
						$q
							->where('registration_status', 0)
							->orWhere('registration_status', -1);
					});
				}

				if ($adminRole->function_status){
					$query->orWhere(function($q){
							$q->where('registration_status', 1)
							  ->where(function($r){
									$r
										->where('function_status', 0)
										->orWhere('function_status', -1);
								})
							;
						})
					;
				}
				

				if ($adminRole->contract_status){
					$query->orWhere(function($q){
							$q->where('function_status', 1)
							  ->where(function($r){
									$r
										->where('contract_status', 0)
										->orWhere('contract_status', -1);
								})
							;
						})
					;
				}
				
				if ($adminRole->spb_status){
					$query->orWhere(function($q){
							$q->where('contract_status', 1)
							  ->where(function($r){
									$r
										->where('spb_status', 0)
										->orWhere('spb_status', -1);
								})
							;
						})
					;
				}
				
				if ($adminRole->payment_status){
					$query->orWhere(function($q){
							$q->where('spb_status', 1)
							  ->where(function($r){
									$r
										->where('payment_status', 0)
										->orWhere('payment_status', -1);
								})
							;
						})
					;
				}

				if ($adminRole->spk_status){
					$query->orWhere(function($q){
							$q->where('payment_status', 1)
							  ->where(function($r){
									$r
										->where('spk_status', 0)
										->orWhere('spk_status', -1);
								})
							;
						})
					;
				}

				if ($adminRole->examination_status){
					$query->orWhere(function($q){
							$q->where('spk_status', 1)
							  ->where(function($r){
									$r
										->where('examination_status', 0)
										->orWhere('examination_status', -1);
								})
							;
						})
					;
				}

				if ($adminRole->resume_status){
					$query->orWhere(function($q){
							$q->where('examination_status', 1)
							  ->where(function($r){
									$r
										->where('resume_status', 0)
										->orWhere('resume_status', -1);
								})
							;
						})
					;
				}

				if ($adminRole->qa_status){
					$query->orWhere(function($q){
							$q->where('resume_status', 1)
							  ->where(function($r){
									$r
										->where('qa_status', 0)
										->orWhere('qa_status', -1);
								})
							;
						})
					;
				}

				if ($adminRole->certificate_status){
					$query->orWhere(function($q){
							$q->where('qa_status', 1)
							  ->where(function($r){
									$r
										->where('certificate_status', 0)
										->orWhere('certificate_status', -1);
								})
							;
						})
					;
				} 
				
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
					$logs->id = Uuid::uuid4();
					$logs->user_id = $currentUser->id;
					$logs->action = "search";  
					$dataSearch = array('search' => $search);
					$logs->data = json_encode($dataSearch);
					$logs->created_by = $currentUser->id;
					$logs->page = "EXAMINATION";
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
					$message = "Data Not Found";
				}
				
				return view('admin.myexam.index')
					->with('message', $message)
					->with('data', $data)
					->with('type', $examType)
					->with('search', $search)
					->with('filterType', $type)
					->with('status', $status)
					->with('before_date', $before)
					->with('after_date', $after);
			}
		}
    }
	
	public function indexs(Request $request)
    {
		$currentUser = Auth::user();
        $adminRole = AdminRole::find($currentUser->id);
		
		$message = null;
        $paginate = 10;
        $search = trim($request->input('search'));
        $type = '';
		$status = '';

		$examType = ExaminationType::all();
		
        $query = Examination::whereNotNull('created_at')
                            // ->where(function($q){
                                // $q->where('registration_status', 0)
                                    // ->orWhere('registration_status', 1)
									// ->orWhere('registration_status', -1)
                                    // ->orWhere('spb_status', 0)
									// ->orWhere('spb_status', -1)
                                    // ->orWhere('spb_status', 1)
									// ->orWhere('payment_status', -1);
                            // })
                            ->with('user')
                            ->with('company')
                            ->with('examinationType')
                            ->with('media')
                            ->with('device')
							;
		// $query->where(function($q){
		// 	$q->where('registration_status', '<' , 2);
		// });
							
		if ($adminRole->registration_status){
			$query->where(function($q){
				$q
					->where('registration_status', 0)
					->orWhere('registration_status', -1);
			});
		}

		if ($adminRole->function_status){
			$query->orWhere(function($q){
					$q->where('registration_status', 1)
					  ->where(function($r){
							$r
								->where('function_status', 0)
								->orWhere('function_status', -1);
						})
					;
				})
			;
		}
		

		if ($adminRole->contract_status){
			$query->orWhere(function($q){
					$q->where('function_status', 1)
					  ->where(function($r){
							$r
								->where('contract_status', 0)
								->orWhere('contract_status', -1);
						})
					;
				})
			;
		}
		
		if ($adminRole->spb_status){
			$query->orWhere(function($q){
					$q->where('contract_status', 1)
					  ->where(function($r){
							$r
								->where('spb_status', 0)
								->orWhere('spb_status', -1);
						})
					;
				})
			;
		}
		
		if ($adminRole->payment_status){
			$query->where(function($q){
					$q->where('spb_status', 1)
					  ->where(function($r){
							$r
								->where('payment_status', 0)
								->orWhere('payment_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->spk_status){
			$query->where(function($q){
					$q->where('payment_status', 1)
					  ->where(function($r){
							$r
								->where('spk_status', 0)
								->orWhere('spk_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->examination_status){
			$query->where(function($q){
					$q->where('spk_status', 1)
					  ->where(function($r){
							$r
								->where('examination_status', 0)
								->orWhere('examination_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->resume_status){
			$query->where(function($q){
					$q->where('examination_status', 1)
					  ->where(function($r){
							$r
								->where('resume_status', 0)
								->orWhere('resume_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->qa_status){
			$query->where(function($q){
					$q->where('resume_status', 1)
					  ->where(function($r){
							$r
								->where('qa_status', 0)
								->orWhere('qa_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->certificate_status){
			$query->where(function($q){
					$q->where('qa_status', 1)
					  ->where(function($r){
							$r
								->where('certificate_status', 0)
								->orWhere('certificate_status', -1);
						})
					;
				})
			;
		} 

		if ($search != null){
            $query->whereHas('device', function ($q) use ($search){
                return $q->where('name', 'like', '%'.strtolower($search).'%');
            });
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
		// if ($request->has('status')){
			// switch ($request->get('status')) {
				// case 1:
					// $query->where('registration_status', '!=', 1);
					// $status = 1;
					// break;
				// case 2:
					// $query->where('registration_status', 1);
					// $query->where('spb_status', '!=', 1);
					// $status = 2;
					// break;
				// case 3:
					// $query->where('spb_status', 1);
					// $query->where('payment_status', '!=', 1);
					// $status = 3;
					// break;
				// case 4:
					// $query->where('payment_status', 1);
					// $status = 4;
					// break;
				// default:
					// $status = 'all';
					// break;
			// }
		// }

        $data = $query->orderBy('created_at')
                    ->paginate($paginate);

        if (count($data) == 0){
            $message = 'Data not found';
        }
		
        return view('admin.myexam.index')
            ->with('message', $message)
            ->with('data', $data)
            ->with('type', $examType)
            ->with('search', $search)
            ->with('filterType', $type)
			->with('status', $status);
    }
	
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

        return view('admin.myexam.show')
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
                            ->first();

        $labs = ExaminationLab::all();
		$gen_spk_code = $this->generateSPKCOde($exam->examinationLab->lab_code,$exam->examinationType->name,date('Y'));
		
		$currentUser = Auth::user();
		$adminRole = AdminRole::find($currentUser->id);
		
		if ($adminRole->registration_status){
			$exam->where('registration_status', 0)
				->orWhere('registration_status', -1);
		}

		if ($adminRole->function_status){
			$exam
				->orWhere(function($q){
					$q
						->where('registration_status', 1)
						->where(function($r){
							$r
								->where('function_status', 0)
								->orWhere('function_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->contract_status){
			$exam
				->orWhere(function($q){
					$q
						->where('function_status', 1)
						->where(function($r){
							$r
								->where('contract_status', 0)
								->orWhere('contract_status', -1);
						})
					;
				})
			;
		}

/* 
		if ($adminRole->spb_status){
			$exam
				->orWhere(function($q){
					$q
						->where('contract_status', 1)
						->where(function($r){
							$r
								->where('spb_status', 0)
								->orWhere('spb_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->payment_status){
			$exam
				->orWhere(function($q){
					$q
						->where('spb_status', 1)
						->where(function($r){
							$r
								->where('payment_status', 0)
								->orWhere('payment_status', -1);
						})
					;
				})
			;
		}

		if ($adminRole->spk_status){
			$exam->where('payment_status', 1)
				->where(function($q){
					$q->where('spk_status', 0)
						->orWhere('spk_status', -1);
				});
		}

		if ($adminRole->examination_status){
			$exam->where('spk_status', 1)
				->where(function($q){
					$q->where('examination_status', 0)
						->orWhere('examination_status', -1);
				});
		}

		if ($adminRole->resume_status){
			$exam->where('examination_status', 1)
				->where(function($q){
					$q->where('resume_status', 0)
						->orWhere('resume_status', -1);
				});
		}

		if ($adminRole->qa_status){
			$exam->where('resume_status', 1)
				->where(function($q){
					$q->where('qa_status', 0)
						->orWhere('qa_status', -1);
				});
		}

		if ($adminRole->certificate_status){
			$exam->where('qa_status', 1)
				->where(function($q){
					$q->where('certificate_status', 0)
						->orWhere('certificate_status', -1);
				});
		}
 */	

        return view('admin.myexam.edit')
            ->with('data', $exam)
			->with('gen_spk_code', $gen_spk_code)
            ->with('labs', $labs)
            ->with('adminRole', $adminRole);
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
				Session::flash('error', 'SPK Code must be unique, please Re-Generate');
                return redirect('/admin/myexam/'.$exam->id.'/edit');
			}
        }
        if ($request->has('registration_status')){
			$status = $request->input('registration_status');
			$exam->registration_status = $status;
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.registrasi", "Acc Registrasi");
			}else if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Registrasi",$request->input('keterangan'));
			}
        }
		if ($request->has('function_status')){
			$status = $request->input('function_status');
			$exam->function_status = $status;
			if($status == 1){
				// $this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.function", "Acc Uji Fungsi");
			}else if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Uji Fungsi",$request->input('keterangan'));
			}
        }
        if ($request->has('contract_status')){
			if ($request->hasFile('contract_file')) {
				$ext_file = $request->file('contract_file')->getClientOriginalExtension();
				$name_file = uniqid().'_contract_'.$exam->id.'.'.$ext_file;
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
					return redirect('/admin/myexam/'.$exam->id.'/edit');
				}
			}
			$status = $request->input('contract_status');
            $exam->contract_status = $status;
			if($status == 1){
				$path_file = public_path().'/media/examination/'.$id;
				$attach = ExaminationAttach::where('name', 'Tinjauan Kontrak')->where('examination_id', ''.$id.'')->first();
					$attach_name = $attach->attachment;
				// $this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.contract", "Upload Tinjauan Pustaka",$path_file."/".$attach_name);
			}else if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Tinjauan Pustaka",$request->input('keterangan'));
			}
        }
		if ($request->has('spb_status')){
			if ($request->hasFile('spb_file')) {
				$ext_file = $request->file('spb_file')->getClientOriginalExtension();
				$name_file = uniqid().'_spb_'.$exam->id.'.'.$ext_file;
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
					return redirect('/admin/myexam/'.$exam->id.'/edit');
				}
			}
			$status = $request->input('spb_status');
            $exam->spb_status = $status;
			if($status == 1){
				$exam->price = $request->input('exam_price');
				$path_file = public_path().'/media/examination/'.$id;
				$attach = ExaminationAttach::where('name', 'SPB')->where('examination_id', ''.$id.'')->first();
					$attach_name = $attach->attachment;
				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.spb", "Upload SPB",$path_file."/".$attach_name);
			}else if($status == -1){
				$exam->price = $request->input('exam_price');
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","SPB",$request->input('keterangan'));
			}
        }
        if ($request->has('payment_status')){
            $status = $request->input('payment_status');
            $exam->payment_status = $status;
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
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran");
			}else if($status == -1){
				Income::where('reference_id', '=' ,''.$exam->id.'')->delete();
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pembayaran",$request->input('keterangan'));
			}
        }
        if ($request->has('spk_status')){
            $status = $request->input('spk_status');
            $exam->spk_status = $status;
			if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pembuatan SPK",$request->input('keterangan'));
			}
        }
        if ($request->has('examination_status')){
            $status = $request->input('examination_status');
            $exam->examination_status = $status;
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.uji", "Pelaksanaan Uji");
			}else if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pelaksanaan Uji",$request->input('keterangan'));
			}
        }
        if ($request->has('resume_status')){
			if ($request->hasFile('lap_uji_file')) {
				$ext_file = $request->file('lap_uji_file')->getClientOriginalExtension();
				$name_file = uniqid().'_resume_'.$exam->id.'.'.$ext_file;
				$path_file = public_path().'/media/examination/'.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('lap_uji_file')->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'Laporan Uji')->where('examination_id', ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = 'Laporan Uji';
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}
				}else{
					Session::flash('error', 'Save resume to directory failed');
					return redirect('/admin/myexam/'.$exam->id.'/edit');
				}
			}
            $status = $request->input('resume_status');
            $exam->resume_status = $status;
			if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Laporan Uji",$request->input('keterangan'));
			}
        }
        if ($request->has('qa_status')){
            $status = $request->input('qa_status');
            $passed = $request->input('passed');
            $exam->qa_status = $status;
            $exam->qa_passed = $passed;
			if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Sidang QA",$request->input('keterangan'));
			}
        }
        if ($request->has('certificate_status')){
            $status = $request->input('certificate_status');
            $exam->certificate_status = $status;
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sertifikat", "Penerbitan Sertfikat");
			}else if($status == -1){
				$exam->keterangan = $request->input('keterangan');
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.fail", "Konfirmasi Pembatalan Pengujian","Pembuatan Sertifikat",$request->input('keterangan'));
			}
        }
        if ($request->has('examination_date')){
            $exam->examination_date = $request->input('examination_date');
        }
        if ($request->has('resume_date')){
            $exam->resume_date = $request->input('resume_date');
        }
        if ($request->has('qa_date')){
            $exam->qa_date = $request->input('qa_date');
        }
        if ($request->has('certificate_date')){
            $exam->certificate_date = $request->input('certificate_date');
        }
		if ($request->has('spk_date')){
            $exam->spk_date = $request->input('spk_date');
        }
		if ($request->has('function_date')){
            $exam->function_date = $request->input('function_date');
        }
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
		if ($request->has('urel_test_date')){
            $exam->urel_test_date = $request->input('urel_test_date');
        }
		if ($request->has('deal_test_date')){
            $exam->deal_test_date = $request->input('deal_test_date');
        }
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
                // return redirect('/admin/myexam/'.$exam->id.'/edit');
            // }
        // }

        if ($request->hasFile('certificate_file')) {
            $ext_file = $request->file('certificate_file')->getClientOriginalExtension();
            $name_file = uniqid().'_certificate_'.$exam->device_id.'.'.$ext_file;
            $path_file = public_path().'/media/device/'.$exam->device_id;
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
                return redirect('/admin/myexam/'.$exam->id.'/edit');
            }
        }
		
			$device = Device::findOrFail($exam->device_id);
			$exam_type = ExaminationType::findOrFail($exam->examination_type_id);
		
        $exam->updated_by = $currentUser->id;

        try{
            $exam->save();
             
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
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Update ".$request->input('status');   
                $logs->data = $exam;
                $logs->created_by = $currentUser->id;
                $logs->page = "MY EXAMINATION";
                $logs->save();

            Session::flash('message', 'Examination successfully updated');
            return redirect('/admin/myexam');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/myexam/'.$exam->id.'/edit');
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
	
	public function autocomplete($query) {
        $respons_result = Examination::adm_exam_autocomplet($query);
        return response($respons_result);
    }
	
	public function tanggalkontrak(Request $request)
    {
		$currentUser = Auth::user();
		
		$exam_id = $request->input('hide_id_exam');
		$testing_start = $request->input('testing_start');
			$testing_start_ina_temp = strtotime($testing_start);
			$testing_start_ina = date('d-m-Y', $testing_start_ina_temp);
		$testing_end = $request->input('testing_end');
			$testing_end_ina_temp = strtotime($testing_end);
			$testing_end_ina = date('d-m-Y', $testing_end_ina_temp);
		$contract_date = $request->input('contract_date');
			$contract_date_ina_temp = strtotime($contract_date);
			$contract_date_ina = date('d-m-Y', $contract_date_ina_temp);
		
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with('device')
				->first();
			
			try{
				$query_update = "UPDATE examinations
					SET 
						contract_date = '".$contract_date."',
						testing_start = '".$testing_start."',
						testing_end = '".$testing_end."',
						updated_by = '".$currentUser['attributes']['id']."',
						updated_at = '".date('Y-m-d h:i:s')."'
					WHERE id = '".$exam_id."'
				";
				$data_update = DB::update($query_update);
				
				$data = Array([
					'nama_pemohon' => $exam->user->name,
					'alamat_pemohon' => $exam->user->address,
					'nama_perangkat' => $exam->device->name,
					'merek_perangkat' => $exam->device->mark,
					'model_perangkat' => $exam->device->model,
					'kapasitas_perangkat' => $exam->device->capacity,
					'referensi_perangkat' => $exam->device->test_reference,
					'pembuat_perangkat' => $exam->device->manufactured_by,
					'testing_start' => $testing_start_ina,
					'testing_end' => $testing_end_ina,
					'contract_date' => $contract_date_ina
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
	
	public function generateSPBParam(Request $request) {
		$request->session()->put('key_my_exam_id_for_generate_spb', $request->input('exam_id'));
		$request->session()->put('key_my_spb_number_for_generate_spb', $request->input('spb_number'));
		$request->session()->put('key_my_spb_date_for_generate_spb', $request->input('spb_date'));
		echo 1;
    }
	
	public function generateSPB(Request $request) {
		$exam_id = $request->session()->pull('key_my_exam_id_for_generate_spb');
		$spb_number = $request->session()->pull('key_my_spb_number_for_generate_spb');
		$spb_date = $request->session()->pull('key_my_spb_date_for_generate_spb');
		$exam = Examination::where('id', $exam_id)
					->with('device')
					->first()
		;
		$query_price = "SELECT price FROM stels WHERE code = '".$exam->device->test_reference."'";
		$price = DB::select($query_price);
		if(count($price) == 0){
			$price = 0;
		}else{
			$price = $price[0]->price;
		}
		
		$query_stels = "SELECT * FROM stels WHERE is_active = 1";
		$data_stels = DB::select($query_stels);
			
		// setlocale(LC_MONETARY, 'it_IT');
		// $price = number_format($price[0]->price, 0, ',', '.');
		return view('admin.myexam.spb')
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
}
