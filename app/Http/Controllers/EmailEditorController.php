<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailEditor;
use App\Services\Logs\LogService;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use Session;
use Auth;

use Ramsey\Uuid\Uuid;

use App\Http\Requests;

class EmailEditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.email_editors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $currentUser = Auth::user();
        
        $email = new EmailEditor;
        $email->id = Uuid::uuid4();
        $email->name = $request->input('name');
        $email->subject = $request->input('subject');
        $email->content = $request->input('content');
        $email->dir_name = $request->input('dir_name');
        // $email->signature = $request->input('signature');
        $email->created_by = $currentUser->id;
        $email->updated_by = $currentUser->id;

        try{
            $email->save(); 
 
            $logService = new LogService();  
            $logService->createLog('Create Email',"EMAIL EDITOR",json_encode($email));
         
            Session::flash('message', 'Email successfully created');
        }catch(Exception $e){
            Session::flash('error', "Email Failed created");
            return redirect('/admin/email_editors/create')->withInput();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = EmailEditor::find($id);

        return view('admin.email_editors.edit')
            ->with('data', $email);
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
        $email = EmailEditor::find($id);
        $oldData = $email;

        if ($request->has('name')){
            $email->name = $request->input('name');
        }
        if ($request->has('dir_name')) {
            $email->dir_name = $request->input('dir_name');
        }
        if ($request->has('subject')){
            $email->subject = $request->input('subject');
        }
        if ($request->has('content')){
            $email->content = $request->input('content');
        }
        if ($request->has('signature')){
            $email->signature = $request->input('signature');
        }

        $email->updated_by = $currentUser->id;

        try{
            $email->save();

            $logService = new LogService();  
            $logService->createLog('Update Email',"EmailEditors",json_encode($oldData));
            
            Session::flash('message', 'Email successfully updated');
            return redirect('/admin/email_editors');
        } catch(Exception $e){
            Session::flash('error', 'email failed to updated');
            return redirect('/admin/email_editors'.'/'.$email->id.'/edit');
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
        //
    }
}
