<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Certification;
use App\Logs;
use App\Services\Logs\LogService;

use Auth;
use Session;
use Image;
use File;
use Response;
use Storage;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class PopUpInformationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private const SEARCH = 'search';
    private const CREATED = 'created_at';
    private const TITLE = 'title';
    private const CERTIFICATION = "CERTIFICATION";
    private const MESSAGE = 'message';
    private const IMAGE = 'image';
    private const ERROR = 'error';
    private const CREATE = '/admin/popupinformation/create';
    private const IS_ACTIVE = 'is_active';
    private const ADMIN = '/admin/popupinformation';

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
            
            if ($search != null){
                $popupinformations = Certification::whereNotNull($this::CREATED)
                    ->where($this::TITLE,'like','%'.$search.'%')
                    ->where('type',0)
                    ->orderBy($this::CREATED)
                    ->paginate($paginate);

                    $logService = new LogService();
                    $logService->createLog("Search Pop Up Information", $this::CERTIFICATION, json_encode(array("search"=>$search)) );
            }else{
                $popupinformations = Certification::whereNotNull($this::CREATED)
                    ->where('type',0)
                    ->orderBy($this::CREATED, 'desc')
                    ->paginate($paginate);
            }
            
            if (count($popupinformations) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.popupinformation.index')
                ->with($this::MESSAGE, $message)
                ->with('data', $popupinformations)
                ->with($this::SEARCH, $search);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.popupinformation.create');
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

        $popupinformation = new Certification;
        $popupinformation->id = Uuid::uuid4();
        $popupinformation->title = $request->input($this::TITLE);
   
        $allowedImage = ['jpeg','jpg','png'];
       
        if ($request->hasFile($this::IMAGE)) { 
            $file = $request->file($this::IMAGE);
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'popupinformation_'.$request->file($this::IMAGE)->getClientOriginalName();
             
           
            if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                Storage::disk('minio')->put("popupinformation/".$popupinformation->id."/$file_name",(string)$image->encode()); 
             }else{
                Session::flash('error', 'Format Not Available');
                return redirect('/admin/popupinformation/create');
            } 
        }
        
        $popupinformation->is_active = $request->input($this::IS_ACTIVE);
            $request->input($this::IS_ACTIVE)==1 ? DB::table('certifications')->where('type', 0)->update([$this::IS_ACTIVE => 0]) : "";
        $popupinformation->type = 0;
        $popupinformation->created_by = $currentUser->id;
		$popupinformation->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $popupinformation->save();

            $logService = new LogService();
            $logService->createLog("Create Pop Up Information", $this::CERTIFICATION, $popupinformation );           

            Session::flash($this::MESSAGE, 'Pop Up Information successfully created');
            return redirect($this::ADMIN);
        } catch(Exception $e){
            Session::flash($this::ERROR, 'Save failed');
            return redirect($this::CREATE);
        }
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
        $popupinformation = Certification::find($id);

        return view('admin.popupinformation.edit')
            ->with('data', $popupinformation);
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

        $popupinformation = Certification::find($id);
        $oldData = $popupinformation;
        if ($request->has($this::TITLE)){
            $popupinformation->title = $request->input($this::TITLE);
        }
        if ($request->has($this::IS_ACTIVE)){
            $popupinformation->is_active = $request->input($this::IS_ACTIVE);
            $request->input($this::IS_ACTIVE)==1 ? DB::table('certifications')->where('type', 0)->update([$this::IS_ACTIVE => 0]) : "";
        }
        if ($request->file($this::IMAGE)) {
            $name_file = 'cert_'.$request->file($this::IMAGE)->getClientOriginalName();
            $path_file = public_path().'/media/popupinformation';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file($this::IMAGE)->move($path_file,$name_file)){
                $popupinformation->image = $name_file;
            }else{
                Session::flash($this::ERROR, 'Save Image to directory failed');
                return redirect($this::CREATE);
            }
        }

        $popupinformation->updated_by = $currentUser->id;
		$popupinformation->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $popupinformation->save();

            $logService = new LogService();
            $logService->createLog("Update Pop Up Information", $this::CERTIFICATION, $oldData );           

            Session::flash($this::MESSAGE, 'Pop Up Information successfully updated');
            return redirect($this::ADMIN);
        } catch(Exception $e){
            Session::flash($this::ERROR, 'Save failed');
            return redirect('/admin/popupinformation/'.$popupinformation->id.'edit');
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
        $popupinformation = Certification::find($id);
        $oldData = $popupinformation;
        $currentUser = Auth::user();
        if ($popupinformation){
            try{
                $popupinformation->delete();

                $logService = new LogService();
                $logService->createLog("Delete Pop Up Information", $this::CERTIFICATION, $oldData );           

                Session::flash($this::MESSAGE, 'Pop Up Information successfully deleted');
                return redirect($this::ADMIN);
            }catch (Exception $e){
                Session::flash($this::ERROR, 'Delete failed');
                return redirect($this::ADMIN);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Certification::autocomplet($query);
        return response($respons_result);
    }
}
