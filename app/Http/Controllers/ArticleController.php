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
    private const SEARCH = 'search';
    private const ERROR = 'error';
    private const MESSAGE = 'message';
    private const TITLE = 'title';
    private const DESCRIPTION = 'description';
    private const DESCRIPTION_ENGLISH = 'description_english';
    private const IS_ACTIVE = 'is_active';
    private const ADMIN_ARTICLE = '/admin/article';
        
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
            $search = trim($request->input($this::SEARCH));
            
            if ($search != null){
                $articles = Article::whereNotNull('created_at')
                    ->where($this::TITLE,'like','%'.$search.'%')
                    ->orderBy($this::TITLE)
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
                    ->orderBy($this::TITLE)
                    ->paginate($paginate);
            }
            
            if (count($articles) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.article.index')
                ->with($this::MESSAGE, $message)
                ->with('data', $articles)
                ->with($this::SEARCH, $search);
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
        $article->title = $request->input($this::TITLE);
        $article->type = $request->input('type');
        $article->description = $request->input($this::DESCRIPTION);
        $article->description_english = $request->input($this::DESCRIPTION_ENGLISH);
        $article->is_active = $request->input($this::IS_ACTIVE);
        $article->created_by = $currentUser->id;
        $article->updated_by = $currentUser->id;

        try{
            $article->save();
            Session::flash($this::MESSAGE, 'Article successfully created');
            return redirect($this::ADMIN_ARTICLE);
        } catch(\Exception $e){
            Session::flash($this::ERROR, 'Save failed');
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

        if ($request->has($this::TITLE)){
            $article->title = $request->input($this::TITLE);
        }
        if ($request->has($this::DESCRIPTION)){
            $article->description = $request->input($this::DESCRIPTION);
        }
        if ($request->has($this::DESCRIPTION_ENGLISH)){
            $article->description_english = $request->input($this::DESCRIPTION_ENGLISH);
        }
        if ($request->has('type')){
            $article->type = $request->input('type');
        }
        if ($request->has($this::IS_ACTIVE)){
            $article->is_active = $request->input($this::IS_ACTIVE);
        }

        $article->updated_by = $currentUser->id;

        try{
            $article->save();
            Session::flash($this::MESSAGE, 'Article successfully updated');
            return redirect($this::ADMIN_ARTICLE);
        } catch(Exception $e){
            Session::flash($this::ERROR, 'Save failed');
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
                
                Session::flash($this::MESSAGE, 'Article successfully deleted');
                return redirect($this::ADMIN_ARTICLE);
            }catch (Exception $e){
                Session::flash($this::ERROR, 'Delete failed');
                return redirect($this::ADMIN_ARTICLE);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Article::autocomplet($query);
        return response($respons_result);
    }
}
