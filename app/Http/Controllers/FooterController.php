<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Footer;
use App\Logs;
use App\Services\Logs\LogService;

use Auth;
use Session;
use Image;
use File;
use Response;
use Excel;
use Storage;
// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FooterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private const SEARCH = 'search';
    private const DESC = 'description';
    private const FOOTER = "FOOTER";
    private const MESS = 'message';
    private const IMAGE = 'image';
    private const ERR = 'error';
    private const CREATE = '/admin/footer/create';
    private const ACT = 'is_active';
    private const ADM = '/admin/footer';

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
                $footers = Footer::whereNotNull('created_at')
                    ->where($this::DESC,'like','%'.$search.'%')
                    ->orderBy('updated_at')
                    ->paginate($paginate);

                    $logService = new LogService();
                    $logService->createLog("Search Footer",self::FOOTER, json_encode( array("search"=>$search)) );
    
            }else{
                $footers = Footer::whereNotNull('created_at')
                    ->orderBy('updated_at')
                    ->paginate($paginate);
            }
            
            if (count($footers) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.footer.index')
                ->with($this::MESS, $message)
                ->with('data', $footers)
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
        return view('admin.footer.create');
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

        $footer = new Footer;
        $footer->id = Uuid::uuid4();
        $footer->description = $request->input($this::DESC);
        
        $allowedImage = ['jpeg','jpg','png'];
        $status  = false;
        if ($request->hasFile($this::IMAGE)) { 
            $file = $request->file($this::IMAGE);
            $ext = $file->getClientOriginalExtension(); 
            $file_name = 'footer_'.$request->file($this::IMAGE)->getClientOriginalName();
            
            $is_uploaded = false;
           
            if (in_array($ext, $allowedImage))
            { 
                $image = Image::make($file);   
                $is_uploaded = Storage::disk('minio')->put("footer/".$footer->id."/$file_name",(string)$image->encode()); 
             }else{
                Session::flash('error', 'Format Not Available'); 
            } 
             
            if($is_uploaded){
                $footer->image = $file_name;
            }else{
                Session::flash($this::ERR, 'Save Image to directory failed'); 
            }
        }        
     
        
        $footer->is_active = $request->input($this::ACT);
        $footer->created_by = $currentUser->id;
        $footer->updated_by = $currentUser->id;

        try{
            $footer->save();

            $logService = new LogService();
            $logService->createLog("Create Footer",self::FOOTER, $footer );

            Session::flash($this::MESS, 'Information successfully created');
            $status = true;
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed'); 
        }

        if($status){
             return redirect($this::ADM);
        }else{
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
        $footer = Footer::find($id);

        return view('admin.footer.edit')
            ->with('data', $footer);
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

        $footer = Footer::find($id);
        $oldData = $footer;

        if ($request->has($this::DESC)){
            $footer->description = $request->input($this::DESC);
        }
        if ($request->has($this::ACT)){
            $footer->is_active = $request->input($this::ACT);
        }
        if ($request->hasFile($this::IMAGE)) {
            
            $name_file = 'footer_'.$request->file($this::IMAGE)->getClientOriginalName();
            $path_file = public_path().'/media/footer';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file($this::IMAGE)->move($path_file,$name_file)){
                $footer->image = $name_file;
            }else{
                Session::flash($this::ERR, 'Save Image to directory failed');
                return redirect($this::CREATE);
            }
        }

        $footer->updated_by = $currentUser->id;

        try{
            $footer->save();

            $logService = new LogService();
            $logService->createLog("Update Footer",self::FOOTER, $oldData );

            Session::flash($this::MESS, 'Information successfully updated');
            return redirect($this::ADM);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
            return redirect('/admin/footer/'.$footer->id.'edit');
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
        $currentUser = Auth::user();
        $footer = Footer::find($id);
        $oldData = $footer;
        if ($footer){
            try{
                $footer->delete();
  
                $logService = new LogService();
                $logService->createLog("Delete Footer",self::FOOTER, $oldData );
                
                Session::flash($this::MESS, 'Information successfully deleted');
                return redirect($this::ADM);
            }catch (Exception $e){
                Session::flash($this::ERR, 'Delete failed');
                return redirect($this::ADM);
            }
        }
    }
	
	public function autocomplete($query) {
        return Footer::select('description as autosuggest')
				->where('description', 'like','%'.$query.'%')
                ->orderBy('description')
                ->take(5)
				->distinct()
                ->get();
    }
}
