<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Certification;
use App\Logs;

use Auth;
use Session;
use Image;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CertificationController extends Controller
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
                $certifications = Certification::whereNotNull('created_at')
                    ->where('title','like','%'.$search.'%')
                    ->where('type',1)
                    ->orderBy('created_at')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Certification";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = "CERTIFICATION";
                    $logs->save();
            }else{
                $certifications = Certification::whereNotNull('created_at')
                    ->where('type',1)
                    ->orderBy('created_at')
                    ->paginate($paginate);
            }
            
            if (count($certifications) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.certification.index')
                ->with('message', $message)
                ->with('data', $certifications)
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
        return view('admin.certification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		// echo"<pre>";print_r($request->file('image')->getRealPath());exit;
		// echo"<pre>";print_r(pathinfo($request->file('image')->getClientOriginalName()));exit;
		// $size = getimagesize($filename);
		// list($width, $height) = $request->file('image')->getClientSize();
		// $image_info = getimagesize($request->file('image'));
		// $image_width = $image_info[0];
		// $image_height = $image_info[1];
		// echo $image_width;
		// echo $image_height;exit;
		// echo"<pre>";print_r($request->file('image')->getClientSize());exit;
        $currentUser = Auth::user();

        $certification = new Certification;
        $certification->id = Uuid::uuid4();
        $certification->title = $request->input('title');
        if ($request->hasFile('image')) {
                $image_info = getimagesize($request->file('image'));
                $image_width = $image_info[0];
                $image_height = $image_info[1];
                $image = $request->file('image');
            $name_file = 'cert_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/certification';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $certification->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/certification/create');
            }
        }
        
        $certification->is_active = $request->input('is_active');
        $certification->type = 1;
        $certification->created_by = $currentUser->id;
		$certification->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $certification->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Certification";
            $logs->data = $certification;
            $logs->created_by = $currentUser->id;
            $logs->page = "CERTIFICATION";
            $logs->save();

            Session::flash('message', 'Certification successfully created');
            return redirect('/admin/certification');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/certification/create');
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
        $certification = Certification::find($id);

        return view('admin.certification.edit')
            ->with('data', $certification);
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

        $certification = Certification::find($id);
        $oldData = $certification;
        if ($request->has('title')){
            $certification->title = $request->input('title');
        }
        if ($request->has('is_active')){
            $certification->is_active = $request->input('is_active');
        }
        if ($request->file('image')) {
            $name_file = 'cert_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/certification';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $certification->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/certification/create');
            }
        }

        $certification->updated_by = $currentUser->id;
		$certification->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $certification->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Certification";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "CERTIFICATION";
            $logs->save();

            Session::flash('message', 'Certification successfully updated');
            return redirect('/admin/certification');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/certification/'.$certification->id.'edit');
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
        $certification = Certification::find($id);
        $oldData = $certification;
        $currentUser = Auth::user();
        if ($certification){
            try{
                $certification->delete();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Certification";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "CERTIFICATION";
                $logs->save();

                Session::flash('message', 'Certification successfully deleted');
                return redirect('/admin/certification');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/certification');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Certification::autocomplet($query);
        return response($respons_result);
    }
}
