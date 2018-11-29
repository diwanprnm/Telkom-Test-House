<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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

            $before = null;
            $after = null;
            $filterUsername = '';
            $filterAction = '';

            $sort_by = 'logs.created_at';
            $sort_type = 'desc';

            $select = array(
            	"logs.action","logs.page","logs.created_at as search_date","users.name"
            );
            $datalogs = Logs::select($select)->whereNotNull('logs.created_at')->join("users","users.id","=","logs.user_id");

            $select2 = array(
                "users.name"
            );
            $datalogs2 = Logs::select($select2)->whereNotNull('logs.created_at')->join("users","users.id","=","logs.user_id");

            $username = $datalogs2->distinct()->orderBy('users.name')->get();

            $select3 = array(
                "logs.action"
            );
            $datalogs3 = Logs::select($select3)->whereNotNull('logs.created_at')->join("users","users.id","=","logs.user_id");
            $action = $datalogs3->distinct()->orderBy('logs.action')->get();

            if ($search != null){
                $data->where('action','like','%'.$search.'%');

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Search Log"; 
                $dataSearch = array("search"=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "LOG";
                $logs->save();

            }

            if ($request->has('before_date')){
                $datalogs->where(DB::raw('DATE(logs.created_at)'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $datalogs->where(DB::raw('DATE(logs.created_at)'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            if ($request->has('username')){
                $filterUsername = $request->get('username');
                if($request->input('username') != 'all'){
                    $datalogs->where('users.name', $request->get('username'));
                }
            }

            if ($request->has('action')){
                $filterAction = $request->get('action');
                if($request->input('action') != 'all'){
                    $datalogs->where('action', $request->get('action'));
                }
            }

            if ($request->has('sort_by')){
                $sort_by = $request->get('sort_by');
            }
            if ($request->has('sort_type')){
                $sort_type = $request->get('sort_type');
            }
                
                $data = $datalogs->orderBy($sort_by, $sort_type)
                            ->paginate($paginate);

            if (count($datalogs) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.log.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search)
                ->with('before_date', $before)
                ->with('after_date', $after)
                ->with('username', $username)
                ->with('filterUsername', $filterUsername)
                ->with('action', $action)
                ->with('filterAction', $filterAction)
                ->with('sort_by', $sort_by)
                ->with('sort_type', $sort_type);
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
		
        $search = trim($request->input('search'));
        
        $before = null;
        $after = null;
        $filterUsername = '';
        $filterAction = '';

        $sort_by = 'logs.created_at';
        $sort_type = 'desc';

        $select = array(
            "logs.action","logs.page","logs.created_at as search_date","users.name"
        );
        $datalogs = Logs::select($select)->whereNotNull('logs.created_at')->join("users","users.id","=","logs.user_id");

        if ($search != null){
            $data->where('action','like','%'.$search.'%');
        }

        if ($request->has('before_date')){
            $datalogs->where(DB::raw('DATE(logs.created_at)'), '<=', $request->get('before_date'));
            $before = $request->get('before_date');
        }

        if ($request->has('after_date')){
            $datalogs->where(DB::raw('DATE(logs.created_at)'), '>=', $request->get('after_date'));
            $after = $request->get('after_date');
        }

        if ($request->has('username')){
            $filterUsername = $request->get('username');
            if($request->input('username') != 'all'){
                $datalogs->where('users.name', $request->get('username'));
            }
        }

        if ($request->has('action')){
            $filterAction = $request->get('action');
            if($request->input('action') != 'all'){
                $datalogs->where('action', $request->get('action'));
            }
        }

        if ($request->has('sort_by')){
            $sort_by = $request->get('sort_by');
        }
        if ($request->has('sort_type')){
            $sort_type = $request->get('sort_type');
        }

		$data = $datalogs->orderBy($sort_by, $sort_type)->get();
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
