<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Company;
use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationLab;
use App\User;
use App\Logs;

use Auth;
use Session;
use Response;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FunctionTestController extends Controller
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
            $paginate = 10;

            $query = Examination::whereNotNull('created_at')
                                ->where("registration_status", "!=", 0)
								->where("function_status", "!=" ,1)
								->whereNotNull("cust_test_date")
                                ->with('device')
                                ->with('company')
                                ;

			$data = $query->orderBy('updated_at', 'desc')
                        ->paginate($paginate);
			
            if (count($query) == 0){
                $message = 'Data not found';
            }
			
            return view('admin.functiontest.index')
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

        $query = Examination::whereNotNull('created_at')
                                ->where("registration_status", "!=", 0)
                                ->where("function_status", "!=" ,1)
                                ->whereNotNull("cust_test_date")
                                ->with('device')
                                ->with('company')
                                ;

        $data = $query->orderBy('updated_at', 'desc')->get();

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'Tanggal FIX',
            'Nama Perusahaan',
            'Nama Perangkat',
            'Merk',
            'Tipe',
            'Kapasitas/Kecepatan',
            'Hasil Uji Fungsi'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        $tanggal_fix = '-';
        foreach ($data as $item) {
            if($item->function_test_date_approval == 1){
                if($item->function_date != null){
                    $tanggal_fix = $item->function_date;
                }
                else{
                    $tanggal_fix = $item->deal_test_date;
                }
            }
            if($item->function_test_TE == 1){
                $hasil = 'Memenuhi';
            }
            elseif($item->function_test_TE == 2){
                $hasil = 'Tidak Memenuhi';
            }
            elseif($item->function_test_TE == 3){
                $hasil = 'dll';
            }
            else{
                $hasil = 'Tidak Ada';
            }
            $examsArray[] = [
                $tanggal_fix,
                $item->company->name,
                $item->device->name,
                $item->device->mark,
                $item->device->model,
                $item->device->capacity,
                $hasil
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;
        $logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Rekap Uji Fungsi";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data Uji Fungsi', function($excel) use ($examsArray) { 
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
