<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\STEL;
use App\ExaminationLab;
use App\Logs;

use Excel;

use Auth;
use File;
use Response;
use Session;
use Input;
use Ramsey\Uuid\Uuid;
class STELController extends Controller
{



    private const EXAMINATION_LAB = 'examinationLab';
    private const SEARCH = 'search';
    private const CREATED_AT = 'created_at';
    private const CATEGORY = 'category';
    private const IS_ACTIVE = 'is_active';
    private const EXAM_LAB = 'examLab';
    private const MESSAGE = 'message';
    private const STEL_TYPE = 'stel_type';
    private const VERSION = 'version';
    private const PRICE = 'price';
    private const TOTAL = 'total';
    private const ATTACHMENT = 'attachment';
    private const ERROR = 'error';
    private const ADMIN_CREATE = '/admin/stel/create';
    private const ADMIN_STEL = '/admin/stel';
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
            $search = trim($request->input(self::SEARCH));
            $category = '';
            $year = '';
            $status = -1;

            $examLab = ExaminationLab::all();
            
            $query = STEL::whereNotNull(self::CREATED_AT)->with(self::EXAMINATION_LAB);

            $tahun = STEL::whereNotNull(self::CREATED_AT)->with(self::EXAMINATION_LAB)->select('year')->orderBy('year','desc')->distinct()->get();

