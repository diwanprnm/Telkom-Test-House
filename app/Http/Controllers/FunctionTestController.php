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
use App\Services\Logs\LogService;

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
        $message = '';

        if (!$currentUser){ return redirect('login');}

        $paginate = 10;

        $query = Examination::whereNotNull('created_at')
            ->where("registration_status", "!=", 0)
            ->where("function_status", "!=" ,1)
            ->whereNotNull("cust_test_date")
            ->with('device')
            ->with('company')
        ;

        $data = $query->orderBy('updated_at', 'desc')
            ->paginate($paginate)
        ;
        
        if (count((array)$query) == 0){ $message = 'Data not found'; }
        
        return view('admin.functiontest.index')
            ->with('data', $data)
            ->with('message', $message)
        ;   
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
        $logService = new LogService();
        $logService->createLog('download_excel', 'Rekap Uji Fungsi', '');

        $excel = \App\Services\ExcelService::download($examsArray, 'Data Uji Fungsi');
		return response($excel['file'], 200, $excel['headers']);
    }
}
