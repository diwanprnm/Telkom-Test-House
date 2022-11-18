<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailEditor;
use App\Services\Logs\LogService;
use Illuminate\Support\Facades\DB;
use App\Services\FileService;

use Session;
use Auth;
use Exception;

use Ramsey\Uuid\Uuid;

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
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $logService = new LogService();
        if (!$currentUser) {return redirect('login');}
        
        $limit = 100;

        $search = trim($request->search);

        $query = EmailEditor::whereNotNull('created_at');

        if ($search) {
            $query->where('name','like','%'.strtolower($search).'%')
            ->orWhere('subject', 'like', '%'.strtolower($search).'%')
            ->orWhere('dir_name', 'like', '%'.strtolower($search).'%');
            $logService->createLog('Search Email Editor', 'Email Editors', json_encode(array("search"=>$search)) );
        }

        $data_emails = $query->orderBy('created_at', 'desc')->paginate($limit);

        return view('admin.email_editors.index')
            ->with('data', $data_emails)
            ->with('search', $search);
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
        $email->signature = $request->input('signature');
        $email->logo = $request->input('logo');
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
     * Update the all logo and signature in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLogoSignature(Request $request)
    {
        $currentUser = Auth::user();
        $oldEmails = EmailEditor::whereNotNull('created_at');
        $updateEmails = DB::table('email_editors');
        $updateFields = array();

        if ($request->file('logo')) {
            $fileService = new FileService();
            $fileProperties = array(
                'path' => 'logo/'
            );
            $fileService->upload($request->file('logo'), $fileProperties);
            $updateFields += ['logo' => $fileService->getFileName()];
        }

        if ($request->has('signature')) {
            $updateFields += ['signature' => $request['signature']];
        }
        $updateFields += ['updated_by' => $currentUser->id];

        try {
            $updateEmails->update($updateFields);
            $logService = new LogService();  
            $logService->createLog('Update Email',"EmailEditors",json_encode($oldEmails));
            
            Session::flash('message', 'Logo or Signature Successfully Updated');
            return redirect('/admin/email_editors');
        }catch(Exception $e){
            Session::flash('error', 'Logo or Signature Failed to Updated');
            return redirect('/admin/email_editors');
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
		$email = EmailEditor::find($id);

        if ($email){
            try{
                $oldEmail = clone $email;
                $email->delete();
                
                $logService = new LogService();
                $logService->createLog('Delete Email', 'Email Editor', $oldEmail);
                
                Session::flash('message', 'Email successfully deleted');
                return redirect('admin/email_editors');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('admin/email_editors');
            }
        }
    }
}
