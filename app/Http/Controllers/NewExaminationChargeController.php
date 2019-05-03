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
use App\newExaminationChargeDetail;

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
            
            return view('admin.newcharge.index')
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
        if(empty($new_charge[0])){
            $query = DB::table('examination_charges')
                    ->leftJoin('new_examination_charges_detail', 'examination_charges.id', '=', 'new_examination_charges_detail.examination_charges_id')
                    ->leftJoin('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                    ->select('examination_charges.*','new_examination_charges_detail.new_price','new_examination_charges_detail.new_vt_price','new_examination_charges_detail.new_ta_price','new_examination_charges.valid_from','new_examination_charges.is_implement')
                    ->whereNotNull('examination_charges.created_at')->where('examination_charges.is_active', 1);
            $examinationCharge = $query->orderByRaw('category, device_name')->get();

            return view('admin.newcharge.create')
                ->with('data', $examinationCharge);
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
            if($charge->save()){
                if(!empty($request->input('examination_charges_id'))){
                    $option_length          = count($request->input('examination_charges_id'));
                    $examination_charges_id = $request->input('examination_charges_id');
                    $price                  = str_replace(",","",$request->input('price'));
                    $vt_price               = str_replace(",","",$request->input('vt_price'));
                    $ta_price               = str_replace(",","",$request->input('ta_price'));
                    $new_price              = str_replace(",","",$request->input('new_price'));
                    $new_vt_price           = str_replace(",","",$request->input('new_vt_price'));
                    $new_ta_price           = str_replace(",","",$request->input('new_ta_price'));
                    for ($i=0; $i <$option_length ; $i++) { 
                       $data[] =
                        array(
                            "id"                    => Uuid::uuid4(),
                            "new_exam_charges_id"   => $charge->id,
                            "examination_charges_id"=> $examination_charges_id[$i],
                            "price"                 => $price[$i],
                            "vt_price"              => $vt_price[$i],
                            "ta_price"              => $ta_price[$i],
                            "new_price"             => $new_price[$i],
                            "new_vt_price"          => $new_vt_price[$i],
                            "new_ta_price"          => $new_ta_price[$i],
                            "created_by"            => $currentUser->id,
                            "updated_by"            => $currentUser->id,
                            "created_at"            => date('Y-m-d H:i:s'),
                            "updated_at"            => date('Y-m-d H:i:s')
                        );
                    }
                }
                DB::table('new_examination_charges_detail')->insert($data);

                Session::flash('message', 'Charge successfully updated');
            }else{
                Session::flash('error', 'Save failed, undefined list');
            }

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create New Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = "NEW EXAMINATION CHARGE";
            $logs->save();

            Session::flash('message', 'New Charge successfully created');
            return redirect('/admin/newcharge');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/newcharge/create');
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
        $query = DB::table('examination_charges')
                ->leftJoin('new_examination_charges_detail', 'examination_charges.id', '=', 'new_examination_charges_detail.examination_charges_id')
                ->leftJoin('new_examination_charges', 'new_examination_charges_detail.new_exam_charges_id', '=', 'new_examination_charges.id')
                ->select('examination_charges.*','new_examination_charges_detail.new_price','new_examination_charges_detail.new_vt_price','new_examination_charges_detail.new_ta_price','new_examination_charges.valid_from','new_examination_charges.is_implement')
                ->whereNotNull('examination_charges.created_at')->where('examination_charges.is_active', 1);
        $examinationCharge = $query->orderByRaw('category, device_name')->get();

        $charge = NewExaminationCharge::with('newExaminationChargeDetail')->find($id);

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
            if($charge->save()){
                NewExaminationChargeDetail::where('new_exam_charges_id', $id)->delete();
                if(!empty($request->input('examination_charges_id'))){
                    $option_length          = count($request->input('examination_charges_id'));
                    $examination_charges_id = $request->input('examination_charges_id');
                    $price                  = str_replace(",","",$request->input('price'));
                    $vt_price               = str_replace(",","",$request->input('vt_price'));
                    $ta_price               = str_replace(",","",$request->input('ta_price'));
                    $new_price              = str_replace(",","",$request->input('new_price'));
                    $new_vt_price           = str_replace(",","",$request->input('new_vt_price'));
                    $new_ta_price           = str_replace(",","",$request->input('new_ta_price'));
                    for ($i=0; $i <$option_length ; $i++) { 
                       $data[] =
                        array(
                            "id"                    => Uuid::uuid4(),
                            "new_exam_charges_id"   => $charge->id,
                            "examination_charges_id"=> $examination_charges_id[$i],
                            "price"                 => $price[$i],
                            "vt_price"              => $vt_price[$i],
                            "ta_price"              => $ta_price[$i],
                            "new_price"             => $new_price[$i],
                            "new_vt_price"          => $new_vt_price[$i],
                            "new_ta_price"          => $new_ta_price[$i],
                            "created_by"            => $currentUser->id,
                            "updated_by"            => $currentUser->id,
                            "created_at"            => date('Y-m-d H:i:s'),
                            "updated_at"            => date('Y-m-d H:i:s')
                        );
                    }
                }
                DB::table('new_examination_charges_detail')->insert($data);

                if($charge->is_implement == '1'){$this->implementNew($data);}

                Session::flash('message', 'New Charge successfully updated');
            }else{
                Session::flash('error', 'Save failed, undefined list');
            }

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

    public function implementNew($data)
    {
        for ($i=0; $i <count($data) ; $i++) {
            DB::table('examination_charges')
                ->where('id', $data[$i]['examination_charges_id'])
                ->update([
                    'price'    => $data[$i]['new_price'],
                    'vt_price' => $data[$i]['new_vt_price'],
                    'ta_price' => $data[$i]['new_ta_price']
                ]);
        }
    }
}
