<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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

class PopUpInformationController extends Controller
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
                $popupinformations = Certification::whereNotNull('created_at')
                    ->where('title','like','%'.$search.'%')
                    ->where('type',0)
                    ->orderBy('created_at')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Pop Up Information";
                    $logs->data = json_encode(array("search"=>$search));
                    $logs->created_by = $currentUser->id;
                    $logs->page = "CERTIFICATION";
                    $logs->save();
            }else{
                $popupinformations = Certification::whereNotNull('created_at')
                    ->where('type',0)
                    ->orderBy('created_at', 'desc')
                    ->paginate($paginate);
            }
            
            if (count($popupinformations) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.popupinformation.index')
                ->with('message', $message)
                ->with('data', $popupinformations)
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
        return view('admin.popupinformation.create');
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

        $popupinformation = new Certification;
        $popupinformation->id = Uuid::uuid4();
        $popupinformation->title = $request->input('title');
        if ($request->hasFile('image')) {
                $image_info = getimagesize($request->file('image'));
                $image_width = $image_info[0];
                $image_height = $image_info[1];
                $image = $request->file('image');
            $name_file = 'cert_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/popupinformation';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $popupinformation->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/popupinformation/create');
            }
        }
        
        $popupinformation->is_active = $request->input('is_active');
            $request->input('is_active')==1 ? DB::table('certifications')->where('type', 0)->update(['is_active' => 0]) : "";
        $popupinformation->type = 0;
        $popupinformation->created_by = $currentUser->id;
		$popupinformation->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $popupinformation->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create Pop Up Information";
            $logs->data = $popupinformation;
            $logs->created_by = $currentUser->id;
            $logs->page = "CERTIFICATION";
            $logs->save();

            Session::flash('message', 'Pop Up Information successfully created');
            return redirect('/admin/popupinformation');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/popupinformation/create');
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
        $popupinformation = Certification::find($id);

        return view('admin.popupinformation.edit')
            ->with('data', $popupinformation);
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

        $popupinformation = Certification::find($id);
        $oldData = $popupinformation;
        if ($request->has('title')){
            $popupinformation->title = $request->input('title');
        }
        if ($request->has('is_active')){
            $popupinformation->is_active = $request->input('is_active');
            $request->input('is_active')==1 ? DB::table('certifications')->where('type', 0)->update(['is_active' => 0]) : "";
        }
        if ($request->file('image')) {
            $name_file = 'cert_'.$request->file('image')->getClientOriginalName();
            $path_file = public_path().'/media/popupinformation';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('image')->move($path_file,$name_file)){
                $popupinformation->image = $name_file;
            }else{
                Session::flash('error', 'Save Image to directory failed');
                return redirect('/admin/popupinformation/create');
            }
        }

        $popupinformation->updated_by = $currentUser->id;
		$popupinformation->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $popupinformation->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Pop Up Information";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "CERTIFICATION";
            $logs->save();

            Session::flash('message', 'Pop Up Information successfully updated');
            return redirect('/admin/popupinformation');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/popupinformation/'.$popupinformation->id.'edit');
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
        $popupinformation = Certification::find($id);
        $oldData = $popupinformation;
        $currentUser = Auth::user();
        if ($popupinformation){
            try{
                $popupinformation->delete();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Pop Up Information";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "CERTIFICATION";
                $logs->save();

                Session::flash('message', 'Pop Up Information successfully deleted');
                return redirect('/admin/popupinformation');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/popupinformation');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = Certification::autocomplet($query);
        return response($respons_result);
    }
}
