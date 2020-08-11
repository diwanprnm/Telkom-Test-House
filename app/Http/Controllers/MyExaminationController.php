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

	private const SEARCH = 'search';
	private const ADMIN_MYEXAM_INDEX = 'admin.myexam.index';
	private const MESSAGE = 'message';
	private const FILTER_TYPE = 'filterType';
	private const STATUS = 'status';
	private const BEFORE_DATE = 'before_date';
	private const AFTER_DATE = 'after_date';
	private const CREATED_AT = 'created_at';
	private const COMPANY = 'company';
	private const EXAMINATIN_TYPE = 'examinationType';
	private const MEDIA = 'media';
	private const DEVICE = 'device';
	private const REGISTRATION_STATUS = 'registration_status';
	private const FUNCTION_STATUS = 'function_status';
	private const CONTRACT_STATUS = 'contract_status';
	private const SPB_STATUS = 'spb_status';
	private const PAYMENT_STATUS = 'payment_status';
	private const SPK_STATUS = 'spk_status';
	private const EXAMINATION_STATUS = 'examination_status';
	private const RESUME_STATUS = 'resume_status';
	private const QA_STATUS = 'qa_status';
	private const CERTIFICATE_STATUS = 'certificate_status';
	private const SPK_DATE = 'spk_date';
	private const SPK_CODE = 'spk_code';
	private const ERROR = 'error';
	private const EDIT_LOC = '/edit';
	private const MY_EXAM_LOC = '/admin/myexam/';
	private const KETERANGAN = 'keterangan';
	private const EMAIL_FAILS = 'emails.fail';
	private const KONFORMASI_PEMBATALAN_PENGUJIAN = 'Konfirmasi Pembatalan Pengujian';
	private const CONTRACT_FILE = 'contract_file';
	private const MEDIA_EXAMINATION_LOC = '/media/examination/';
	private const TINJAUAN_KONTRAK = 'Tinjauan Kontrak';
	private const EXAMINATION_ID = 'examination_id';
	private const SPB_FILE = 'spb_file';
	private const EXAM_PRICE = 'exam_price';
	private const SPB_NUMBER = 'spb_number';
	private const SPB_DATE = 'spb_date';
	private const REFERENCE_ID = 'reference_id';
	private const LAP_UJI_FILE = 'lap_uji_file';
	private const LAP_UJI = 'Laporan Uji';
	private const CONTRACT_DATE = 'contract_date';
	private const TESTING_START = 'testing_start';
	private const TESTING_END = 'testing_end';
	private const CERTIFICATE_FILE = 'certificate_file';
	private const DATE_FORMAT = 'Y-m-d H:i:s';
	private const USER_NAME = 'user_name';
	private const DEV_NAME = 'dev_name';
	private const EXAM_TYPE = 'exam_type';
	private const EXAM_TYPE_DESC = 'exam_type_desc';
	private const DATE_FORMAT2 = 'd-m-Y';
	private const EXAM_ID = 'exam_id';
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
		$search = trim($request->input(self::SEARCH));
		$type = '';
		$status = '';
		$before = null;
		$after = null;
		$examType = ExaminationType::all();
		$data = ExaminationType::where("id", "=", "")
							->paginate($paginate);
		
		if(!$adminRole){
			$message = "Data Not Found";
			return view(self::ADMIN_MYEXAM_INDEX)
					->with(self::MESSAGE, $message)
					->with('data', $data)
					->with('type', $examType)
					->with(self::SEARCH, $search)
					->with(self::FILTER_TYPE, $type)
					->with(self::STATUS, $status)
					->with(self::BEFORE_DATE, $before)
					->with(self::AFTER_DATE, $after);
		}else{
			if ($currentUser){
				$query = Examination::whereNotNull(self::CREATED_AT)
									->with('user')
									->with(self::COMPANY)
									->with(self::EXAMINATIN_TYPE)
									->with(self::MEDIA)
									->with(self::DEVICE);

				if ($adminRole->registration_status){
					$query->where(function($q){
						$q
							->where(self::REGISTRATION_STATUS, 0)
							->orWhere(self::REGISTRATION_STATUS, -1);
					});
				}

				if ($adminRole->function_status){
					$query->orWhere(function($q){
							$q->where(self::REGISTRATION_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::FUNCTION_STATUS, 0)
										->orWhere(self::FUNCTION_STATUS, -1);
								})
							;
						})
					;
				}
				

				if ($adminRole->contract_status){
					$query->orWhere(function($q){
							$q->where(self::FUNCTION_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::CONTRACT_STATUS, 0)
										->orWhere(self::CONTRACT_STATUS, -1);
								})
							;
						})
					;
				}
				
				if ($adminRole->spb_status){
					$query->orWhere(function($q){
							$q->where(self::CONTRACT_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::SPB_STATUS, 0)
										->orWhere(self::SPB_STATUS, -1);
								})
							;
						})
					;
				}
				
				if ($adminRole->payment_status){
					$query->orWhere(function($q){
							$q->where(self::SPB_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::PAYMENT_STATUS, 0)
										->orWhere(self::PAYMENT_STATUS, -1);
								})
							;
						})
					;
				}

				if ($adminRole->spk_status){
					$query->orWhere(function($q){
							$q->where(self::PAYMENT_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::SPK_STATUS, 0)
										->orWhere(self::SPK_STATUS, -1);
								})
							;
						})
					;
				}

				if ($adminRole->examination_status){
					$query->orWhere(function($q){
							$q->where(self::SPK_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::EXAMINATION_STATUS, 0)
										->orWhere(self::EXAMINATION_STATUS, -1);
								})
							;
						})
					;
				}

				if ($adminRole->resume_status){
					$query->orWhere(function($q){
							$q->where(self::EXAMINATION_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::RESUME_STATUS, 0)
										->orWhere(self::RESUME_STATUS, -1);
								})
							;
						})
					;
				}

				if ($adminRole->qa_status){
					$query->orWhere(function($q){
							$q->where(self::RESUME_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::QA_STATUS, 0)
										->orWhere(self::QA_STATUS, -1);
								})
							;
						})
					;
				}

				if ($adminRole->certificate_status){
					$query->orWhere(function($q){
							$q->where(self::QA_STATUS, 1)
							  ->where(function($r){
									$r
										->where(self::CERTIFICATE_STATUS, 0)
										->orWhere(self::CERTIFICATE_STATUS, -1);
								})
							;
						})
					;
				} 
				
				if ($search != null){
					$query->where(function($qry) use($search){
						$qry->whereHas(self::DEVICE, function ($q) use ($search){
								return $q->where('name', 'like', '%'.strtolower($search).'%');
							})
						->orWhereHas(self::COMPANY, function ($q) use ($search){
								return $q->where('name', 'like', '%'.strtolower($search).'%');
							});
					});

					$logs = new Logs;
					$logs->id = Uuid::uuid4();
					$logs->user_id = $currentUser->id;
					$logs->action = self::SEARCH;  
					$dataSearch = array(self::SEARCH => $search);
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

				if ($request->has(self::COMPANY)){
					$query->whereHas(self::COMPANY, function ($q) use ($request){
						return $q->where('name', 'like', '%'.strtolower($request->get(self::COMPANY)).'%');
					});
				}

				if ($request->has(self::DEVICE)){
					$query->whereHas(self::DEVICE, function ($q) use ($request){
						return $q->where('name', 'like', '%'.strtolower($request->get(self::DEVICE)).'%');
					});
				}

				if ($request->has(self::STATUS)){
					switch ($request->get(self::STATUS)) {
						case 1:
							$query->where(self::REGISTRATION_STATUS, 1);
							$status = 1;
							break;
						case 2:
							$query->where(self::FUNCTION_STATUS, 1);
							$status = 2;
							break;
						case 3:
							$query->where(self::CONTRACT_STATUS, 1);
							$status = 3;
							break;
						case 4:
							$query->where(self::SPB_STATUS, 1);
							$status = 4;
							break;
						case 5:
							$query->where(self::PAYMENT_STATUS, 1);
							$status = 5;
							break;
						case 6:
							$query->where(self::SPK_STATUS, 1);
							$status = 6;
							break;
						case 7:
							$query->where(self::EXAMINATION_STATUS, 1);
							$status = 7;
							break;
						case 8:
							$query->where(self::RESUME_STATUS, 1);
							$status = 8;
							break;
						case 9:
							$query->where(self::QA_STATUS, 1);
							$status = 9;
							break;
						case 10:
							$query->where(self::CERTIFICATE_STATUS, 1);
							$status = 10;
							break;
						
						default:
							$status = 'all';
							break;
					}
				}
				
				if ($request->has(self::BEFORE_DATE)){
					$query->where(self::SPK_DATE, '<=', $request->get(self::BEFORE_DATE));
					$before = $request->get(self::BEFORE_DATE);
				}

				if ($request->has(self::AFTER_DATE)){
					$query->where(self::SPK_DATE, '>=', $request->get(self::AFTER_DATE));
					$after = $request->get(self::AFTER_DATE);
				}

				$data_excel = $query->orderBy('updated_at', 'desc')->get();
				$data = $query->orderBy('updated_at', 'desc')
							->paginate($paginate);
							
				$request->session()->put('excel_pengujian', $data_excel);

				if (count($query) == 0){
					$message = "Data Not Found";
				}
				
				return view(self::ADMIN_MYEXAM_INDEX)
					->with(self::MESSAGE, $message)
					->with('data', $data)
					->with('type', $examType)
					->with(self::SEARCH, $search)
					->with(self::FILTER_TYPE, $type)
					->with(self::STATUS, $status)
					->with(self::BEFORE_DATE, $before)
					->with(self::AFTER_DATE, $after);
			}
		}
    }
	
	public function indexs(Request $request)
    {
		$currentUser = Auth::user();
        $adminRole = AdminRole::find($currentUser->id);
		
		$message = null;
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));
        $type = '';
		$status = '';

		$examType = ExaminationType::all();
		
        $query = Examination::whereNotNull(self::CREATED_AT)
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with(self::EXAMINATIN_TYPE)
                            ->with(self::MEDIA)
                            ->with(self::DEVICE)
							;
							
		if ($adminRole->registration_status){
			$query->where(function($q){
				$q
					->where(self::REGISTRATION_STATUS, 0)
					->orWhere(self::REGISTRATION_STATUS, -1);
			});
		}

		if ($adminRole->function_status){
			$query->orWhere(function($q){
					$q->where(self::REGISTRATION_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::FUNCTION_STATUS, 0)
								->orWhere(self::FUNCTION_STATUS, -1);
						})
					;
				})
			;
		}
		

		if ($adminRole->contract_status){
			$query->orWhere(function($q){
					$q->where(self::FUNCTION_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::CONTRACT_STATUS, 0)
								->orWhere(self::CONTRACT_STATUS, -1);
						})
					;
				})
			;
		}
		
		if ($adminRole->spb_status){
			$query->orWhere(function($q){
					$q->where(self::CONTRACT_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::SPB_STATUS, 0)
								->orWhere(self::SPB_STATUS, -1);
						})
					;
				})
			;
		}
		
		if ($adminRole->payment_status){
			$query->where(function($q){
					$q->where(self::SPB_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::PAYMENT_STATUS, 0)
								->orWhere(self::PAYMENT_STATUS, -1);
						})
					;
				})
			;
		}

		if ($adminRole->spk_status){
			$query->where(function($q){
					$q->where(self::PAYMENT_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::SPK_STATUS, 0)
								->orWhere(self::SPK_STATUS, -1);
						})
					;
				})
			;
		}

		if ($adminRole->examination_status){
			$query->where(function($q){
					$q->where(self::SPK_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::EXAMINATION_STATUS, 0)
								->orWhere(self::EXAMINATION_STATUS, -1);
						})
					;
				})
			;
		}

		if ($adminRole->resume_status){
			$query->where(function($q){
					$q->where(self::EXAMINATION_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::RESUME_STATUS, 0)
								->orWhere(self::RESUME_STATUS, -1);
						})
					;
				})
			;
		}

		if ($adminRole->qa_status){
			$query->where(function($q){
					$q->where(self::RESUME_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::QA_STATUS, 0)
								->orWhere(self::QA_STATUS, -1);
						})
					;
				})
			;
		}

		if ($adminRole->certificate_status){
			$query->where(function($q){
					$q->where(self::QA_STATUS, 1)
					  ->where(function($r){
							$r
								->where(self::CERTIFICATE_STATUS, 0)
								->orWhere(self::CERTIFICATE_STATUS, -1);
						})
					;
				})
			;
		} 

		if ($search != null){
            $query->whereHas(self::DEVICE, function ($q) use ($search){
                return $q->where('name', 'like', '%'.strtolower($search).'%');
            });
        }

        if ($request->has('type')){
            $type = $request->get('type');
			if($request->input('type') != 'all'){
				$query->where('examination_type_id', $request->get('type'));
			}
        }

        if ($request->has(self::COMPANY)){
            $query->whereHas(self::COMPANY, function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get(self::COMPANY)).'%');
            });
        }

        if ($request->has(self::DEVICE)){
            $query->whereHas(self::DEVICE, function ($q) use ($request){
                return $q->where('name', 'like', '%'.strtolower($request->get(self::DEVICE)).'%');
            });
        }

        $data = $query->orderBy(self::CREATED_AT)
                    ->paginate($paginate);

        if (count($data) == 0){
            $message = 'Data not found';
        }
		
        return view(self::ADMIN_MYEXAM_INDEX)
            ->with(self::MESSAGE, $message)
            ->with('data', $data)
            ->with('type', $examType)
            ->with(self::SEARCH, $search)
            ->with(self::FILTER_TYPE, $type)
			->with(self::STATUS, $status);
    }
	
	public function show($id)
    {
        $exam = Examination::where('id', $id)
                            ->with('user')
                            ->with(self::COMPANY)
                            ->with(self::EXAMINATIN_TYPE)
                            ->with('examinationLab')
                            ->with(self::DEVICE)
                            ->with(self::MEDIA)
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
                            ->with(self::COMPANY)
                            ->with(self::EXAMINATIN_TYPE)
                            ->with('examinationLab')
                            ->with(self::DEVICE)
                            ->with(self::MEDIA)
                            ->first();

        $labs = ExaminationLab::all();
		$gen_spk_code = $this->generateSPKCOde($exam->examinationLab->lab_code,$exam->examinationType->name,date('Y'));
		
		$currentUser = Auth::user();
		$adminRole = AdminRole::find($currentUser->id);
		
		if ($adminRole->registration_status){
			$exam->where(self::REGISTRATION_STATUS, 0)
				->orWhere(self::REGISTRATION_STATUS, -1);
		}

		if ($adminRole->function_status){
			$exam
				->orWhere(function($q){
					$q
						->where(self::REGISTRATION_STATUS, 1)
						->where(function($r){
							$r
								->where(self::FUNCTION_STATUS, 0)
								->orWhere(self::FUNCTION_STATUS, -1);
						})
					;
				})
			;
		}

		if ($adminRole->contract_status){
			$exam
				->orWhere(function($q){
					$q
						->where(self::FUNCTION_STATUS, 1)
						->where(function($r){
							$r
								->where(self::CONTRACT_STATUS, 0)
								->orWhere(self::CONTRACT_STATUS, -1);
						})
					;
				})
			;
		}

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
        if ($request->has(self::SPK_CODE)){
            $exam->spk_code = $request->input(self::SPK_CODE);
			if($this->checkSPKCode($request->input(self::SPK_CODE)) > 0){
				Session::flash(self::ERROR, 'SPK Number must be unique, please Re-Generate');
                return redirect(self::MY_EXAM_LOC.$exam->id.self::EDIT_LOC);
			}
        }
        if ($request->has(self::REGISTRATION_STATUS)){
			$status = $request->input(self::REGISTRATION_STATUS);
			$exam->registration_status = $status;
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.registrasi", "Acc Registrasi");
			}else if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Registrasi",$request->input(self::KETERANGAN));
			}
        }
		if ($request->has(self::FUNCTION_STATUS)){
			$status = $request->input(self::FUNCTION_STATUS);
			$exam->function_status = $status;
			if($status == 1){
				// $this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.function", "Acc Uji Fungsi")
			}else if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Uji Fungsi",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::CONTRACT_STATUS)){
			if ($request->hasFile(self::CONTRACT_FILE)) {
				$ext_file = $request->file(self::CONTRACT_FILE)->getClientOriginalExtension();
				$name_file = uniqid().'_contract_'.$exam->id.'.'.$ext_file;
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::CONTRACT_FILE)->move($path_file,$name_file)){

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = self::TINJAUAN_KONTRAK;
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}
				}else{
					Session::flash(self::ERROR, 'Save contract review to directory failed');
					return redirect(self::MY_EXAM_LOC.$exam->id.self::EDIT_LOC);
				}
			}
			$status = $request->input(self::CONTRACT_STATUS);
            $exam->contract_status = $status;
			if($status == 1){
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$id;
				$attach = ExaminationAttach::where('name', self::TINJAUAN_KONTRAK)->where(self::EXAMINATION_ID, ''.$id.'')->first();
					$attach_name = $attach->attachment;
			}else if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Tinjauan Pustaka",$request->input(self::KETERANGAN));
			}
        }
		if ($request->has(self::SPB_STATUS)){
			if ($request->hasFile(self::SPB_FILE)) {
				$ext_file = $request->file(self::SPB_FILE)->getClientOriginalExtension();
				$name_file = uniqid().'_spb_'.$exam->id.'.'.$ext_file;
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::SPB_FILE)->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', 'SPB')->where(self::EXAMINATION_ID, ''.$exam->id.'')->first();

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
					Session::flash(self::ERROR, 'Save spb to directory failed');
					return redirect(self::MY_EXAM_LOC.$exam->id.self::EDIT_LOC);
				}
			}
			$status = $request->input(self::SPB_STATUS);
            $exam->spb_status = $status;
			if($status == 1){
				$exam->price = $request->input(self::EXAM_PRICE);
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$id;
				$attach = ExaminationAttach::where('name', 'SPB')->where(self::EXAMINATION_ID, ''.$id.'')->first();
					$attach_name = $attach->attachment;
				$this->sendEmailNotification_wAttach($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.spb", "Upload SPB",$path_file."/".$attach_name);
			}else if($status == -1){
				$exam->price = $request->input(self::EXAM_PRICE);
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"SPB",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::PAYMENT_STATUS)){
            $status = $request->input(self::PAYMENT_STATUS);
            $exam->payment_status = $status;
			if($status == 1){
				if($this->cekRefID($exam->id) == 0){
					$income = new Income;
					$income->id = Uuid::uuid4();
					$income->company_id = $exam->company_id;
					$income->inc_type = 1; 
					$income->reference_id = $exam->id; 
					$income->reference_number = $request->input(self::SPB_NUMBER);
					$income->tgl = $request->input(self::SPB_DATE);
					$income->created_by = $currentUser->id;

				}else{
					$income = Income::where(self::REFERENCE_ID, $exam->id)->first();
				}
					$income->price = $request->input(self::EXAM_PRICE);
					$income->updated_by = $currentUser->id;
					$income->save();
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.pembayaran", "ACC Pembayaran");
			}else if($status == -1){
				Income::where(self::REFERENCE_ID, '=' ,''.$exam->id.'')->delete();
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Pembayaran",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::SPK_STATUS)){
            $status = $request->input(self::SPK_STATUS);
            $exam->spk_status = $status;
			if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Pembuatan SPK",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::EXAMINATION_STATUS)){
            $status = $request->input(self::EXAMINATION_STATUS);
            $exam->examination_status = $status;
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.uji", "Pelaksanaan Uji");
			}else if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Pelaksanaan Uji",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::RESUME_STATUS)){
			if ($request->hasFile(self::LAP_UJI_FILE)) {
				$ext_file = $request->file(self::LAP_UJI_FILE)->getClientOriginalExtension();
				$name_file = uniqid().'_resume_'.$exam->id.'.'.$ext_file;
				$path_file = public_path().self::MEDIA_EXAMINATION_LOC.$exam->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::LAP_UJI_FILE)->move($path_file,$name_file)){
					$attach = ExaminationAttach::where('name', self::LAP_UJI)->where(self::EXAMINATION_ID, ''.$exam->id.'')->first();

					if ($attach){
						$attach->attachment = $name_file;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					} else{
						$attach = new ExaminationAttach;
						$attach->id = Uuid::uuid4();
						$attach->examination_id = $exam->id; 
						$attach->name = self::LAP_UJI;
						$attach->attachment = $name_file;
						$attach->created_by = $currentUser->id;
						$attach->updated_by = $currentUser->id;

						$attach->save();
					}
				}else{
					Session::flash(self::ERROR, 'Save resume to directory failed');
					return redirect(self::MY_EXAM_LOC.$exam->id.self::EDIT_LOC);
				}
			}
            $status = $request->input(self::RESUME_STATUS);
            $exam->resume_status = $status;
			if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,self::LAP_UJI,$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::QA_STATUS)){
            $status = $request->input(self::QA_STATUS);
            $passed = $request->input('passed');
            $exam->qa_status = $status;
            $exam->qa_passed = $passed;
			if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Sidang QA",$request->input(self::KETERANGAN));
			}
        }
        if ($request->has(self::CERTIFICATE_STATUS)){
            $status = $request->input(self::CERTIFICATE_STATUS);
            $exam->certificate_status = $status;
			if($status == 1){
				$this->sendEmailNotification($exam->created_by,$device->name,$exam_type->name,$exam_type->description, "emails.sertifikat", "Penerbitan Sertfikat");
			}else if($status == -1){
				$exam->keterangan = $request->input(self::KETERANGAN);
				$this->sendEmailFailure($exam->created_by,$device->name,$exam_type->name,$exam_type->description, self::EMAIL_FAILS, self::KONFORMASI_PEMBATALAN_PENGUJIAN,"Pembuatan Sertifikat",$request->input(self::KETERANGAN));
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
		if ($request->has(self::SPK_DATE)){
            $exam->spk_date = $request->input(self::SPK_DATE);
        }
		if ($request->has('function_date')){
            $exam->function_date = $request->input('function_date');
        }
		if ($request->has('catatan')){
            $exam->catatan = $request->input('catatan');
        }
		if ($request->has(self::CONTRACT_DATE)){
            $exam->contract_date = $request->input(self::CONTRACT_DATE);
        }
		if ($request->has(self::TESTING_START)){
            $exam->testing_start = $request->input(self::TESTING_START);
        }
		if ($request->has(self::TESTING_END)){
            $exam->testing_end = $request->input(self::TESTING_END);
        }
		if ($request->has('urel_test_date')){
            $exam->urel_test_date = $request->input('urel_test_date');
        }
		if ($request->has('deal_test_date')){
            $exam->deal_test_date = $request->input('deal_test_date');
        }
		if ($request->has(self::SPB_NUMBER)){
            $exam->spb_number = $request->input(self::SPB_NUMBER);
        }
		if ($request->has(self::SPB_DATE)){
            $exam->spb_date = $request->input(self::SPB_DATE);
        }

        if ($request->hasFile(self::CERTIFICATE_FILE)) {
            $ext_file = $request->file(self::CERTIFICATE_FILE)->getClientOriginalExtension();
            $name_file = uniqid().'_certificate_'.$exam->device_id.'.'.$ext_file;
            $path_file = public_path().'/media/device/'.$exam->device_id;
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::CERTIFICATE_FILE)->move($path_file,$name_file)){
                $device = Device::findOrFail($exam->device_id);
                if ($device){
                    $device->certificate = $name_file;
                    $device->status = $request->input(self::CERTIFICATE_STATUS);
                    $device->valid_from = $request->input('valid_from');
                    $device->valid_thru = $request->input('valid_thru');

                    $device->save();
                }
            }else{
                Session::flash(self::ERROR, 'Save spb to directory failed');
                return redirect(self::MY_EXAM_LOC.$exam->id.self::EDIT_LOC);
            }
        }
		
			$device = Device::findOrFail($exam->device_id);
			$exam_type = ExaminationType::findOrFail($exam->examination_type_id);
		
        $exam->updated_by = $currentUser->id;

        try{
            $exam->save();
             
				$exam_hist = new ExaminationHistory;
				$exam_hist->examination_id = $exam->id;
				$exam_hist->date_action = date(self::DATE_FORMAT);
				$exam_hist->tahap = $request->input(self::STATUS);
					if ($request->has(self::REGISTRATION_STATUS)){$exam_hist->status = $request->input(self::REGISTRATION_STATUS);}
					if ($request->has(self::FUNCTION_STATUS)){$exam_hist->status = $request->input(self::FUNCTION_STATUS);}
					if ($request->has(self::CONTRACT_STATUS)){$exam_hist->status = $request->input(self::CONTRACT_STATUS);}
					if ($request->has(self::SPB_STATUS)){$exam_hist->status = $request->input(self::SPB_STATUS);}
					if ($request->has(self::PAYMENT_STATUS)){$exam_hist->status = $request->input(self::PAYMENT_STATUS);}
					if ($request->has(self::SPK_STATUS)){$exam_hist->status = $request->input(self::SPK_STATUS);}
					if ($request->has(self::EXAMINATION_STATUS)){$exam_hist->status = $request->input(self::EXAMINATION_STATUS);}
					if ($request->has(self::RESUME_STATUS)){$exam_hist->status = $request->input(self::RESUME_STATUS);}
					if ($request->has(self::QA_STATUS)){$exam_hist->status = $request->input(self::QA_STATUS);}
					if ($request->has(self::CERTIFICATE_STATUS)){$exam_hist->status = $request->input(self::CERTIFICATE_STATUS);}
				$exam_hist->keterangan = $request->input(self::KETERANGAN);
				$exam_hist->created_by = $currentUser->id;
				$exam_hist->created_at = date(self::DATE_FORMAT);
				$exam_hist->save();

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Update ".$request->input(self::STATUS);   
                $logs->data = $exam;
                $logs->created_by = $currentUser->id;
                $logs->page = "MY EXAMINATION";
                $logs->save();

            Session::flash(self::MESSAGE, 'Examination successfully updated');
            return redirect('/admin/myexam');
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::MY_EXAM_LOC.$exam->id.self::EDIT_LOC);
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
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc
			), function ($m) use ($data,$subject) {
            $m->to($data->email)->subject($subject);
        });

        return true;
    }
	
	public function sendEmailNotification_wAttach($user, $dev_name, $exam_type, $exam_type_desc, $message, $subject, $attach)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc
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
			self::USER_NAME => $data->name,
			self::DEV_NAME => $dev_name,
			self::EXAM_TYPE => $exam_type,
			self::EXAM_TYPE_DESC => $exam_type_desc,
			'tahap' => $tahap,
			self::KETERANGAN => $keterangan
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
		$testing_start = $request->input(self::TESTING_START);
			$testing_start_ina_temp = strtotime($testing_start);
			$testing_start_ina = date(self::DATE_FORMAT2, $testing_start_ina_temp);
		$testing_end = $request->input(self::TESTING_END);
			$testing_end_ina_temp = strtotime($testing_end);
			$testing_end_ina = date(self::DATE_FORMAT2, $testing_end_ina_temp);
		$contract_date = $request->input(self::CONTRACT_DATE);
			$contract_date_ina_temp = strtotime($contract_date);
			$contract_date_ina = date(self::DATE_FORMAT2, $contract_date_ina_temp);
		
		$exam = Examination::where('id', $exam_id)
				->with('user')
				->with(self::DEVICE)
				->first();
			
			try{
				$query_update = "UPDATE examinations
					SET 
						contract_date = '".$contract_date."',
						testing_start = '".$testing_start."',
						testing_end = '".$testing_end."',
						updated_by = '".$currentUser['attributes']['id']."',
						updated_at = '".date(self::DATE_FORMAT)."'
					WHERE id = '".$exam_id."'
				";
				DB::update($query_update);
				
				$data = Array([
					'nama_pemohon' => $exam->user->name,
					'alamat_pemohon' => $exam->user->address,
					'nama_perangkat' => $exam->device->name,
					'merek_perangkat' => $exam->device->mark,
					'model_perangkat' => $exam->device->model,
					'kapasitas_perangkat' => $exam->device->capacity,
					'referensi_perangkat' => $exam->device->test_reference,
					'pembuat_perangkat' => $exam->device->manufactured_by,
					self::TESTING_START => $testing_start_ina,
					self::TESTING_END => $testing_end_ina,
					self::CONTRACT_DATE => $contract_date_ina
				]);
				
				$request->session()->put('key_contract', $data);
				
				Session::flash(self::MESSAGE, 'Contract successfully created');
				echo 1;
			} catch(Exception $e){
				Session::flash(self::ERROR, 'Contract failed');
				echo 0;
			}
    }
	
	public function checkSPKCode($a) {
        $query_exam = "SELECT * FROM examinations WHERE spk_code = '".$a."'";
		$data_exam = DB::select($query_exam);
		return count($data_exam);
    }
	
	public function generateSPKCodeManual(Request $request) {
		return $this->generateSPKCOde($request->input('lab_code'),$request->input(self::EXAM_TYPE),$request->input('year'));
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
		if (!count($data)){
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
		$request->session()->put('key_my_exam_id_for_generate_spb', $request->input(self::EXAM_ID));
		$request->session()->put('key_my_spb_number_for_generate_spb', $request->input(self::SPB_NUMBER));
		$request->session()->put('key_my_spb_date_for_generate_spb', $request->input(self::SPB_DATE));
		echo 1;
    }
	
	public function generateSPB(Request $request) {
		$exam_id = $request->session()->pull('key_my_exam_id_for_generate_spb');
		$spb_number = $request->session()->pull('key_my_spb_number_for_generate_spb');
		$spb_date = $request->session()->pull('key_my_spb_date_for_generate_spb');
		$exam = Examination::where('id', $exam_id)
					->with(self::DEVICE)
					->first()
		;
		$query_price = "SELECT price FROM stels WHERE code = '".$exam->device->test_reference."'";
		$price = DB::select($query_price);
		if(!count($price)){
			$price = 0;
		}else{
			$price = $price[0]->price;
		}
		
		$query_stels = "SELECT * FROM stels WHERE is_active = 1";
		$data_stels = DB::select($query_stels);
			
		return view('admin.myexam.spb')
					->with(self::EXAM_ID, $exam_id)
					->with(self::SPB_NUMBER, $spb_number)
					->with(self::SPB_DATE, $spb_date)
					->with('data', $exam)
					->with('price', $price)
					->with('data_stels', $data_stels)
		;
    }
	
	public function generateSPBData(Request $request) {
		if($this->cekSPBNumber($request->input(self::SPB_NUMBER)) == 0){
			$exam_id = $request->input(self::EXAM_ID);
			$spb_number = $request->input(self::SPB_NUMBER);
			$spb_date = $request->input(self::SPB_DATE);
			$arr_nama_perangkat = $request->input('arr_nama_perangkat');
			$arr_biaya = $request->input('arr_biaya');
			$exam = Examination::where('id', $exam_id)
						->with('user')
						->with(self::COMPANY)
						->with(self::EXAMINATIN_TYPE)
						->first()
			;
			$data = []; 
			$data[] = [
				self::SPB_NUMBER => $spb_number,
				self::SPB_DATE => $spb_date,
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
		$exam = Examination::where(self::SPB_NUMBER,'=',''.$spb_number.'')->get();
		return count($exam);
    }
	
	function cekRefID($exam_id)
    {
		$income = Income::where(self::REFERENCE_ID,'=',''.$exam_id.'')->get();
		return count($income);
    }
}