<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\STEL;
use App\ExaminationLab;
use App\Logs;
use Ramsey\Uuid\Uuid;

use Auth;
use Session;

class STELClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $currentUser = Auth::user();

        // if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            $type = '';
            
            $examLab = ExaminationLab::all();

            if ($search != null){
                $query = STEL::whereNotNull('created_at')
                    ->where('stel_type','=','1')
					->where('is_active','=','1')
                    ->where(function($q) use ($search){
                        return $q->where('code','like','%'.$search.'%')
                        ->orWhere('name','like','%'.$search.'%')
                        ;
                    })
                    ->with('examinationLab')
                    ;
                    //$logs = new Logs;
                    //$currentUser = Auth::user();
                    //$logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    //$logs->action = "Search STEL";
                    //$datasearch = array("search"=>$search,"type"=>$type);
                    //$logs->data = json_encode($datasearch);
                    //$logs->created_by = $currentUser->id;
                    //$logs->page = "STEL";
                    //$logs->save();
            }else{
                $query = STEL::whereNotNull('created_at')
					->where('stel_type','=','1')
					->where('is_active','=','1')
                    ->with('examinationLab')
                    ;
            }

            if ($request->has('type')){
                $type = $request->get('type');
                if($request->input('type') != 'all'){
                    $query->whereHas('examinationLab', function ($q) use ($type){
                        return $q->where('id', $type);
                    });
                }
            }
            
				// $stels = $query->orderBy('updated_at', 'desc')
				$stels = $query->orderBy('name')
                         ->paginate($paginate);
			
            if (count($stels) == 0){
                $message = 'Data not found';
            }
            $page = "STELclient";
            return view('client.STEL.index')
                ->with('examLab', $examLab)
                ->with('message', $message)
                ->with('data', $stels)
                ->with('page', $page)
                ->with('search', $search)
                ->with('type', $type);
        // }
    }
	
	public function filter(Request $request)
    {
		$paginate = 2;
		$category = trim($request->input('category'));
		if ($category != null){
			$stels = STEL::whereNotNull('created_at')
				->where('type','=',''.$category.'')
				->orderBy('code')
				->paginate($paginate);
		}else{
			$stels = STEL::whereNotNull('created_at')
				->orderBy('code')
				->paginate($paginate);
		}
		return response()
			->view('client.STEL.filter', $stels, 200)
			->header('Content-Type', 'text/html');
    }
	
	public function autocomplete($query) {
        $respons_result = STEL::autocomplet_stel($query,1);
        return response($respons_result);
    }  
}
