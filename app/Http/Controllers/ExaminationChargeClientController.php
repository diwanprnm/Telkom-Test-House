<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use App\Logs;
use App\ExaminationLab;

use App\ExaminationCharge;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationChargeClientController extends Controller
{

    private const CATEGORY = 'category';
    private const CREATED_AT = 'created_at';
    private const DEVICE_NAME = 'device_name';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
			$category = trim($request->input(self::CATEGORY));

            $examLab = ExaminationLab::all();
            
            $query = ExaminationCharge::with('ExaminationLab')->whereNotNull(self::CREATED_AT)
                ->where('is_active', 1);

            if ($search != null){
                $query = $query->where(self::DEVICE_NAME,'like','%'.$search.'%');
            }
			
            if ($request->has(self::CATEGORY)){
                $category = $request->get(self::CATEGORY);
                if($request->input(self::CATEGORY) != 'all'){
                    $query->where(self::CATEGORY, $request->get(self::CATEGORY));
                }
            }

			$examinationCharge = $query->orderByRaw('category, device_name')
                    ->paginate($paginate);
            
            if (count($examinationCharge) == 0){
                $message = 'Data not found';
            }
            $page = "Chargeclient";
            $examinationLabs = ExaminationLab::orderBy('lab_code', 'asc')->get();
            return view('client.charge.index')
                ->with('examLab', $examLab)
                ->with('message', $message)
                ->with('data', $examinationCharge)
                ->with('search', $search)
                ->with('page', $page)
                ->with(self::CATEGORY, $category)
                ->with('labs', $examinationLabs)
            ;
    }
	
	
	public function filter(Request $request)
    {
		$paginate = 2;
		$category = trim($request->input(self::CATEGORY));
		if ($category != null){
			$data = ExaminationCharge::whereNotNull(self::CREATED_AT)
				->where(self::CATEGORY,'=',''.$category.'')
				->orderBy(self::DEVICE_NAME)
				->paginate($paginate);
		}else{
			$data = ExaminationCharge::whereNotNull(self::CREATED_AT)
				->orderBy(self::DEVICE_NAME)
				->paginate($paginate);
		}
		return response()
            ->view('client.charge.filter', $data, 200)
            ->header('Content-Type', 'text/html');
    }
	
	public function autocomplete($query) {
        return ExaminationCharge::select('device_name as autosuggest')
                ->where(self::DEVICE_NAME, 'like','%'.$query.'%')
				->orderBy(self::DEVICE_NAME)
                ->take(5)
                ->distinct()
                ->get();
    }
}
