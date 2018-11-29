<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\STELSales;
use App\Income;
use App\Kuitansi;
use App\Company;
use App\Logs;
use App\Examination;
use App\GeneralSetting;
use App\ExaminationType;
use App\ExaminationLab;

use Auth;
use Session;
use File;
use Response;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Events\Notification;
use App\NotificationTable;

class IncomeController extends Controller
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
            $paginate = 10;
            $search = trim($request->input('search'));
            $type = '';
            $lab = '';
            $status = '';
			$before = null;
            $after = null;

            $examType = ExaminationType::all();
            $examLab = ExaminationLab::all();

            $query = Income::selectRaw("incomes.*, examinations.examination_type_id, examinations.examination_lab_id,
			            	examinations.registration_status,
			            	examinations.function_status,
							examinations.contract_status,
							examinations.spb_status,
							examinations.payment_status,
							examinations.spk_status,
							examinations.examination_status,
							examinations.resume_status,
							examinations.qa_status,
							examinations.certificate_status
            				")
            				->join("examinations","examinations.id","=","incomes.reference_id")
        					->whereNotNull('incomes.created_at')
							->where('incomes.inc_type', 1)
                            ->with('company')
                            ->with('examination');
			
			if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->whereHas('company', function ($q) use ($search){
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
                $logs->page = "INCOME";
                $logs->save();
            }

            if ($request->has('type')){
                $type = $request->get('type');
                if($request->input('type') != 'all'){
					$query->where('examination_type_id', $request->get('type'));
				}
            }

            if ($request->has('status')){
                switch ($request->get('status')) {
                    case 1:
						$query->where('examinations.registration_status', '!=', 1);
                        $status = 1;
                        break;
                    case 2:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '!=', 1);
                        $status = 2;
                        break;
                    case 3:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '!=', 1);
                        $status = 3;
                        break;
                    case 4:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '!=', 1);
                        $status = 4;
                        break;
                    case 5:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '=', 1);
						$query->where('examinations.payment_status', '!=', 1);
                        $status = 5;
                        break;
                    case 6:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '=', 1);
						$query->where('examinations.payment_status', '=', 1);
						$query->where('examinations.spk_status', '!=', 1);
                        $status = 6;
                        break;
                    case 7:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '=', 1);
						$query->where('examinations.payment_status', '=', 1);
						$query->where('examinations.spk_status', '=', 1);
						$query->where('examinations.examination_status', '!=', 1);
                        $status = 7;
                        break;
                    case 8:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '=', 1);
						$query->where('examinations.payment_status', '=', 1);
						$query->where('examinations.spk_status', '=', 1);
						$query->where('examinations.examination_status', '=', 1);
						$query->where('examinations.resume_status', '!=', 1);
                        $status = 8;
                        break;
                    case 9:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '=', 1);
						$query->where('examinations.payment_status', '=', 1);
						$query->where('examinations.spk_status', '=', 1);
						$query->where('examinations.examination_status', '=', 1);
						$query->where('examinations.resume_status', '=', 1);
						$query->where('examinations.qa_status', '!=', 1);
                        $status = 9;
                        break;
                    case 10:
						$query->where('examinations.registration_status', '=', 1);
						$query->where('examinations.function_status', '=', 1);
						$query->where('examinations.contract_status', '=', 1);
						$query->where('examinations.spb_status', '=', 1);
						$query->where('examinations.payment_status', '=', 1);
						$query->where('examinations.spk_status', '=', 1);
						$query->where('examinations.examination_status', '=', 1);
						$query->where('examinations.resume_status', '=', 1);
						$query->where('examinations.qa_status', '=', 1);
						$query->where('examinations.certificate_status', '!=', 1);
                        $status = 10;
                        break;
                    
                    default:
						$status = 'all';
                        break;
                }
            }

            if ($request->has('lab')){
                $lab = $request->get('lab');
                if($request->input('lab') != 'all'){
					$query->where('examination_lab_id', $request->get('lab'));
				}
            }

			if ($request->has('before_date')){
				$query->where('tgl', '<=', $request->get('before_date'));
				$before = $request->get('before_date');
			}

			if ($request->has('after_date')){
				$query->where('tgl', '>=', $request->get('after_date'));
				$after = $request->get('after_date');
			}

			$data = $query->orderBy('tgl', 'desc')
                        ->paginate($paginate);
			
            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.income.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('type', $examType)
                ->with('status', $status)
                ->with('filterType', $type)
                ->with('lab', $examLab)
                ->with('filterLab', $lab)
				->with('before_date', $before)
                ->with('after_date', $after);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$number = $request->session()->get('key_kode_for_kuitansi');
		$from = $request->session()->get('key_from_for_kuitansi');
		$price = $request->session()->get('key_price_for_kuitansi');
		$for = $request->session()->get('key_for_for_kuitansi');
		return view('admin.income.create_kuitansi')
			->with('number', $number)
			->with('from', $from)
			->with('price', $price)
			->with('for', $for)
		;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if($this->cekKuitansi($request->input('number')) == 0){
			$currentUser = Auth::user();

			$kuitansi = new Kuitansi;
			$kuitansi->id = Uuid::uuid4();
			$kuitansi->number = $request->input('number');
			$kuitansi->from = $request->input('from');
			$kuitansi->price = $request->input('price');
			$kuitansi->for = $request->input('for');
			$kuitansi->kuitansi_date = $request->input('kuitansi_date');
			$kuitansi->created_by = $currentUser->id;
			$kuitansi->updated_by = $currentUser->id;

			try{
				$kuitansi->save();
				Session::flash('message', 'Kuitansi successfully created');
				Session::flash('id', $kuitansi->id);
				return redirect('/admin/kuitansi');
			} catch(\Exception $e){
				Session::flash('error', 'Save failed');
				return redirect('/admin/kuitansi/create')->withInput($request->all());
			}
		}else{
			Session::flash('error', 'Existing Number');
			return redirect('/admin/kuitansi/create')->withInput($request->all());
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
        
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
        
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
        
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
        
    // }
	
	public function kuitansi(Request $request){
		$currentUser = Auth::user();

        if ($currentUser){
            $id = null;
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));

            $before = null;
            $after = null;
            $type = '';

            $sort_by = 'kuitansi_date';
            $sort_type = 'desc';

            $query = Kuitansi::whereNotNull('created_at');
			
			if ($search != null){
                $query->where("number", "like", '%'.strtolower($search).'%')
					->orWhere("from", "like", '%'.strtolower($search).'%')
					->orWhere("for", "like", '%'.strtolower($search).'%')
				;

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "search";  
                $dataSearch = array('search' => $search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "KUITANSI";
                $logs->save();
            }

            if ($request->has('before_date')){
                $query->where('kuitansi_date', '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $query->where('kuitansi_date', '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            if ($request->has('type')){
                $type = $request->get('type');
                if($request->input('type') != 'all'){
                	$request->input('type') == 'spb' ? $query->where("for", "like", '%pengujian%') : $query->where("for", "not like", '%pengujian%');
                }
            }

            if ($request->has('sort_by')){
                $sort_by = $request->get('sort_by');
            }
            if ($request->has('sort_type')){
                $sort_type = $request->get('sort_type');
            }

            $data = $query->orderBy($sort_by, $sort_type)
                        ->paginate($paginate);
			
            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.income.kuitansi')
                ->with('message', $message)
                ->with('id', $id)
                ->with('search', $search)
                ->with('before_date', $before)
                ->with('after_date', $after)
                ->with('filterType', $type)
                ->with('sort_by', $sort_by)
                ->with('sort_type', $sort_type)
                ->with('data', $data);
        }
	}

	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.

        $search = trim($request->input('search'));
        $type = '';
        $lab = '';
        $status = '';
        $before = null;
        $after = null;

        $query = Income::selectRaw("incomes.*, examinations.examination_type_id, examinations.examination_lab_id,
                        examinations.registration_status,
                        examinations.function_status,
                        examinations.contract_status,
                        examinations.spb_status,
                        examinations.payment_status,
                        examinations.spk_status,
                        examinations.examination_status,
                        examinations.resume_status,
                        examinations.qa_status,
                        examinations.certificate_status
                        ")
                        ->join("examinations","examinations.id","=","incomes.reference_id")
                        ->whereNotNull('incomes.created_at')
                        ->where('incomes.inc_type', 1)
                        ->with('company')
                        ->with('examination');
        
        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas('company', function ($q) use ($search){
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
            $logs->page = "INCOME";
            $logs->save();
        }

        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where('examination_type_id', $request->get('type'));
            }
        }

        if ($request->has('status')){
            switch ($request->get('status')) {
                case 1:
                    $query->where('examinations.registration_status', '!=', 1);
                    $status = 1;
                    break;
                case 2:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '!=', 1);
                    $status = 2;
                    break;
                case 3:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '!=', 1);
                    $status = 3;
                    break;
                case 4:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '!=', 1);
                    $status = 4;
                    break;
                case 5:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '=', 1);
                    $query->where('examinations.payment_status', '!=', 1);
                    $status = 5;
                    break;
                case 6:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '=', 1);
                    $query->where('examinations.payment_status', '=', 1);
                    $query->where('examinations.spk_status', '!=', 1);
                    $status = 6;
                    break;
                case 7:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '=', 1);
                    $query->where('examinations.payment_status', '=', 1);
                    $query->where('examinations.spk_status', '=', 1);
                    $query->where('examinations.examination_status', '!=', 1);
                    $status = 7;
                    break;
                case 8:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '=', 1);
                    $query->where('examinations.payment_status', '=', 1);
                    $query->where('examinations.spk_status', '=', 1);
                    $query->where('examinations.examination_status', '=', 1);
                    $query->where('examinations.resume_status', '!=', 1);
                    $status = 8;
                    break;
                case 9:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '=', 1);
                    $query->where('examinations.payment_status', '=', 1);
                    $query->where('examinations.spk_status', '=', 1);
                    $query->where('examinations.examination_status', '=', 1);
                    $query->where('examinations.resume_status', '=', 1);
                    $query->where('examinations.qa_status', '!=', 1);
                    $status = 9;
                    break;
                case 10:
                    $query->where('examinations.registration_status', '=', 1);
                    $query->where('examinations.function_status', '=', 1);
                    $query->where('examinations.contract_status', '=', 1);
                    $query->where('examinations.spb_status', '=', 1);
                    $query->where('examinations.payment_status', '=', 1);
                    $query->where('examinations.spk_status', '=', 1);
                    $query->where('examinations.examination_status', '=', 1);
                    $query->where('examinations.resume_status', '=', 1);
                    $query->where('examinations.qa_status', '=', 1);
                    $query->where('examinations.certificate_status', '!=', 1);
                    $status = 10;
                    break;
                
                default:
                    $status = 'all';
                    break;
            }
        }

        if ($request->has('lab')){
            $lab = $request->get('lab');
            if($request->input('lab') != 'all'){
                $query->where('examination_lab_id', $request->get('lab'));
            }
        }

        if ($request->has('before_date')){
            $query->where('tgl', '<=', $request->get('before_date'));
            $before = $request->get('before_date');
        }

        if ($request->has('after_date')){
            $query->where('tgl', '>=', $request->get('after_date'));
            $after = $request->get('after_date');
        }

        $data = $query->orderBy('tgl', 'desc')
                    ->get();
		
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'No',
			'Sumber Pendapatan',
			'Nama Perusahaan',
			'Tanggal',
			'No. Referensi',
            'Nilai',
			'Nomor SPK'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
			$no = 1;
		foreach ($data as $row) {
			if($row->inc_type == 1){
				$sumber_pendapatan = "Pengujian Perangkat";
			}
			else{
				$sumber_pendapatan = "Pembelian STEL";
			}
			$examsArray[] = [
				$no,
				$sumber_pendapatan.' '.$row->examination->device->name,
				$row->examination->user->name.' ('.$row->company->name.')',
				$row->tgl,
				"'".$row->reference_number,
                $row->price,
				$row->examination->spk_code
			];
			$no++;
		}
		// Generate and return the spreadsheet
		Excel::create('Data Pendapatan', function($excel) use ($examsArray) {

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
        $respons_result = Income::autocomplet($query);
        return response($respons_result);
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
	
	public function cetakKuitansi($id, Request $request) {
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
		
		$data = Kuitansi::find($id);
		    
		if (count($data) == 0){
			$message = 'Data not found';
		}
		if( strpos( $data->number, "/" ) !== false ) {$number = urlencode(urlencode($data->number));}else{$number = $data->number?: '-';}
		if( strpos( $data->from, "/" ) !== false ) {$from = urlencode(urlencode($data->from));}else{$from = $data->from?: '-';}
		if( strpos( $data->price, "/" ) !== false ) {$price = urlencode(urlencode($data->price));}else{$price = $data->price?: '0';}
		if( strpos( $data->for, "/" ) !== false ) {$for = urlencode(urlencode($data->for));}else{$for = $data->for?: '-';}
		if( strpos( $data->kuitansi_date, "/" ) !== false ) {$kuitansi_date = urlencode(urlencode($data->kuitansi_date));}else{$kuitansi_date = $data->kuitansi_date?: '-';}
		return \Redirect::route('cetakHasilKuitansi', [
			'nomor' => $number,
			'dari' => $from,
			'jumlah' => $price,
			'untuk' => $for,
			'manager_urel' => $manager_urels,
			'tanggal' => $kuitansi_date,
			'is_poh' => $is_poh
		]);
    }
	
	function cekKuitansi($number)
    {
		$inc = Kuitansi::where('number','=',''.$number.'')->get();
		return count($inc);
    }
}