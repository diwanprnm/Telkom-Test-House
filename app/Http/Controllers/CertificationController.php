<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Certification;
use App\Logs;

use Auth;
use Session;
use Image;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Storage;

class CertificationController extends Controller
{

    private const SEARCH = 'search';
    private const ERROR = 'error';
    private const CREATED_AT = 'created_at';
    private const TITLE = 'title';
    private const MESSAGE = 'message';
    private const IMAGE = 'image';
    private const IS_ACTIVE = 'is_active';
    private const CERTIFICATION = 'CERTIFICATION';
    private const ADMIN_CERTIFICATION = '/admin/certification';
    private const ADMIN_CERTIFICATION_CREATE = '/admin/certification/create';

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
            
            if ($search != null){
                $certifications = Certification::whereNotNull($this::CREATED_AT)
                    ->where($this::TITLE,'like','%'.$search.'%')
                    ->where('type',1)
                    ->orderBy($this::CREATED_AT)
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Certification";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = $this::CERTIFICATION;
                    $logs->save();
            }else{
                $certifications = Certification::whereNotNull($this::CREATED_AT)
                    ->where('type',1)
                    ->orderBy($this::CREATED_AT)
                    ->paginate($paginate);
            }
            
            if (count($certifications) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.certification.index')
                ->with($this::MESSAGE, $message)
                ->with('data', $certifications)
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
        return view('admin.certification.create');
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

        $certification = new Certification;
        $certification->id = Uuid::uuid4();
        $certification->title = $request->input($this::TITLE);

        if ($request->hasFile($this::IMAGE)) {

            $name_file = 'cert_'.$request->file($this::IMAGE)->getClientOriginalName(); 
            $image_ori = Image::make($request->file($this::IMAGE)); 
            $saveMinio = Storage::disk('minio')->put("certification/$name_file",(string) $image_ori->encode());
 
            if($saveMinio){
                $certification->image = $name_file;
            }else{
                Session::flash($this::ERROR, 'Save Image to directory failed');
                return redirect($this::ADMIN_CERTIFICATION_CREATE);
            }
        }
        
        $certification->is_active = $request->input($this::IS_ACTIVE);
        $certification->type = 1;
        $certification->created_by = $currentUser->id;
		$certification->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $certification->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Certification";
            $logs->data = $certification;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::CERTIFICATION;
            $logs->save();

            Session::flash($this::MESSAGE, 'Certification successfully created');
            return redirect($this::ADMIN_CERTIFICATION);
        } catch(Exception $e){
            Session::flash($this::ERROR, 'Save failed');
            return redirect($this::ADMIN_CERTIFICATION_CREATE);
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
        $certification = Certification::find($id);

        return view('admin.certification.edit')
            ->with('data', $certification);
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

        $certification = Certification::find($id);
        $oldData = $certification;
        if ($request->has($this::TITLE)){
            $certification->title = $request->input($this::TITLE);
        }
        if ($request->has($this::IS_ACTIVE)){
            $certification->is_active = $request->input($this::IS_ACTIVE);
        }
        if ($request->file($this::IMAGE)) {
            $name_file = 'cert_'.$request->file($this::IMAGE)->getClientOriginalName();
            $path_file = public_path().'/media/certification';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file($this::IMAGE)->move($path_file,$name_file)){
                $certification->image = $name_file;
            }else{
                Session::flash($this::ERROR, 'Save Image to directory failed');
                return redirect($this::ADMIN_CERTIFICATION_CREATE);
            }
        }

        $certification->updated_by = $currentUser->id;
		$certification->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $certification->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Certification";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::CERTIFICATION;
            $logs->save();

            Session::flash($this::MESSAGE, 'Certification successfully updated');
            return redirect($this::ADMIN_CERTIFICATION);
        } catch(Exception $e){
            Session::flash($this::ERROR, 'Save failed');
            return redirect('/admin/certification/'.$certification->id.'edit');
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
        $certification = Certification::find($id);
        $oldData = $certification;
        $currentUser = Auth::user();
        if ($certification){
            try{
                $certification->delete();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Certification";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = $this::CERTIFICATION;
                $logs->save();

                Session::flash($this::MESSAGE, 'Certification successfully deleted');
                return redirect($this::ADMIN_CERTIFICATION);
            }catch (Exception $e){
                Session::flash($this::ERROR, 'Delete failed');
                return redirect($this::ADMIN_CERTIFICATION);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Certification::autocomplet($query);
        return response($respons_result);
    }
}
