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

use Storage;
use Ramsey\Uuid\Uuid;

use App\Services\Logs\LogService;
use App\Services\FileService;

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
    private const STEL_URL = '/stel/';

    private const NAME_AUTOSUGGEST = 'name as autosuggest';
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

        if (!$currentUser){ return redirect('login');}

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

            $logService = new LogService();
            $logService->createLog('Search STEL', "STEL", json_encode(array(self::SEARCH=>$search)) );

        }
        
        if ($request->has(self::CATEGORY)){
            $category = $request->get(self::CATEGORY);
            if($request->input(self::CATEGORY) != 'all'){ 
                 $query->where('type', $request->input(self::CATEGORY));
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
            ->with('status', $status)
        ;
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
       
		if($name_exists == 0){
			$return_page =  redirect()->back()
			->with('error_name', 1)
			->withInput($request->all()); 
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

    		$fileService = new FileService();
            if ($request->hasFile(self::ATTACHMENT)) { 
                $fileService = new FileService();
                $fileProperties = array(
                    'path' => self::STEL_URL,
                    'prefix' => "stel_"
                );
                $fileService->upload($request->file($this::ATTACHMENT), $fileProperties);
                $stel->attachment = $fileService->isUploaded() ? $fileService->getFileName() : '';
            }else{
                $stel->attachment = "";
            }
            try{
                $stel->save(); 
                $logService = new LogService();  
                $logService->createLog('Create STEL',"STEL",$stel);
                
                Session::flash(self::MESSAGE, 'STEL successfully created');
                $return_page =  redirect(self::ADMIN_STEL);
            } catch(Exception $e){ $return_page =  redirect(self::ADMIN_CREATE)->with(self::ERROR, 'Save failed');
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
        //code goes here
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
        $logService = new LogService();

        $stel = STEL::find($id);
        if(!empty($stel)){
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

            $fileService = new FileService();
            if ($request->hasFile(self::ATTACHMENT)) {
                $fileService = new FileService();
                $fileProperties = array(
                    'path' => self::STEL_URL,
                    'prefix' => "stel_",
                    'oldFile' => $stel->attachment
                );
                $fileService->upload($request->file($this::ATTACHMENT), $fileProperties);
            $stel->attachment = $fileService->isUploaded() ? $fileService->getFileName() : '';
            }else{
                $stel->attachment = "";
            }

            $stel->updated_by = $currentUser->id;  
            try{
                $stel->save();

                $logService->createLog('Update STEL', 'STEL', $oldStel );

                Session::flash(self::MESSAGE, 'STEL successfully updated');
                return redirect(self::ADMIN_STEL);
            } catch(Exception $e){ return redirect('/admin/stel/'.$stel->id.'/edit')->with(self::ERROR, 'Save failed');
            }
        }else{ return redirect(self::ADMIN_STEL)->with(self::ERROR, 'STEL not Found');
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
        $logService = new LogService();
        $stel = STEL::find($id);
        if(empty($stel)){ return redirect(self::ADMIN_STEL)->with(self::ERROR, 'Delete failed, STEL Not Found'); }

        $oldStel = $stel; 
        try{
            $stel->delete();
            $fileService = new FileService();
            $fileProperties = array(
                'path' => self::STEL_URL,
                'fileName' => $oldStel->attachment
            );
            $fileService->deleteFile($fileProperties);

            $logService->createLog('Delete STEL', "STEL", $oldStel );

            Session::flash(self::MESSAGE, 'STEL successfully deleted');
            return redirect(self::ADMIN_STEL);
        }catch (Exception $e){ return redirect(self::ADMIN_STEL)->with(self::ERROR, 'Delete failed');
        }
    }

    public function viewMedia($id)
    {
        $stel = STEL::find($id);

        if (!$stel){   return redirect(self::ADMIN_STEL)->with(self::ERROR, 'STEL Not Found'); }

        $stream = Storage::disk("minio")->get(self::STEL_URL.$stel->attachment);
                    
        $filename = $stel->attachment;

        return response()->download($stream, $filename); 
    }
	
    function cekNamaSTEL($name)
    {
		$stels = STEL::where('name','=',''.$name.'')->get();
		return count($stels);
    }
	
	public function autocomplete($query) {
        return STEL::select(self::NAME_AUTOSUGGEST)
				->where('name', 'like','%'.$query.'%')
				->orderBy('name')
                ->take(5)
				->distinct()
                ->get(); 
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
        $year = '';
        $status = -1;

        $query = STEL::whereNotNull(self::CREATED_AT)->with(self::EXAMINATION_LAB);

        if ($search != null){
            $query->where(function($qry) use($search){
                $qry->where('name', 'like', '%'.strtolower($search).'%')
                ->orWhere('code', 'like', '%'.strtolower($search).'%');
            });
        }
        
        if ($request->has(self::CATEGORY)){
            $category = $request->get(self::CATEGORY);
            if($request->input(self::CATEGORY) != 'all'){ 
                 $query->where('type', $request->input(self::CATEGORY));
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

        $data = $query->get(); 
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
                (string)$row->id,
                $row->code,
                $row->name,
                @$row->examinationLab->name,
                $row->version,
                $row->year,
                number_format($row->price, 0, '.', ','),
                number_format($row->total, 0, '.', ','),
                $row->is_active == '1' ? 'Active' : 'Not Active'
            ];
        }

        $logService = new LogService();  
        $logService->createLog('download_excel',"STEL/STD","");
 
        $excel = \App\Services\ExcelService::download($examsArray, 'Data STEL-STD');
        return response($excel['file'], 200, $excel['headers']);
    } 
}
