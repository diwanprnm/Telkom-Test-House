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
                $slideshows = Slideshow::whereNotNull('created_at')
                    ->where('title','like','%'.$search.'%')
                    ->orderBy('created_at')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Slideshow";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = "SLIDESHOW";
                    $logs->save();
            }else{
                $slideshows = Slideshow::whereNotNull('created_at')
                    ->orderBy('created_at')
                    ->paginate($paginate);
            }
            
            if (count($slideshows) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.slideshow.index')
                ->with('message', $message)
                ->with('data', $slideshows)
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
		// echo"<pre>";print_r($request->file('image')->getRealPath());exit;
		// echo"<pre>";print_r(pathinfo($request->file('image')->getClientOriginalName()));exit;
		// $size = getimagesize($filename);
		// list($width, $height) = $request->file('image')->getClientSize();
		// $image_info = getimagesize($request->file('image'));
		// $image_width = $image_info[0];
		// $image_height = $image_info[1];
		// echo $image_width;
		// echo $image_height;exit;
		// echo"<pre>";print_r($request->file('image')->getClientSize());exit;
        $currentUser = Auth::user();

        $slideshow = new Slideshow;
        $slideshow->id = Uuid::uuid4();
        $slideshow->title = $request->input('title');
        $slideshow->headline = $request->input('headline');
        $slideshow->color = $request->input('color');

        if ($request->hasFile('image')) {
				$image_info = getimagesize($request->file('image'));
				$image_width = $image_info[0];
				$image_height = $image_info[1];
				$image = $request->file('image');
            /*$ext_file = $request->file('image')->getClientOriginalExtension();
            $name_file = uniqid().'_slide_'.$slideshow->id.'.'.$ext_file;*/
            $name_file = 'slide_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/slideshow';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
			/* if($image_height > 768){
				$percent = ($image_width/768);
				$newwidth = $image_width * $percent;
				$newheight = $image_height * $percent;
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				$source = imagecreatefromjpeg($request->file('image'));
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $image_width, $image_height);
				// $img = Image::make($image->getRealPath())->resize(1024, 768)->insert($path_file.'/'.$name_file);
				file_put_contents(''.$path_file.'/'.$name_file.'', imagejpeg($thumb));
				$slideshow->image = $name_file;
				// echo imagejpeg($thumb);exit;
				// if(imagejpeg($thumb)->move($path_file,$name_file)){					
					// $slideshow->image = $name_file;
				// }else{
					// Session::flash('error', 'Save Image to directory failed');
					// return redirect('/admin/slideshow/create');
				// }
			}
            else  */
				if($request->file('image')->move($path_file,$name_file)){
                $slideshow->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/slideshow/create');
            }
        }
        
        $slideshow->is_active = $request->input('is_active');
        $slideshow->created_by = $currentUser->id;
        // $slideshow->updated_by = $currentUser->id;
		$slideshow->created_at = ''.date('Y-m-d h:i:s').'';
        // $slideshow->updated_at = ''.date('Y-m-d h:i:s').'';

        try{
            $slideshow->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Slideshow";
            $logs->data = $slideshow;
            $logs->created_by = $currentUser->id;
            $logs->page = "SLIDESHOW";
            $logs->save();

            Session::flash('message', 'Slideshow successfully created');
            return redirect('/admin/slideshow');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/slideshow/create');
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
        if ($request->has('title')){
            $slideshow->title = $request->input('title');
        }
        if ($request->has('headline')){
            $slideshow->headline = $request->input('headline');
        }
        if ($request->has('is_active')){
            $slideshow->is_active = $request->input('is_active');
        }
        if ($request->has('color')){
            $slideshow->color = $request->input('color');
        }
        if ($request->hasFile('image')) {
            /*$ext_file = $request->file('image')->getClientOriginalExtension();
            $name_file = uniqid().'_slide_'.$slideshow->id.'.'.$ext_file;*/
            $name_file = 'slide_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/slideshow';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $slideshow->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/slideshow/create');
            }
        }

        $slideshow->updated_by = $currentUser->id;
		$slideshow->updated_at = ''.date('Y-m-d h:i:s').'';

        try{
            $slideshow->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Slideshow";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "SLIDESHOW";
            $logs->save();

            Session::flash('message', 'Slideshow successfully updated');
            return redirect('/admin/slideshow');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
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
                $logs->page = "SLIDESHOW";
                $logs->save();

                Session::flash('message', 'Slideshow successfully deleted');
                return redirect('/admin/slideshow');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/slideshow');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Slideshow::autocomplet($query);
        return response($respons_result);
    }
}
