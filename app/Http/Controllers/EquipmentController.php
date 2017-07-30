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

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class EquipmentController extends Controller
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
                $equipments = Equipment::whereNotNull('created_at')
                    ->with('examination.device')
                    ->with('user')
                    ->where('name','like','%'.$search.'%')
                    ->orderBy('name')
                    ->paginate($paginate);

                    $logs = new Logs;
                    $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
                    $logs->action = "Search User";
                    $datasearch = array("search"=>$search);
                    $logs->data = json_encode($datasearch);
                    $logs->created_by = $currentUser->id;
                    $logs->page = "EQUIPMENT";
                    $logs->save();
            }else{
                $query = Equipment::whereNotNull('created_at')
					->with('examination.device')
                    ->with('user');

                $equipments = $query->orderBy('name')
                            ->paginate($paginate);

                $devices = $query->orderBy('name')
                            ->groupBy('examination_id')
                            ->paginate($paginate);
            }
            
            if (count($equipments) == 0){
                $message = 'Data not found';
            }

            // return $equipments;
            
            return view('admin.equipment.index')
                ->with('message', $message)
                ->with('data', $devices)
                ->with('equipments', $equipments)
                ->with('search', $search);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$exam_id = $request->session()->pull('key_exam_id_for_generate_equip_masuk');
		$examination = DB::table('examinations')
			->join('devices', 'examinations.device_id', '=', 'devices.id')
			->select(
					'examinations.id',
					'devices.name',
					'devices.model'
					)
			->orderBy('devices.name')
			->get();
		
        return view('admin.equipment.create')
            ->with('exam_id', $exam_id)
            ->with('examination', $examination);
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

        // return $request->all();

        for ($key=0; $key < sizeof($request->input('qty')); $key++) {
            $equipment = new Equipment;
            $equipment->id = Uuid::uuid4();
            $equipment->examination_id = $request->input('examination_id');
            $equipment->name = 'name';
            $equipment->qty = $request->input('qty.'.$key);
            $equipment->unit = $request->input('unit.'.$key);
            $equipment->description = $request->input('description.'.$key);
            $equipment->location = 2;
            $equipment->pic = $request->input('pic.'.$key);
            $equipment->remarks = $request->input('remarks.'.$key);

            $equipment->created_by = $currentUser->id;
            $equipment->updated_by = $currentUser->id;
            
            $equipment->created_at = ''.date('Y-m-d h:i:s').'';
            $equipment->updated_at = ''.date('Y-m-d h:i:s').'';

            $equipment->save();
        }

        try{
			$examination = Examination::where('id', $equipment->examination_id)->first();
			$examination->location = 2;
			$examination->save();

			$equipmenth = new EquipmentHistory;
			$equipmenth->id = Uuid::uuid4();
			$equipmenth->examination_id = $equipment->examination_id;
			$equipmenth->action_date = $request->input('equip_date');
			$equipmenth->location = 2;
			
			$equipmenth->created_by = $currentUser->id;
			$equipmenth->updated_by = $currentUser->id;
			$equipmenth->created_at = ''.date('Y-m-d h:i:s').'';
			$equipmenth->updated_at = ''.date('Y-m-d h:i:s').'';

			$equipmenth->save();
            
            $logs = new Logs;
			$logs->id = Uuid::uuid4();
            $logs->user_id = $currentUser->id;
            $logs->action = "Create Equipment"; 
            $logs->data = $equipment;
            $logs->created_by = $currentUser->id;
            $logs->page = "EQUIPMENT";
            $logs->save();

            Session::flash('message', 'Equipment successfully created');
            return redirect('/admin/equipment');
        } catch(\Exception $e){
            return $e;
			Session::flash('error', 'Save Failed');
            return redirect('/admin/equipment/create')
                        ->withInput();
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
        $equipment = Equipment::where('examination_id', $id)->get();
        $EquipmentHistory = EquipmentHistory::where('examination_id', $id)->get();
        $location = Equipment::where('examination_id', $id)->first();
        $examination = DB::table('examinations')
            ->join('devices', 'examinations.device_id', '=', 'devices.id')
            ->select(
                    'examinations.id',
                    'devices.name',
                    'devices.model'
                    )
            ->where('examinations.id', $id)
            ->orderBy('devices.name')
            ->first();

        return view('admin.equipment.show')
            ->with('item', $examination)
            ->with('location', $location)
            ->with('data', $equipment)
            ->with('history', $EquipmentHistory);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipment = Equipment::where('examination_id', $id)->get();
        $location = Equipment::where('examination_id', $id)->first();
        $examination = DB::table('examinations')
			->join('devices', 'examinations.device_id', '=', 'devices.id')
			->select(
					'examinations.id',
					'devices.name',
					'devices.model'
					)
            ->where('examinations.id', $id)
			->orderBy('devices.name')
			->first();

        return view('admin.equipment.edit')
            ->with('item', $examination)
            ->with('location', $location)
            ->with('data', $equipment);
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

        $equipment = Equipment::where('examination_id', $id)
                    ->update(['location' => $request->input('location'), 
                        'pic' => $request->input('pic'),
                        'updated_by' => $currentUser->id]);

        try{
			
			if($request->input('location') != $request->input('location_id')){
				$equipmenth = new EquipmentHistory;
				$equipmenth->id = Uuid::uuid4();
				$equipmenth->examination_id = $id;
				$equipmenth->action_date = $request->input('equip_date');
				$equipmenth->location = $request->input('location');
				
				$equipmenth->updated_by = $currentUser->id;
				$equipmenth->updated_at = ''.date('Y-m-d h:i:s').'';

				$equipmenth->save();
				
				$examination = Examination::where('id', $id)->first();
				$examination->location = $request->input('location');
				$examination->save();
			}
            
            $logs = new Logs;
            $logs->user_id = $currentUser->id;$logs->id = Uuid::uuid4();
            $logs->action = "Update Equipment"; 
            $logs->data = "";
            $logs->created_by = $currentUser->id;
            $logs->page = "EQUIPMENT";
            $logs->save();
            
            Session::flash('message', 'Equipment successfully updated');
            return redirect('/admin/equipment');
        } catch(Exception $e){
            Session::flash('error', 'Save failed');
            return redirect('/admin/equipment/'.$equipment->id.'edit');
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
		$equipment = Equipment::find($id);
		$equipmenth = EquipmentHistory::where('equipment_id', $id);

        if ($equipment){
            try{
                $equipmenth->delete();
                $equipment->delete();
                
                Session::flash('message', 'Equipment successfully deleted');
                return redirect('/admin/equipment');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/equipment');
            }
        }
    }
	
	public function autocomplete($query) {
        $respons_result = User::autocomplet($query);
        return response($respons_result);
    }
}