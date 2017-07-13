<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Income;
use App\Kuitansi;
use App\Company;
use App\Logs;

use Auth;
use Session;
use File;
use Response;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

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
            $paginate = 5;
            $search = trim($request->input('search'));
            $type = '';
            $status = '';
			$before = null;
            $after = null;

            $query = Income::whereNotNull('created_at')
                                ->with('company');
			
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
					$query->where('inc_type', $request->get('type'));
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

			$data_excel = $query->orderBy('tgl', 'desc')->get();
            $data = $query->orderBy('tgl', 'desc')
                        ->paginate($paginate);
						
			$request->session()->put('excel_income', $data_excel);

            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.income.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('type', $type)
                ->with('search', $search)
				->with('before_date', $before)
                ->with('after_date', $after);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('admin.income.create_kuitansi');
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
            $paginate = 5;
            $search = trim($request->input('search'));

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

            $data = $query->orderBy('created_at', 'desc')
                        ->paginate($paginate);
			
            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.income.kuitansi')
                ->with('message', $message)
                ->with('id', $id)
                ->with('search', $search)
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
		
		$data = $request->session()->get('excel_income');
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'No',
			'Sumber Pendapatan',
			'Nama Perusahaan',
			'Tanggal',
			'No. Referensi',
			'Nilai'
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
				$sumber_pendapatan,
				$row->company->name,
				$row->tgl,
				"'".$row->reference_number,
				$row->price
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
		$data = Kuitansi::find($id);
		    
		if (count($data) == 0){
			$message = 'Data not found';
		}
		return \Redirect::route('cetakHasilKuitansi', [
			'nomor' => urlencode(urlencode($data->number)) ?: '-',
			'dari' => urlencode(urlencode($data->from)) ?: '-',
			'jumlah' => urlencode(urlencode($data->price)) ?: '-',
			'untuk' => urlencode(urlencode($data->for)) ?: '-'
		]);
    }
	
	function cekKuitansi($number)
    {
		$inc = Kuitansi::where('number','=',''.$number.'')->get();
		return count($inc);
    }
}