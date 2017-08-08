<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\ExaminationHistory;
use App\User;
use App\Logs;
use App\Income;

use Auth;
use File;
use Mail;
use Session;
use Response;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationDoneController extends Controller
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
            $type = '';
            $before = null;
            $after = null;

            $examType = ExaminationType::all();

            $query = Examination::whereNotNull('created_at')
                                ->with('user')
                                ->with('company')
                                ->with('examinationType')
                                ->with('examinationLab')
                                ->with('media')
                                ->with('device')
								;
			$query->where(function($qry){
				$qry->where(function($q){
					return $q->where('examination_type_id', '=', '1')
						->where('registration_status', '=', '1')
						->where('function_status', '=', '1')
						->where('contract_status', '=', '1')
						->where('spb_status', '=', '1')
						->where('payment_status', '=', '1')
						->where('spk_status', '=', '1')
						->where('examination_status', '=', '1')
						->where('resume_status', '=', '1')
						->where('qa_status', '=', '1')
						->where('certificate_status', '=', '1')
						;
					})
				->orWhere(function($q){
					return $q->where('examination_type_id', '!=', '1')
						->where('registration_status', '=', '1')
						->where('function_status', '=', '1')
						->where('contract_status', '=', '1')
						->where('spb_status', '=', '1')
						->where('payment_status', '=', '1')
						->where('spk_status', '=', '1')
						->where('examination_status', '=', '1')
						->where('resume_status', '=', '1')
						;
					});
			});
			$query->where(function($qry) use($search){
				$qry->whereHas('device', function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					})
				->orWhereHas('company', function ($q) use ($search){
						return $q->where('name', 'like', '%'.strtolower($search).'%');
					});
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
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "EXAMINATION DONE";
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
						
			$request->session()->put('excel_pengujian_lulus', $data_excel);

            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.examinationdone.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('type', $examType)
                ->with('search', $search)
                ->with('filterType', $type)
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

        return view('admin.examinationdone.show')
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
							->with('examinationHistory')
							->with('questioner')
                            ->first();

        $labs = ExaminationLab::all();
		
        return view('admin.examinationdone.edit')
            ->with('data', $exam)
            ->with('labs', $labs);
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
			'Total Biaya'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
		foreach ($data as $row) {
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
				$row->price
			];
		}
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->id = Uuid::uuid4();
        $logs->user_id = $currentUser->id;
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "EXAMINATION DONE";
        $logs->save();

		// Generate and return the spreadsheet
		Excel::create('Data Pengujian Lulus', function($excel) use ($examsArray) {

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
	
	public function autocomplete($query) {
        $respons_result = Examination::adm_exam_done_autocomplet($query);
        return response($respons_result);
    }
	
	function cetakKepuasanKonsumen($id, Request $request)
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
		->with('User')
		->with('ExaminationType')
		->with('ExaminationLab')
		->with('Company')
		->with('Device')
		->with('Questioner')
		->get();
		
		$request->session()->put('key_exam_for_questioner', $data);
		return \Redirect::route('cetakKuisioner');
    }
	
	function cetakComplaint($id, Request $request)
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
		->with('User')
		->with('ExaminationType')
		->with('ExaminationLab')
		->with('Company')
		->with('Device')
		->with('Questioner')
		->get();
		
		$request->session()->put('key_exam_for_complaint', $data);
		return \Redirect::route('cetakComplaints');
    }
}