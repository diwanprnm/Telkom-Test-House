<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\Logs;
use App\Faq;

use Auth;
use File;
use Response;
use Session;
use Input;

use Ramsey\Uuid\Uuid;

class FaqController extends Controller
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
            $category = '';
            $status = -1;
            
            if ($search != null){
                $faq = Faq::whereNotNull('created_at')
                    ->where('question','like','%'.$search.'%')
                    ->orderBy('question')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Faq";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "Faq";
                    $logs->save();
            }else{
                $query = Faq::whereNotNull('created_at'); 
                
                $faq = $query->orderBy('question')
                            ->paginate($paginate);
            }
            
            if (count($faq) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.faq.index')
                ->with('message', $message)
                ->with('data', $faq)
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
        return view('admin.faq.create');
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
        $faq = new Faq;
        $faq->id = Uuid::uuid4();
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
      
        $faq->created_by = $currentUser->id;
        $faq->updated_by = $currentUser->id; 

        try{
            $faq->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create Faq";
            $logs->data = $faq;
            $logs->created_by = $currentUser->id;
            $logs->page = "Faq";
            $logs->save();
            
            Session::flash('message', 'FAQ successfully created');
            return redirect('/admin/faq');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/faq/create');
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
        $faq = Faq::find($id);

        return view('admin.faq.edit')
            ->with('data', $faq);
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

        $faq = Faq::find($id);
        $oldFaq = $faq; 

        if ($request->has('question')){
            $faq->question = $request->input('question');
        }

        if ($request->has('answer')){
            $faq->answer = $request->input('answer');
        }

        $faq->updated_by = $currentUser->id; 

        try{
            $faq->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update Faq";
            $logs->data = $oldFaq;
            $logs->created_by = $currentUser->id;
            $logs->page = "Faq";
            $logs->save();

            Session::flash('message', 'FAQ successfully updated');
            return redirect('/admin/faq');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/faq/'.$faq->id.'/edit');
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
        $faq = Faq::find($id);
        $oldFaq = $faq;
        $currentUser = Auth::user();
        if ($faq){
            try{
                $faq->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Faq";
                $logs->data = $oldFaq;
                $logs->created_by = $currentUser->id;
                $logs->page = "Faq";
                $logs->save();

                Session::flash('message', 'FAQ successfully deleted');
                return redirect('/admin/faq');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/faq');
            }
        }else{
             Session::flash('error', 'Role Not Found');
                return redirect('/admin/faq');
        }
    }    
}
