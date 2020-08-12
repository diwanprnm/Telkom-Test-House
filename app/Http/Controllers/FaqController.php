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
    private const SEARCH = 'search';
    private const QUESTION = 'question';
    private const MESSAGE = 'message';
    private const ANSWER = 'answer';
    private const ADMIN_FAQ_LOC = '/admin/faq';
    private const ERROR = 'error';
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
            $search = trim($request->input(self::SEARCH));
            
            if ($search != null){
                $faq = Faq::whereNotNull('created_at')
                    ->where(self::QUESTION,'like','%'.$search.'%')
                    ->orderBy(self::QUESTION)
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Faq";
                    $datasearch = array(self::SEARCH=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "Faq";
                    $logs->save();
            }else{
                $query = Faq::whereNotNull('created_at'); 
                
                $faq = $query->orderBy(self::QUESTION)
                            ->paginate($paginate);
            }
            
            if (count($faq) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.faq.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $faq)
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
        $currentUser = Auth::user(); 
        $faq = new Faq;
        $faq->id = Uuid::uuid4();
        $faq->question = $request->input(self::QUESTION);
        $faq->answer = $request->input(self::ANSWER);
      
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
            
            Session::flash(self::MESSAGE, 'FAQ successfully created');
            return redirect(self::ADMIN_FAQ_LOC);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
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

        if ($request->has(self::QUESTION)){
            $faq->question = $request->input(self::QUESTION);
        }

        if ($request->has(self::ANSWER)){
            $faq->answer = $request->input(self::ANSWER);
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

            Session::flash(self::MESSAGE, 'FAQ successfully updated');
            return redirect(self::ADMIN_FAQ_LOC);
        } catch(Exception $e){
            Session::flash(self::ERROR, 'Save failed');
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

                Session::flash(self::MESSAGE, 'FAQ successfully deleted');
                return redirect(self::ADMIN_FAQ_LOC);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_FAQ_LOC);
            }
        }else{
             Session::flash(self::ERROR, 'Role Not Found');
                return redirect(self::ADMIN_FAQ_LOC);
        }
    }    
}
