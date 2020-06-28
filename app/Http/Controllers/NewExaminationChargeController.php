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
    private const SEARCH = 'search';
    private const NEW_EXAMINATION_CHARGE = "NEW EXAMINATION CHARGE";
    private const IS_IMPLEMENT = 'is_implement';
    private const BEFORE_DATE = 'before_date';
    private const AFTER_DATE = 'after_date';
    private const VALID_FROM = 'valid_from';
    private const MESSAGE = 'message';
    private const ERROR = 'error';
    private const DESCRIPTION = 'description';
    private const SAFE_FAILED = 'Save failed';
    private const EXAMINATION_CHARGE = 'examinationCharge';
    private const EXAMINATION_CHARGES = 'examination_charges';
    private const ID_IN_EXAMINATION_CHARGES = 'examination_charges.id';
    private const ALL_EXAMINATION_CHARGES = 'examination_charges.*';
    private const EXAMINATION_CHARGES_ID = 'examination_charges_id';
    private const CREATED_AT_IN_EXAMINATION_CHARGES = 'examination_charges.created_at';
    private const IS_ACTIVE_IN_EXAMINATION_CHARGES = 'examination_charges.is_active';
    private const NEW_EXAMINATION_CHARGES_DETAIL = 'new_examination_charges_detail';
    private const EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL = 'new_examination_charges_detail.examination_charges_id';
    private const NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES = 'new_examination_charges_detail.new_exam_charges_id';
    private const NEW_EXAMINATION_CHARGES = 'new_examination_charges';
    private const ID_IN_NEW_EXAMINATION_CHARGES = 'new_examination_charges.id';
    private const CATEGORY_AND_DEVICE_NAME = 'category, device_name';
    private const PRICE = 'price';
    private const TA_PRICE = 'ta_price';
    private const VT_PRICE = 'vt_price';
    private const DEVICE_NAME = 'device_name';
    private const CATEGORY = 'category';
    private const DURATION = 'duration';
    private const NEW_PRICE = 'new_price';
    private const NEW_TA_PRICE = 'new_ta_price';
    private const NEW_VT_PRICE = 'new_vt_price';
    private const DATE_FORMAT = "Y-m-d H:i:s";
    private const NEW_EXAM_CHARGES_ID = 'new_exam_charges_id';
    private const ADMIN_NEWCHARGE = '/admin/newcharge/';

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
            $search = trim($request->input(self::SEARCH));
            $before = null;
            $after = null;
            $status = '';
            
            $query = NewExaminationCharge::whereNotNull('created_at');
            if ($search != null){
                    $query = $query->where('name','like','%'.$search.'%');

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Name";
                    $datasearch = array(self::SEARCH=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = self::NEW_EXAMINATION_CHARGE;
                    $logs->save();
            }
            
            if ($request->has(self::IS_IMPLEMENT)){
                $status = $request->get(self::IS_IMPLEMENT);
                if($request->input(self::IS_IMPLEMENT) != 'all'){
                    $query->where(self::IS_IMPLEMENT, $request->get(self::IS_IMPLEMENT));
                }
            }

            if ($request->has(self::BEFORE_DATE)){
                $query->where(DB::raw('DATE(valid_from)'), '<=', $request->get(self::BEFORE_DATE));
                $before = $request->get(self::BEFORE_DATE);
            }

            if ($request->has(self::AFTER_DATE)){
                $query->where(DB::raw('DATE(valid_from)'), '>=', $request->get(self::AFTER_DATE));
                $after = $request->get(self::AFTER_DATE);
            }

            $newExaminationCharge = $query->orderBy(self::VALID_FROM, 'desc')->paginate($paginate);
            
            if (count($newExaminationCharge) == 0){
                $message = 'Data not found';
            }

            $newCharge = NewExaminationCharge::where(self::IS_IMPLEMENT,0)->orderBy(self::MESSAGE,"desc")->get();
            
            return view('admin.newcharge.index')
                ->with('new_charge', $newCharge)
                ->with(self::MESSAGE, $message)
                ->with('data', $newExaminationCharge)
                ->with(self::SEARCH, $search)
                ->with(self::BEFORE_DATE, $before)
                ->with(self::AFTER_DATE, $after)
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
        $newCharge = NewExaminationCharge::where(self::IS_IMPLEMENT,0)->orderBy(self::MESSAGE,"desc")->get();
        if(empty($newCharge[0])){
            return view('admin.newcharge.create');
        }else{
            return redirect('admin/newcharge')->with(self::ERROR, 'You have not processing data!');
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
        $charge->description = $request->input(self::DESCRIPTION);
        $charge->valid_from = $request->input(self::VALID_FROM);
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create New Charge";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = self::NEW_EXAMINATION_CHARGE;
            $logs->save();

            Session::flash(self::MESSAGE, 'New Charge successfully created');
            return redirect(self::ADMIN_NEWCHARGE.$charge->id);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::SAFE_FAILED);
            return redirect('/admin/newcharge/create');
        }
    }

    public function createDetail($id)
    {
        $newCharge = NewExaminationCharge::find($id);
        if($newCharge->is_implement == 0){
            $query = DB::table(self::EXAMINATION_CHARGES)
                    ->leftJoin(self::NEW_EXAMINATION_CHARGES_DETAIL, function($q) use ($id)
                    {
                        $q->on(self::ID_IN_EXAMINATION_CHARGES, '=', self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                            ->where(self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', "$id");
                    })
                    ->leftJoin(self::NEW_EXAMINATION_CHARGES, self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', self::ID_IN_NEW_EXAMINATION_CHARGES)
                    ->select(self::ALL_EXAMINATION_CHARGES)
                    ->whereNull(self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                    ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1);

            $examinationCharge = $query->orderByRaw(self::CATEGORY_AND_DEVICE_NAME)->get();

            return view('admin.newcharge.createDetail')
                ->with('id', $id)
                ->with(self::EXAMINATION_CHARGE, $examinationCharge)
                ;
        }else{
            return redirect('admin/newcharge/'.$id)->with(self::ERROR, 'The data has been processed!');
        }
    }

    public function postDetail(Request $request, $id)
    {
        $currentUser = Auth::user();

        $charge = new NewExaminationChargeDetail;
        $charge->id = Uuid::uuid4();
        $charge->new_exam_charges_id = $id;
        if ($request->has(self::EXAMINATION_CHARGES_ID)){
            $charge->examination_charges_id = $request->input(self::EXAMINATION_CHARGES_ID);
            $charge->old_device_name = $request->input('old_device_name');
            $charge->old_stel = $request->input('old_stel');
            $charge->old_category = $request->input('old_category');
            $charge->old_duration = str_replace(",","",$request->input('old_duration'));
            $charge->price = str_replace(",","",$request->input(self::PRICE));
            $charge->ta_price = str_replace(",","",$request->input(self::TA_PRICE));
            $charge->vt_price = str_replace(",","",$request->input(self::VT_PRICE));
        }else{
            $charge->examination_charges_id = Uuid::uuid4();
        }
        $charge->device_name = $request->input(self::DEVICE_NAME);
        $charge->stel = $request->input('stel');
        $charge->category = $request->input(self::CATEGORY);
        $charge->duration = str_replace(",","",$request->input(self::DURATION));
        $charge->new_price = str_replace(",","",$request->input(self::NEW_PRICE));
        $charge->new_ta_price = str_replace(",","",$request->input(self::NEW_TA_PRICE));
        $charge->new_vt_price = str_replace(",","",$request->input(self::NEW_VT_PRICE));

        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;
        $charge->created_at = date(self::DATE_FORMAT);
        $charge->updated_at = date(self::DATE_FORMAT);

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Create New Charge Detail";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = self::NEW_EXAMINATION_CHARGE;
            $logs->save();

            Session::flash(self::MESSAGE, 'New Charge successfully created');
            return redirect(self::ADMIN_NEWCHARGE.$id);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::SAFE_FAILED);
            return redirect(self::ADMIN_NEWCHARGE.$id.'/createDetail');
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
            $search = trim($request->input(self::SEARCH));
            $category = '';
            
            $query = NewExaminationChargeDetail::whereNotNull('created_at')->where(self::NEW_EXAM_CHARGES_ID, $id);
            
            if ($search != null){
                $query->where(function($qry) use($search){
                    $qry->where(self::DEVICE_NAME, 'like', '%'.strtolower($search).'%')
                    ->orWhere('stel', 'like', '%'.strtolower($search).'%');
                });

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search Charge";
                    $datasearch = array(self::SEARCH=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = self::NEW_EXAMINATION_CHARGE;
                    $logs->save();
            }
            
            if ($request->has(self::CATEGORY)){
                $category = $request->get(self::CATEGORY);
                if($request->input(self::CATEGORY) != 'all'){
                    $query->where(self::CATEGORY,'like', '%'.$request->get(self::CATEGORY).'%');
                }
            }

            $examinationCharge = $query->orderByRaw(self::CATEGORY_AND_DEVICE_NAME)->paginate($paginate);

            if (count($examinationCharge) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.newcharge.show')
                ->with('charge', $charge)
                ->with(self::MESSAGE, $message)
                ->with('data', $examinationCharge)
                ->with(self::SEARCH, $search)
                ->with(self::CATEGORY, $category);
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
        $query = DB::table(self::EXAMINATION_CHARGES)
                ->leftJoin(self::NEW_EXAMINATION_CHARGES_DETAIL, self::ID_IN_EXAMINATION_CHARGES, '=', self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                ->leftJoin(self::NEW_EXAMINATION_CHARGES, self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', self::ID_IN_NEW_EXAMINATION_CHARGES)
                ->select(self::ALL_EXAMINATION_CHARGES,'new_examination_charges_detail.new_price','new_examination_charges_detail.new_vt_price','new_examination_charges_detail.new_ta_price','new_examination_charges.valid_from','new_examination_charges.is_implement')
                ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1);
        $examinationCharge = $query->orderByRaw(self::CATEGORY_AND_DEVICE_NAME)->get();

        $charge = NewExaminationCharge::find($id);

        return view('admin.newcharge.edit')
            ->with('data', $charge)
            ->with(self::EXAMINATION_CHARGE, $examinationCharge)
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
        if ($request->has(self::VALID_FROM)){
            $charge->valid_from = $request->input(self::VALID_FROM);
        }
        if ($request->has(self::DESCRIPTION)){
            $charge->description = $request->input(self::DESCRIPTION);
        }
        if ($request->has(self::IS_IMPLEMENT)){
            $charge->is_implement = $request->input(self::IS_IMPLEMENT);
        }

        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();
            if($charge->is_implement == '1'){$this->implementNew($id);}
            Session::flash(self::MESSAGE, 'New Charge successfully updated');

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update New Charge";
            $logs->data = $oldData;
            $logs->created_by = $currentUser->id;
            $logs->page = self::NEW_EXAMINATION_CHARGE;
            $logs->save();

            return redirect(self::ADMIN_NEWCHARGE);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::SAFE_FAILED);
            return redirect(self::ADMIN_NEWCHARGE.$charge->id.'/edit');
        }
    }

    public function editDetail($id, $exam_id)
    {
        $newCharge = NewExaminationCharge::find($id);
        $newChargeDetail = NewExaminationChargeDetail::find($exam_id);
        
        $query = DB::table(self::EXAMINATION_CHARGES)
                ->leftJoin(self::NEW_EXAMINATION_CHARGES_DETAIL, function($q) use ($id)
                    {
                        $q->on(self::ID_IN_EXAMINATION_CHARGES, '=', self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                            ->where(self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', "$id");
                    })
                ->leftJoin(self::NEW_EXAMINATION_CHARGES, self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', self::ID_IN_NEW_EXAMINATION_CHARGES)
                ->select(self::ALL_EXAMINATION_CHARGES)
                ->whereNull(self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                ->orWhere('new_examination_charges_detail.id', $exam_id)
                ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1);
        $examinationCharge = $query->orderByRaw(self::CATEGORY_AND_DEVICE_NAME)->get();

        return view('admin.newcharge.editDetail')
            ->with('id', $id)
            ->with('exam_id', $exam_id)
            ->with('data', $newChargeDetail)
            ->with(self::EXAMINATION_CHARGE, $examinationCharge)
            ->with(self::IS_IMPLEMENT, $newCharge->is_implement)
            ;
    }

    public function updateDetail(Request $request, $id, $exam_id)
    {
        $currentUser = Auth::user();

        $charge = NewExaminationChargeDetail::find($exam_id);
        $charge->new_exam_charges_id = $id;
        if ($request->has(self::EXAMINATION_CHARGES_ID)){
            $charge->examination_charges_id = $request->input(self::EXAMINATION_CHARGES_ID);
            $charge->old_device_name = $request->input('old_device_name');
            $charge->old_stel = $request->input('old_stel');
            $charge->old_category = $request->input('old_category');
            $charge->old_duration = str_replace(",","",$request->input('old_duration'));
            $charge->price = str_replace(",","",$request->input(self::PRICE));
            $charge->ta_price = str_replace(",","",$request->input(self::TA_PRICE));
            $charge->vt_price = str_replace(",","",$request->input(self::VT_PRICE));
        }else{
            $charge->examination_charges_id = Uuid::uuid4();
        }
        $charge->device_name = $request->input(self::DEVICE_NAME);
        $charge->stel = $request->input('stel');
        $charge->category = $request->input(self::CATEGORY);
        $charge->duration = str_replace(",","",$request->input(self::DURATION));
        $charge->new_price = str_replace(",","",$request->input(self::NEW_PRICE));
        $charge->new_ta_price = str_replace(",","",$request->input(self::NEW_TA_PRICE));
        $charge->new_vt_price = str_replace(",","",$request->input(self::NEW_VT_PRICE));

        $charge->updated_by = $currentUser->id;
        $charge->updated_at = date(self::DATE_FORMAT);

        try{
            $charge->save();

            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Edit New Charge Detail";
            $logs->data = $charge;
            $logs->created_by = $currentUser->id;
            $logs->page = self::NEW_EXAMINATION_CHARGE;
            $logs->save();

            Session::flash(self::MESSAGE, 'New Charge successfully updated');
            return redirect(self::ADMIN_NEWCHARGE.$id);
        } catch(Exception $e){
            Session::flash(self::ERROR, self::SAFE_FAILED);
            return redirect(self::ADMIN_NEWCHARGE.$id.'/editDetail/'.$exam_id);
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
        NewExaminationChargeDetail::where(self::NEW_EXAM_CHARGES_ID, $id)->delete();
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
                $logs->page = self::NEW_EXAMINATION_CHARGE;
                $logs->save();
                
                Session::flash(self::MESSAGE, 'New Charge successfully deleted');
                return redirect(self::ADMIN_NEWCHARGE);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_NEWCHARGE);
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
                $logs->page = self::NEW_EXAMINATION_CHARGE;
                $logs->save();
                
                Session::flash(self::MESSAGE, 'New Charge detail successfully deleted');
                return redirect(self::ADMIN_NEWCHARGE.$id);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_NEWCHARGE.$id);
            }
        }
    }

    public function implementNew($id){
        $currentUser = Auth::user();

        /* query id examination_charges yang tidak ada di new_examination_charges_detail, lalu dinonaktifkan (is_active == 0) */

        $query = DB::table(self::EXAMINATION_CHARGES)
                ->leftJoin(self::NEW_EXAMINATION_CHARGES_DETAIL, function($q) use ($id)
                    {
                        $q->on(self::ID_IN_EXAMINATION_CHARGES, '=', self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                            ->where(self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', "$id");
                    })
                ->leftJoin(self::NEW_EXAMINATION_CHARGES, self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', self::ID_IN_NEW_EXAMINATION_CHARGES)
                ->select(self::ID_IN_EXAMINATION_CHARGES)
                ->whereNull(self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1);
        $examinationCharge = $query->get();

        for ($i=0; $i <count($examinationCharge) ; $i++) {
            $update = DB::table(self::EXAMINATION_CHARGES)
                ->where('id', $examinationCharge[$i]->id)
                ->update([
                    'is_active'     => '0',
                    'updated_by'    => $currentUser->id
                ]);
        }

        /* implement dengan cek examination_charges.id = new_examination_charges_detail.examination_charges_id */       
        $data = NewExaminationChargeDetail::where("new_exam_charges_id", $id)->get();
        
        for ($i=0; $i <count($data) ; $i++) {
            $update = DB::table(self::EXAMINATION_CHARGES)
                ->where('id', $data[$i][self::EXAMINATION_CHARGES_ID])
                ->update([
                    self::DEVICE_NAME   => $data[$i][self::DEVICE_NAME],
                    'stel'          => $data[$i]['stel'],
                    self::CATEGORY      => $data[$i][self::CATEGORY],
                    self::DURATION      => $data[$i][self::DURATION],
                    self::PRICE         => $data[$i][self::NEW_PRICE],
                    self::VT_PRICE      => $data[$i][self::NEW_VT_PRICE],
                    self::TA_PRICE      => $data[$i][self::NEW_TA_PRICE],
                    'is_active'     => '1',
                    'updated_by'    => $currentUser->id
                ]);
            if(!$update){
                $charge = new ExaminationCharge;
                $charge->id = Uuid::uuid4();
                $charge->device_name = $data[$i][self::DEVICE_NAME];
                $charge->stel = $data[$i]['stel'];
                $charge->category = $data[$i][self::CATEGORY];
                $charge->duration = $data[$i][self::DURATION];
                $charge->price = $data[$i][self::NEW_PRICE];
                $charge->vt_price = $data[$i][self::VT_PRICE];
                $charge->ta_price = $data[$i][self::TA_PRICE];
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
                    continue;
                }
            }
        }
    }
}
