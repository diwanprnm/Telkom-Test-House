<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ExaminationLab;
use App\Logs;

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
                $labs = ExaminationLab::whereNotNull('created_at')
                    ->where('name','like','%'.$search.'%')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Examination Lab";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = "EXAMINATION LABS";
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
                ->with('message', $message)
                ->with('data', $labs)
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
        $labs->lab_code = $request->input('lab_code');
        $labs->description = $request->input('description');
        $labs->is_active = $request->input('is_active');
        $labs->created_by = $currentUser->id;
        $labs->updated_by = $currentUser->id;

        try{
            $labs->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Examination Lab";
            $logs->data = $labs;
            $logs->created_by = $currentUser->id;
            $logs->page = "EXAMINATION LABS";
            $logs->save();

            Session::flash('message', 'Labs successfully created');
            return redirect('/admin/labs');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
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
        if ($request->has('lab_code')){
            $labs->lab_code = $request->input('lab_code');
        }
        if ($request->has('description')){
            $labs->description = $request->input('description');
        }
        if ($request->has('is_active')){
            $labs->is_active = $request->input('is_active');
        }

        $labs->updated_by = $currentUser->id;

        try{
            $labs->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Examination Lab";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "EXAMINATION LABS";
            $logs->save();

            Session::flash('message', 'Labs successfully updated');
            return redirect('/admin/labs');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
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
                $labs->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Examination Lab";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "EXAMINATION LABS";
                $logs->save();

                Session::flash('message', 'Labs successfully deleted');
                return redirect('/admin/labs');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/labs');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = ExaminationLab::autocomplet($query);
        return response($respons_result);
    }
}
