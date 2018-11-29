<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Requests;

use App\STEL;
use App\ExaminationLab;
use App\Logs;

use Excel;

use Auth;
use File;
use Response;
use Session;
use Input;
use Ramsey\Uuid\Uuid;
class STELController extends Controller
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
            $category = '';
            $status = -1;

            $examLab = ExaminationLab::all();
            
            if ($search != null){
                $stels = STEL::whereNotNull('created_at')
                    ->with('examinationLab')
                    ->where('name','like','%'.$search.'%')
                    ->orWhere('code','like','%'.$search.'%')
                    ->orderBy('code')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search STEL";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "STEL";
                    $logs->save();
            }else{
                $query = STEL::whereNotNull('created_at')->with('examinationLab');

                if ($request->has('category')){
                    $category = $request->get('category');
					if($request->input('category') != 'all'){
						$query->whereHas('examinationLab', function ($q) use ($category){
                            return $q->where('id', $category);
                        });
					}
                }

                if ($request->has('is_active')){
                    $status = $request->get('is_active');
                    if ($request->get('is_active') > -1){
						$query->where('is_active', $request->get('is_active'));
                    }
                }
                
                $stels = $query->orderBy('name')
                            ->paginate($paginate);
            }
            
            if (count($stels) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.STEL.index')
                ->with('examLab', $examLab)
                ->with('message', $message)
                ->with('data', $stels)
                ->with('search', $search)
                ->with('category', $category)
                ->with('status', $status);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $examLab = ExaminationLab::all();
        return view('admin.STEL.create')
            ->with('examLab',$examLab)
        ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		// $request->flash();
		$currentUser = Auth::user();
			
			$name_exists = $this->cekNamaSTEL($request->input('name'));
		if($name_exists == 1){
			return redirect()->back()
			->with('error_name', 1)
			->withInput($request->all());
		}
		$stel = new STEL;
		$stel->code = $request->input('code');
		$stel->stel_type = $request->input('stel_type');
		$stel->name = $request->input('name');
		$stel->type = $request->input('type');
		$stel->version = $request->input('version');
		$stel->year = $request->input('year');
		$stel->price = str_replace(",","",$request->input('price'));
		$stel->total = str_replace(",","",$request->input('total'));
		$stel->is_active = $request->input('is_active');
		$stel->created_by = $currentUser->id;
		$stel->updated_by = $currentUser->id;

		if ($request->hasFile('attachment')) {
			/*$ext_file = $request->file('attachment')->getClientOriginalExtension();
			$name_file = uniqid().'_stel_'.$stel->code.'.'.$ext_file;*/
            $name_file = 'stel_'.$request->file('attachment')->getClientOriginalName();
			$path_file = public_path().'/media/stel';
			if (!file_exists($path_file)) {
				mkdir($path_file, 0775);
			}
			if($request->file('attachment')->move($path_file,$name_file)){
				$stel->attachment = $name_file;
			}else{
				Session::flash('error', 'Save STEL to directory failed');
				return redirect('/admin/stel/create');
			}
		}
 

		try{
			$stel->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;
            $logs->id = Uuid::uuid4();
            $logs->action = "Create STEL";
            $logs->data = $stel;
            $logs->created_by = $currentUser->id;
            $logs->page = "STEL";
            $logs->save();
			
            Session::flash('message', 'STEL successfully created');
			return redirect('/admin/stel');
		} catch(Exception $e){
			Session::flash('error', 'Save failed');
			return redirect('/admin/stel/create');
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
        $examLab = ExaminationLab::all();
        $stel = STEL::find($id);

        return view('admin.STEL.edit')
            ->with('examLab', $examLab)
            ->with('data', $stel)
        ;
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

        $stel = STEL::find($id);
        $oldStel = $stel;

        if ($request->has('code')){
            $stel->code = $request->input('code');
        }
        if ($request->has('stel_type')){
            $stel->stel_type = $request->input('stel_type');
        }
        if ($request->has('name')){
            $stel->name = $request->input('name');
        }
        if ($request->has('type')){
            $stel->type = $request->input('type');
        }
        if ($request->has('version')){
            $stel->version = $request->input('version');
        }
        if ($request->has('year')){
            $stel->year = $request->input('year');
        }
        if ($request->has('price')){
            $stel->price = str_replace(",","",$request->input('price'));
        }
        if ($request->has('is_active')){
            $stel->is_active = $request->input('is_active');
        }
        if ($request->has('total')){
            $stel->total = str_replace(",","",$request->input('total'));
        }
        if ($request->hasFile('attachment')) {
            /*$ext_file = $request->file('attachment')->getClientOriginalExtension();
            $name_file = uniqid().'_stel_'.$stel->code.'.'.$ext_file;*/
            $name_file = 'stel_'.$request->file('attachment')->getClientOriginalName();
            $path_file = public_path().'/media/stel';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('attachment')->move($path_file,$name_file)){
                $stel->attachment = $name_file;
            }else{
                Session::flash('error', 'Save STEL to directory failed');
                return redirect('/admin/stel/create');
            }
        }

        $stel->updated_by = $currentUser->id;

       

        try{
            $stel->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update STEL";
            $logs->data = $oldStel;
            $logs->created_by = $currentUser->id;
            $logs->page = "STEL";
            $logs->save();

            Session::flash('message', 'STEL successfully updated');
            return redirect('/admin/stel');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/stel/'.$stel->id.'/edit');
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
        $stel = STEL::find($id);
        $oldStel = $stel;
        $currentUser = Auth::user();
        if ($stel){
            try{
                $stel->delete();
                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete STEL";
                $logs->data = $oldStel;
                $logs->created_by = $currentUser->id;
                $logs->page = "STEL";
                $logs->save();

                Session::flash('message', 'STEL successfully deleted');
                return redirect('/admin/stel');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/stel');
            }
        }
    }

    public function viewMedia($id)
    {
        $stel = STEL::find($id);

        if ($stel){
            $file = public_path().'/media/stel/'.$stel->attachment;
            $headers = array(
              'Content-Type: application/octet-stream',
            );

            return Response::file($file, $headers);
        }
    }
	
    function cekNamaSTEL($name)
    {
		$stels = STEL::where('name','=',''.$name.'')->get();
		return count($stels);
    }
	
	public function autocomplete($query) {
        $respons_result = STEL::adm_stel_autocomplet($query);
        return response($respons_result);
    }

    public function excel(Request $request) 
    {
        // Execute the query used to retrieve the data. In this example
        // we're joining hypothetical users and payments tables, retrieving
        // the payments table's primary key, the user's first and last name, 
        // the user's e-mail address, the amount paid, and the payment
        // timestamp.

        $search = trim($request->input('search'));
        
        $category = '';
        $status = -1;

        if ($search != null){
            $stels = STEL::whereNotNull('created_at')
                ->with('examinationLab')
                ->where('name','like','%'.$search.'%')
                ->orWhere('code','like','%'.$search.'%')
                ->orderBy('code');
        }else{
            $query = STEL::whereNotNull('created_at')->with('examinationLab');

            if ($request->has('category')){
                $category = $request->get('category');
                if($request->input('category') != 'all'){
                    $query->whereHas('examinationLab', function ($q) use ($category){
                        return $q->where('id', $category);
                    });
                }
            }

            if ($request->has('is_active')){
                $status = $request->get('is_active');
                if ($request->get('is_active') > -1){
                    $query->where('is_active', $request->get('is_active'));
                }
            }
            
            $stels = $query->orderBy('name');
        }

        $data = $stels->get();

        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray[] = [
            'ID',
            'Kode',
            'Nama Dokumen',
            'Tipe',
            'Versi',
            'Tahun',
            'Harga',
            'Total',
            'Status'
        ]; 
        
        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            $examsArray[] = [
                $row->id,
                $row->code,
                $row->name,
                $row->examinationLab->name,
                $row->version,
                $row->year,
                number_format($row->price, 0, '.', ','),
                number_format($row->total, 0, '.', ','),
                $row->is_active == '1' ? 'Active' : 'Not Active'
            ];
        }
        $currentUser = Auth::user();
        $logs = new Logs;
        $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
        $logs->action = "download_excel";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "STEL/STD";
        $logs->save();

        // Generate and return the spreadsheet
        Excel::create('Data STEL/STD', function($excel) use ($examsArray) {

            // Set the spreadsheet title, creator, and description
            // $excel->setTitle('Payments');
            // $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            // $excel->setDescription('payments file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($examsArray) {
                $sheet->fromArray($examsArray, null, 'A1', false, false);
            });
        })->export('xlsx'); 
    }
}
