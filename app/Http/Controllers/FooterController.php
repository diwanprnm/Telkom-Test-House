<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Footer;
use App\Logs;

use Auth;
use Session;

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

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Footer"; 
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = $this::FOOTER;
                    $logs->save();
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
        
        $footer->is_active = $request->input($this::ACT);
        $footer->created_by = $currentUser->id;
        $footer->updated_by = $currentUser->id;

        try{
            $footer->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Footer";  
            $logs->data = $footer;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::FOOTER;
            $logs->save();

            Session::flash($this::MESS, 'Information successfully created');
            return redirect($this::ADM);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
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

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Footer";  
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::FOOTER;
            $logs->save();

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
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Footer";  
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = $this::FOOTER;
                $logs->save();
                
                Session::flash($this::MESS, 'Information successfully deleted');
                return redirect($this::ADM);
            }catch (Exception $e){
                Session::flash($this::ERR, 'Delete failed');
                return redirect($this::ADM);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Footer::autocomplet($query);
        return response($respons_result);
    }
}
