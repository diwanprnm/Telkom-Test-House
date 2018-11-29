<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\QuestionerQuestion;
use App\QuestionerDynamic;
use App\Logs;

use Auth;
use Response;
use Session;
use Input;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class QuestionerQuestionController extends Controller
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
            $paginate = 100;
            $search = trim($request->input('search'));
            
            if ($search != null){
                $data = QuestionerQuestion::whereNotNull('created_at')
                    ->where('question','like','%'.$search.'%')
                    ->orderBy('order_question')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Question of Questioner";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "Question of Questioner";
                    $logs->save();
            }else{
                $query = QuestionerQuestion::whereNotNull('created_at'); 
                
                $data = $query->orderBy('order_question')
                            ->paginate($paginate);
            }
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.questionerquestion.index')
                ->with('message', $message)
                ->with('data', $data)
                ->with('search', $search) ;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$query = "
			SELECT order_question + 1 AS last_numb
			FROM questioner_questions WHERE is_active = 1
			ORDER BY last_numb DESC LIMIT 1
		";
		$data = DB::select($query);
		if (count($data) == 0){
			$last_numb = 1;
		}
		else{
			$last_numb = $data[0]->last_numb;
		}
		
        return view('admin.questionerquestion.create')
			->with('last_numb', $last_numb);
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
		$question = new QuestionerQuestion;
		$question->id = Uuid::uuid4();
		$question->question = $request->input('question');
		$question->order_question = $request->input('order_question');
		if($request->input('is_essay')){
			$question->is_essay = 1;
		}else{
			$question->is_essay = 0;
		}
		$question->is_active = 1;
	  
		$question->created_by = $currentUser->id;
		$question->updated_by = $currentUser->id; 

		try{
			$question->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create Question of Questioner";
            $logs->data = $question;
            $logs->created_by = $currentUser->id;
            $logs->page = "Question";
            $logs->save();
			
            Session::flash('message', 'question successfully created');
			return redirect('/admin/questionerquestion');
		} catch(Exception $e){
			Session::flash('error', 'Save failed');
			return redirect('/admin/questionerquestion/create');
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
        $data = QuestionerQuestion::find($id);

        return view('admin.questionerquestion.edit')
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

        $data = QuestionerQuestion::find($id);
        $olddata = $data; 
		
        if ($request->has('question')){
            $data->question = $request->input('question');
        } 
        if ($request->has('order_question')){
            $data->order_question = $request->input('order_question');
        } 
		if($request->input('is_essay')){
			$data->is_essay = 1;
		}else{
			$data->is_essay = 0;
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
            $logs->action = "Update Question of Questioner";
            $logs->data = $olddata;
            $logs->created_by = $currentUser->id;
            $logs->page = "Question of Questioner";
            $logs->save();

            Session::flash('message', 'Question successfully updated');
            return redirect('/admin/questionerquestion');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/questionerquestion/'.$data->id.'/edit');
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
        $data = QuestionerQuestion::find($id);
        $olddata = $data;
        $currentUser = Auth::user();
        if ($data){
            try{
				QuestionerDynamic::where('question_id', '=' ,''.$id.'')->delete();
                $data->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Question of Questioner";
                $logs->data = $olddata;
                $logs->created_by = $currentUser->id;
                $logs->page = "Question";
                $logs->save();

                Session::flash('message', 'Question successfully deleted');
                return redirect('/admin/questionerquestion');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/questionerquestion');
            }
        }else{
             Session::flash('error', 'Question Not Found');
                return redirect('/admin/questionerquestion');
        }
    }
}
