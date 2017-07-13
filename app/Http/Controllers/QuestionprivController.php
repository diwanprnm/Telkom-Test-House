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
			
			$questionpriv = Questionpriv::whereNotNull('created_at')
				->with('user')
				->with('question');

            if ($search != null){
				$questionpriv->where(function($qry) use($search){
                    $qry->whereHas('user', function ($q) use ($search){
							return $q->where('name', 'like', '%'.strtolower($search).'%')
									->orWhere('email', 'like', '%'.strtolower($search).'%');
						})
					->orWhereHas('question', function ($q) use ($search){
							return $q->where('name', 'like', '%'.strtolower($search).'%');
						});
                });
            }
			$data = $questionpriv->orderBy('user_id')
                    ->paginate($paginate);
            
            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.questionpriv.index')
                ->with('message', $message)
                ->with('data', $data)
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
        $user = User::where('role_id','=','1')->get();
        $question = Question::where('is_active','=','1')->get();

        return view('admin.questionpriv.create')
            ->with('question', $question)
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
		$questionpriv = Questionpriv::where('user_id','=',$request->input('user_id'))->get();

		if(count($questionpriv) == 0 AND count($request->input('check-privilege')) > 0)
		{
			$currentUser = Auth::user();
			for($i=0;$i<count($request->input('check-privilege'));$i++){
				$questionpriv = new Questionpriv;
				$questionpriv->user_id = $request->input('user_id');
				$questionpriv->question_id = $request->input('check-privilege')[$i];
				
				$questionpriv->created_by = $currentUser->id;
				$questionpriv->updated_by = $currentUser->id;

				try{
					$questionpriv->save();
					// Session::flash('message', 'User successfully created');
					// return redirect('/admin/questionpriv');
				} catch(\Exception $e){
					// Session::flash('error', 'Save failed');
					// return redirect('/admin/questionpriv/create')
								// ->withInput();
				}
			}
			Session::flash('message', 'User successfully created');
					return redirect('/admin/questionpriv');
		}else{
			Session::flash('error', 'User Existing or No Privilege selected');
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
        $questionpriv = Questionpriv::where('user_id','=',$id)
					->with('user')
					->with('question')
					->get();
        $question = Question::where('is_active','=','1')->get();

        return view('admin.questionpriv.edit')
			->with('question', $question)
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
		if(count($request->input('check-privilege')) > 0)
		{
			$currentUser = Auth::user();
			$questionpriv = Questionpriv::where('user_id','=',$id)->delete();
			for($i=0;$i<count($request->input('check-privilege'));$i++){
				$questionpriv = new Questionpriv;
				$questionpriv->user_id = $id;
				$questionpriv->question_id = $request->input('check-privilege')[$i];
				
				$questionpriv->created_by = $currentUser->id;
				$questionpriv->updated_by = $currentUser->id;

				try{
					$questionpriv->save();
					// Session::flash('message', 'User successfully created');
					// return redirect('/admin/questionpriv');
				} catch(\Exception $e){
					// Session::flash('error', 'Save failed');
					// return redirect('/admin/questionpriv/create')
								// ->withInput();
				}
			}
			Session::flash('message', 'User successfully updated');
					return redirect('/admin/questionpriv');
		}else{
			Session::flash('error', 'No Privilege selected');
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
		$questionpriv = Questionpriv::where('user_id','=',$id);

        if ($questionpriv){
            try{
                $questionpriv->delete();
                
                Session::flash('message', 'Privilege successfully deleted');
                return redirect('/admin/questionpriv');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/questionpriv');
            }
        }
    }
}
