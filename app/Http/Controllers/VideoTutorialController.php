<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\Logs;
use App\VideoTutorial;

use Auth;
use File;
use Response;
use Session;
use Input;

use Ramsey\Uuid\Uuid;

class VideoTutorialController extends Controller
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
            $videotutorial = VideoTutorial::all();
            if (count($videotutorial) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.videotutorial.index')
                ->with('message', $message)
                ->with('data', $videotutorial);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.videotutorial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->flash();
        $currentUser = Auth::user(); 
        $videotutorial = new VideoTutorial;
        $videotutorial->id = Uuid::uuid4();
        $videotutorial->question = $request->input('question');
        $videotutorial->answer = $request->input('answer');
      
        $videotutorial->created_by = $currentUser->id;
        $videotutorial->updated_by = $currentUser->id; 

        try{
            $videotutorial->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create VideoTutorial";
            $logs->data = $videotutorial;
            $logs->created_by = $currentUser->id;
            $logs->page = "VideoTutorial";
            $logs->save();
            
            Session::flash('message', 'FAQ successfully created');
            return redirect('/admin/videoTutorial');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/videoTutorial/create');
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
        $videotutorial = VideoTutorial::find($id);

        return view('admin.videotutorial.edit')
            ->with('data', $videotutorial);
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

            $videotutorial = VideoTutorial::find($id);
            $oldVideoTutorial = $videotutorial; 
            $videotutorial->profile_url = $request->input('profile_url');
            $videotutorial->buy_stel_url = $request->input('buy_stel_url');
            $videotutorial->qa_url = $request->input('qa_url');
            $videotutorial->ta_url = $request->input('ta_url');
            $videotutorial->vt_url = $request->input('vt_url');
            $videotutorial->playlist_url = $request->input('playlist_url');

            $videotutorial->updated_by = $currentUser->id; 

        try{
            $videotutorial->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update Video Tutorial";
            $logs->data = $oldVideoTutorial;
            $logs->created_by = $currentUser->id;
            $logs->page = "Video Tutorial";
            $logs->save();

            Session::flash('message', 'Video Tutorial successfully updated');
            return redirect('/admin/videoTutorial');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/videoTutorial');
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
        $videotutorial = VideoTutorial::find($id);
        $oldVideoTutorial = $videotutorial;
        $currentUser = Auth::user();
        if ($videotutorial){
            try{
                $videotutorial->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete VideoTutorial";
                $logs->data = $oldVideoTutorial;
                $logs->created_by = $currentUser->id;
                $logs->page = "VideoTutorial";
                $logs->save();

                Session::flash('message', 'FAQ successfully deleted');
                return redirect('/admin/videoTutorial');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/videoTutorial');
            }
        }else{
             Session::flash('error', 'Role Not Found');
                return redirect('/admin/VideoTutorialutorial');
        }
    }    
}
