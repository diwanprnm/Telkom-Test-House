<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Examination;
use Auth;
use Illuminate\Support\Facades\DB;
use App\TrackerLog;
use App\TrackerSessions;

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
		$now = date('Y-m-d H:i:s');
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
			/*TODAY*/
            $log = DB::select("
				SELECT * FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%'
				AND DATE(l.created_at) = '".$datenow."'
			");
				$log_now = count($log);
			$sess = DB::select("
				SELECT DISTINCT(client_ip) FROM tracker_sessions WHERE DATE(created_at) = '".$datenow."'
			");
				// $sess_now_old = $this->temp_sess_today($sess,$datenow);
				$sess_now_old = $this->temp_sess_today($datenow);
				$sess_now = count($sess) - $sess_now_old;
            /*YESTERDAY*/
			$log = DB::select("
				SELECT * FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%'
				AND DATE(l.created_at) = '".$dateyesterday."'
			");
				$log_yesterday = count($log);
            $sess = DB::select("SELECT DISTINCT(client_ip) FROM tracker_sessions WHERE DATE(created_at) = '".$dateyesterday."'");
				$sess_yesterday_old = $this->temp_sess_today($dateyesterday);
				$sess_yesterday = count($sess) - $sess_yesterday_old;
            /*LAST WEEK*/
            $log = DB::select("
				SELECT * FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%'
				AND DATE(l.created_at) BETWEEN '".$datelastweek."' AND '".$datenow."'
			");
				$log_lastweek = count($log);
            $sess = DB::select("SELECT DISTINCT(client_ip) FROM tracker_sessions WHERE DATE(created_at) BETWEEN '".$datelastweek."' AND '".$datenow."'");
				$sess_lastweek_old = $this->temp_sess_today($datelastweek);
				$sess_lastweek = count($sess) - $sess_lastweek_old;
            /*THIS MONTH*/
			$log = DB::select("
				SELECT * FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%'
				AND MONTH(l.created_at) = ".$thisMonth." AND YEAR(l.created_at) = ".$thisYear."
			");
				$log_thismonth = count($log);
			$sess = DB::select("SELECT DISTINCT(client_ip) FROM tracker_sessions WHERE MONTH(created_at) = '".$thisMonth."' AND YEAR(created_at) = '".$thisYear."'");
				$sess_thismonth_old = $this->temp_sess_today('".$thisYear."-".$thisMonth."-01');
				$sess_thismonth = count($sess) - $sess_thismonth_old;
            /*LAST MONTH*/
            $log = DB::select("
				SELECT * FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%'
				AND MONTH(l.created_at) = ".$lastMonth." AND YEAR(l.created_at) = ".$lastYear."
			");
				$log_lastmonth = count($log);
			$sess = DB::select("SELECT DISTINCT(client_ip) FROM tracker_sessions WHERE MONTH(created_at) = '".$lastMonth."' AND YEAR(created_at) = '".$lastYear."'");
				$sess_lastmonth_old = $this->temp_sess_today('".$lastMonth."-".$lastYear."-01');
				$sess_lastmonth = count($sess) - $sess_lastmonth_old;
            /*SITE HISTORY*/
			$log = DB::select("
				SELECT l.created_at FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%' ORDER BY l.created_at LIMIT 1
			");
				$first_log = $log[0]->created_at;
            $log = DB::select("
				SELECT l.created_at FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%' ORDER BY l.created_at DESC LIMIT 1
			");
				$last_log = $log[0]->created_at;
			$log = DB::select("
				SELECT COUNT(*) as jml FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%'
			");
				$log_count = $log[0]->jml;
            $sess = DB::select("SELECT COUNT(DISTINCT(client_ip)) as jml FROM tracker_sessions");
				$sess_count = $sess[0]->jml;
			$log = DB::select("
				SELECT DATE(l.created_at) AS created_at, COUNT(l.created_at) AS jml FROM tracker_log l, tracker_route_paths rp 
				WHERE l.route_path_id = rp.id 
				AND rp.path NOT LIKE '%admin%' AND rp.path NOT LIKE '%mylogsbl%' GROUP BY DATE(l.created_at) ORDER BY jml DESC LIMIT 1
			");
            	$log_max_date = $log[0]->created_at;
				$log_max_count = $log[0]->jml;
				
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
            
			$data = [
				'now' => $now,
				'log_now' => $log_now,
				'sess_now' => $sess_now,
				'sess_now_old' => $sess_now_old,
				'sess_now_total' => ($sess_now + $sess_now_old),
				'log_yesterday' => $log_yesterday,
				'sess_yesterday' => $sess_yesterday,
				'sess_yesterday_old' => $sess_yesterday_old,
				'sess_yesterday_total' => ($sess_yesterday + $sess_yesterday_old),
				'log_lastweek' => $log_lastweek,
				'sess_lastweek' => $sess_lastweek,
				'sess_lastweek_old' => $sess_lastweek_old,
				'sess_lastweek_total' => ($sess_lastweek + $sess_lastweek_old),
				'log_thismonthavg' => (number_format(($log_thismonth/$thisDay), 2, '.', '')),
				'sess_thismonthavg' => (number_format(($sess_thismonth/$thisDay), 2, '.', '')),
				'sess_thismonth_old' => (number_format(($sess_thismonth_old/$thisDay), 2, '.', '')),
				'sess_thismonthavg_total' => (number_format((($sess_thismonth/$thisDay) + ($sess_thismonth_old/$thisDay)), 2, '.', '')),
				'log_thismonth' => $log_thismonth,
				'sess_thismonth' => $sess_thismonth,
				'sess_thismonth_old' => $sess_thismonth_old,
				'sess_thismonth_total' => ($sess_thismonth + $sess_thismonth_old),
				'log_lastmonth' => $log_lastmonth,
				'sess_lastmonth' => $sess_lastmonth,
				'sess_lastmonth_old' => $sess_lastmonth_old,
				'sess_lastmonth_total' => ($sess_lastmonth + $sess_lastmonth_old),
				'first_log' => $first_log,
				'last_log' => $last_log,
				'log_count' => $log_count,
				'sess_count' => $sess_count,
				'log_max_date' => $log_max_date,
				'log_max_count' => $log_max_count,
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
				'count_exam_all' => count($pengujian)
			];
			// echo"<pre>";print_r($data);exit;
			
            if (count($data) == 0){
                $message = 'Data not found';
            }
			
			$count_stel = 0;
			$count_device = 0;
			
			for($i=0;$i<12;$i++){
				$jml_stel = DB::select("
					SELECT sum(total) AS jml
					from stels_sales where payment_status = 1
					AND YEAR(created_at) = ".$thisYear."
					AND MONTH(created_at) = ".($i+1)."
					;
				");				
				$jml_device = DB::select("
					SELECT sum(price) AS jml
					from incomes where YEAR(tgl) = ".$thisYear."
					AND MONTH(tgl) = ".($i+1)."
					;
				");				
				$chart['stel'][$i]=(float)$jml_stel[0]->jml;
					$count_stel = $count_stel + $jml_stel[0]->jml;
				$chart['device'][$i]=(float)$jml_device[0]->jml;
					$count_device = $count_device + $jml_device[0]->jml;
			}
			
            return view('admin.topdashboard.index')
                ->with('search', $search)
                ->with('message', $message)
                ->with('tahun', $thisYear)
                ->with('data', $data)
                ->with('stel', $chart['stel'])
                ->with('device', $chart['device'])
                ->with('count_stel', $count_stel)
                ->with('count_device', $count_device)
				;
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