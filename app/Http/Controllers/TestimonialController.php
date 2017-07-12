<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Testimonial;

use Auth;
use Session;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class TestimonialController extends Controller
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
                $testimonials = Testimonial::whereNotNull('created_at')
					->with('examination.user')
					->with('examination.company')
                    ->where('message','like','%'.$search.'%')
                    ->orderBy('updated_at')
                    ->paginate($paginate);
            }else{
                $testimonials = Testimonial::whereNotNull('created_at')
                    ->orderBy('created_at', 'DESC')
                    ->paginate($paginate);
            }
            
            if (count($testimonials) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.testimonial.index')
                ->with('message', $message)
                ->with('data', $testimonials)
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
        // return view('admin.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $currentUser = Auth::user();

        // $testimonial = new Testimonial;
        // $testimonial->id = Uuid::uuid4();
        // $testimonial->description = $request->input('description');

        
        // $testimonial->is_active = $request->input('is_active');
        // $testimonial->created_by = $currentUser->id;
        // $testimonial->updated_by = $currentUser->id;

        // try{
            // $testimonial->save();
            // Session::flash('message', 'Information successfully created');
            // return redirect('/admin/testimonial');
        // } catch(Exception $e){
            // Session::flash('error', 'Save failed');
            // return redirect('/admin/testimonial/create');
        // }
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
        $testimonial = Testimonial::find($id);

        return view('admin.testimonial.edit')
            ->with('data', $testimonial);
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

        $testimonial = Testimonial::find($id);

        // if ($request->has('description')){
            // $testimonial->description = $request->input('description');
        // }
        if ($request->has('is_active')){
            $testimonial->is_active = $request->input('is_active');
        }
        // if ($request->hasFile('image')) {
            // $ext_file = $request->file('image')->getClientOriginalExtension();
            // $name_file = uniqid().'_testimonial_'.$testimonial->id.'.'.$ext_file;
            // $path_file = public_path().'/media/testimonial';
            // if (!file_exists($path_file)) {
                // mkdir($path_file, 0775);
            // }
            // if($request->file('image')->move($path_file,$name_file)){
                // $testimonial->image = $name_file;
            // }else{
                // Session::flash('error', 'Save Image to directory failed');
                // return redirect('/admin/testimonial/create');
            // }
        // }

        $testimonial->updated_by = $currentUser->id;

        try{
            $testimonial->save();
            Session::flash('message', 'Information successfully updated');
            return redirect('/admin/testimonial');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/testimonial/'.$testimonial->id.'edit');
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
        // $testimonial = Testimonial::find($id);

        // if ($testimonial){
            // try{
                // $testimonial->delete();
                
                // Session::flash('message', 'Information successfully deleted');
                // return redirect('/admin/testimonial');
            // }catch (Exception $e){
                // Session::flash('error', 'Delete failed');
                // return redirect('/admin/testimonial');
            // }
        // }
    }
}
