<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Article;
use App\Logs;
use App\Services\Logs\LogService;

use Auth;
use Session;
use Validator;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ArticleController extends Controller
{
    private const ADMIN_ARTICLE = '/admin/article';
    private const DESCRIPTION = 'description';
    private const DESCRIPTION_ENGLISH = 'description_english';
    private const ERROR = 'error';
    private const IS_ACTIVE = 'is_active';
    private const MESSAGE = 'message';
    private const SEARCH = 'search';
    private const TITLE = 'title';
    private const TYPE = 'type';
    private const REQUIRED = 'required';
        

    public function __construct()
    {
        $this->middleware('auth.admin');
    }


    public function index(Request $request)
    {
        $articles = Article::whereNotNull('created_at');
        $logService = new LogService();
        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        
        if ($search){
            $articles = $articles->where(self::TITLE,'like','%'.$search.'%');
            $logService->createLog('Search Article', 'ARTICLE',json_encode(array(self::SEARCH=>$search)) );
        }

        $articles = $articles->orderBy(self::TITLE)
                        ->paginate($paginate);
        
        if (count($articles) == 0){ $message = 'Data not found'; }
        
        return view('admin.article.index')
            ->with(self::MESSAGE, $message)
            ->with('data', $articles)
            ->with(self::SEARCH, $search);
    }

    
    public function create()
    {
        return view('admin.article.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            self::TITLE => self::REQUIRED,
            self::TYPE => self::REQUIRED,
            self::DESCRIPTION => self::REQUIRED,
            self::DESCRIPTION_ENGLISH => self::REQUIRED,
            self::IS_ACTIVE => 'required|boolean',
        ]);
        $currentUser = Auth::user();

        $article = new Article;
        $article->id = Uuid::uuid4();
        $article->title = $request->input(self::TITLE);
        $article->type = $request->input(self::TYPE);
        $article->description = $request->input(self::DESCRIPTION);
        $article->description_english = $request->input(self::DESCRIPTION_ENGLISH);
        $article->is_active = $request->input(self::IS_ACTIVE,0);
        $article->created_by = $currentUser->id;
        $article->updated_by = $currentUser->id;

        try{
            $article->save();
            Session::flash(self::MESSAGE, 'Article successfully created');
            return redirect(self::ADMIN_ARTICLE);
        } catch(\Exception $e){
            return redirect('/admin/article/create')->with(self::ERROR, 'Save failed');
        }
    }


    public function edit($id)
    {
        $article = Article::find($id);

        return view('admin.article.edit')
            ->with('data', $article);
    }

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            self::IS_ACTIVE => 'boolean',
        ]);

        $currentUser = Auth::user();

        $article = Article::find($id);

        if ($request->has(self::TITLE)){
            $article->title = $request->input(self::TITLE);
        }
        if ($request->has(self::DESCRIPTION)){
            $article->description = $request->input(self::DESCRIPTION);
        }
        if ($request->has(self::DESCRIPTION_ENGLISH)){
            $article->description_english = $request->input(self::DESCRIPTION_ENGLISH);
        }
        if ($request->has(self::TYPE)){
            $article->type = $request->input(self::TYPE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $article->is_active = $request->input(self::IS_ACTIVE);
        }

        $article->updated_by = $currentUser->id;

        try{
            $article->save();
            Session::flash(self::MESSAGE, 'Article successfully updated');
            return redirect(self::ADMIN_ARTICLE);
        } catch(Exception $e){
            return redirect('/admin/article/'.$article->id.'/edit')->with(self::ERROR, 'Save failed');
        }
    }

    
    public function destroy($id)
    {
        $article = Article::find($id);

        if ($article){
            try{
                $article->delete();
                
                Session::flash(self::MESSAGE, 'Article successfully deleted');
                return redirect(self::ADMIN_ARTICLE);
            }catch (Exception $e){
                return redirect(self::ADMIN_ARTICLE)->with(self::ERROR, 'Delete failed');
            }
        }
    }
    
    
	// public function autocomplete($query) { -
    //     $respons_result = Article::autocomplet($query)
    //     return response($respons_result)
    // } -
}
