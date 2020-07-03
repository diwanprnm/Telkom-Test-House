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
use App\Services\Logs\LogService;

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
    private const SEARCH = 'search';
    private const QUERY = 'query';
    private const COMPANY = 'company';
    private const BEFORE_DATE = 'before_date';
    private const AFTER_DATE = 'after_date';
    private const MESSAGE = 'message';
    private const STATUS = 'status';
    private const NUMBER = 'number';
    private const KUITANSI_DATE = 'kuitansi_date';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';
    private const MANAGER_UREL = 'manager_urel';


    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser){
            return false;
        }
        $message = null;
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));
        $type = '';
        $lab = '';
        $status = '';
        $before = null;
        $after = null;

        $examType = ExaminationType::all();
        $examLab = ExaminationLab::all();

        $initialQuery = $this->initialQuery($search, $currentUser);
        $search = $initialQuery[self::SEARCH];
        $query = $initialQuery[self::QUERY];


        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where('examination_type_id', $request->get('type'));
            }
        }

        $filterStatus = $this->filterStatus($query, $request);
        $query = $filterStatus->query;
        $status = $filterStatus->status;

        if ($request->has('lab')){
            $lab = $request->get('lab');
            if($request->input('lab') != 'all'){
                $query->where('examination_lab_id', $request->get('lab'));
            }
        }

        if ($request->has(self::BEFORE_DATE)){
            $query->where('tgl', '<=', $request->get(self::BEFORE_DATE));
            $before = $request->get(self::BEFORE_DATE);
        }

        if ($request->has(self::AFTER_DATE)){
            $query->where('tgl', '>=', $request->get(self::AFTER_DATE));
            $after = $request->get(self::AFTER_DATE);
        }

        $data = $query->orderBy('tgl', 'desc')
                    ->paginate($paginate);
        
        if (count($query) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.income.index')
            ->with(self::MESSAGE, $message)
            ->with('data', $data)
            ->with(self::SEARCH, $search)
            ->with('type', $examType)
            ->with(self::STATUS, $status)
            ->with('filterType', $type)
            ->with('lab', $examLab)
            ->with('filterLab', $lab)
            ->with(self::BEFORE_DATE, $before)
            ->with(self::AFTER_DATE, $after);
        
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
			->with(self::NUMBER, $number)
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
		if($this->cekKuitansi($request->input(self::NUMBER)) == 0){
			$currentUser = Auth::user();

			$kuitansi = new Kuitansi;
			$kuitansi->id = Uuid::uuid4();
			$kuitansi->number = $request->input(self::NUMBER);
			$kuitansi->from = $request->input('from');
			$kuitansi->price = $request->input('price');
			$kuitansi->for = $request->input('for');
			$kuitansi->kuitansi_date = $request->input(self::KUITANSI_DATE);
			$kuitansi->created_by = $currentUser->id;
			$kuitansi->updated_by = $currentUser->id;

			try{
				$kuitansi->save();
				Session::flash(self::MESSAGE, 'Kuitansi successfully created');
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
	
	public function kuitansi(Request $request){
        $currentUser = Auth::user();
        $logService = new LogService();
        
        if (!$currentUser){
            return false;
        }

        $id = null;
        $message = null;
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));

        $before = null;
        $after = null;
        $type = '';

        $sort_by = self::KUITANSI_DATE;
        $sort_type = 'desc';

        $query = Kuitansi::whereNotNull('created_at');
        
        if ($search != null){
            $query->where("number", "like", '%'.strtolower($search).'%')
                ->orWhere("from", "like", '%'.strtolower($search).'%')
                ->orWhere("for", "like", '%'.strtolower($search).'%')
            ;

            $logService->createLog(self::SEARCH, 'KUITANSI', json_encode(array(self::SEARCH => $search)) );
        }

        if ($request->has(self::BEFORE_DATE)){
            $query->where(self::KUITANSI_DATE, '<=', $request->get(self::BEFORE_DATE));
            $before = $request->get(self::BEFORE_DATE);
        }

        if ($request->has(self::AFTER_DATE)){
            $query->where(self::KUITANSI_DATE, '>=', $request->get(self::AFTER_DATE));
            $after = $request->get(self::AFTER_DATE);
        }

        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $request->input('type') == 'spb' ? $query->where("for", "like", '%pengujian%') : $query->where("for", "not like", '%pengujian%');
            }
        }

        if ($request->has(self::SORT_BY)){
            $sort_by = $request->get(self::SORT_BY);
        }
        if ($request->has(self::SORT_TYPE)){
            $sort_type = $request->get(self::SORT_TYPE);
        }

        $data = $query->orderBy($sort_by, $sort_type)
                    ->paginate($paginate);
        
        if (count($query) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.income.kuitansi')
            ->with(self::MESSAGE, $message)
            ->with('id', $id)
            ->with(self::SEARCH, $search)
            ->with(self::BEFORE_DATE, $before)
            ->with(self::AFTER_DATE, $after)
            ->with('filterType', $type)
            ->with(self::SORT_BY, $sort_by)
            ->with(self::SORT_TYPE, $sort_type)
            ->with('data', $data);
        
	}

	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
        // timestamp.
        
        $currentUser = Auth::user();

        $search = trim($request->input(self::SEARCH));

        $initialQuery = $this->initialQuery($search, $currentUser);
        $query = $initialQuery[self::QUERY];

        if ($request->has('type') && $request->input('type') != 'all'){

            $query->where('examination_type_id', $request->get('type'));
        }

        $filterStatus = $this->filterStatus($query, $request);
        $query = $filterStatus->query;

        if ($request->has('lab') && $request->input('lab') != 'all'){
            $query->where('examination_lab_id', $request->get('lab'));
        }

        if ($request->has(self::BEFORE_DATE)){
            $query->where('tgl', '<=', $request->get(self::BEFORE_DATE));
        }

        if ($request->has(self::AFTER_DATE)){
            $query->where('tgl', '>=', $request->get(self::AFTER_DATE));
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
            $last_numb = str_pad($data[0]->last_numb,3,"0");
            return $last_numb.'/DDS-73/'.$thisYear.'';
		}
    }
	
	public function cetakKuitansi($id, Request $request) {
 
        $manager_urels = '-';
        
        $is_poh = 0;
		$general_setting_poh = GeneralSetting::where('code', 'poh_manager_urel')->first();
		if($general_setting_poh){
			if($general_setting_poh->is_active){
                $is_poh = 1;
                $manager_urels = $this->filterUrlEncode ($general_setting_poh->value);
			}else{
				$general_setting = GeneralSetting::where('code', self::MANAGER_UREL)->first();
				if($general_setting){
                    $manager_urels = $this->filterUrlEncode ($general_setting->value);
				}
			}
		}else{
			$general_setting = GeneralSetting::where('code', self::MANAGER_UREL)->first();
			if($general_setting){
                $manager_urels = $this->filterUrlEncode ($general_setting->value);
			}
		}
		
		$data = Kuitansi::find($id);

	    return \Redirect::route('cetakHasilKuitansi', [
			'nomor' => $this->filterUrlEncode($data->number),
			'dari' => $this->filterUrlEncode($data->from),
			'jumlah' => $this->filterUrlEncode($data->price, true),
			'untuk' => $this->filterUrlEncode($data->for),
			'tanggal' => $this->filterUrlEncode($data->kuitansi_date),
            'is_poh' => $is_poh,
            self::MANAGER_UREL => $manager_urels,
		]);
    }
	
	function cekKuitansi($number)
    {
		$inc = Kuitansi::where(self::NUMBER,'=',''.$number.'')->get();
		return count($inc);
    }

    private function filterStatus($query, Request $request)
    {
        $filterStatus = new \stdClass();
        $status = '';

        $bussinessStep = array(
            'examinations.registration_status',
            'examinations.function_status',
            'examinations.contract_status',
            'examinations.spb_status',
            'examinations.payment_status',
            'examinations.spk_status',
            'examinations.examination_status',
            'examinations.resume_status',
            'examinations.qa_status',
            'examinations.certificate_status',
        );
        
        if ( $request->has(self::STATUS) && isset($bussinessStep[$request->get(self::STATUS)]) ) {
            $req = $request->get(self::STATUS);
            for ($step = 1; $step <= $req; $step++) {
                if ( $step != $req ){
                    $query->where($bussinessStep[$step-1], '=', 1);
                }else{
                    $query->where($bussinessStep[$step-1], '!=', 1);
                    $status = $step;
                }
              }
        } else{
            $status = 'all';
        }

        $filterStatus->query = $query;
        $filterStatus->status = $status;
        return $filterStatus;
    }

    private function filterUrlEncode ($string, $number=false)
    {
        if(!$string && $number){
            return '0';
        }
        if(!$string && !$number){
            return '-';
        }

        if( strpos( $string, "/" ) ){
            $string = urlencode(urlencode($string));
        }

        return $string;
    }

    private function initialQuery($search, $currentUser)
    {
        $logService = new LogService();

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
            examinations.certificate_status"
            )
            ->join("examinations","examinations.id","=","incomes.reference_id")
            ->whereNotNull('incomes.created_at')
            ->where('incomes.inc_type', 1)
            ->with(self::COMPANY)
            ->with('examination');

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->whereHas(self::COMPANY, function ($q) use ($search){
                    return $q->where('name', 'like', '%'.strtolower($search).'%');
                });
            });


            $logService->createLog(self::SEARCH, 'INCOME', json_encode(array(self::SEARCH => $search)) );
        }
        return array(
            self::SEARCH=> $search,
            'currentUser' => $currentUser,
            self::QUERY => $query,
        );
    }
}