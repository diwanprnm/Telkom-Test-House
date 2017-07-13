<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\DB;
use App\TrackerLog;
use App\TrackerSessions;

class AnalyticController extends Controller
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
				'log_max_count' => $log_max_count
			];
			// echo"<pre>";print_r($data);exit;
			
            if (count($data) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.analytic.index')
                ->with('search', $search)
                ->with('message', $message)
                ->with('data', $data);
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
}