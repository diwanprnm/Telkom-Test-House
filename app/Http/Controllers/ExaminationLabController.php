<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ExaminationLab;
use App\Logs;
use App\ManageProcess;

use Auth;
use Session;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ExaminationLabController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private const MESSAGE = 'message';
    private const SEARCH = 'search';
    private const EXAM = "EXAMINATION LABS";
    private const LAB = 'lab_code';
    private const INIT = 'lab_init';
    private const DESC = 'description';
    private const ACTIVE = 'is_active';
    private const LABS = '/admin/labs';
    private const ERR = 'error';
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
                $labs = ExaminationLab::whereNotNull('created_at')
                    ->where('name','like','%'.$search.'%')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Examination Lab";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = $this::EXAM;
                    $logs->save();
            }else{
                $labs = ExaminationLab::whereNotNull('created_at')
                    ->orderBy('name')
                    ->paginate($paginate);
            }
            
            if (count($labs) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.labs.index')
                ->with($this::MESSAGE, $message)
                ->with('data', $labs)
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
        return view('admin.labs.create');
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

        $labs = new ExaminationLab;
        $labs->id = Uuid::uuid4();
        $labs->name = $request->input('name');
        $labs->lab_code = $request->input($this::LAB);
        $labs->lab_init = $request->input($this::INIT);
        $labs->description = $request->input($this::DESC);
        $labs->is_active = $request->input($this::ACTIVE);
        $labs->created_by = $currentUser->id;
        $labs->updated_by = $currentUser->id;

        try{
            $labs->save();

            for ($i=1; $i <= 4; $i++) { 
                $mp = new ManageProcess;
                $mp->exam_type_id = $i;
                $mp->lab_id = $labs->id;
                $mp->is_active = 1;

                $mp->created_by = $currentUser->id;
                $mp->updated_by = $currentUser->id;
                $mp->save();
            }

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Examination Lab";
            $logs->data = $labs;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::EXAM;
            $logs->save();

            Session::flash($this::MESSAGE, 'Labs successfully created');
            return redirect($this::LABS);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
            return redirect('/admin/labs/create');
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
        $labs = ExaminationLab::find($id);

        return view('admin.labs.edit')
            ->with('data', $labs);
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

        $labs = ExaminationLab::find($id);
        $oldData = $labs;

        if ($request->has('name')){
            $labs->name = $request->input('name');
        }
        if ($request->has($this::LAB)){
            $labs->lab_code = $request->input($this::LAB);
        }
        if ($request->has($this::INIT)){
            $labs->lab_init = $request->input($this::INIT);
        }
        if ($request->has($this::DESC)){
            $labs->description = $request->input($this::DESC);
        }
        if ($request->has($this::ACTIVE)){
            $labs->is_active = $request->input($this::ACTIVE);
        }
        if ($request->has('close_until')){
            $labs->close_until = $request->input('close_until');
        }
        if ($request->has('open_at')){
            $labs->open_at = $request->input('open_at');
        }

        $labs->updated_by = $currentUser->id;

        try{
            $labs->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Examination Lab";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = $this::EXAM;
            $logs->save();

            Session::flash($this::MESSAGE, 'Labs successfully updated');
            return redirect($this::LABS);
        } catch(Exception $e){
            Session::flash($this::ERR, 'Save failed');
            return redirect('/admin/labs/'.$labs->id.'edit');
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
        $labs = ExaminationLab::find($id);
        $oldData = $labs;
        $currentUser = Auth::user();

        if ($labs){
            try{
                ManageProcess::where('lab_id', '=' ,''.$id.'')->delete();
                $labs->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Examination Lab";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = $this::EXAM;
                $logs->save();

                Session::flash($this::MESSAGE, 'Labs successfully deleted');
                return redirect($this::LABS);
            }catch (Exception $e){
                Session::flash($this::ERR, 'Delete failed');
                return redirect($this::LABS);
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = ExaminationLab::autocomplet($query);
        return response($respons_result);
    }
}
