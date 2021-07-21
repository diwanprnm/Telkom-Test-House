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
    private const STELCLIENT_STRING = 'STELclient';
    private const CREATED_AT = 'created_at';
    private const CODE_AUTOSUGGEST = 'code as autosuggest';
    private const NAME_AUTOSUGGEST = 'name as autosuggest';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
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
        $type = '';
        switch ($request->path()) {
            case 'STEL':
                $stel_type = 1;
                break;
            case 'S-TSEL':
                $stel_type = 2;
                break;
            case 'STD':
                $stel_type = 3;
                break;
            case 'INTERNAL':
                $stel_type = 4;
                break;
            case 'PERDIRJEN':
                $stel_type = 5;
                break;
            case 'PERMENKOMINFO':
                $stel_type = 6;
                break;
            case 'OTHER':
                $stel_type = 7;
                break;
        
            default:
                $stel_type = 1;
                break;
        }

        $examLab = ExaminationLab::all();

        $query = STEL::with('examinationLab')
            ->whereNotNull(self::CREATED_AT)
            ->where('stel_type','=',$stel_type)
            ->where('is_active','=','1')
            ;

        if ($search != null){
            $query->where(function($q) use ($search){
                return $q->where('code','like','%'.$search.'%')
                ->orWhere('name','like','%'.$search.'%')
                ;
            });
        }

        if ($request->has('type')){
            $type = $request->get('type');
            if($request->input('type') != 'all'){
                $query->where('type', $type); 
            }
        }
        
        $stels = $query->orderBy('code')->paginate($paginate);
        
        if (count($stels) == 0){
            $message = 'Data not found';
        }
        return view('client.STSEL.index')
            ->with('examLab', $examLab)
            ->with('message', $message)
            ->with('data', $stels)
            ->with('page', $request->path())
            ->with('search', $search)
            ->with('type', $type);
    }
	
	public function filter(Request $request)
    {
		$paginate = 2;
		$category = trim($request->input('category'));
		if ($category != null){
			$stels = STEL::whereNotNull(self::CREATED_AT)
				->where('type','=',''.$category.'')
				->orderBy('code')
				->paginate($paginate);
		}else{
			$stels = STEL::whereNotNull(self::CREATED_AT)
				->orderBy('code')
				->paginate($paginate);
        }
        
		return response()
			->view($request->path() == self::STELCLIENT_STRING ? 'client.STEL.filter' : 'client.STSEL.filter', $stels, 200)
			->header('Content-Type', 'text/html');
    }
	
	public function autocomplete($query, $stel_type) {
        $where =   [
            ['stel_type', '=', $stel_type],
            ['code', 'like', '%'.$query.'%'] 
        ];
        $data1 = STEL::select(self::CODE_AUTOSUGGEST)
				->where($where) 
				->orderBy('code')
                ->take(3)
                ->distinct()
                ->get();
		$data2 = STEL::select(self::NAME_AUTOSUGGEST)
				->where($where) 
				->orderBy('name')
                ->take(3)
				->distinct()
                ->get();  
        if(count($data1) > 0 && count($data2) >0 ){  
            return array_merge($data1,$data2);
        }else{
            return [];
        }
    }
}
