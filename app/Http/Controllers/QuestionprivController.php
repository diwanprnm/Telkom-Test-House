<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Questionpriv;
use App\User;
use App\Question;

use Auth;
use Session;

class QuestionprivController extends Controller
{
    private const ADMIN_QUESTIONPRIV = '/admin/questionpriv';
    private const CHECK_PRIVILEGE = 'check-privilege';
    private const ERROR = 'error';
    private const MESSAGE = 'message';
    private const QUESTION = 'question';
    private const REQUIRED = 'required';
    private const SEARCH = 'search';
    private const USER_ID = 'user_id';
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
        $dataNotFound = '';
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        
        $questionpriv = Questionpriv::whereNotNull('created_at')
            ->with('user')
            ->with(self::QUESTION);

        if ($search){
            $questionpriv->where(function($qry) use($search){
                $qry->whereHas('user', function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%')
                                ->orWhere('email', 'like', '%'.strtolower($search).'%');
                    })
                ->orWhereHas(self::QUESTION, function ($q) use ($search){
                        return $q->where('name', 'like', '%'.strtolower($search).'%');
                    });
            });
        }
        $data = $questionpriv->orderBy(self::USER_ID)
                ->paginate($paginate);
        
        if (count($data) == 0){
            $dataNotFound = 'Data not found';
        }
        
        return view('admin.questionpriv.index')
            ->with('dataNotFound', $dataNotFound)
            ->with('data', $data)
            ->with('search', $search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::where('role_id','=','1')->get();
        $question = Question::where('is_active','=','1')->get();

        return view('admin.questionpriv.create')
            ->with(self::QUESTION, $question)
            ->with('user', $user);
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
            self::USER_ID => self::REQUIRED,
        ]);

		$questionpriv = Questionpriv::where(self::USER_ID,'=',$request->input(self::USER_ID))->get();

		if(!count((array)$questionpriv) && count((array)$request->input(self::CHECK_PRIVILEGE)) )
		{
			$currentUser = Auth::user();
			for($i=0;$i<count($request->input(self::CHECK_PRIVILEGE));$i++){
				$questionpriv = new Questionpriv;
				$questionpriv->user_id = $request->input(self::USER_ID);
				$questionpriv->question_id = $request->input(self::CHECK_PRIVILEGE)[$i];
				$questionpriv->created_by = $currentUser->id;
				$questionpriv->updated_by = $currentUser->id;

                $questionpriv->save();
			}
			Session::flash(self::MESSAGE, 'User successfully created');
					return redirect(self::ADMIN_QUESTIONPRIV);
		}else{
			Session::flash(self::ERROR, 'User Existing or No Privilege selected');
				return redirect('/admin/questionpriv/create')
							->withInput();
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
        $questionpriv = Questionpriv::where(self::USER_ID,'=',$id)
					->with('user')
					->with(self::QUESTION)
					->get();
        $question = Question::where('is_active','=','1')->get();

        return view('admin.questionpriv.edit')
			->with(self::QUESTION, $question)
            ->with('data', $questionpriv);
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
		if(count($request->input(self::CHECK_PRIVILEGE)) > 0)
		{
			$currentUser = Auth::user();
            $questionpriv = Questionpriv::where(self::USER_ID,'=',$id);
            $questionpriv->delete();
			for($i=0;$i<count($request->input(self::CHECK_PRIVILEGE));$i++){
				$questionpriv = new Questionpriv;
				$questionpriv->user_id = $id;
				$questionpriv->question_id = $request->input(self::CHECK_PRIVILEGE)[$i];
				
				$questionpriv->created_by = $currentUser->id;
				$questionpriv->updated_by = $currentUser->id;

                $questionpriv->save();

			}
			Session::flash(self::MESSAGE, 'User successfully updated');
					return redirect(self::ADMIN_QUESTIONPRIV);
		}else{
			Session::flash(self::ERROR, 'No Privilege selected');
				return redirect('/admin/questionpriv/'.$id.'/edit')
							->withInput();
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
		$questionpriv = Questionpriv::where(self::USER_ID,'=',$id)->first();
        
        if (count($questionpriv)){
            try{
                $questionpriv->delete();
                
                Session::flash(self::MESSAGE, 'Privilege successfully deleted');
                return redirect(self::ADMIN_QUESTIONPRIV);
            }catch (Exception $e){ return redirect(self::ADMIN_QUESTIONPRIV)->with(self::ERROR, 'Delete failed');
            }
        }
        return redirect(self::ADMIN_QUESTIONPRIV)
            ->with(self::MESSAGE, 'Data not found');
    }
}
