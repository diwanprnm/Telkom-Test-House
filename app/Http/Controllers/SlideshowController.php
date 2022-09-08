<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Slideshow;
use App\Logs;
use App\Services\Logs\LogService;
use App\Services\FileService;

use Auth;
use Session;
use Image;
use Storage;
use Exception;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class SlideshowController extends Controller
{
    private const ADMIN_SLIDE_SHOW = '/admin/slideshow';
    private const ADMIN_SLIDESHOW_CREATE = '/admin/slideshow/create';
    private const COLOR = 'color';
    private const CREATED_AT = 'created_at';
    private const ERROR = 'error';
    private const HEADLINE = 'headline';
    private const IMAGE = 'image';
    private const IS_ACTIVE = 'is_active';
    private const MESSAGE = 'message';
    private const REQUIRED = 'required';
    private const SEARCH = 'search';
    private const SLIDESHOW = 'SLIDESHOW';
    private const TIMEOUT = 'timeout';
    private const TITLE = 'title';
    private const SLIDESHOW_PATH = "slideshow/";
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
        $logService = new LogService();
        $noDataFound = '';
        $paginate = 100;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        
        if ($search){
            $slideshows = Slideshow::whereNotNull(self::CREATED_AT)
                ->where(self::TITLE,'like','%'.$search.'%')
                ->orderBy(self::CREATED_AT)
                ->paginate($paginate);
                $logService->createLog('Search Slideshow', self::SLIDESHOW, json_encode(array(self::SEARCH=>$search)));
        }else{
            $slideshows = Slideshow::whereNotNull(self::CREATED_AT)
                ->orderBy('position')
                ->paginate($paginate);
        }
        
        if (count($slideshows) == 0){
            $noDataFound = 'Data not found';
        }
        
        return view('admin.slideshow.index')
            ->with('noDataFound', $noDataFound)
            ->with('data', $slideshows)
            ->with(self::SEARCH, $search);
        
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
        $this->validate($request, [
            self::TITLE => self::REQUIRED,
            self::HEADLINE => self::REQUIRED,
            self::IMAGE => 'required|mimes:jpg,jpeg,png,jfif',
            self::TIMEOUT => 'numeric',
            self::IS_ACTIVE => 'required|boolean'
        ]);

        $currentUser = Auth::user();
        $logService = new LogService();
        $slideshow = new Slideshow;
        $slideshow->id = Uuid::uuid4();
        $slideshow->title = $request->input(self::TITLE);
        $slideshow->headline = $request->input(self::HEADLINE);
        $slideshow->color = $request->input(self::COLOR);
        $slideshow->timeout = $request->input(self::TIMEOUT,'1');
        $slideshow->updated_by = $currentUser->id;

        if ($request->hasFile(self::IMAGE))
        {

            $fileService = new FileService();
            $fileProperties = array(
                'path' => self::SLIDESHOW_PATH,
                'prefix' => "slide_"
            );
            $fileService->upload($request->file($this::IMAGE), $fileProperties);
 
            if($fileService->isUploaded()){
                $slideshow->image = $fileService->getFileName();
            }else { return redirect(self::ADMIN_SLIDESHOW_CREATE)->with(self::ERROR, 'Save Image to directory failed');
            }
        }
        
        $slideshow->is_active = $request->input(self::IS_ACTIVE);
        $slideshow->created_by = $currentUser->id;
		$slideshow->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $slideshow->save();
            $logService->createLog('Create Slideshow', self::SLIDESHOW, $slideshow);

            Session::flash(self::MESSAGE, 'Slideshow successfully created');
            return redirect(self::ADMIN_SLIDE_SHOW);
        } catch(Exception $e){ return redirect(self::ADMIN_SLIDESHOW_CREATE)->with(self::ERROR, 'Save failed');
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
        $this->validate($request, [
            self::IMAGE => 'mimes:jpg,jpeg,png,jfif',
            self::TIMEOUT => 'numeric',
            self::IS_ACTIVE => 'boolean'
        ]);

        $currentUser = Auth::user();
        $logService = new LogService();
        $slideshow = Slideshow::find($id);
        $oldData = clone $slideshow;
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

            $fileService = new FileService();
            $fileProperties = array(
                'path' => self::SLIDESHOW_PATH,
                'prefix' => "slide_",
                'oldFile' => $slideshow->image
            );
            $fileService->upload($request->file($this::IMAGE), $fileProperties);

            if($fileService->isUploaded()){
                $slideshow->image =  $fileService->getFileName();
            }else {
                return redirect(self::ADMIN_SLIDESHOW_CREATE)->with(self::ERROR, 'Save Image to directory failed');
            }
        }

        $slideshow->updated_by = $currentUser->id;
		$slideshow->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $slideshow->save();
            $logService->createLog('Update Slideshow', self::SLIDESHOW, $oldData);
            Session::flash(self::MESSAGE, 'Slideshow successfully updated');
            return redirect(self::ADMIN_SLIDE_SHOW);
        } catch(Exception $e){ return redirect('/admin/slideshow/'.$slideshow->id.'edit')->with(self::ERROR, 'Save failed');
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
        $slideshow = Slideshow::find($id);
        if ($slideshow){
            try{
                $oldData = $slideshow;
                $slideshow->delete();
                $logService->createLog('Delete Slideshow', self::SLIDESHOW, $oldData);

                $fileService = new FileService();
                $fileProperties = array(
                    'path' => self::SLIDESHOW_PATH,
                    'fileName' => $slideshow->image,
                );
                $fileService->deleteFile($fileProperties);

                Session::flash(self::MESSAGE, 'Slideshow successfully deleted');
                return redirect(self::ADMIN_SLIDE_SHOW);
            }catch (Exception $e){ return redirect(self::ADMIN_SLIDE_SHOW)->with(self::ERROR, 'Delete failed');
            }
        }
        return redirect(self::ADMIN_SLIDE_SHOW)->with(self::ERROR, 'Slideshow not found');
    }
	
	public function autocomplete($query) {
        return Slideshow::select('title as autosuggest')
				->where(self::TITLE, 'like','%'.$query.'%')
                ->orderBy(self::TITLE)
                ->take(5)
				->distinct()
                ->get();
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
