<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;


use App\Article;
use App\Logs;
use App\LogsAdministrator;

use Auth;
use Session;
use Validator;
use Excel;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Services\Logs\LogService;
class Log_administratorController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    private const SEARCH = 'search';
    private const LOG_ADMIN_CREATED = 'logs_administrator.created_at';
    private const LOG_ADMIN_ACTION = "logs_administrator.action";
    private const LOG_ADMIN_PAGE = "logs_administrator.page";
    private const LOG_ADMIN_SEARCH = "logs_administrator.created_at as search_date";
    private const USER_NAME = "users.name";
    private const LOG_ADMIN_USER = "logs_administrator.user_id";
    private const USER_ID = "users.id";
    private const USER = "users";
    private const ACTION = 'action';
    private const BEFORE = 'before_date';
    private const DATE = 'DATE(logs_administrator.created_at)';
    private const AFTER = 'after_date';
    private const USERNAME = 'username';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';
    

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
            $search = trim($request->input($this::SEARCH));

            $before = null;
            $after = null;
            $filterUsername = '';
            $filterAction = '';

            $sort_by = $this::LOG_ADMIN_CREATED;
            $sort_type = 'desc';

            $select = array(
            	$this::LOG_ADMIN_ACTION,$this::LOG_ADMIN_PAGE,"logs_administrator.reason",$this::LOG_ADMIN_SEARCH,$this::USER_NAME
            );
            $datalogs = LogsAdministrator::select($select)->whereNotNull($this::LOG_ADMIN_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_ADMIN_USER);

            $select2 = array(
                $this::USER_NAME
            );
            $datalogs2 = LogsAdministrator::select($select2)->whereNotNull($this::LOG_ADMIN_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_ADMIN_USER);

            $username = $datalogs2->distinct()->orderBy(self::USER_NAME)->get();

            $select3 = array(
                $this::LOG_ADMIN_ACTION
            );
            $datalogs3 = LogsAdministrator::select($select3)->whereNotNull($this::LOG_ADMIN_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_ADMIN_USER);
            $action = $datalogs3->distinct()->orderBy('logs_administrator.action')->get();

            if ($search != null){
                $datalogs->where($this::ACTION,'like','%'.$search.'%');

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->action = "Search Log"; 
                $dataSearch = array("search"=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "Administrator Log";
                $logs->save();

            }

            if ($request->has($this::BEFORE)){
                $datalogs->where(DB::raw($this::DATE), '<=', $request->get($this::BEFORE));
                $before = $request->get($this::BEFORE);
            }

            if ($request->has($this::AFTER)){
                $datalogs->where(DB::raw($this::DATE), '>=', $request->get($this::AFTER));
                $after = $request->get($this::AFTER);
            }

            if ($request->has($this::USERNAME)){
                $filterUsername = $request->get($this::USERNAME);
                if($request->input($this::USERNAME) != 'all'){
                    $datalogs->where(self::USER_NAME, $request->get($this::USERNAME));
                }
            }

            if ($request->has($this::ACTION)){
                $filterAction = $request->get($this::ACTION);
                if($request->input($this::ACTION) != 'all'){
                    $datalogs->where($this::ACTION, $request->get($this::ACTION));
                }
            }

            if ($request->has($this::SORT_BY)){
                $sort_by = $request->get($this::SORT_BY);
            }
            if ($request->has($this::SORT_TYPE)){
                $sort_type = $request->get($this::SORT_TYPE);
            }
                
                $data = $datalogs->orderBy($sort_by, $sort_type)
                            ->paginate($paginate);

            if (count($datalogs) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.log_administrator.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with($this::SEARCH, $search)
                ->with($this::BEFORE, $before)
                ->with($this::AFTER, $after)
                ->with($this::USERNAME, $username)
                ->with('filterUsername', $filterUsername)
                ->with($this::ACTION, $action)
                ->with('filterAction', $filterAction)
                ->with($this::SORT_BY, $sort_by)
                ->with($this::SORT_TYPE, $sort_type);
        }
    }

    public function autocomplete($query) {
    	$select = array(
            	$this::LOG_ADMIN_ACTION,$this::LOG_ADMIN_PAGE,$this::LOG_ADMIN_SEARCH,$this::USER_NAME
            );
        $respons_result = LogsAdministrator::select($select)->autocomplet($query);
        return response($respons_result);
    }
    
	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		
        $search = trim($request->input($this::SEARCH));
        
    

        $sort_by = $this::LOG_ADMIN_CREATED;
        $sort_type = 'desc';

        $select = array(
            $this::LOG_ADMIN_ACTION,$this::LOG_ADMIN_PAGE,$this::LOG_ADMIN_SEARCH,$this::USER_NAME
        );
        $datalogs = LogsAdministrator::select($select)->whereNotNull($this::LOG_ADMIN_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_ADMIN_USER);

        if ($search != null){
            $datalogs->where($this::ACTION,'like','%'.$search.'%');
        }

        if ($request->has($this::BEFORE)){
            $datalogs->where(DB::raw($this::DATE), '<=', $request->get($this::BEFORE));
           
        }

        if ($request->has($this::AFTER)){
            $datalogs->where(DB::raw($this::DATE), '>=', $request->get($this::AFTER));
           
        }

        if ($request->has($this::USERNAME) && ($request->input($this::USERNAME) != 'all')){
           
            
                $datalogs->where(self::USER_NAME, $request->get($this::USERNAME));
            
        }

        if ($request->has($this::ACTION) && $request->input($this::ACTION) != 'all' ){
            
            
                $datalogs->where($this::ACTION, $request->get($this::ACTION));
            
        }

        if ($request->has($this::SORT_BY)){
            $sort_by = $request->get($this::SORT_BY);
        }
        if ($request->has($this::SORT_TYPE)){
            $sort_type = $request->get($this::SORT_TYPE);
        }

		$data = $datalogs->orderBy($sort_by, $sort_type)->get();
        $examsArray = []; 

		// Define the Excel spreadsheet headers
		$examsArray[] = [
			'No',
			'Username',
			'Action',
            'Page',
			'Note',
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
				$row->reason,
				$row->search_date
			];
		}  
        $logService = new LogService();  
        $logService->createLog('download_excel','Administrator Log');

		// Generate and return the spreadsheet
		Excel::create('Data Aktivitas Administrator', function($excel) use ($examsArray) {  
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx'); 
	}
}
