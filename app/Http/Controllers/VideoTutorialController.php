<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\Services\Logs\LogService;
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
    //cek

    private const MESSAGE = 'message';
    private const TUTOR = '/admin/videoTutorial';
    private const ERR = 'error';

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
        $videotutorial = VideoTutorial::all();
        if (count($videotutorial) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.videotutorial.index')
            ->with($this::MESSAGE, $message)
            ->with('data', $videotutorial);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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

        if(!$videotutorial) {
            return redirect($this::TUTOR)
                ->with($this::ERR, 'Data not found');
        }

        try{
            $oldVideoTutorial = clone $videotutorial; 
            $videotutorial->profile_url = $request->input('profile_url');
            $videotutorial->buy_stel_url = $request->input('buy_stel_url');
            $videotutorial->qa_url = $request->input('qa_url');
            $videotutorial->ta_url = $request->input('ta_url');
            $videotutorial->vt_url = $request->input('vt_url');
            $videotutorial->playlist_url = $request->input('playlist_url');
            $videotutorial->created_by = $currentUser->id; 
            $videotutorial->updated_by = $currentUser->id; 
            $videotutorial->save();

            $logService = new LogService();
            $logService->createLog('Update VideoTutorial','VideoTutorial', $oldVideoTutorial);

            Session::flash($this::MESSAGE, 'Video tutorial successfully updated');
            return redirect($this::TUTOR);
        } catch(Exception $e){ return redirect($this::TUTOR)->with($this::ERR, 'Save failed');
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

        if (!$videotutorial){
            return redirect($this::TUTOR)
                ->with($this::ERR, 'Video Tutorial Not Found');
        }

        try{
            $oldVideoTutorial = clone $videotutorial;
            $videotutorial->delete();

            $logService = new LogService();
            $logService->createLog('Delete Video Tutorial','VideoTutorial', $oldVideoTutorial);

            return redirect($this::TUTOR)
                ->with($this::MESSAGE, 'Video tutorial successfully deleted');
        }catch (Exception $e){ return redirect($this::TUTOR)->with($this::ERR, 'Delete failed');
        }
        
    }    
}