            if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->where('name', 'like', '%'.strtolower($search).'%')
                    ->orWhere('code', 'like', '%'.strtolower($search).'%');
                });

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search STEL";
                $datasearch = array(self::SEARCH=>$search);
                $logs->data = json_encode($datasearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "STEL";
                $logs->save();
            }
            
            if ($request->has(self::CATEGORY)){
                $category = $request->get(self::CATEGORY);
				if($request->input(self::CATEGORY) != 'all'){
					$query->whereHas(self::EXAMINATION_LAB, function ($q) use ($category){
                        return $q->where('id', $category);
                    });
				}
            }

            if ($request->has('year') && $request->input('year') != 'all'){ 
                $year = $request->get('year');
                $query->where('year', $request->get('year'));
            
            }

            if ($request->has(self::IS_ACTIVE)){
                $status = $request->get(self::IS_ACTIVE);
                if ($request->get(self::IS_ACTIVE) > -1){
					$query->where(self::IS_ACTIVE, $request->get(self::IS_ACTIVE));
                }
            }
                
            $stels = $query->orderBy('name')->paginate($paginate);
            
            if (count($stels) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.STEL.index')
                ->with(self::EXAM_LAB, $examLab)
                ->with(self::MESSAGE, $message)
                ->with('data', $stels)
                ->with(self::SEARCH, $search)
                ->with(self::CATEGORY, $category)
                ->with('tahun', $tahun)
                ->with('year', $year)
                ->with('status', $status);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $examLab = ExaminationLab::all();
        return view('admin.STEL.create')
            ->with(self::EXAM_LAB,$examLab)
        ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
		$currentUser = Auth::user();
		$return_page = redirect(self::ADMIN_CREATE);

	    $name_exists = $this->cekNamaSTEL($request->input('name'));
		if($name_exists == 1){
			$return_page =  redirect()->back()
			->with('error_name', 1)
			->withInput($request->all());
		}else{
            $stel = new STEL;
            $stel->code = $request->input('code');
            $stel->stel_type = $request->input(self::STEL_TYPE);
            $stel->name = $request->input('name');
            $stel->type = $request->input('type');
            $stel->version = $request->input(self::VERSION);
            $stel->year = $request->input('year');
            $stel->price = str_replace(",","",$request->input(self::PRICE));
            $stel->total = str_replace(",","",$request->input(self::TOTAL));
            $stel->is_active = $request->input(self::IS_ACTIVE);
            $stel->created_by = $currentUser->id;
            $stel->updated_by = $currentUser->id;

            if ($request->hasFile(self::ATTACHMENT)) { 
                $name_file = 'stel_'.$request->file(self::ATTACHMENT)->getClientOriginalName();
                $path_file = public_path().'/media/stel';
                if (!file_exists($path_file)) {
                    mkdir($path_file, 0775);
                }
                if($request->file(self::ATTACHMENT)->move($path_file,$name_file)){
                    $stel->attachment = $name_file;
                }else{
                    Session::flash(self::ERROR, 'Save STEL to directory failed');
                    $return_page =  redirect(self::ADMIN_CREATE);
                }
            }
     

            try{
                $stel->save();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;
                $logs->id = Uuid::uuid4();
                $logs->action = "Create STEL";
                $logs->data = $stel;
                $logs->created_by = $currentUser->id;
                $logs->page = "STEL";
                $logs->save();
                
                Session::flash(self::MESSAGE, 'STEL successfully created');
                $return_page =  redirect(self::ADMIN_STEL);
            } catch(Exception $e){
                Session::flash(self::ERROR, 'Save failed');
                $return_page =  redirect(self::ADMIN_CREATE);
            }
        }
		

        return $return_page;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $examLab = ExaminationLab::all();
        $stel = STEL::find($id);

        return view('admin.STEL.edit')
            ->with(self::EXAM_LAB, $examLab)
            ->with('data', $stel)
        ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();

        $stel = STEL::find($id);
        $oldStel = $stel;

        if ($request->has('code')){
            $stel->code = $request->input('code');
        }
        if ($request->has(self::STEL_TYPE)){
            $stel->stel_type = $request->input(self::STEL_TYPE);
        }
        if ($request->has('name')){
            $stel->name = $request->input('name');
        }
        if ($request->has('type')){
            $stel->type = $request->input('type');
        }
        if ($request->has(self::VERSION)){
            $stel->version = $request->input(self::VERSION);
        }
        if ($request->has('year')){
            $stel->year = $request->input('year');
        }
        if ($request->has(self::PRICE)){
            $stel->price = str_replace(",","",$request->input(self::PRICE));
        }
        if ($request->has(self::IS_ACTIVE)){
            $stel->is_active = $request->input(self::IS_ACTIVE);
        }
        if ($request->has(self::TOTAL)){
            $stel->total = str_replace(",","",$request->input(self::TOTAL));
        }
        if ($request->hasFile(self::ATTACHMENT)) { 
            $name_file = 'stel_'.$request->file(self::ATTACHMENT)->getClientOriginalName();
            $path_file = public_path().'/media/stel';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::ATTACHMENT)->move($path_file,$name_file)){
                $stel->attachment = $name_file;
            }else{
                Session::flash(self::ERROR, 'Save STEL to directory failed');
                return redirect(self::ADMIN_CREATE);
            }
        }

        $stel->updated_by = $currentUser->id;

       

        try{
            $stel->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update STEL";
            $logs->data = $oldStel;
            $logs->created_by = $currentUser->id;
            $logs->page = "STEL";
            $logs->save();

            Session::flash(self::MESSAGE, 'STEL successfully updated');
            return redirect(self::ADMIN_STEL);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect('/admin/stel/'.$stel->id.'/edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stel = STEL::find($id);
        $oldStel = $stel;
        $currentUser = Auth::user();
        if ($stel){
            try{
                $stel->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete STEL";
                $logs->data = $oldStel;
                $logs->created_by = $currentUser->id;
                $logs->page = "STEL";
                $logs->save();

                Session::flash(self::MESSAGE, 'STEL successfully deleted');
                return redirect(self::ADMIN_STEL);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_STEL);
            }
        }
    }

    public function viewMedia($id)
    {
        $stel = STEL::find($id);

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file, $headers);
        }
    }
	
    function cekNamaSTEL($name)
    {
		$stels = STEL::where('name','=',''.$name.'')->get();
		return count($stels);
    }
	
	public function autocomplete($query) {
        $respons_result = STEL::adm_stel_autocomplet($query);
        return response($respons_result);
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input(self::SEARCH));
        
        $category = '';
        $status = $request->get(self::IS_ACTIVE);

        if ($search != null){
            $stels = STEL::whereNotNull(self::CREATED_AT)
                ->with(self::EXAMINATION_LAB)
                ->where('name','like','%'.$search.'%')
                ->orWhere('code','like','%'.$search.'%')
                ->orderBy('code');
        }else{
            $query = STEL::whereNotNull(self::CREATED_AT)->with(self::EXAMINATION_LAB);

            if ($request->has(self::CATEGORY)){
                $category = $request->get(self::CATEGORY);
                if($request->input(self::CATEGORY) != 'all'){
                    $query->whereHas(self::EXAMINATION_LAB, function ($q) use ($category){
                        return $q->where('id', $category);
                    });
                }
            }

            if ($request->has(self::IS_ACTIVE) && $status > -1){  
                $query->where(self::IS_ACTIVE, $status); 
            }
            
            $stels = $query->orderBy('name');
        }

        $data = $stels->get();

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'ID',
            'Kode',
            'Nama Dokumen',
            'Tipe',
            'Versi',
            'Tahun',
            'Harga',
            'Total',
            'Status'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            $examsArray[] = [
                $row->id,
                $row->code,
                $row->name,
                $row->examinationLab->name,
                $row->version,
                $row->year,
                number_format($row->price, 0, '.', ','),
                number_format($row->total, 0, '.', ','),
                $row->is_active == '1' ? 'Active' : 'Not Active'
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "STEL/STD";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data STEL/STD', function($excel) use ($examsArray) {
 

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
