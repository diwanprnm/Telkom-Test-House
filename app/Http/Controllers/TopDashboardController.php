<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Examination;
use Auth;
use Illuminate\Support\Facades\DB;
use App\TrackerLog;
use App\TrackerSessions;
use App\StelSales;
use App\Income;
use App\STEL;
use App\ExaminationLab;

class TopDashboardController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth.admin');
    }
	
	public function index(Request $request)
    {
		$message = null;
		$search = null;
        $currentUser = Auth::user();
		$datenow = date('Y-m-d');
		$dateyesterday = date('Y-m-d',strtotime("-1 days"));
		$datelastweek = date('Y-m-d',strtotime("-7 days"));
		$thisDay = date('d');
		$thisMonth = date('m');
		$thisYear = date('Y');
			$datestring=date('Y-m-d').' first day of last month';
			$dt=date_create($datestring);
		$lastMonth = $dt->format('m');
		$lastYear = $dt->format('Y');
		
		$d=cal_days_in_month(CAL_GREGORIAN,$thisMonth,$thisYear);

        if ($currentUser){
				
			$pemohon = DB::select("
				select created_by, count(distinct created_by) as jml
				from examinations
				group by created_by;
			");
				$jmlpemohon = $pemohon[0]->jml;
            
			$perusahaan = DB::select("
				select company_id, count(distinct company_id) as jml
				from examinations
				group by company_id;
			");
				$jmlperusahaan = $perusahaan[0]->jml;
            
			$perangkat_lulus = DB::select("
				select count(*) as jml
				from examinations
				where registration_status = 1 AND function_status = 1 AND contract_status = 1
				AND spb_status = 1 AND payment_status = 1 AND spk_status = 1 AND examination_status = 1
				AND resume_status = 1 AND qa_status = 1 AND certificate_status = 1;
			");
			$jmlperangkatlulus = $perangkat_lulus[0]->jml;
            
			$count_reg = 0;
			$count_func = 0;
			$count_cont = 0;
			$count_spb = 0;
			$count_pay = 0;
			$count_spk = 0;
			$count_exam = 0;
			$count_resu = 0;
			$count_qa = 0;
			$count_cert = 0;
			$pengujian = Examination::all();
			foreach ($pengujian as $row) {
				if($row->registration_status < 1){$count_reg = $count_reg + 1;}
				else if($row->registration_status == 1 and $row->function_status < 1){$count_func = $count_func + 1;}
				else if($row->function_status == 1 and $row->contract_status < 1){$count_cont = $count_cont + 1;}
				else if($row->contract_status == 1 and $row->spb_status < 1){$count_spb = $count_spb + 1;}
				else if($row->spb_status == 1 and $row->payment_status < 1){$count_pay = $count_pay + 1;}
				else if($row->payment_status == 1 and $row->spk_status < 1){$count_spk = $count_spk + 1;}
				else if($row->spk_status == 1 and $row->examination_status < 1){$count_exam = $count_exam + 1;}
				else if($row->examination_status == 1 and $row->resume_status < 1){$count_resu = $count_resu + 1;}
				else if($row->resume_status == 1 and $row->qa_status < 1){$count_qa = $count_qa + 1;}
				else if($row->qa_status == 1 and $row->certificate_status < 1){$count_cert = $count_cert + 1;}
			}

			$deviceNotComp = Examination::where('qa_passed', -1)->count();
            
			$data = [
				'jml_pemohon' => $jmlpemohon,
				'jml_perusahaan' => $jmlperusahaan,
				'jml_perangkatlulus' => $jmlperangkatlulus,
				'count_reg' => $count_reg,
				'count_func' => $count_func,
				'count_cont' => $count_cont,
				'count_spb' => $count_spb,
				'count_pay' => $count_pay,
				'count_spk' => $count_spk,
				'count_exam' => $count_exam,
				'count_resu' => $count_resu,
				'count_qa' => $count_qa,
				'count_cert' => $count_cert,
				'count_exam_all' => count($pengujian),
				'count_dev_notComp' => $deviceNotComp
			];
			// echo"<pre>";print_r($data);exit;
			
            if (count($data) == 0){
                $message = 'Data not found';
            }
			
			for($i=0;$i<12;$i++){

				$sum_stel_kab = 0;
				$id_kab = ExaminationLab::where('name', 'like', '%kabel%')->select('id')->first();
				$jml_stel_kab = StelSales::with(['sales_detail' => function ($query) use ($id_kab) {
		            $query->with('stel')->whereHas('stel', function ($q) use ($id_kab) {
		                return $q->where('type', $id_kab->id);
		            })->get();
		        }])
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->get();

		        foreach($jml_stel_kab as $item_sales){
		        	if ($item_sales->sales_detail != []){
			        	foreach($item_sales->sales_detail as $item){
			        		$sum_stel_kab = $sum_stel_kab + $item->stel->price;
			        	}
			        }
		        }

		        $sum_stel_ene = 0;
				$id_ene = ExaminationLab::where('name', 'like', '%energi%')->select('id')->first();
				$jml_stel_ene = StelSales::with(['sales_detail' => function ($query) use ($id_ene) {
		            $query->with('stel')->whereHas('stel', function ($q) use ($id_ene) {
		                return $q->where('type', $id_ene->id);
		            })->get();
		        }])
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->get();

		        foreach($jml_stel_ene as $item_sales){
		        	if ($item_sales->sales_detail != []){
			        	foreach($item_sales->sales_detail as $item){
			        		$sum_stel_ene = $sum_stel_ene + $item->stel->price;
			        	}
			        }
		        }

		        $sum_stel_tra = 0;
				$id_tra = ExaminationLab::where('name', 'like', '%transmisi%')->select('id')->first();
				$jml_stel_tra = StelSales::with(['sales_detail' => function ($query) use ($id_tra) {
		            $query->with('stel')->whereHas('stel', function ($q) use ($id_tra) {
		                return $q->where('type', $id_tra->id);
		            })->get();
		        }])
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->get();

		        foreach($jml_stel_tra as $item_sales){
		        	if ($item_sales->sales_detail != []){
			        	foreach($item_sales->sales_detail as $item){
			        		$sum_stel_tra = $sum_stel_tra + $item->stel->price;
			        	}
			        }
		        }

		        $sum_stel_cpe = 0;
				$id_cpe = ExaminationLab::where('name', 'like', '%cpe%')->select('id')->first();
				$jml_stel_cpe = StelSales::with(['sales_detail' => function ($query) use ($id_cpe) {
		            $query->with('stel')->whereHas('stel', function ($q) use ($id_cpe) {
		                return $q->where('type', $id_cpe->id);
		            })->get();
		        }])
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->get();

		        foreach($jml_stel_cpe as $item_sales){
		        	if ($item_sales->sales_detail != []){
			        	foreach($item_sales->sales_detail as $item){
			        		$sum_stel_cpe = $sum_stel_cpe + $item->stel->price;
			        	}
			        }
		        }

				$jml_stel = DB::select("
					SELECT sum(total) AS jml
					from stels_sales where payment_status = 1
					AND YEAR(created_at) = ".$thisYear."
					AND MONTH(created_at) = ".($i+1)."
					;
				");

				$jml_device_qa = Income::whereHas('examination', function ($q){
						return $q->where('examination_type_id', 1);
					})
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->select('price')
		        ->sum('price');

		        $jml_device_vt = Income::whereHas('examination', function ($q){
						return $q->where('examination_type_id', 3);
					})
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->select('price')
		        ->sum('price');

		        $jml_device_ta = Income::whereHas('examination', function ($q){
						return $q->where('examination_type_id', 2);
					})
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->select('price')
		        ->sum('price');

		        $jml_device_cal = Income::whereHas('examination', function ($q){
						return $q->where('examination_type_id', 4);
					})
		        ->whereYear('created_at', '=', $thisYear)
		        ->whereMonth('created_at', '=', $i+1)
		        ->select('price')
		        ->sum('price');

				$jml_device = DB::select("
					SELECT sum(price) AS jml
					from incomes where YEAR(tgl) = ".$thisYear."
					AND MONTH(tgl) = ".($i+1)."
					;
				");				
				$chart['stel'][$i]=(float)$jml_stel[0]->jml;
				$chart['stel_kab'][$i]=(float)$sum_stel_kab;
				$chart['stel_ene'][$i]=(float)$sum_stel_ene;
				$chart['stel_tra'][$i]=(float)$sum_stel_tra;
				$chart['stel_cpe'][$i]=(float)$sum_stel_cpe;

				$chart['device'][$i]=(float)$jml_device[0]->jml;
				$chart['device_qa'][$i] = (float)$jml_device_qa;
				$chart['device_vt'][$i] = (float)$jml_device_vt;
				$chart['device_ta'][$i] = (float)$jml_device_ta;
				$chart['device_cal'][$i] = (float)$jml_device_cal;
			}
			
            return view('admin.topdashboard.index')
                ->with('search', $search)
                ->with('message', $message)
                ->with('tahun', $thisYear)
                ->with('data', $data)
                ->with('stel', $chart['stel'])
                ->with('stel_kab', $chart['stel_kab'])
                ->with('stel_ene', $chart['stel_ene'])
                ->with('stel_tra', $chart['stel_tra'])
                ->with('stel_cpe', $chart['stel_cpe'])
                ->with('device', $chart['device'])
                ->with('device_qa', $chart['device_qa'])
                ->with('device_vt', $chart['device_vt'])
                ->with('device_ta', $chart['device_ta'])
                ->with('device_cal', $chart['device_cal']);
        }
    }
	
	public function temp_sess_today($b){
		// $return_today = 0;
		$temp_sess = DB::select("
			SELECT COUNT(DISTINCT
					(s1.client_ip)) AS jml
				FROM
					tracker_sessions s1
				RIGHT JOIN (
					SELECT DISTINCT
						(s2.client_ip)
					FROM
						tracker_sessions s2
					WHERE
						DATE(s2.created_at) < '".$b."'
				) AS test_join ON s1.client_ip = test_join.client_ip
				WHERE
					DATE(s1.created_at) = '".$b."'
		");
		// foreach ($a as $row) {
			// $temp_sess = DB::select("SELECT COUNT(*) as jml FROM tracker_sessions WHERE client_ip = '".$row->client_ip."' AND DATE(created_at) < '".$b."'");
			// if($temp_sess[0]->jml > 0){
				// $return_today = $return_today + 1;
			// }
		// }
		return $temp_sess[0]->jml;
	}
	
	public function searchGrafik(Request $request){
		if($request->input('type') == 1){
			$count_stel = 0;
			for($i=0;$i<12;$i++){
				$jml_stel = DB::select("
					SELECT sum(total) AS jml
					from stels_sales where payment_status = 1
					AND YEAR(created_at) = ".$request->input('keyword')."
					AND MONTH(created_at) = ".($i+1)."
					;
				");			
				$chart['stel'][$i]=(float)$jml_stel[0]->jml;
				$count_stel = $count_stel + $jml_stel[0]->jml;
			}
			echo json_encode($chart['stel'])."||";
			echo json_encode($count_stel)."||";
		}else{
			$count_device = 0;
			for($i=0;$i<12;$i++){
				$jml_device = DB::select("
					SELECT sum(price) AS jml
					from incomes where YEAR(tgl) = ".$request->input('keyword')."
					AND MONTH(tgl) = ".($i+1)."
					;
				");				
				$chart['device'][$i]=(float)$jml_device[0]->jml;
				$count_device = $count_device + $jml_device[0]->jml;
			}
			echo json_encode($chart['device'])."||";
			echo json_encode($count_device)."||";
		}
	}
}