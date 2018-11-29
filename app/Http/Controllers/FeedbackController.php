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

            }
            if ($request->has('before_date')){
                $query->where(DB::raw('DATE(created_at)'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $query->where(DB::raw('DATE(created_at)'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            $feedbacks = $query->orderBy($sort_by, $sort_type)
                            ->paginate($paginate);
            
            if (count($feedbacks) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.feedback.index')
                ->with('message', $message)
                ->with('data', $feedbacks)
                ->with('search', $search)
                ->with('status', $status)
                ->with('before_date', $before)
                ->with('after_date', $after)
                ->with('sort_by', $sort_by)
                ->with('sort_type', $sort_type);
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
                $logs->page = "FEEDBACK";
                $logs->save();

                Session::flash('message', 'Feedback successfully deleted');
                return redirect('/admin/feedback');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/feedback');
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

        // Mail::send('emails.feedback', array('data' => $request->get('description')), function ($m) use ($data) {
            // $m->to($data->email)->subject($data->subject);
        // });
		
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
