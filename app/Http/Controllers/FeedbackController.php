<?php

namespace App\Http\Controllers;

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
            $status = '';
            
            if ($search != null){
                $feedbacks = Feedback::whereNotNull('created_at')
                    ->where('subject','like','%'.$search.'%')
                    ->orderBy('created_by', 'desc')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Feedback";  
                    $dataSearch = array("search"=>$search);
                    $logs->data = json_encode($dataSearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "FEEDBACK";
                    $logs->save();
            }else{
                $query = Feedback::whereNotNull('created_at');

                if ($request->has('status')){
                    $status = $request->get('status');
					if($request->input('status') != 'all'){
						$query->where('status', $request->get('status'));
					}
                }

                $feedbacks = $query->orderBy('created_by', 'desc')
                                ->paginate($paginate);
            }
            
            if (count($feedbacks) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.feedback.index')
                ->with('message', $message)
                ->with('data', $feedbacks)
                ->with('search', $search)
                ->with('status', $status);
        }
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

    /**
     * Send an e-mail feedback to the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function sendEmailReplyFeedback(Request $request)
    {
        $data = Feedback::findOrFail($request->get('id'));

        Mail::send('emails.feedback', array('data' => $request->get('description')), function ($m) use ($data) {
            $m->to($data->email)->subject($data->subject);
        });
		
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
        $logs->page = "FEEDBACK";
        $logs->save();

        Session::flash('message', 'Reply sudah terkirim');
        return redirect('/admin/feedback');
    }
	
	public function autocomplete($query) {
        $respons_result = Feedback::adm_feedback_autocomplet($query);
        return response($respons_result);
    }
}
