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
use App\Services\Querys\QueryFilter;

use Auth;
use Excel;
use File;
use Response;
use Session;
use Storage;


// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Events\Notification;
use App\NotificationTable;

class IncomeController extends Controller
{
    private const AFTER_DATE = 'after_date';
    private const BEFORE_DATE = 'before_date';
    private const COMPANY = 'company';
    private const FOR = 'for';
    private const FROM = 'from';
    private const KUITANSI_DATE = 'kuitansi_date';
    private const MANAGER_UREL = 'manager_urel';
    private const MESSAGE = 'message';
    private const NUMBER = 'number';
    private const PRICE = 'price';
    private const QUERY = 'query';
    private const SEARCH = 'search';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';
    private const STATUS = 'status';

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $dataNotFound='';

        //getting relation db
        $examType = ExaminationType::all();
        $examLab = ExaminationLab::all();

        //Filter query based on request
        $initialQuery = $this->initialQuery($search);
        $queryFilter = new QueryFilter($request, $initialQuery[self::QUERY]);
        $queryFilter->examination_type('type');
        $filterStatus = $this->filterStatus($queryFilter->query, $request);
        $queryFilter
            ->updateQuery($filterStatus[self::QUERY])
            ->examination_lab('lab')
            ->beforeDate('tgl')
            ->afterDate('tgl')
            ->getSortedAndOrderedData('tgl', 'desc')              
        ;

        //get data
        $data = $queryFilter
            ->getQuery()
            ->paginate($paginate)
        ;
        
        //set $dataNotFound if Data not found
        if (count($data) == 0){
            $dataNotFound = 'Data not found';
        }
        
        return view('admin.income.index')
            ->with('data',              $data)
            ->with('type',              $examType)
            ->with('lab',               $examLab)
            ->with('dataNotFound',      $dataNotFound)
            ->with(self::STATUS,        $filterStatus[self::STATUS])
            ->with(self::SEARCH,        $initialQuery[self::SEARCH])
            ->with('filterType',        $queryFilter->examination_type)
            ->with('filterLab',         $queryFilter->examination_lab)
            ->with(self::BEFORE_DATE,   $queryFilter->before)
            ->with(self::AFTER_DATE,    $queryFilter->after)
        ;
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
        $this->validate($request, [
            self::NUMBER => 'required',
            self::FROM => 'required',
            self::PRICE => 'required',
            self::FOR => 'required',
            self::KUITANSI_DATE => 'required|date'
        ]);

		if($this->cekKuitansi($request->input(self::NUMBER)) == 0){
			$currentUser = Auth::user();

			$kuitansi = new Kuitansi;
			$kuitansi->id = Uuid::uuid4();
			$kuitansi->number = $request->input(self::NUMBER);
			$kuitansi->from = $request->input(self::FROM);
			$kuitansi->price = $request->input(self::PRICE);
			$kuitansi->for = $request->input(self::FOR);
			$kuitansi->kuitansi_date = $request->input(self::KUITANSI_DATE);
			$kuitansi->created_by = $currentUser->id;
			$kuitansi->updated_by = $currentUser->id;

			try{
				$kuitansi->save();
				Session::flash(self::MESSAGE, 'Kuitansi successfully created');
				Session::flash('id', $kuitansi->id);
				return redirect('/admin/kuitansi');
			} catch(\Exception $e){
				return redirect('/admin/kuitansi/create')->withInput($request->all())->with('error', 'Save failed');
			}
		}else{
			Session::flash('error', '"Nomor Kuitansi" is already exist');
			return redirect('/admin/kuitansi/create')->withInput($request->all());
		}
    }
	
	public function kuitansi(Request $request){
        $logService = new LogService();

        $id = null;
        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $before = null;
        $after = null;
        $type = '';
        $sort_by = self::KUITANSI_DATE;
        $sort_type = 'desc';
        $dataNotFound = '';

        $queryFilter = new QueryFilter($request, Kuitansi::whereNotNull('created_at'));
        
        if ($search != null){
            $query = $queryFilter
                ->getQuery()
                ->where("number", "like", '%'.strtolower($search).'%')
                ->orWhere("from", "like", '%'.strtolower($search).'%')
                ->orWhere("for", "like", '%'.strtolower($search).'%')
            ;
            $queryFilter = $queryFilter->updateQuery($query);
            $logService->createLog(self::SEARCH, 'KUITANSI', json_encode(array(self::SEARCH => $search)) );
        }

        $queryFilter
            ->beforeDate(self::KUITANSI_DATE)
            ->afterDate(self::KUITANSI_DATE)
        ;

        $query = $queryFilter->getQuery();
        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $request->input('type') == 'spb' ? $query->where("for", "like", '%pengujian%') : $query->where("for", "not like", '%pengujian%');
            }
        }

        $queryFilter
            ->updateQuery($query)
            ->getSortedAndOrderedData($sort_by, $sort_type)
        ;

        $data = $queryFilter
            ->getQuery()
            ->paginate($paginate)
        ;
        
        if (count($data) == 0){
            $dataNotFound = 'Data not found';
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
            ->with('data', $data)
            ->with('dataNotFound', $dataNotFound);
	}

	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
        // timestamp.

        //initial var
        $search = trim(strip_tags($request->input(self::SEARCH,'')));

        //Filter query based on request
        $initialQuery = $this->initialQuery($search);
        $queryFilter = new QueryFilter($request, $initialQuery[self::QUERY]);
        $queryFilter->examination_type('type');
        $filterStatus = $this->filterStatus($queryFilter->getQuery(), $request);
        $queryFilter
            ->updateQuery($filterStatus[self::QUERY])
            ->examination_lab('lab')
            ->beforeDate('tgl')
            ->afterDate('tgl')
            ->getSortedAndOrderedData('tgl', 'desc') 
        ;

        //Get data
        $data = $queryFilter
            ->getQuery()
            ->get()
        ;

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
        $excel = \App\Services\ExcelService::download($examsArray, 'Data Pendapatan');
        return response($excel['file'], 200, $excel['headers']);
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
		if (!count($data)){
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
		
        $data = Kuitansi::find($id)->get()[0];

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
	
	private function cekKuitansi($number)
    {
		$inc = Kuitansi::where(self::NUMBER,'=',''.$number.'')->get();
		return count($inc);
    }

    private function filterStatus($query, Request $request)
    {
        $status = '';

        $bussinessStep = array(
            '',
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
            $status = $req;
            for ($step = 1; $step <= $req; $step++) {
                if ( $step < $req ){
                    $query->where($bussinessStep[$step], '=', 1);
                }else{
                    $query->where($bussinessStep[$step], '!=', 1);
                    break;
                }
            }
        }else {
            $status = 'all';
        }
        return array(
            self::QUERY => $query,
            self::STATUS => $status
        );
    }

    private function filterUrlEncode ($string, $number=false)
    {
        if(!$string && $number){
            $string = '0';
        }
        if(!$string && !$number){
            $string = '-';
        }
        if( $string && strpos( $string, "/" ) ){
            $string = urlencode(urlencode($string));
        }
        return $string;
    }

    private function initialQuery($search)
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
            ->with('examination')
            ->with(self::COMPANY)
        ;

        if ($search){
            $query->where(function($qry) use($search){
                $qry->whereHas(self::COMPANY, function ($q) use ($search){
                    return $q->where('name', 'like', '%'.strtolower($search).'%');
                });
            });

            $logService->createLog( self::SEARCH, 'INCOME', json_encode(array(self::SEARCH=>$search)) );
        }
        return array(
            self::SEARCH=> $search,
            self::QUERY => $query,
        );
    }
}