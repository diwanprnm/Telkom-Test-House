<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Faq;
use App\Services\Logs\LogService;

use Auth;
use Session;

use Ramsey\Uuid\Uuid;

class FaqController extends Controller
{
    private const SEARCH = 'search';
    private const QUESTION = 'question';
    private const MESSAGE = 'message';
    private const ANSWER = 'answer';
    private const ADMIN_FAQ_LOC = '/admin/faq';
    private const ERROR = 'error';

    private static $CATEGORY = [
        '1' => 'Registrasi Akun',
        '2' => 'STEL dan Pengujian Perangkat',
        '3' => 'Uji Fungsi',
        '4' => 'Invoice dan Pembayaran',
        '5' => 'SPK',
        '6' => 'Kapabilitas TTH',
        '7' => 'Pengambilan Laporan dan Sertifikat'
    ];
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

        if (!$currentUser){ return redirect('login');}

        $message = null;
        $paginate = 10;
        $search = trim($request->input(self::SEARCH));
        
        if ($search != null){
            $faq = Faq::whereNotNull('created_at')
                ->where(self::QUESTION,'like','%'.$search.'%')
                ->orderBy(self::QUESTION)
                ->paginate($paginate);

                $logService = new LogService();
                $logService->createLog('Search Faq', 'Faq', json_encode(array(self::SEARCH=>$search)));

        }else{
            $query = Faq::whereNotNull('created_at'); 
            
            $faq = $query->orderBy('category')->orderBy('created_at')
                        ->paginate($paginate);
        }
        
        if (count($faq) == 0){
            $message = 'Data not found';
        }
        for ($i=0; $i < count($faq); $i++) { 
            $temp = $faq[$i]->category;
            $faq[$i]->category = self::$CATEGORY[$temp];
        }
        
        return view('admin.faq.index')
            ->with(self::MESSAGE, $message)
            ->with('data', $faq)
            ->with(self::SEARCH, $search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.faq.create');
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
        $faq = new Faq;
        $faq->id = Uuid::uuid4();
        $faq->question = $request->input(self::QUESTION);
        $faq->answer = $request->input(self::ANSWER);
        $faq->category = $request->input('category');
        $faq->is_active = $request->input('status');
        $faq->created_by = $currentUser->id;
        $faq->updated_by = $currentUser->id; 

        try{
            $faq->save();

            $logService = new LogService();
            $logService->createLog('Create Faq', 'Faq', $faq);
            
            Session::flash(self::MESSAGE, 'FAQ successfully created');
            return redirect(self::ADMIN_FAQ_LOC);
        } catch(Exception $e){ return redirect('/admin/faq/create')->with(self::ERROR, 'Save failed');
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
        $faq = Faq::find($id);

        return view('admin.faq.edit')
            ->with('data', $faq);
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

        $faq = Faq::find($id);
        $oldFaq = $faq; 

        if ($request->has(self::QUESTION)){
            $faq->question = $request->input(self::QUESTION);
        }

        if ($request->has(self::ANSWER)){
            $faq->answer = $request->input(self::ANSWER);
        }

        if ($request->has('category')){
            $faq->category = $request->input('category'); 
        }

        if ($request->has('status')){
            $faq->is_active = $request->input('status');
        }

        $faq->updated_by = $currentUser->id; 

        try{
            $faq->save();

            $logService = new LogService();
            $logService->createLog('Update Faq', 'Faq', $oldFaq);

            Session::flash(self::MESSAGE, 'FAQ successfully updated');
            return redirect(self::ADMIN_FAQ_LOC);
        } catch(Exception $e){ return redirect("/admin/faq/$faq->id/edit")->with(self::ERROR, 'Save failed');
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
        $faq = Faq::find($id);
        
        if ($faq){
            try{
                $oldFaq = clone $faq;
                $faq->delete();
                
                $logService = new LogService();
                $logService->createLog('Delete Faq', 'Faq', $oldFaq);

                Session::flash(self::MESSAGE, 'FAQ successfully deleted');
                return redirect(self::ADMIN_FAQ_LOC);
            }catch (Exception $e){ return redirect(self::ADMIN_FAQ_LOC)->with(self::ERROR, 'Delete failed');
            }
        }else{
            return redirect(self::ADMIN_FAQ_LOC)
                ->with(self::ERROR, 'FAQ Not Found')
            ;
        }
    }    
}
