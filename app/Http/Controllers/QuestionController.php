<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Question;
use App\Logs;
use App\Services\Logs\LogService;

use Auth;
use Response;
use Session;
use Input;
use Exception;

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

        if (!$currentUser){ return redirect('login');}

        $message = null;
        $paginate = 10;
        $search = trim($request->input($this::SEARCH));
        
        if ($search){
            $data = Question::whereNotNull('created_at')
                ->where('name','like','%'.$search.'%')
                ->orderBy('name')
                ->paginate($paginate)
            ;

            $logService = new LogService();
            $logService->createLog( "Search Question Category",self::QUESTION, json_encode( array("search"=>$search)) );

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

            $logService = new LogService();
            $logService->createLog( "Create Question Category",self::QUESTION,$question);
    
            
			
            Session::flash($this::MESSA, 'question successfully created');
			return redirect($this::ADMIN);
		} catch(Exception $e){ return redirect('/admin/question/create')->with($this::ERR, 'Save failed');
		}
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

            
            $logService = new LogService();
            $logService->createLog( "Update Question Category",self::QUESTION,$olddata);
            
            Session::flash($this::MESSA, 'Question successfully updated');
            return redirect($this::ADMIN);
        } catch(Exception $e){ return redirect('/admin/question/'.$data->id.'/edit')->with($this::ERR, 'Save failed');
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
        Auth::user();
        if ($data){
            try{
                $data->delete();
          

                $logService = new LogService();
                $logService->createLog( "Delete Question Category",self::QUESTION,$olddata);

                Session::flash($this::MESSA, 'Question successfully deleted');
                return redirect($this::ADMIN);
            }catch (Exception $e){ return redirect($this::ADMIN)->with($this::ERR, 'Delete failed');
            }
        }else{
             Session::flash($this::ERR, 'Question Not Found');
                return redirect($this::ADMIN);
        }
    }
}
