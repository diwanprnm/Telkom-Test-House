<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Article;
use App\Logs;

use Auth;
use Session;
use Validator;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ArticleController extends Controller
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
            
            if ($search != null){
                $articles = Article::whereNotNull('created_at')
                    ->where('title','like','%'.$search.'%')
                    ->orderBy('title')
                    ->paginate($paginate);

                $logs = new Logs;
                $logs->id = Uuid::uuid4();
                $logs->user_id = $currentUser->id;
                $logs->user_id = $currentUser->id;
                $logs->action = "Search Article"; 
                $dataSearch = array("search"=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = "ARTICLE";
                $logs->save();

            }else{
                $articles = Article::whereNotNull('created_at')
                    ->orderBy('title')
                    ->paginate($paginate);
            }
            
            if (count($articles) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.article.index')
                ->with('message', $message)
                ->with('data', $articles)
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
        return view('admin.article.create');
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

        $article = new Article;
        $article->id = Uuid::uuid4();
        $article->title = $request->input('title');
        $article->type = $request->input('type');
        $article->description = $request->input('description');
        $article->description_english = $request->input('description_english');
        $article->is_active = $request->input('is_active');
        $article->created_by = $currentUser->id;
        $article->updated_by = $currentUser->id;

        try{
            $article->save();
            Session::flash('message', 'Article successfully created');
            return redirect('/admin/article');
        } catch(\Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/article/create');
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
        $article = Article::find($id);

        return view('admin.article.edit')
            ->with('data', $article);
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

        $article = Article::find($id);

        if ($request->has('title')){
            $article->title = $request->input('title');
        }
        if ($request->has('description')){
            $article->description = $request->input('description');
        }
        if ($request->has('description_english')){
            $article->description_english = $request->input('description_english');
        }
        if ($request->has('type')){
            $article->type = $request->input('type');
        }
        if ($request->has('is_active')){
            $article->is_active = $request->input('is_active');
        }

        $article->updated_by = $currentUser->id;

        try{
            $article->save();
            Session::flash('message', 'Article successfully updated');
            return redirect('/admin/article');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/article/'.$article->id.'/edit');
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
        $article = Article::find($id);

        if ($article){
            try{
                $article->delete();
                
                Session::flash('message', 'Article successfully deleted');
                return redirect('/admin/article');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/article');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Article::autocomplet($query);
        return response($respons_result);
    }
}
