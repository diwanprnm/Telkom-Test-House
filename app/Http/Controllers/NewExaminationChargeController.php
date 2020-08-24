<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

use Auth;
use Response;
use Session;
use Input;
use Excel;

use App\Logs;
use App\ExaminationCharge;
use App\NewExaminationCharge;
use App\NewExaminationChargeDetail;

use App\Services\Logs\LogService;
use App\Services\Querys\QueryFilter;



use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class NewExaminationChargeController extends Controller
{
    private const ADMIN_NEWCHARGE = '/admin/newcharge/';
    private const AFTER_DATE = 'after_date';
    private const ALL_EXAMINATION_CHARGES = 'examination_charges.*';
    private const BEFORE_DATE = 'before_date';
    private const CATEGORY = 'category';
    private const CATEGORY_AND_DEVICE_NAME = 'category, device_name';
    private const CREATED_AT_IN_EXAMINATION_CHARGES = 'examination_charges.created_at';
    private const DATA_NOT_FOUND = 'Data not found';
    private const DATE_FORMAT = "Y-m-d H:i:s";
    private const DESCRIPTION = 'description';
    private const DEVICE_NAME = 'device_name';
    private const DURATION = 'duration';
    private const ERROR = 'error';
    private const EXAMINATION_CHARGE = 'examinationCharge';
    private const EXAMINATION_CHARGES = 'examination_charges';
    private const EXAMINATION_CHARGES_ID = 'examination_charges_id';
    private const EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL = 'new_examination_charges_detail.examination_charges_id';
    private const ID_IN_EXAMINATION_CHARGES = 'examination_charges.id';
    private const ID_IN_NEW_EXAMINATION_CHARGES = 'new_examination_charges.id';
    private const IS_ACTIVE_IN_EXAMINATION_CHARGES = 'examination_charges.is_active';
    private const IS_IMPLEMENT = 'is_implement';
    private const MESSAGE = 'message';
    private const NAME = 'name';
    private const NEW_CHARGE_SUCCEED_UPDATED = 'New Charge successfully updated';
    private const NEW_EXAM_CHARGES_ID = 'new_exam_charges_id';
    private const NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES = 'new_examination_charges_detail.new_exam_charges_id';
    private const NEW_EXAMINATION_CHARGE = "NEW EXAMINATION CHARGE";
    private const NEW_EXAMINATION_CHARGES = 'new_examination_charges';
    private const NEW_EXAMINATION_CHARGES_DETAIL = 'new_examination_charges_detail';
    private const NEW_PRICE = 'new_price';
    private const NEW_TA_PRICE = 'new_ta_price';
    private const NEW_VT_PRICE = 'new_vt_price';
    private const PRICE = 'price';
    private const REQUIRED = 'required';
    private const SEARCH = 'search';
    private const SAVE_FAILED = 'Save failed';
    private const STEL = 'stel';
    private const SUCCEED = 'succeed';
    private const TA_PRICE = 'ta_price';
    private const VALID_FROM = 'valid_from';
    private const VT_PRICE = 'vt_price';

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
        //initial var
        $logService = new LogService();
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $status = '';
        $dataNotFound = '';

        // get query
        $newCharge = NewExaminationCharge::where(self::IS_IMPLEMENT,0)->orderBy(self::VALID_FROM,"desc")->get();
        $queryFilter = new QueryFilter($request, NewExaminationCharge::whereNotNull('created_at'));
        
        //filter query
        if ($search){
                $query = $queryFilter
                    ->getQuery()
                    ->where('name','like','%'.$search.'%')
                ;
                $queryFilter->updateQuery($query);
                $logService->createLog(' Search Name', self::NEW_EXAMINATION_CHARGE, json_encode(array(self::SEARCH=>$search)) );
        }
        if ($request->has(self::IS_IMPLEMENT)){
            $status = $request->get(self::IS_IMPLEMENT);
            if($request->input(self::IS_IMPLEMENT) != 'all'){
                $query = $queryFilter
                    ->getQuery()
                    ->where(self::IS_IMPLEMENT, $request->get(self::IS_IMPLEMENT))
                ;
                $queryFilter->updateQuery($query);
            }
        }
        $queryFilter
            ->beforeDate(DB::raw('DATE(valid_from)'))
            ->afterDate(DB::raw('DATE(valid_from)'))
            ->getSortedAndOrderedData(self::VALID_FROM, 'desc')
        ;

        //get data from query
        $newExaminationCharge = $queryFilter
            ->getQuery()
            ->paginate($paginate);
        
        //leave message if not found
        if (count($newExaminationCharge) == 0){
            $dataNotFound = self::DATA_NOT_FOUND;
        }

        //return view
        return view('admin.newcharge.index')
            ->with('new_charge', $newCharge)
            ->with('dataNotFound', $dataNotFound)
            ->with('data', $newExaminationCharge)
            ->with(self::SEARCH, $search)
            ->with(self::BEFORE_DATE, $queryFilter->before)
            ->with(self::AFTER_DATE, $queryFilter->after)
            ->with('status', $status);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newCharge = NewExaminationCharge::where(self::IS_IMPLEMENT,0)->orderBy(self::VALID_FROM,"desc")->get();
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
        $this->validate($request, [
            self::NAME => self::REQUIRED,
            self::DESCRIPTION => self::REQUIRED,
            self::VALID_FROM => 'required|date',
        ]);

        $currentUser = Auth::user();
        $logService = new LogService();
			
        $charge = new NewExaminationCharge;
        $charge->id = Uuid::uuid4();
        $charge->name = $request->input(self::NAME);
        $charge->description = $request->input(self::DESCRIPTION);
        $charge->valid_from = $request->input(self::VALID_FROM);
        $charge->created_by = $currentUser->id;
        $charge->updated_by = $currentUser->id;

        try{
            $charge->save();
            $logService->createLog('Create New Charge', self::NEW_EXAMINATION_CHARGE, $charge);
            Session::flash(self::MESSAGE, 'New Charge successfully created');
            return redirect(self::ADMIN_NEWCHARGE.$charge->id);
        } catch(Exception $e){ return redirect('/admin/newcharge/create')->with(self::ERROR, self::SAVE_FAILED);
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
                                    ->where(self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', "$id")
                                ;
                            })
                        ->leftJoin(self::NEW_EXAMINATION_CHARGES, self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', self::ID_IN_NEW_EXAMINATION_CHARGES)
                        ->select(self::ALL_EXAMINATION_CHARGES)
                        ->whereNull(self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                        ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1)
                    ;

            $examinationCharge = $query->orderByRaw(self::CATEGORY_AND_DEVICE_NAME)->get();

            return view('admin.newcharge.createDetail')
                ->with('id', $id)
                ->with(self::EXAMINATION_CHARGE, $examinationCharge)
            ;
        }else{
            Session::flash(self::ERROR, 'The data has been processed!');
            return redirect('admin/newcharge/'.$id);
        }
    }

    public function postDetail(Request $request, $id)
    {
        $currentUser = Auth::user();

        $charge = new NewExaminationChargeDetail;
        $charge->id = Uuid::uuid4();
        $charge->created_by = $currentUser->id;
        $charge->created_at = date(self::DATE_FORMAT);
        
        $customSave = $this->customSaveDetail($charge,$id,$request,$currentUser);

        if($customSave == self::SUCCEED){
            Session::flash(self::MESSAGE, self::NEW_CHARGE_SUCCEED_UPDATED);
            return redirect(self::ADMIN_NEWCHARGE.$id);
        }
        return redirect(self::ADMIN_NEWCHARGE.$id.'/createDetail')->with(self::ERROR, self::SAVE_FAILED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //intial var
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        $category = '';
        $dataNotFound = '';
        
        //get initial data
        $logService = new LogService();
        $charge = NewExaminationCharge::find($id);
        $query = NewExaminationChargeDetail::whereNotNull('created_at')->where(self::NEW_EXAM_CHARGES_ID, $id);
        
        if ($search){
            $query
                ->where(self::DEVICE_NAME, 'like', '%'.strtolower($search).'%')
                ->orWhere('stel', 'like', '%'.strtolower($search).'%')
            ;

                $logService->createLog('Search Charge', self::NEW_EXAMINATION_CHARGE, json_encode(array(self::SEARCH=>$search)));
        }
        
        if ($request->has(self::CATEGORY)){
            $category = $request->get(self::CATEGORY);
            if($request->input(self::CATEGORY) != 'all'){
                $query->where(self::CATEGORY,'like', '%'.$request->get(self::CATEGORY).'%');
            }
        }

        $examinationCharge = $query->orderByRaw(self::CATEGORY_AND_DEVICE_NAME)->paginate($paginate);

        if (count($examinationCharge) == 0){
            $dataNotFound = self::DATA_NOT_FOUND;
        }
        
        return view('admin.newcharge.show')
            ->with('charge', $charge)
            ->with('dataNotFound', $dataNotFound)
            ->with('data', $examinationCharge)
            ->with(self::SEARCH, $search)
            ->with(self::CATEGORY, $category);
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
        $logService = new LogService();
        $oldData = clone $charge;

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
            
            $logService->createLog('Update New Charge', self::NEW_EXAMINATION_CHARGE, $oldData);
            Session::flash(self::MESSAGE, self::NEW_CHARGE_SUCCEED_UPDATED);
            return redirect(self::ADMIN_NEWCHARGE);
        } catch(Exception $e){ return redirect(self::ADMIN_NEWCHARGE.$charge->id.'/edit')->with(self::ERROR, self::SAVE_FAILED);
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
            ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1)
        ;
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
        
        $customSave = $this->customSaveDetail($charge,$id,$request,$currentUser);

        if($customSave == self::SUCCEED){
            Session::flash(self::MESSAGE, self::NEW_CHARGE_SUCCEED_UPDATED);
            return redirect(self::ADMIN_NEWCHARGE.$id);
        }
        return redirect(self::ADMIN_NEWCHARGE.$id.'/editDetail/'.$exam_id)->with(self::ERROR, self::SAVE_FAILED);
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
        $logService = new LogService();
        if ($charge){
            $oldData = clone $charge;
            try{
                $charge->delete();
                $logService->createLog('Delete Charge', self::NEW_EXAMINATION_CHARGE, $oldData);
                Session::flash(self::MESSAGE, 'New Charge successfully deleted');
                return redirect(self::ADMIN_NEWCHARGE);
            }catch (Exception $e){ return redirect(self::ADMIN_NEWCHARGE)->with(self::ERROR, 'Delete failed');
            }
        }
        return redirect(self::ADMIN_NEWCHARGE)
            ->with(self::ERROR, self::DATA_NOT_FOUND);
    }

    public function deleteDetail($id, $exam_id)
    {
        $charge = NewExaminationChargeDetail::find($exam_id);
        $logService = new LogService();
        if ($charge){
            $oldData = clone $charge;
            try{
                $charge->delete();
                $logService->createLog('Delete Charge Detail', self::NEW_EXAMINATION_CHARGE, $oldData);                
                Session::flash(self::MESSAGE, 'New Charge detail successfully deleted');
                return redirect(self::ADMIN_NEWCHARGE.$id);
            }catch (Exception $e){ return redirect(self::ADMIN_NEWCHARGE.$id)->with(self::ERROR, 'Delete failed');
            }
        }
        return redirect(self::ADMIN_NEWCHARGE)
            ->with(self::ERROR, self::DATA_NOT_FOUND);
    }

    private function implementNew($id){
        $currentUser = Auth::user();
        $logService = new LogService();

        /* query id examination_charges yang tidak ada di new_examination_charges_detail, lalu dinonaktifkan (is_active == 0) */
        $query = DB::table(self::EXAMINATION_CHARGES)
            ->leftJoin(self::NEW_EXAMINATION_CHARGES_DETAIL, function($q) use ($id){
                $q->on(self::ID_IN_EXAMINATION_CHARGES, '=', self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
                    ->where(self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', "$id");
            })
            ->leftJoin(self::NEW_EXAMINATION_CHARGES, self::NEW_EXAM_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES, '=', self::ID_IN_NEW_EXAMINATION_CHARGES)
            ->select(self::ID_IN_EXAMINATION_CHARGES)
            ->whereNull(self::EXAMINATION_CHARGES_ID_IN_NEW_EXAMINATION_CHARGES_DETAIL)
            ->whereNotNull(self::CREATED_AT_IN_EXAMINATION_CHARGES)->where(self::IS_ACTIVE_IN_EXAMINATION_CHARGES, 1)
        ;
        
        $examinationCharge = $query->get();
        
        for ($i=0; $i <count($examinationCharge) ; $i++) {
            $update = DB::table(self::EXAMINATION_CHARGES)
                ->where('id', $examinationCharge[$i]->id)
                ->update([
                    'is_active'     => '0',
                    'updated_by'    => $currentUser->id
                ])
            ;
        }

        /* implement dengan cek examination_charges.id = new_examination_charges_detail.examination_charges_id */       
        $data = NewExaminationChargeDetail::where("new_exam_charges_id", $id)->get();

        for ($i=0; $i <count($data) ; $i++) {
            $update = DB::table(self::EXAMINATION_CHARGES)
                ->where('id', $data[$i][self::EXAMINATION_CHARGES_ID])
                ->update([
                    self::DEVICE_NAME   => $data[$i][self::DEVICE_NAME],
                    'stel'              => $data[$i]['stel'],
                    self::CATEGORY      => $data[$i][self::CATEGORY],
                    self::DURATION      => $data[$i][self::DURATION],
                    self::PRICE         => $data[$i][self::NEW_PRICE],
                    self::VT_PRICE      => $data[$i][self::NEW_VT_PRICE],
                    self::TA_PRICE      => $data[$i][self::NEW_TA_PRICE],
                    'is_active'         => '1',
                    'updated_by'        => $currentUser->id
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
                    $logService->createLog('Create Charge', 'EXAMINATION CHARGE', $charge);
                } catch(Exception $e){
                    continue;
                }
            }
        }
    }

    private function customSaveDetail($charge,$id,Request $request,$currentUser)
    {

        $this->validate($request, [
            self::DEVICE_NAME => self::REQUIRED,
            self::STEL => self::REQUIRED,
            self::CATEGORY => self::REQUIRED,
            self::DURATION => self::REQUIRED,
            self::NEW_PRICE => self::REQUIRED,
            self::NEW_TA_PRICE => self::REQUIRED,
            self::NEW_VT_PRICE => self::REQUIRED,
        ]);


        $logService = new LogService();
        $charge->new_exam_charges_id = $id;
        if ($request->has(self::EXAMINATION_CHARGES_ID)){

            $this->validate($request, [
                'old_device_name' => self::REQUIRED,
                'old_stel' => self::REQUIRED,
                'old_category' => self::REQUIRED,
                'old_duration' => self::REQUIRED,
                self::PRICE => self::REQUIRED,
                self::TA_PRICE => self::REQUIRED,
                self::VT_PRICE => self::REQUIRED,
            ]);

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
        $charge->stel = $request->input(self::STEL);
        $charge->category = $request->input(self::CATEGORY);
        $charge->duration = str_replace(",","",$request->input(self::DURATION));
        $charge->new_price = str_replace(",","",$request->input(self::NEW_PRICE));
        $charge->new_ta_price = str_replace(",","",$request->input(self::NEW_TA_PRICE));
        $charge->new_vt_price = str_replace(",","",$request->input(self::NEW_VT_PRICE));
        $charge->updated_by = $currentUser->id;
        $charge->updated_at = date(self::DATE_FORMAT);

        try{
            $charge->save();
            $logService->createLog('Edit New Charge Detail', self::NEW_EXAMINATION_CHARGE, $charge);
            $status = self::SUCCEED;
        } catch(Exception $e){ $status = 'error';
        }

        return $status;
    }
}
