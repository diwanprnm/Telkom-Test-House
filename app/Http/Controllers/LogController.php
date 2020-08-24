<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;


use App\Article;
use App\Logs;
use App\Logs_administrator;

use Auth;
use Session;
use Validator;
use Excel;
use App\Services\Logs\LogServices;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class LogController extends Controller
{
    private const SEARCH = 'search';
    private const LOG_PATH = 'log';
    private const LOG_CREATED = 'logs.created_at';
    private const LOG_ACTION = "logs.action";
    private const LOG_PAGE = "logs.page";
    private const LOG_SEARCH = "logs.created_at as search_date";
    private const LOG_USER = "logs.user_id";
    private const LOG_ADMIN_PATH = 'log_administrator';
    private const LOG_ADMIN_CREATED = 'logs_administrator.created_at';
    private const LOG_ADMIN_ACTION = "logs_administrator.action";
    private const LOG_ADMIN_PAGE = "logs_administrator.page";
    private const LOG_ADMIN_SEARCH = "logs_administrator.created_at as search_date";
    private const LOG_ADMIN_USER = "logs_administrator.user_id";
    private const USER_NAME = "users.name";
    private const USER_ID = "users.id";
    private const USER = "users";
    private const ACTION = 'action';
    private const BEFORE = 'before_date';
    private const DATE = 'DATE(logs.created_at)';
    private const DATE_ADM = 'DATE(logs_administrator.created_at)';
    private const AFTER = 'after_date';
    private const USERNAME = 'users.name';
    private const SORT_BY = 'sort_by';
    private const SORT_TYPE = 'sort_type';
    private const USN = 'username' ;
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
            $search = trim($request->input($this::SEARCH));

            $before = null;
            $after = null;
            $filterUsername = '';
            $filterAction = '';

            $sort_by = $request->path() == $this::LOG_PATH ? $this::LOG_CREATED : $this::LOG_ADMIN_CREATED;
            $sort_type = 'desc';

            if($request->path() == $this::LOG_PATH){
                $select = array(
                    $this::LOG_ACTION,$this::LOG_PAGE,$this::LOG_SEARCH,$this::USER_NAME
                );
                $datalogs = Logs::select($select)->whereNotNull($this::LOG_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_USER);
    
                $select2 = array(
                    $this::USER_NAME
                );
                $datalogs2 = Logs::select($select2)->whereNotNull($this::LOG_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_USER);
    
                $username = $datalogs2->distinct()->orderBy(self::USERNAME)->get();
    
                $select3 = array(
                    $this::LOG_ACTION
                );
                $datalogs3 = Logs::select($select3)->whereNotNull($this::LOG_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_USER);
                $action = $datalogs3->distinct()->orderBy('logs.action')->get();
    
                if ($search != null){
                    $datalogs->where($this::ACTION,'like','%'.$search.'%');
    
                    $logService = new LogService();
                    $logService->createLog('Search Log',"LOG", json_encode(array("search"=>$search)) );
                   
    
                }
    
                if ($request->has($this::BEFORE)){
                    $datalogs->where(DB::raw($this::DATE), '<=', $request->get($this::BEFORE));
                    $before = $request->get($this::BEFORE);
                }
    
                if ($request->has($this::AFTER)){
                    $datalogs->where(DB::raw($this::DATE), '>=', $request->get($this::AFTER));
                    $after = $request->get($this::AFTER);
                }
    
            }else{
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
                    $logService = new LogService();
                    $logService->createLog('Search Log',"Administrator Log", json_encode(array("search"=>$search)) );
                }
    
                if ($request->has($this::BEFORE)){
                    $datalogs->where(DB::raw($this::DATE_ADM), '<=', $request->get($this::BEFORE));
                    $before = $request->get($this::BEFORE);
                }
    
                if ($request->has($this::AFTER)){
                    $datalogs->where(DB::raw($this::DATE_ADM), '>=', $request->get($this::AFTER));
                    $after = $request->get($this::AFTER);
                }
            }

            if ($request->has($this::USN)){
                $filterUsername = $request->get($this::USN);
                if($request->input($this::USN) != 'all'){
                    $datalogs->where(self::USERNAME, $request->get($this::USN));
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
            
            return view($request->path() == $this::LOG_PATH ? 'admin.log.index' : 'admin.log_administrator.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with($this::SEARCH, $search)
                ->with($this::BEFORE, $before)
                ->with($this::AFTER, $after)
                ->with($this::USN, $username)
                ->with('filterUsername', $filterUsername)
                ->with($this::ACTION, $action)
                ->with('filterAction', $filterAction)
                ->with($this::SORT_BY, $sort_by)
                ->with($this::SORT_TYPE, $sort_type);
        }
    }

	public function excel(Request $request) 
	{
		// Execute the query used to retrieve the data. In this example
		// we're joining hypothetical users and payments tables, retrieving
		// the payments table's primary key, the user's first and last name, 
		// the user's e-mail address, the amount paid, and the payment
		// timestamp.
		
        $search = trim($request->input($this::SEARCH));

        $sort_by = $request->path() == $this::LOG_PATH ? $this::LOG_CREATED : $this::LOG_ADMIN_CREATED;
        $sort_type = 'desc';

        if($request->path() == $this::LOG_PATH){
            $select = array(
                $this::LOG_ACTION,$this::LOG_PAGE,$this::LOG_SEARCH,$this::USER_NAME
            );
            $datalogs = Logs::select($select)->whereNotNull($this::LOG_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_USER);

            if ($request->has($this::BEFORE)){
                $datalogs->where(DB::raw($this::DATE), '<=', $request->get($this::BEFORE));      
            }
    
            if ($request->has($this::AFTER)){
                $datalogs->where(DB::raw($this::DATE), '>=', $request->get($this::AFTER));     
            }
        }else{
            $select = array(
                $this::LOG_ADMIN_ACTION,$this::LOG_ADMIN_PAGE,$this::LOG_ADMIN_SEARCH,$this::USER_NAME
            );
            $datalogs = LogsAdministrator::select($select)->whereNotNull($this::LOG_ADMIN_CREATED)->join($this::USER,$this::USER_ID,"=",$this::LOG_ADMIN_USER);

            if ($request->has($this::BEFORE)){
                $datalogs->where(DB::raw($this::DATE_ADM), '<=', $request->get($this::BEFORE));      
            }
    
            if ($request->has($this::AFTER)){
                $datalogs->where(DB::raw($this::DATE_ADM), '>=', $request->get($this::AFTER));     
            }
        }

        if ($request->has($this::USN) && $request->input($this::USN) != 'all'){          
            $datalogs->where(self::USERNAME, $request->get($this::USN));
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
        $no = 0;
        
        if($request->path() == $this::LOG_PATH){
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
            
            $logService = new LogService();
            $logService->createLog('download_excel',"LOG","" );
        }else{
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
        }
    
		// Generate and return the spreadsheet
		Excel::create($request->path() == $this::LOG_PATH ? 'Data Aktivitas User' : 'Data Aktivitas Administrator', function($excel) use ($examsArray) {

			// Set the spreadsheet title, creator, and description
		
			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($examsArray) {
				$sheet->fromArray($examsArray, null, 'A1', false, false);
			});
		})->export('xlsx'); 
	}
}
