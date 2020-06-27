<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Slideshow;
use App\Logs;

use Auth;
use Session;
use Image;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SlideshowController extends Controller
{
    private const SEARCH = 'search';
    private const CREATED_AT = 'created_at';
    private const TITLE = 'title';
    private const SLIDESHOW = 'SLIDESHOW';
    private const MESSAGE = 'message';
    private const HEADLINE = 'headline';
    private const COLOR = 'color';
    private const TIMEOUT = 'timeout';
    private const IMAGE = 'image';
    private const ERROR = 'error';
    private const ADMIN_SLIDESHOW_CREATE = '/admin/slideshow/create';
    private const IS_ACTIVE = 'is_active';
    private const ADMIN_SLIDE_SHOW = '/admin/slideshow';
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
            $paginate = 100;
            $search = trim($request->input(self::SEARCH));
            
            if ($search != null){
                $slideshows = Slideshow::whereNotNull(self::CREATED_AT)
                    ->where(self::TITLE,'like','%'.$search.'%')
                    ->orderBy(self::CREATED_AT)
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Slideshow";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = self::SLIDESHOW;
                    $logs->save();
            }else{
                $slideshows = Slideshow::whereNotNull(self::CREATED_AT)
                    ->orderBy('position')
                    ->paginate($paginate);
            }
            
            if (count($slideshows) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.slideshow.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $slideshows)
                ->with(self::SEARCH, $search);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.slideshow.create');
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

        $slideshow = new Slideshow;
        $slideshow->id = Uuid::uuid4();
        $slideshow->title = $request->input(self::TITLE);
        $slideshow->headline = $request->input(self::HEADLINE);
        $slideshow->color = $request->input(self::COLOR);
        $slideshow->timeout = $request->input(self::TIMEOUT);

        if ($request->hasFile(self::IMAGE))
        {
            //belum ada koding untuk simpan image ke storage!

            $name_file = 'slide_'.$request->file(self::IMAGE)->getClientOriginalName();
            $path_file = public_path().'/media/slideshow';
        
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
			
            if($request->file(self::IMAGE)->move($path_file,$name_file)){
                    $slideshow->image = $name_file;
            }else{
                Session::flash(self::ERROR, 'Save Image to directory failed');
                return redirect(self::ADMIN_SLIDESHOW_CREATE);
            }
        }
        
        $slideshow->is_active = $request->input(self::IS_ACTIVE);
        $slideshow->created_by = $currentUser->id;
		$slideshow->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $slideshow->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Slideshow";
            $logs->data = $slideshow;
            $logs->created_by = $currentUser->id;
            $logs->page = self::SLIDESHOW;
            $logs->save();

            Session::flash(self::MESSAGE, 'Slideshow successfully created');
            return redirect(self::ADMIN_SLIDE_SHOW);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect(self::ADMIN_SLIDESHOW_CREATE);
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
        $slideshow = Slideshow::find($id);

        return view('admin.slideshow.edit')
            ->with('data', $slideshow);
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

        $slideshow = Slideshow::find($id);
        $oldData = $slideshow;
        if ($request->has(self::TITLE)){
            $slideshow->title = $request->input(self::TITLE);
        }
        if ($request->has(self::HEADLINE)){
            $slideshow->headline = $request->input(self::HEADLINE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $slideshow->is_active = $request->input(self::IS_ACTIVE);
        }
        if ($request->has(self::COLOR)){
            $slideshow->color = $request->input(self::COLOR);
        }
        if ($request->has(self::TIMEOUT)){
            $slideshow->timeout = $request->input(self::TIMEOUT);
        }
        if ($request->file(self::IMAGE)) {
            $name_file = 'slide_'.$request->file(self::IMAGE)->getClientOriginalName();
            $path_file = public_path().'/media/slideshow';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::IMAGE)->move($path_file,$name_file)){
                $slideshow->image = $name_file;
            }else{
                Session::flash(self::ERROR, 'Save Image to directory failed');
                return redirect(self::ADMIN_SLIDESHOW_CREATE);
            }
        }

        $slideshow->updated_by = $currentUser->id;
		$slideshow->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $slideshow->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Slideshow";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = self::SLIDESHOW;
            $logs->save();

            Session::flash(self::MESSAGE, 'Slideshow successfully updated');
            return redirect(self::ADMIN_SLIDE_SHOW);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
            return redirect('/admin/slideshow/'.$slideshow->id.'edit');
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
        $slideshow = Slideshow::find($id);
        $oldData = $slideshow;
        $currentUser = Auth::user();
        if ($slideshow){
            try{
                $slideshow->delete();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Slideshow";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = self::SLIDESHOW;
                $logs->save();

                Session::flash(self::MESSAGE, 'Slideshow successfully deleted');
                return redirect(self::ADMIN_SLIDE_SHOW);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_SLIDE_SHOW);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Slideshow::autocomplet($query);
        return response($respons_result);
    }

    public function orderSlideshow(Request $request){
        $position = $request->input('position');
        $i=1;
        foreach($position as $v){
            $slideshow = Slideshow::find($v);
            $slideshow->position = $i;
            $slideshow->save();
            $i++;
        }
        return 1;
    }
}
