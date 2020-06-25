<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Feedback;
use App\User;
use App\Logs;

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
        $currentUser = Auth::user();

        if (!$currentUser){
            return false;
        }

        
        $message = null;
        $paginate = 10;
        $search = trim($request->input($this::SEARCH));
        $status = '';
        $before = null;
        $after = null;

        $sort_by = 'updated_at';
        $sort_type = 'desc';

        if ($search != null){
            $query = Feedback::whereNotNull('created_at')
                ->where('subject','like','%'.$search.'%');

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Search Feedback";  
                $dataSearch = array($this::SEARCH=>$search);
                $logs->data = json_encode($dataSearch);
                $logs->created_by = $currentUser->id;
                $logs->page = $this::FEEDBACK;
                $logs->save();
        }else{
            $query = Feedback::whereNotNull('created_at');

            if ($request->has($this::STATUS)){
                $status = $request->get($this::STATUS);
                if($request->input($this::STATUS) != 'all'){
                    $query->where($this::STATUS, $request->get($this::STATUS));
                }
            }

        }
        if ($request->has($this::BEFORE_DATE)){
            $query->where(DB::raw('DATE(created_at)'), '<=', $request->get($this::BEFORE_DATE));
            $before = $request->get($this::BEFORE_DATE);
        }

        if ($request->has($this::AFTER_DATE)){
            $query->where(DB::raw('DATE(created_at)'), '>=', $request->get($this::AFTER_DATE));
            $after = $request->get($this::AFTER_DATE);
        }

        $feedbacks = $query->orderBy($sort_by, $sort_type)
                        ->paginate($paginate);
        
        if (count($feedbacks) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.feedback.index')
            ->with($this::MESSAGE, $message)
            ->with('data', $feedbacks)
            ->with($this::SEARCH, $search)
            ->with($this::STATUS, $status)
            ->with($this::BEFORE_DATE, $before)
            ->with($this::AFTER_DATE, $after)
            ->with('sort_by', $sort_by)
            ->with('sort_type', $sort_type);
        
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
        $feedback = Feedback::find($id);
        $oldData = $feedback;
        $currentUser = Auth::user();
        if ($feedback){
            try{
                $feedback->delete();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Feedback";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = $this::FEEDBACK;
                $logs->save();

                Session::flash($this::MESSAGE, 'Feedback successfully deleted');
                return redirect($this::ADMIN_FEEDBACK);
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect($this::ADMIN_FEEDBACK);
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
		
        $currentUser = Auth::user();		
		$data->updated_by = $currentUser->id;
        $data->status = true;
        $data->save();

        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "Reply Feedback";  
        $dataUpdate = array('data' => $request->get('description'));
        $logs->data = json_encode($dataUpdate);
        $logs->created_by = $currentUser->id;
        $logs->page = $this::FEEDBACK;
        $logs->save();

        Session::flash($this::MESSAGE, 'Reply sudah terkirim');
        return redirect($this::ADMIN_FEEDBACK);
    }
	
	public function autocomplete($query) {
        $respons_result = Feedback::adm_feedback_autocomplet($query);
        return response($respons_result);
    }
}
