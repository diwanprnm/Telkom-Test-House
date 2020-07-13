<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Feedback;
use App\User;
use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;
use App\Services\MyHelper;

use Auth;
use Session;
use Mail;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class FeedbackController extends Controller
{
    private const SEARCH = 'search';
    private const FEEDBACK = 'FEEDBACK';
    private const BEFORE_DATE = 'before_date';
    private const STATUS = 'status';
    private const AFTER_DATE = 'after_date';
    private const MESSAGE = 'message';
    private const ADMIN_FEEDBACK = '/admin/feedback';

    //$this::ADMIN_CERTIFICATION
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
        $logService = New LogService();
        $query = Feedback::whereNotNull('created_at');
        $search = MyHelper::filterDefault($request->input($this::SEARCH));
        $message = null;

        $queryFilter = New QueryFilter($request,$query);
        if ($search){
            $queryFilter = New QueryFilter($request, $query->where('subject','like','%'.$search.'%'));
            $logService->createLog('Search Feedback',$this::FEEDBACK, json_encode(array($this::SEARCH=>$search)) );
        }else if ($request->has($this::STATUS && $request->input($this::STATUS) != 'all')){
            $queryFilter = New QueryFilter($request, $query->where($this::STATUS, $request->get($this::STATUS)));
        }

        $feedbacks = $queryFilter
                        ->beforeDate(DB::raw('DATE(created_at)'))
                        ->afterDate(DB::raw('DATE(created_at)'))
                        ->getSortedAndOrderedData('updated_at', 'desc')
                        ->query
                        ->paginate(10);
        
        if (count($feedbacks) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.feedback.index')
            ->with('data', $feedbacks)
            ->with($this::MESSAGE, $message)
            ->with($this::SEARCH, $search)
            ->with($this::STATUS, MyHelper::filterDefault($request->input($this::STATUS)))
            ->with($this::BEFORE_DATE, MyHelper::filterDefault($request->input('before')))
            ->with($this::AFTER_DATE, MyHelper::filterDefault($request->input('after')))
            ->with('sort_by', MyHelper::filterDefault($request->input('sort_by')))
            ->with('sort_type', MyHelper::filterDefault($request->input('sort_type')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reply($id)
    {
        $data = Feedback::findOrFail($id);
        return view('admin.feedback.create')
            ->with('data', $data);
    }
	
	public function destroy($id)
    {
        $logService = new LogService();
        $feedback = Feedback::find($id);
        $oldData = clone $feedback;
        if ($feedback){
            try{
                $feedback->delete();
                $logService->createLog('Delete Feedback',$this::FEEDBACK, $oldData );
                Session::flash($this::MESSAGE, 'Feedback successfully deleted');
                return redirect($this::ADMIN_FEEDBACK);
            }catch (Exception $e){
                return redirect($this::ADMIN_FEEDBACK)->with('error', 'Delete failed');
            }
        }
    }

    /**
     * Send an e-mail feedback to the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function sendEmailReplyFeedback(Request $request)
    {
        $data = Feedback::findOrFail($request->get('id'));
        $logService = new LogService();
        $currentUser = Auth::user();	
		$data->updated_by = $currentUser->id;
        $data->status = true;
        $data->save();

        $logService->createLog('Reply Feedback',$this::FEEDBACK, json_encode(array('data' => $request->get('description'))) );

        Session::flash($this::MESSAGE, 'Reply sudah terkirim');
        return redirect($this::ADMIN_FEEDBACK);
    }
	
	public function autocomplete($query) {
        $respons_result = Feedback::adm_feedback_autocomplet($query);
        return response($respons_result);
    }
}
