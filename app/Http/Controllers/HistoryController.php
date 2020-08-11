<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ExaminationHistory;
use App\User;
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

class HistoryController extends Controller
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
            $type = ''; 
			$before = null;
            $after = null;

            $query = ExaminationHistory::whereNotNull('created_at')
								->where('status', '!=', 0)
                                ->with('examination')
                                ->with('examination.user')
                                ->with('examination.company')
                                ->with('examination.device')
					; 
            if ($request->has('type')){
                $type = $request->get('type');
                if($request->input('type') != 'all'){
					$query->where('status', $request->get('type'));
				}
            }
			
			if ($request->has('before_date')){
				$query->where('date_action', '<=', $request->get('before_date'));
				$before = $request->get('before_date');
			}

			if ($request->has('after_date')){
				$query->where('date_action', '>=', $request->get('after_date'));
				$after = $request->get('after_date');
			}
 
            $data = $query->orderBy('created_at', 'desc')
                        ->paginate($paginate); 

            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.history.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('type', $type) 
				->with('before_date', $before)
                ->with('after_date', $after)
			;
        }
    }  
	public function excel(Request $request) 
	{ 
		$data = $request->session()->get('excel_history');
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
			if($row->status == 1){
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
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx');
	}
}