<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use App\Article;
use App\Logs;

use Auth;
use Session;
use Validator;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class LogController extends Controller
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
            $select = array(
            	"logs.action","logs.page","logs.created_at as search_date","users.name"
            );
            if ($search != null){
            
                $datalogs = Logs::select($select)->whereNotNull('logs.created_at')
                ->join("users","users.id","=","logs.user_id")
                    ->where('action','like','%'.$search.'%')
                    ;

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Search Log"; 
                $dataSearch = array("search"=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "LOG";
                $logs->save();

            }else{
                $datalogs = Logs::select($select)->whereNotNull('logs.created_at')
                	->join("users","users.id","=","logs.user_id")
                    ;
            }
				$data_excel = $datalogs->orderBy('logs.created_at', 'desc')->get();
				$data = $datalogs->orderBy('logs.created_at', 'desc')
							->paginate($paginate);
							
			$request->session()->put('excel_log', $data_excel);
			
            if (count($datalogs) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.log.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search);
        }
    }

    public function autocomplete($query) {
    	$select = array(
            	"logs.action","logs.page","logs.created_at as search_date","users.name"
            );
        $respons_result = Logs::select($select)->autocomplet($query);
        return response($respons_result);
    }
    
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		
		$data = $request->session()->get('excel_log');
		$examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'No',
			'Username',
			'Action',
			'Page',
			'Date'
		]; 
		
		// Convert each member of the returned collection into an array,
		// and append it to the payments array.
			$no = 0;
		foreach ($data as $row) {
			$no ++;
			$examsArray[] = [
				$no,
				$row->name,
				$row->action,
				$row->page,
				$row->search_date
			];
		}
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->id = Uuid::uuid4();
        $logs->user_id = $currentUser->id;
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "LOG";
        $logs->save();

		// Generate and return the spreadsheet
		Excel::create('Data Aktivitas User', function($excel) use ($examsArray) {

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
}
