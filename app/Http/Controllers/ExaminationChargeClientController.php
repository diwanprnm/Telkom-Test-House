<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use App\Logs;

use App\ExaminationCharge;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationChargeClientController extends Controller
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
			$category = trim($request->input('category'));
            
            if ($search != null){
                $query = ExaminationCharge::whereNotNull('created_at')
                    ->where('device_name','like','%'.$search.'%');

                    $logs = new Logs;
                    $currentUser = Auth::user();
                    $logs->user_id = $currentUser->id;
                    $logs->id = Uuid::uuid4();
                    $logs->action = "Search Charge";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "CHARGE";
                    $logs->save();

            }else{
                $query = ExaminationCharge::whereNotNull('created_at');
            }
			
			if ($request->has('category')){
				$query->where('category', $request->get('category'));
				$category = $request->input('category');
			}
			
			$examinationCharge = $query->orderBy('updated_at', 'desc')
                    ->paginate($paginate);
            
            if (count($examinationCharge) == 0){
                $message = 'Data not found';
            }
			 $page = "Chargeclient";
            return view('client.charge.index')
                ->with('message', $message)
                ->with('data', $examinationCharge)
                ->with('search', $search)
                 ->with('page', $page)
				->with('category', $category);
        // }
    }
	
	
	public function filter(Request $request)
    {
		$paginate = 2;
		$category = trim($request->input('category'));
		if ($category != null){
			$data = ExaminationCharge::whereNotNull('created_at')
				->where('category','=',''.$category.'')
				->orderBy('device_name')
				->paginate($paginate);
		}else{
			$data = ExaminationCharge::whereNotNull('created_at')
				->orderBy('device_name')
				->paginate($paginate);
		}
		// print_r($data);exit;
		return response()
            ->view('client.charge.filter', $data, 200)
            ->header('Content-Type', 'text/html');
    }
	
	public function autocomplete($query) {
        $respons_result = ExaminationCharge::autocomplet($query);
        return response($respons_result);
    }
}
