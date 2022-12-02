<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

use App\Company;
use App\User;
use App\Examination;
use App\EquipmentHistory;
use App\Equipment;
use App\Logs;

use Auth;
use Session;
use Hash;
use Image;
use Exception;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Events\Notification;
use App\NotificationTable;
use App\Services\Logs\LogService;
use App\Services\NotificationService;

class EquipmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private const SEARCH = 'search';
    private const CREATED = 'created_at';
    private const EQUIPMENT_LC = 'equipment';
    private const EQUIPMENT = "EQUIPMENT";
    private const EQUIPMENT_HISTORY = 'equipmentHistory';
    private const EXAM_ID = 'examination_id';
    private const MESSAGE = 'message';
    private const EXAMINATIONS = 'examinations';
    private const DEVICE = 'devices';
    private const EXAMINATIONDEVICE = 'examinations.device_id';
    private const DEVICEID = 'devices.id';
    private const EXAMID = 'examinations.id';
    private const DEVICENAME = 'devices.name';
    private const DEVICEMODEL = 'devices.model';
    private const YMDH = 'Y-m-d H:i:s';
    private const EQUIPDATE = 'equip_date';
    private const ADMINEQUIP = '/admin/equipment';
    private const ERROR ='error';
    private const LOCATION = 'location';
    private const EXAMINATION = 'examination';

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
        $search = trim($request->input($this::SEARCH));

        $query = Equipment::whereNotNull($this::CREATED)
            ->with('examination.device')
            ->with('user')
        ;

        if ($search)
        {
            $query->where('name','like','%'.$search.'%');
            $logService = new LogService();
            $logService->createLog("Search User",self::EQUIPMENT, json_encode( array("search"=>$search)) );
        }
            
        $equipments = $query->orderBy('name')
            ->paginate($paginate)
        ;

        $devices = $query->orderBy('name')
            ->groupBy($this::EXAM_ID)
            ->paginate($paginate)
        ;
        
        if (!count($equipments))
        {
            $message = 'Data not found';
        }

        return view('admin.equipment.index')
            ->with($this::MESSAGE, $message)
            ->with('data', $devices)
            ->with('equipments', $equipments)
            ->with($this::SEARCH, $search)
        ;
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$exam_id = $request->session()->get('key_exam_id_for_generate_equip_masuk');
		$in_equip_date = $request->session()->get('key_in_equip_date_for_generate_equip_masuk');
		$examination = Examination::where('id', $exam_id)->with('device')->with('user')->first();
		return view('admin.equipment.create')
            ->with('exam_id', $exam_id)
            ->with('in_equip_date', $in_equip_date)
            ->with($this::EXAMINATION, $examination);
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

        for ($key=0; $key < sizeof($request->input('qty')); $key++) {
            $equipment = new Equipment;
            $equipment->id = Uuid::uuid4();
            $equipment->examination_id = $request->input($this::EXAM_ID);
            $equipment->name = 'name';
            $equipment->qty = $request->input('qty.'.$key);
            $equipment->unit = $request->input('unit.'.$key);
            $equipment->description = $request->input('description.'.$key);
            $equipment->location = 2;
            $equipment->pic = $request->input('pic');
            $equipment->remarks = $request->input('remarks.'.$key);
            $equipment->created_by = $currentUser->id;
            $equipment->updated_by = $currentUser->id;
            $equipment->created_at = ''.date($this::YMDH).'';
            $equipment->updated_at = ''.date($this::YMDH).'';
            $equipment->save();
        }
        
       try{
			$examination = Examination::where('id', $equipment->examination_id)->first();
			$examination->contract_date = $request->input($this::EQUIPDATE);
            $examination->location = 2;
            $examination->function_test_status_detail = 'Perangkat siap diuji fungsi';
			$examination->save();

			$equipmenth = new EquipmentHistory;
			$equipmenth->id = Uuid::uuid4();
			$equipmenth->examination_id = $equipment->examination_id;
			$equipmenth->action_date = $request->input($this::EQUIPDATE);
			$equipmenth->location = 2;
			$equipmenth->created_by = $currentUser->id;
			$equipmenth->updated_by = $currentUser->id;
			$equipmenth->created_at = ''.date($this::YMDH).'';
			$equipmenth->updated_at = ''.date($this::YMDH).'';
			$equipmenth->save();

            $logService = new LogService();
            $logService->createLog( "Create Equipment",self::EQUIPMENT, $equipment );

            /* push notif*/
            $data= array( 
                "from"=>"admin",
                "to"=>$examination->created_by,
                "message"=>"Perangkat yang akan diuji, sudah masuk Gudang Urel",
                "url"=>"pengujian/".$equipment->examination_id."/detail",
                "is_read"=>0,
                "created_by"=>$currentUser->id,
                "updated_by"=>$currentUser->id,
                "created_at"=>date($this::YMDH),
                "updated_at"=>date(self::YMDH)
            );

            $notificationService = new NotificationService();
            $notification_id = $notificationService->make($data);

            $data['id'] = $notification_id;  
            event(new Notification($data));
            Session::flash($this::MESSAGE, 'Equipment successfully created');
            return redirect($this::ADMINEQUIP);
       } catch(\Exception $e){ return redirect('/admin/equipment/create')->withInput()->with($this::ERROR, 'Save Failed');
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
        $data = $this->getData($id);

        return view('admin.equipment.show')
            ->with('item', $data[$this::EXAMINATION])
            ->with($this::LOCATION, $data[$this::LOCATION])
            ->with('data', $data[$this::EQUIPMENT_LC])
            ->with('history', $data[$this::EQUIPMENT_HISTORY])
        ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->getData($id);

        return view('admin.equipment.edit')
            ->with('item', $data[$this::EXAMINATION])
            ->with($this::LOCATION, $data[$this::LOCATION])
            ->with('data', $data[$this::EQUIPMENT_LC])
            ->with('history', $data[$this::EQUIPMENT_HISTORY])
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

        $equipment = Equipment::where($this::EXAM_ID, $id)
            ->update([
                $this::LOCATION => $request->input($this::LOCATION), 
                'pic' => $request->input('pic'),
                'updated_by' => $currentUser->id]
        );

        try{
			if($request->input($this::LOCATION) != $request->input('location_id')){
				$equipmenth = new EquipmentHistory;
				$equipmenth->id = Uuid::uuid4();
				$equipmenth->examination_id = $id;
				$equipmenth->action_date = $request->input($this::EQUIPDATE);
				$equipmenth->location = $request->input($this::LOCATION);
				$equipmenth->created_by = $currentUser->id;
				$equipmenth->updated_by = $currentUser->id;
				$equipmenth->created_at = ''.date($this::YMDH).'';
				$equipmenth->updated_at = ''.date($this::YMDH).'';
				$equipmenth->save();
				
				$examination = Examination::where('id', $id)->first();
				$examination->location = $request->input($this::LOCATION);
                $examination->save();
			}
            
            $logService = new LogService();
            $logService->createLog( "Update Equipment",self::EQUIPMENT, "" );
            
            Session::flash($this::MESSAGE, 'Equipment successfully updated');
            return redirect($this::ADMINEQUIP);
        } catch(Exception $e){ return redirect('/admin/equipment/'.$equipment->id.'edit')->with($this::ERROR, 'Save failed');
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
		$equipment = Equipment::where($this::EXAM_ID, $id);
		$equipmenth = EquipmentHistory::where($this::EXAM_ID, $id);
        if ($equipment->first()){
            
            try{
                $equipmenth->delete();
                $equipment->delete();
                
                Session::flash($this::MESSAGE, 'Equipment successfully deleted');
                return redirect($this::ADMINEQUIP);
            }catch (Exception $e){ return redirect($this::ADMINEQUIP)->with($this::ERROR, 'Delete failed');
            }
        }
        return redirect($this::ADMINEQUIP)
            ->with($this::ERROR, 'Equipment not found')
        ;
    }

    private function getData($id){
        $EquipmentHistory = EquipmentHistory::where($this::EXAM_ID, $id)->get();
        $equipment = Equipment::where($this::EXAM_ID, $id)->get();
        $location = Equipment::where($this::EXAM_ID, $id)->first();
        $examination = DB::table($this::EXAMINATIONS)
            ->join($this::DEVICE, $this::EXAMINATIONDEVICE, '=', $this::DEVICEID)
            ->select(
                $this::EXAMID,
                $this::DEVICENAME,
                $this::DEVICEMODEL
            )
            ->where($this::EXAMID, $id)
            ->orderBy($this::DEVICENAME)
            ->first()
        ;

        return array(
            $this::EQUIPMENT_HISTORY => $EquipmentHistory,
            $this::EQUIPMENT_LC => $equipment,
            $this::LOCATION => $location,
            $this::EXAMINATION => $examination,
        );
    }
}