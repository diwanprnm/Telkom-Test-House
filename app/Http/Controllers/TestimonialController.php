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
    private const CREATED_AT = 'created_at';
    private const MESSAGE = 'message';
    private const SEARCH = 'search';
    private const IS_ACTIVE = 'is_active';

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
        $noDataFound = '';
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        
        if ($search){
            $testimonials = Testimonial::whereNotNull(self::CREATED_AT)
                ->with('examination.user')
                ->with('examination.company')
                ->where(self::MESSAGE,'like','%'.$search.'%')
                ->orderBy('updated_at')
                ->paginate($paginate);
        }else{
            $testimonials = Testimonial::whereNotNull(self::CREATED_AT)
                ->orderBy(self::CREATED_AT, 'DESC')
                ->paginate($paginate);
        }
        
        if (count($testimonials) == 0){
            $noDataFound = 'Data not found';
        }
        
        return view('admin.testimonial.index')
            ->with('noDataFound', $noDataFound)
            ->with('data', $testimonials)
            ->with('search', $search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
        $this->validate($request, [
            self::IS_ACTIVE => 'boolean',
        ]);

        $currentUser = Auth::user();
        $testimonial = Testimonial::find($id);
 
        if ($request->has(self::IS_ACTIVE)){
            $testimonial->is_active = $request->input(self::IS_ACTIVE);
        }

        $testimonial->updated_by = $currentUser->id;

        try{
            $testimonial->save();
            Session::flash(self::MESSAGE, 'Information successfully updated');
            return redirect('/admin/testimonial');
        } catch(Exception $e){
            return redirect('/admin/testimonial/'.$testimonial->id.'edit')->with('error', 'Save failed');
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

    }
}
