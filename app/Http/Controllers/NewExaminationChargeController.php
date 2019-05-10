<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use Auth;
use Response;
use Session;
use Input;

use App\Logs;
use App\ExaminationCharge;
use App\NewExaminationCharge;
use App\NewExaminationChargeDetail;

use Excel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class NewExaminationChargeController extends Controller
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
            $before = null;
            $after = null;
            $status = '';
            
            $query = NewExaminationCharge::whereNotNull('created_at');
            if ($search != null){
                    $query = $query->where('name','like','%'.$search.'%');

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Name";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "NEW EXAMINATION CHARGE";
                    $logs->save();
            }
            
            if ($request->has('is_implement')){
                $status = $request->get('is_implement');
                if($request->input('is_implement') != 'all'){
                    $query->where('is_implement', $request->get('is_implement'));
                }
            }

            if ($request->has('before_date')){
                $query->where(DB::raw('DATE(valid_from)'), '<=', $request->get('before_date'));
                $before = $request->get('before_date');
            }

            if ($request->has('after_date')){
                $query->where(DB::raw('DATE(valid_from)'), '>=', $request->get('after_date'));
                $after = $request->get('after_date');
            }

            $newExaminationCharge = $query->orderBy('valid_from', 'desc')->paginate($paginate);
            
            if (count($newExaminationCharge) == 0){
                $message = 'Data not found';
            }

            $newCharge = NewExaminationCharge::where("is_implement",0)->orderBy("valid_from","desc")->get();
            
            return view('admin.newcharge.index')
                ->with('new_charge', $newCharge)
                ->with('message', $message)
                ->with('data', $newExaminationCharge)
                ->with('search', $search)
                ->with('before_date', $before)
                ->with('after_date', $after)
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
        $newCharge = NewExaminationCharge::where("is_implement",0)->orderBy("valid_from","desc")->get();
        if(empty($newCharge[0])){
            return view('admin.newcharge.create');
        }else{
            return redirect('admin/newcharge')->with('error', 'You have not processing data!');
        }
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
			
        $charge = new NewExaminationCharge;
        $charge->id = Uuid::uuid4();
        $charge->name = $request->input('name');
        $charge->description = $request->input('description');
        $charge->valid_from = $request->input('valid_from');
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create New Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = "NEW EXAMINATION CHARGE";
            $logs->save();

            Session::flash('message', 'New Charge successfully created');
            return redirect('/admin/newcharge/'.$charge->id);
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/newcharge/create');
        }
    }

    public function createDetail($id)
    {
        $newCharge = NewExaminationCharge::find($id);
        if($newCharge->is_implement == 0){
            $query = DB::table('examination_charges')
                    ->leftJoin('new_examination_charges_detail', function($q) use ($id)
                    {
                        $q->on('examination_charges.id', '=', 'new_examination_charges_detail.examination_charges_id')
                            ->where('new_examination_charges_detail.new_exam_charges_id', '=', "$id");
                    })
                    ->leftJoin('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                    ->select('examination_charges.*')
                    ->whereNull('new_examination_charges_detail.examination_charges_id')
                    ->whereNotNull('examination_charges.created_at')->where('examination_charges.is_active', 1);

            $examinationCharge = $query->orderByRaw('category, device_name')->get();

            return view('admin.newcharge.createDetail')
                ->with('id', $id)
                ->with('examinationCharge', $examinationCharge)
                ;
        }else{
            return redirect('admin/newcharge/'.$id)->with('error', 'The data has been processed!');
        }
    }

    public function postDetail(Request $request, $id)
    {
        $currentUser = Auth::user();

        $charge = new NewExaminationChargeDetail;
        $charge->id = Uuid::uuid4();
        $charge->new_exam_charges_id = $id;
        if ($request->has('examination_charges_id')){
            $charge->examination_charges_id = $request->input('examination_charges_id');
            $charge->old_device_name = $request->input('old_device_name');
            $charge->old_stel = $request->input('old_stel');
            $charge->old_category = $request->input('old_category');
            $charge->old_duration = str_replace(",","",$request->input('old_duration'));
            $charge->price = str_replace(",","",$request->input('price'));
            $charge->ta_price = str_replace(",","",$request->input('ta_price'));
            $charge->vt_price = str_replace(",","",$request->input('vt_price'));
        }else{
            $charge->examination_charges_id = Uuid::uuid4();
        }
        $charge->device_name = $request->input('device_name');
        $charge->stel = $request->input('stel');
        $charge->category = $request->input('category');
        $charge->duration = str_replace(",","",$request->input('duration'));
        $charge->new_price = str_replace(",","",$request->input('new_price'));
        $charge->new_ta_price = str_replace(",","",$request->input('new_ta_price'));
        $charge->new_vt_price = str_replace(",","",$request->input('new_vt_price'));

        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;
        $charge->created_at = date("Y-m-d H:i:s");
        $charge->updated_at = date("Y-m-d H:i:s");

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create New Charge Detail";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = "NEW EXAMINATION CHARGE";
            $logs->save();

            Session::flash('message', 'New Charge successfully created');
            return redirect('/admin/newcharge/'.$id);
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/newcharge/'.$id.'/createDetail');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $currentUser = Auth::user();

        if ($currentUser){
            $charge = NewExaminationCharge::find($id);

            $message = null;
            $paginate = 10;
            $search = trim($request->input('search'));
            $category = '';
            
            $query = NewExaminationChargeDetail::whereNotNull('created_at')->where('new_exam_charges_id', $id);
            
            if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->where('device_name', 'like', '%'.strtolower($search).'%')
                    ->orWhere('stel', 'like', '%'.strtolower($search).'%');
                });

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Charge";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "NEW EXAMINATION CHARGE";
                    $logs->save();
            }
            
            if ($request->has('category')){
                $category = $request->get('category');
                if($request->input('category') != 'all'){
                    $query->where('category','like', '%'.$request->get('category').'%');
                }
            }

            $examinationCharge = $query->orderByRaw('category, device_name')->paginate($paginate);

            if (count($examinationCharge) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.newcharge.show')
                ->with('charge', $charge)
                ->with('message', $message)
                ->with('data', $examinationCharge)
                ->with('search', $search)
                ->with('category', $category);
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
        $query = DB::table('examination_charges')
                ->leftJoin('new_examination_charges_detail', 'examination_charges.id', '=', 'new_examination_charges_detail.examination_charges_id')
                ->leftJoin('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                ->select('examination_charges.*','new_examination_charges_detail.new_price','new_examination_charges_detail.new_vt_price','new_examination_charges_detail.new_ta_price','new_examination_charges.valid_from','new_examination_charges.is_implement')
                ->whereNotNull('examination_charges.created_at')->where('examination_charges.is_active', 1);
        $examinationCharge = $query->orderByRaw('category, device_name')->get();

        $charge = NewExaminationCharge::find($id);

        return view('admin.newcharge.edit')
            ->with('data', $charge)
            ->with('examinationCharge', $examinationCharge)
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

        $charge = NewExaminationCharge::find($id);
        $oldData = $charge;

        if ($request->has('name')){
            $charge->name = $request->input('name');
        }
        if ($request->has('valid_from')){
            $charge->valid_from = $request->input('valid_from');
        }
        if ($request->has('description')){
            $charge->description = $request->input('description');
        }
        if ($request->has('is_implement')){
            $charge->is_implement = $request->input('is_implement');
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();
            if($charge->is_implement == '1'){$this->implementNew($id);}
            Session::flash('message', 'New Charge successfully updated');

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update New Charge";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = "NEW EXAMINATION CHARGE";
            $logs->save();

            return redirect('/admin/newcharge');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/newcharge/'.$charge->id.'/edit');
        }
    }

    public function editDetail($id, $exam_id)
    {
        $newCharge = NewExaminationCharge::find($id);
        $newChargeDetail = NewExaminationChargeDetail::find($exam_id);
        
        $query = DB::table('examination_charges')
                ->leftJoin('new_examination_charges_detail', function($q) use ($id)
                    {
                        $q->on('examination_charges.id', '=', 'new_examination_charges_detail.examination_charges_id')
                            ->where('new_examination_charges_detail.new_exam_charges_id', '=', "$id");
                    })
                ->leftJoin('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                ->select('examination_charges.*')
                ->whereNull('new_examination_charges_detail.examination_charges_id')
                ->orWhere('new_examination_charges_detail.id', $exam_id)
                ->whereNotNull('examination_charges.created_at')->where('examination_charges.is_active', 1);
        $examinationCharge = $query->orderByRaw('category, device_name')->get();

        return view('admin.newcharge.editDetail')
            ->with('id', $id)
            ->with('exam_id', $exam_id)
            ->with('data', $newChargeDetail)
            ->with('examinationCharge', $examinationCharge)
            ->with('is_implement', $newCharge->is_implement)
            ;
    }

    public function updateDetail(Request $request, $id, $exam_id)
    {
        $currentUser = Auth::user();

        $charge = NewExaminationChargeDetail::find($exam_id);
        $charge->new_exam_charges_id = $id;
        if ($request->has('examination_charges_id')){
            $charge->examination_charges_id = $request->input('examination_charges_id');
            $charge->old_device_name = $request->input('old_device_name');
            $charge->old_stel = $request->input('old_stel');
            $charge->old_category = $request->input('old_category');
            $charge->old_duration = str_replace(",","",$request->input('old_duration'));
            $charge->price = str_replace(",","",$request->input('price'));
            $charge->ta_price = str_replace(",","",$request->input('ta_price'));
            $charge->vt_price = str_replace(",","",$request->input('vt_price'));
        }else{
            $charge->examination_charges_id = Uuid::uuid4();
        }
        $charge->device_name = $request->input('device_name');
        $charge->stel = $request->input('stel');
        $charge->category = $request->input('category');
        $charge->duration = str_replace(",","",$request->input('duration'));
        $charge->new_price = str_replace(",","",$request->input('new_price'));
        $charge->new_ta_price = str_replace(",","",$request->input('new_ta_price'));
        $charge->new_vt_price = str_replace(",","",$request->input('new_vt_price'));

        $charge->updated_by = $currentUser->id;
        $charge->updated_at = date("Y-m-d H:i:s");

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Edit New Charge Detail";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = "NEW EXAMINATION CHARGE";
            $logs->save();

            Session::flash('message', 'New Charge successfully updated');
            return redirect('/admin/newcharge/'.$id);
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/newcharge/'.$id.'/editDetail/'.$exam_id);
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
        NewExaminationChargeDetail::where('new_exam_charges_id', $id)->delete();
        $charge = NewExaminationCharge::find($id);
        $currentUser = Auth::user();
        $oldData = $charge;
        if ($charge){
            try{
                $charge->delete();

                
                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Charge";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "NEW EXAMINATION CHARGE";
                $logs->save();
                
                Session::flash('message', 'New Charge successfully deleted');
                return redirect('/admin/newcharge');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/newcharge');
            }
        }
    }

    public function deleteDetail($id, $exam_id)
    {
        $charge = NewExaminationChargeDetail::find($exam_id);
        $currentUser = Auth::user();
        $oldData = $charge;
        if ($charge){
            try{
                $charge->delete();

                $logs = new Logs;
                $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                $logs->action = "Delete Charge Detail";
                $logs->data = $oldData;
                $logs->created_by = $currentUser->id;
                $logs->page = "NEW EXAMINATION CHARGE";
                $logs->save();
                
                Session::flash('message', 'New Charge detail successfully deleted');
                return redirect('/admin/newcharge/'.$id);
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/newcharge/'.$id);
            }
        }
    }

    public function implementNew($id){
        $currentUser = Auth::user();

        /* query id examination_charges yang tidak ada di new_examination_charges_detail, lalu dinonaktifkan (is_active == 0) */

        $query = DB::table('examination_charges')
                ->leftJoin('new_examination_charges_detail', function($q) use ($id)
                    {
                        $q->on('examination_charges.id', '=', 'new_examination_charges_detail.examination_charges_id')
                            ->where('new_examination_charges_detail.new_exam_charges_id', '=', "$id");
                    })
                ->leftJoin('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                ->select('examination_charges.id')
                ->whereNull('new_examination_charges_detail.examination_charges_id')
                ->whereNotNull('examination_charges.created_at')->where('examination_charges.is_active', 1);
        $examinationCharge = $query->get();

        for ($i=0; $i <count($examinationCharge) ; $i++) {
            $update = DB::table('examination_charges')
                ->where('id', $examinationCharge[$i]->id)
                ->update([
                    'is_active'     => '0',
                    'updated_by'    => $currentUser->id
                ]);
        }

        /* implement dengan cek examination_charges.id = new_examination_charges_detail.examination_charges_id */

        /* if exist(){update device_name, stel, duration, category, price, vt_price, dan ta_price, updated_at, updated_by, is_active == 1} */
        /* else(){insert device_name, stel, duration, category, price, vt_price, dan ta_price, created_at, created_by, updated_at, updated_by, is_active == 1} */
       
        $data = NewExaminationChargeDetail::where("new_exam_charges_id", $id)->get();
        
        for ($i=0; $i <count($data) ; $i++) {
            $update = DB::table('examination_charges')
                ->where('id', $data[$i]['examination_charges_id'])
                ->update([
                    'device_name'   => $data[$i]['device_name'],
                    'stel'          => $data[$i]['stel'],
                    'category'      => $data[$i]['category'],
                    'duration'      => $data[$i]['duration'],
                    'price'         => $data[$i]['new_price'],
                    'vt_price'      => $data[$i]['new_vt_price'],
                    'ta_price'      => $data[$i]['new_ta_price'],
                    'is_active'     => '1',
                    'updated_by'    => $currentUser->id
                ]);
            if(!$update){
                $charge = new ExaminationCharge;
                $charge->id = Uuid::uuid4();
                $charge->device_name = $data[$i]['device_name'];
                $charge->stel = $data[$i]['stel'];
                $charge->category = $data[$i]['category'];
                $charge->duration = $data[$i]['duration'];
                $charge->price = $data[$i]['new_price'];
                $charge->vt_price = $data[$i]['vt_price'];
                $charge->ta_price = $data[$i]['ta_price'];
                $charge->is_active = '1';
                $charge->created_by = $currentUser->id;
                $charge->updated_by = $currentUser->id;

                try{
                    $charge->save();

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Create Charge";
                    $logs->data = $charge;
                    $logs->created_by = $currentUser->id;
                    $logs->page = "EXAMINATION CHARGE";
                    $logs->save();
                } catch(Exception $e){
                    
                }
            }
        }
    }
}
