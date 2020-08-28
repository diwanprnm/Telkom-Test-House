<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\QuestionerQuestion;
use App\QuestionerDynamic;
use App\Logs;
use App\Services\Logs\LogService;

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

    private const SEARCH = 'search';
    private const QUESTION = 'question';
    private const MESSA = 'message';
    private const ORDER = 'order_question';
    private const ERR = 'error';
    private const ADMIN='/admin/questionerquestion';
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
            $paginate = 100;
            $search = trim($request->input($this::SEARCH));
            
            if ($search != null){
                $data = QuestionerQuestion::whereNotNull('created_at')
                    ->where($this::QUESTION,'like','%'.$search.'%')
                    ->orderBy($this::ORDER)
                    ->paginate($paginate);

                    $logService = new LogService();
                    $logService->createLog("Search Question of Questioner","Question of Questioner", json_encode( array("search"=>$search)) );
    
            }else{
                $query = QuestionerQuestion::whereNotNull('created_at'); 
                
                $data = $query->orderBy($this::ORDER)
                            ->paginate($paginate);
            }
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.questionerquestion.index')
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
		$question->question = $request->input($this::QUESTION);
		$question->order_question = $request->input($this::ORDER);
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

            $logService = new LogService();
            $logService->createLog("Create Question of Questioner","Question",$question );
			
            Session::flash($this::MESSA, 'question successfully created');
			return redirect($this::ADMIN);
		} catch(Exception $e){
			Session::flash($this::ERR, 'Save failed');
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
		
        if ($request->has($this::QUESTION)){
            $data->question = $request->input($this::QUESTION);
        } 
        if ($request->has($this::ORDER)){
            $data->order_question = $request->input($this::ORDER);
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

            $logService = new LogService();
            $logService->createLog("Update Question of Questioner","Question of Questioner",$olddata );

            Session::flash($this::MESSA, 'Question successfully updated');
            return redirect($this::ADMIN);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
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
               
                $logService = new LogService();
                $logService->createLog("Delete Question of Questioner","Question",$olddata );

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
