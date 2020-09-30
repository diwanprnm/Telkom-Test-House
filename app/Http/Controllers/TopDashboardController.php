<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Examination;
use Auth;
use Illuminate\Support\Facades\DB;
use App\TrackerLog;
use App\TrackerSessions;
use App\STELSales;
use App\Income;
use App\STEL;
use App\ExaminationLab;

class TopDashboardController extends Controller
{

	private const STELS = "stels_sales.id";
	private const UPDATE = 'stels_sales_attachment.updated_at';
	private const KAB = 'stel_kab';
	private const ENE = 'stel_ene';
	private const TRA = 'stel_tra';
	private const CPE = 'stel_cpe';
	private const KAL = 'stel_kal';
	private const EXAM = 'examination';
	private const EXAMID = 'examination_type_id';
	private const CREATED = 'created_at';
	private const PRICE = 'price' ;
	private const DEVICE = 'device';
	private const QA = 'device_qa';
	private const VT = 'device_vt';
	private const TA = 'device_ta';
	private const CAL = 'device_cal';
	private const KEYW = 'keyword';


	public function __construct()
    {
        $this->middleware('auth.admin');
    }
	
	public function index(Request $request)
    {
		$message = null; $search = null; $currentUser = Auth::user();	$thisYear = date('Y');
      	if ($currentUser){
			$pemohon = DB::select("
				select count(distinct created_by) as jml
				from examinations;
			");$jmlpemohon = $pemohon[0]->jml;
			$perusahaan = DB::select("
				select count(distinct company_id) as jml
				from examinations;
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
			$count_reg = 0; $count_func = 0; $count_cont = 0; $count_spb = 0;	$count_pay = 0;	$count_spk = 0;
			$count_exam = 0; $count_resu = 0; $count_qa = 0;$count_cert = 0; $pengujian = Examination::all();
			foreach ($pengujian as $row) {
				if($row->registration_status < 1){$count_reg = $count_reg + 1;}
				else if($row->registration_status == 1 && $row->function_status < 1){$count_func = $count_func + 1;}
				else if($row->function_status == 1 && $row->contract_status < 1){$count_cont = $count_cont + 1;}
				else if($row->contract_status == 1 && $row->spb_status < 1){$count_spb = $count_spb + 1;}
				else if($row->spb_status == 1 && $row->payment_status < 1){$count_pay = $count_pay + 1;}
				else if($row->payment_status == 1 && $row->spk_status < 1){$count_spk = $count_spk + 1;}
				else if($row->spk_status == 1 && $row->examination_status < 1){$count_exam = $count_exam + 1;}
				else if($row->examination_status == 1 && $row->resume_status < 1){$count_resu = $count_resu + 1;}
				else if($row->resume_status == 1 && $row->qa_status < 1){$count_qa = $count_qa + 1;}
				else if($row->qa_status == 1 && $row->certificate_status < 1){$count_cert = $count_cert + 1;}
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
            if (count($data) == 0){
                $message = 'Data not found';
			}
			
			for($i=0;$i<12;$i++){
				$sum_stelFunc = $this->sum_stelFunc($thisYear, $i);

				$chart['stel'][$i]=(float)$sum_stelFunc[0]; $chart[$this::KAB][$i]=(float)$sum_stelFunc[1]; $chart[$this::ENE][$i]=(float)$sum_stelFunc[2];
				$chart[$this::TRA][$i]=(float)$sum_stelFunc[3]; $chart[$this::CPE][$i]=(float)$sum_stelFunc[4]; $chart[$this::KAL][$i]=(float)$sum_stelFunc[5];
				$jml_device_qa = Income::where("reference_id", '=', 1)
		        
				 ->where(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $thisYear)
				->where(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);
		        $jml_device_vt = Income::where("reference_id", '=', 3)
		        ->where(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $thisYear)
				->where(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);
		        $jml_device_ta = Income::where("reference_id", '=', 2)
		         ->where(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $thisYear)
				->where(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);
		        $jml_device_cal = Income::where("reference_id", '=', 4)
		        ->where(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $thisYear)
				->where(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);

		        $jml_device = Income::where(DB::raw("SUBSTR(tgl, 1, 4)"), '=', $thisYear)
				->where(DB::raw("SUBSTR(tgl, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);

				@$chart[$this::DEVICE][$i]=(float)$jml_device[0]->jml;  $chart[$this::QA][$i] = (float)$jml_device_qa;
				@$chart[$this::VT][$i] = (float)$jml_device_vt;  $chart[$this::TA][$i] = (float)$jml_device_ta;
				@$chart[$this::CAL][$i] = (float)$jml_device_cal;
			}
            return view('admin.topdashboard.index')
                ->with('search', $search)
                ->with('message', $message)
                ->with('tahun', $thisYear)
                ->with('data', $data)
                ->with('stel', $chart['stel'])
                ->with($this::KAB, $chart[$this::KAB])
                ->with($this::ENE, $chart[$this::ENE])
                ->with($this::TRA, $chart[$this::TRA])
                ->with($this::CPE, $chart[$this::CPE])
                ->with($this::KAL, $chart[$this::KAL])
                ->with($this::DEVICE, $chart[$this::DEVICE])
                ->with($this::QA, $chart[$this::QA])
                ->with($this::VT, $chart[$this::VT])
                ->with($this::TA, $chart[$this::TA])
                ->with($this::CAL, $chart[$this::CAL]);
        }
    } 
	
	public function searchGrafik(Request $request){
		if($request->input('type') == 1){
			for($i=0;$i<12;$i++){
				$sum_stelFunc = $this->sum_stelFunc($request->input($this::KEYW), $i);
                
				$chart['stel'][$i]=(float)$sum_stelFunc[0];
				$chart[$this::KAB][$i]=(float)$sum_stelFunc[1];
				$chart[$this::ENE][$i]=(float)$sum_stelFunc[2];
				$chart[$this::TRA][$i]=(float)$sum_stelFunc[3];
				$chart[$this::CPE][$i]=(float)$sum_stelFunc[4];
				$chart[$this::KAL][$i]=(float)$sum_stelFunc[5];
			}
			echo json_encode($chart['stel'])."||";
			echo json_encode($chart[$this::KAB])."||";
			echo json_encode($chart[$this::TRA])."||";
			echo json_encode($chart[$this::CPE])."||";
			echo json_encode($chart[$this::ENE])."||";
			echo json_encode($chart[$this::KAL])."||";
		}else{
			for($i=0;$i<12;$i++){
				$jml_device_qa = Income::where("reference_id", '=', 1)
		        ->whereYear(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $request->input($this::KEYW))
		        ->whereMonth(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);

		        $jml_device_vt = Income::where("reference_id", '=', 3)
		         ->whereYear(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $request->input($this::KEYW))
		        ->whereMonth(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);

		        $jml_device_ta = Income::where("reference_id", '=', 2)
		         ->whereYear(DB::raw("SUBSTR(created_at, 1, 4)"), '=', $request->input($this::KEYW))
		        ->whereMonth(DB::raw("SUBSTR(created_at, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);

		        $jml_device_cal = Income::where("reference_id", '=', 4)
		        ->whereYear(DB::raw("SUBSTR(tgl, 1, 4)"), '=', $request->input($this::KEYW))
		        ->whereMonth(DB::raw("SUBSTR(tgl, 6, 2)"), '=', $i+1)
		        ->select($this::PRICE)
		        ->sum($this::PRICE);

				$jml_device = DB::select("
					SELECT sum(price) AS jml
					from incomes where SUBSTR(tgl, 1, 4) = ".$request->input($this::KEYW)."
					AND SUBSTR(tgl, 6, 2) = ".($i+1)."
					;
				");

				$chart[$this::DEVICE][$i]=(float)$jml_device[0]->jml;
				$chart[$this::QA][$i] = (float)$jml_device_qa;
				$chart[$this::VT][$i] = (float)$jml_device_vt;
				$chart[$this::TA][$i] = (float)$jml_device_ta;
				$chart[$this::CAL][$i] = (float)$jml_device_cal;
			}
			echo json_encode($chart[$this::DEVICE])."||";
			echo json_encode($chart[$this::QA])."||";
			echo json_encode($chart[$this::VT])."||";
			echo json_encode($chart[$this::TA])."||";
			echo json_encode($chart[$this::CAL])."||";
		}
	}

	public function sum_stelFunc($year, $i){
		$id_kab = ExaminationLab::where('name', 'like', '%kabel%')->select('id')->first();
		$id_ene = ExaminationLab::where('name', 'like', '%energi%')->select('id')->first();
		$id_tra = ExaminationLab::where('name', 'like', '%transmisi%')->select('id')->first();
		$id_cpe = ExaminationLab::where('name', 'like', '%cpe%')->select('id')->first();
		$id_kal = ExaminationLab::where('name', 'like', '%kalibrasi%')->select('id')->first();

		$sum_stel = 0; $sum_stel_kab = 0; $sum_stel_ene = 0; $sum_stel_tra = 0; $sum_stel_cpe = 0; $sum_stel_kal = 0;
		$select = array('DISTINCT stels_sales.cust_price_payment', 'stels.type');
		$query = STELSales::selectRaw(implode(",", $select))
				->join("stels_sales_attachment","stels_sales_attachment.stel_sales_id","=",$this::STELS)
				->join("stels_sales_detail","stels_sales_detail.stels_sales_id","=",$this::STELS)
				->join("stels","stels.id","=","stels_sales_detail.stels_id")
				->whereNotNull('stels_sales_attachment.attachment')
				->where(DB::raw("SUBSTR(stels_sales.updated_at, 1, 4)"), '=', $year)
				->where(DB::raw("SUBSTR(stels_sales.updated_at, 6, 2)"), '=', $i+1);
		$stels_sales = $query->get();
		foreach($stels_sales as $item){
			switch ($item->type) {
				case $id_kab->id:
					$sum_stel_kab += $item->cust_price_payment;
					break;
				case $id_ene->id:
					$sum_stel_ene += $item->cust_price_payment;
					break;
				case $id_tra->id:
					$sum_stel_tra += $item->cust_price_payment;
					break;
				case $id_cpe->id:
					$sum_stel_cpe += $item->cust_price_payment;
					break;
				case $id_kal->id:
					$sum_stel_kal += $item->cust_price_payment;
					break;
				default:
					# code...
					break;
			}
			$sum_stel += $item->cust_price_payment;
		}

		return array($sum_stel, $sum_stel_kab, $sum_stel_ene, $sum_stel_tra, $sum_stel_cpe, $sum_stel_kal);
	}
}