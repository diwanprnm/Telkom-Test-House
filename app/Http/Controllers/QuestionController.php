<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Question;
use App\Logs;

use Auth;
use Response;
use Session;
use Input;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class QuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private const SEARCH = 'search';
    private const QUESTION = "Question";
    private const MESSA = 'message';
    private const ADMIN = '/admin/question';
    private const ERR = 'error';
    //test question

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
                $data = Question::whereNotNull('created_at')
                    ->where('name','like','%'.$search.'%')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Question Category";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = this::QUESTION;
                    $logs->save();
            }else{
                $query = Question::whereNotNull('created_at'); 
                
                $data = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.question.index')
                ->with($this::MESSA, $message)
                ->with('data', $data)
                ->with($this::SEARCH, $search) ;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.question.create');
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
		$question = new Question;
		$question->id = Uuid::uuid4();
		$question->name = $request->input('name');
		$question->is_active = 1;
	  
		$question->created_by = $currentUser->id;
		$question->updated_by = $currentUser->id; 

		try{
			$question->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create Question Category";
            $logs->data = $question;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::QUESTION;
            $logs->save();
			
            Session::flash($this::MESSA, 'question successfully created');
			return redirect($this::ADMIN);
		} catch(Exception $e){
			Session::flash($this::ERR, 'Save failed');
			return redirect('/admin/question/create');
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
        $data = Question::find($id);

        return view('admin.question.edit')
            ->with('data', $data);
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

        $data = Question::find($id);
        $olddata = $data; 

        if ($request->has('name')){
            $data->name = $request->input('name');
        } 
        if ($request->has('is_active')){
            $data->is_active = $request->input('is_active');
        } 
        $data->updated_by = $currentUser->id; 
        try{
            $data->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Update Question Category";
            $logs->data = $olddata;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::QUESTION;
            $logs->save();

            Session::flash($this::MESSA, 'Question successfully updated');
            return redirect($this::ADMIN);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
            return redirect('/admin/question/'.$data->id.'/edit');
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
        $data = Question::find($id);
        $olddata = $data;
        $currentUser = Auth::user();
        if ($data){
            try{
                $data->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Question Category";
                $logs->data = $olddata;
                $logs->created_by = $currentUser->id;
                $logs->page = $this::QUESTION;
                $logs->save();

                Session::flash($this::MESSA, 'Question successfully deleted');
                return redirect($this::ADMIN);
            }catch (Exception $e){
                Session::flash($this::ERR, 'Delete failed');
                return redirect($this::ADMIN);
            }
        }else{
             Session::flash($this::ERR, 'Question Not Found');
                return redirect($this::ADMIN);
        }
    }
}
