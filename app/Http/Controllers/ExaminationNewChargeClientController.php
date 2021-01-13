<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

use Auth;
use Session;
use App\Logs;
use App\ExaminationLab;

use App\NewExaminationCharge;
use App\NewExaminationChargeDetail;
use App\ExaminationCharge;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationNewChargeClientController extends Controller
{
    private const CATEGORY = 'category';
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
            $dateNewCharge = null;
            $examinationCharge = null;
            $page = "NewChargeclient";

            $examLab = ExaminationLab::all();
            $newCharge = NewExaminationCharge::where("is_implement",0)->orderBy("valid_from","desc")->limit(1)->get();
            $examinationLabs = ExaminationLab::orderBy('lab_code', 'asc')->get();

            if(count($newCharge))
            {
                $dateNewCharge = date("j M Y", strtotime($newCharge[0]->valid_from));
                $query = DB::table('new_examination_charges_detail')
                    ->join('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                    ->join('examination_labs', 'examination_labs.id', '=', 'new_examination_charges_detail.category')
                    ->select('new_examination_charges_detail.*', 'new_examination_charges.*', 'examination_labs.name AS labsName' )
                    ->where('new_examination_charges.id','=',$newCharge[0]->id)
                ;
            
                if ($search){
                    $query = $query->where('new_examination_charges_detail.device_name','like','%'.$search.'%');
                }
                
                if ($request->has(self::CATEGORY)){
                    $category = $request->get(self::CATEGORY);
                    if($request->input(self::CATEGORY) != 'all'){
                        $query->where(self::CATEGORY, $request->get(self::CATEGORY));
                    }
                }

                $examinationCharge = $query->orderByRaw('new_examination_charges_detail.category, new_examination_charges_detail.device_name')
                        ->paginate($paginate);
                        
                if (count($examinationCharge) == 0){ $message = 'Data not found'; }
            }
            return view('client.new_charge.index')
                ->with('dateNewCharge', $dateNewCharge)
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
			$data = ExaminationCharge::whereNotNull('created_at')
				->where(self::CATEGORY,'=',''.$category.'')
				->orderBy(self::DEVICE_NAME)
				->paginate($paginate);
		}else{
			$data = ExaminationCharge::whereNotNull('created_at')
				->orderBy(self::DEVICE_NAME)
				->paginate($paginate);
		}
		return response()
            ->view('client.new_charge.filter', $data, 200)
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
