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
            $search = trim($request->input('search'));
            
            if ($search != null){
                $footers = Footer::whereNotNull('created_at')
                    ->where('description','like','%'.$search.'%')
                    ->orderBy('updated_at')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Footer"; 
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "FOOTER";
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
                ->with('message', $message)
                ->with('data', $footers)
                ->with('search', $search);
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
        $footer->description = $request->input('description');

        if ($request->hasFile('image')) {
            /*$ext_file = $request->file('image')->getClientOriginalExtension();
            $name_file = uniqid().'_footer_'.$footer->id.'.'.$ext_file;*/
            $name_file = 'footer_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/footer';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $footer->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/footer/create');
            }
        }
        
        $footer->is_active = $request->input('is_active');
        $footer->created_by = $currentUser->id;
        $footer->updated_by = $currentUser->id;

        try{
            $footer->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Footer";  
            $logs->data = $footer;
            $logs->created_by = $currentUser->id;
            $logs->page = "FOOTER";
            $logs->save();

            Session::flash('message', 'Information successfully created');
            return redirect('/admin/footer');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/footer/create');
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

        if ($request->has('description')){
            $footer->description = $request->input('description');
        }
        if ($request->has('is_active')){
            $footer->is_active = $request->input('is_active');
        }
        if ($request->hasFile('image')) {
            /*$ext_file = $request->file('image')->getClientOriginalExtension();
            $name_file = uniqid().'_footer_'.$footer->id.'.'.$ext_file;*/
            $name_file = 'footer_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/footer';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $footer->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/footer/create');
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
            $logs->page = "FOOTER";
            $logs->save();

            Session::flash('message', 'Information successfully updated');
            return redirect('/admin/footer');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
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
                $logs->page = "FOOTER";
                $logs->save();
                
                Session::flash('message', 'Information successfully deleted');
                return redirect('/admin/footer');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/footer');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Footer::autocomplet($query);
        return response($respons_result);
    }
}
