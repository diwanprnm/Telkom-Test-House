<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;
use Mail;
use Input;
use File;
use Session;
use Response;
// use Hash;
// use Fpdf;

use App\Device;
use App\Examination;
use App\ExaminationAttach;
use App\ExaminationType;
use App\Feedback;
use App\ExaminationHistory;
use App\Logs;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class PermohonanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
	{
		// if(!Auth::User()->hasPermission('home_view')){
			// return view('permissionDenied');
		// }
		
		
		return view('client.home');
		// return view('home')
			// ->with('about', $about);
	}
	
	public function createPermohonan()
	{
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$query = "SELECT * FROM examination_types";
		$page = "";
		$data = DB::select($query);
            
		if (count($data) == 0){
			$message = 'Data not found';
		}
		
        $query_slideshow = "SELECT * FROM slideshows WHERE is_active = 1 ORDER BY created_at";
		$data_slideshow = DB::select($query_slideshow);
            
		if (count($data_slideshow) == 0){
			$message_slideshow = 'Data not found';
		}
		
        $query_about = "SELECT * FROM articles WHERE is_active = 1 AND type='About'";
		$data_about = DB::select($query_about);
            
		if (count($data_about) == 0){
			$message_about = "Data Not Found";
		}
		
        $query_procedure = "SELECT * FROM articles WHERE is_active = 1 AND type='Procedure'";
		$data_procedure = DB::select($query_procedure);
            
		if (count($data_about) == 0){
			$message_about = "Data Not Found";
		}
		
        $query_stels = "SELECT * FROM stels WHERE is_active = 1";
		$data_stels = DB::select($query_stels);
            
		if (count($data_stels) == 0){
			$message_stels = "Data Not Found";
		}
		
        $query_question = "SELECT * FROM question_categories WHERE is_active = 1 ORDER BY name";
		$data_question = DB::select($query_question);
            
		if (count($data_question) == 0){
			$message_question = "Data Not Found";
		}
		
        // $query_footers = "SELECT * FROM footers WHERE is_active = 1";
		// $data_footers = DB::select($query_footers);
            
		// if (count($data_footers) == 0){
			// $message_footers = "Data Not Found";
		// }
		return view('client.home')
			->with('user_id', $user_id)
			->with('data', $data)
			->with('data_slideshow', $data_slideshow)
			->with('data_about', $data_about)
			->with('data_procedure', $data_procedure)
			->with('data_stels', $data_stels)
			->with('data_question', $data_question)
			->with('page', $page);
			// ->with('data_footers', $data_footers);
	}
	
	public function submit(Request $request)
	{
		// print_r($request->all());
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$user_name = ''.$currentUser['attributes']['name'].'';
		$user_email = ''.$currentUser['attributes']['email'].'';
		$company_id = ''.$currentUser['attributes']['company_id'].'';
		$nama_pemohon = 
			// $request->input('nama_pemohon');
			$request->input('f1-nama-pemohon');
		$alamat_pemohon = 
			// $request->input('alamat_pemohon');
			$request->input('f1-alamat-pemohon');
		$telepon_pemohon = 
			// $request->input('telepon_pemohon');
			$request->input('f1-telepon-pemohon');
		$faksimile_pemohon = 
			// $request->input('faksimile_pemohon');
			$request->input('f1-faksimile-pemohon');
		$email_pemohon = 
			// $request->input('email_pemohon');
			$request->input('f1-email-pemohon');
		$jns_perusahaan = 
			// $request->input('jns_perusahaan');
			$request->input('jns_perusahaan');
		$nama_perusahaan = 
			// $request->input('nama_perusahaan');
			$request->input('f1-nama-perusahaan');
		$alamat_perusahaan = 
			// $request->input('alamat_perusahaan');
			$request->input('f1-alamat-perusahaan');
		$telepon_perusahaan = 
			// $request->input('telepon_perusahaan');
			$request->input('f1-telepon-perusahaan');
		$faksimile_perusahaan = 
			// $request->input('faksimile_perusahaan');
			$request->input('f1-faksimile-perusahaan');
		$email_perusahaan = 
			// $request->input('email_perusahaan');
			$request->input('f1-email-perusahaan');
		$jns_pengujian = 
			// $request->input('hide_jns_pengujian');
			$request->input('hide_jns_pengujian');
				$exam_type = DB::table('examination_types')->where('id', ''.$jns_pengujian.'')->first();
			$jns_pengujian_name = ''.$exam_type->name.'';
			$jns_pengujian_desc = ''.$exam_type->description.'';
		$nama_perangkat = 
			// $request->input('nama_perangkat');
			$request->input('f1-nama-perangkat');
		$merek_perangkat = 
			// $request->input('merek_perangkat');
			$request->input('f1-merek-perangkat');
		$kapasitas_perangkat = 
			// $request->input('kapasitas_perangkat');
			$request->input('f1-kapasitas-perangkat');
		$pembuat_perangkat = 
			// $request->input('pembuat_perangkat');
			$request->input('f1-pembuat-perangkat');
		$serialNumber_perangkat = 
			// $request->input('serialNumber_perangkat');
			$request->input('f1-serialNumber-perangkat');
		$model_perangkat = 
			// $request->input('model_perangkat');
			$request->input('f1-model-perangkat');
		if($request->input('f1-jns-referensi-perangkat') == 1){
			$referensi_perangkat = 
				// $request->input('referensi_perangkat');
				$request->input('f1-cmb-ref-perangkat');
		}else{
			$referensi_perangkat = 
				// $request->input('referensi_perangkat');
				$request->input('f1-referensi-perangkat');
		}
		$no_siupp = 
			// $request->input('no_siupp');
			$request->input('f1-no-siupp');
		$tgl_siupp = 
			// $request->input('tgl_siupp');
			$request->input('f1-tgl-siupp');
		$sertifikat_sistem_mutu = 
			// $request->input('sertifikat_sistem_mutu');
			$request->input('f1-sertifikat-sistem-mutu');
		$batas_waktu_sistem = 
			// $request->input('batas_waktu_sistem');
			$request->input('f1-batas-waktu');
			
		if($request->ajax()){
			$data = Array([
				'nama_pemohon' => $nama_pemohon,
				'alamat_pemohon' => $alamat_pemohon,
				'telepon_pemohon' => $telepon_pemohon,
				'faksimile_pemohon' => $faksimile_pemohon,
				'email_pemohon' => $email_pemohon,
				'jns_perusahaan' => $jns_perusahaan,
				'nama_perusahaan' => $nama_perusahaan,
				'alamat_perusahaan' => $alamat_perusahaan,
				'telepon_perusahaan' => $telepon_perusahaan,
				'faksimile_perusahaan' => $faksimile_perusahaan,
				'email_perusahaan' => $email_perusahaan,
				'nama_perangkat' => $nama_perangkat,
				'merek_perangkat' => $merek_perangkat,
				'model_perangkat' => $model_perangkat,
				'kapasitas_perangkat' => $kapasitas_perangkat,
				'referensi_perangkat' => $referensi_perangkat,
				'pembuat_perangkat' => $pembuat_perangkat,
				'jnsPengujian' => $jns_pengujian,
				'initPengujian' => $jns_pengujian_name,
				'descPengujian' => $jns_pengujian_desc
			]);
		}
		
		$request->session()->put('key', $data);
		// $this->sendProgressEmail("Pemohon atas nama ".$user_name." dengan alamat email ".$user_email.", mengajukan proses pengujian baru");
		$path_file_company = public_path().'/media/company/'.$company_id.'';
		if (!file_exists($path_file_company)) {
			mkdir($path_file_company, 0775);
		}
		if ($request->hasFile('fuploadsiupp')) {
			/*$ext_file = $request->file('fuploadsiupp')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'siupp_'.$request->file('fuploadsiupp')->getClientOriginalName();
			// $request->file('fuploadsiupp')->move($path_file_company, $name_file);
                // $fuploadsiupp_name = $name_file;
				// if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_siupp_file'))){
					// File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_siupp_file'));
				// }
			if($request->file('fuploadsiupp')->move($path_file_company, $name_file)){
                $fuploadsiupp_name = $name_file;
				if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_siupp_file'))){
					File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_siupp_file'));
				}
            }else{
                $fuploadsiupp_name = $request->input('hide_siupp_file');
            }
		}else{
			$fuploadsiupp_name = $request->input('hide_siupp_file');
		}
		if ($request->hasFile('fuploadlampiran')) {
			/*$ext_file = $request->file('fuploadlampiran')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'serti_uji_mutu_'.$request->file('fuploadlampiran')->getClientOriginalName();
			if($request->file('fuploadlampiran')->move($path_file_company, $name_file)){
                $fuploadlampiran_name = $name_file;
				if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_sertifikat_file'))){
					File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_sertifikat_file'));
				}
            }else{
                $fuploadlampiran_name = $request->input('hide_sertifikat_file');
            }
		}else{
			$fuploadlampiran_name = $request->input('hide_sertifikat_file');
		}
		if ($request->hasFile('fuploadnpwp')) {
			/*$ext_file = $request->file('fuploadnpwp')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'npwp_'.$request->file('fuploadnpwp')->getClientOriginalName();
			if($request->file('fuploadnpwp')->move($path_file_company, $name_file)){
                $fuploadnpwp_name = $name_file;
				if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_npwp_file'))){
					File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_npwp_file'));
				}
            }else{
                $fuploadnpwp_name = $request->input('hide_npwp_file');
            }
		}else{
			$fuploadnpwp_name = $request->input('hide_npwp_file');
		}
		$device = new Device;
        $device_id = Uuid::uuid4();
        $device->id = $device_id;
        $device->name = ''.$nama_perangkat.'';
        $device->mark = ''.$merek_perangkat.'';
        $device->capacity = ''.$kapasitas_perangkat.'';
        $device->manufactured_by = ''.$pembuat_perangkat.'';
        $device->serial_number = ''.$serialNumber_perangkat.'';
        $device->model = ''.$model_perangkat.'';
        $device->test_reference = ''.$referensi_perangkat.'';
        $device->certificate = NULL;
        $device->status = 1;
        $device->valid_from = NULL; //
        $device->valid_thru = NULL; //
        $device->is_active = 1;
        $device->created_by = ''.$user_id.'';
        $device->updated_by = ''.$user_id.'';
        $device->created_at = ''.date('Y-m-d h:i:s').'';
        $device->updated_at = ''.date('Y-m-d h:i:s').'';

        try{
            $device->save();
            // Session::flash('message', 'device successfully created');
            // return redirect('/device');
            // return $device->id;
        } catch(Exception $e){
            // Session::flash('error', 'Save failed');
            // return redirect('/device/create');
        }
		
        $exam = new Examination;
        $exam_id = Uuid::uuid4();
        $exam->id = $exam_id;
        $exam->examination_type_id = ''.$jns_pengujian.'';
        $exam->company_id = ''.$company_id.'';
        $exam->device_id = ''.$device_id.'';
        $exam->examination_lab_id = NULL;
        $exam->spk_code = NULL;
        $exam->registration_status = 0;
        $exam->function_status = 0;
        $exam->contract_status = 0;
        $exam->spb_status = 0;
        $exam->payment_status = 0;
        $exam->spk_status = 0;
        $exam->examination_status = 0;
        $exam->resume_status = 0;
        $exam->qa_status = 0;
        $exam->certificate_status = 0;
		$exam->created_by = ''.$user_id.'';
        $exam->updated_by = ''.$user_id.'';
        $exam->created_at = ''.date('Y-m-d h:i:s').'';
        $exam->updated_at = ''.date('Y-m-d h:i:s').'';
        $exam->jns_perusahaan = ''.$jns_perusahaan.'';

        try{
            $exam->save();
			$request->session()->put('exam_id', $exam_id);
            // Session::flash('message', 'device successfully created');
            // return redirect('/device');
            // return $device->id;
        } catch(Exception $e){
			// DB::table('devices')->where('id', '=', ''.$device_id.'')->delete();
            // Session::flash('error', 'Save failed');
            // return redirect('/device/create');
        }
		
		// if($jns_pengujian == 1){
			// try{
				// DB::table('examination_attachments')->insert([
					// ['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => ''.$no_ref_uji.'', 'tgl' => ''.$tgl_ref_uji.'', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					// ['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Surat Dukungan Prinsipal', 'attachment' => ''.$fuploadprinsipal_name.'', 'no' => ''.$no_surat_prinsipal.'', 'tgl' => ''.$tgl_surat_prinsipal.'', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
				// ]);
			// } catch(Exception $e){
				// DB::table('examinations')->where('id', '=', ''.$exam_id.'')->delete();
				// DB::table('devices')->where('id', '=', ''.$device_id.'')->delete();
			// }
		// }else if($jns_pengujian == 2){
			// try{
				// DB::table('examination_attachments')->insert([
					// ['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => ''.$no_ref_uji.'', 'tgl' => ''.$tgl_ref_uji.'', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					// ['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'SP3', 'attachment' => ''.$fuploadsp3_name.'', 'no' => ''.$no_sp3.'', 'tgl' => ''.$tgl_sp3.'', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
				// ]);
			// } catch(Exception $e){
				// DB::table('examinations')->where('id', '=', ''.$exam_id.'')->delete();
				// DB::table('devices')->where('id', '=', ''.$device_id.'')->delete();
			// }
		// }else{
			// try{
				// DB::table('examination_attachments')->insert([
					// ['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => ''.$no_ref_uji.'', 'tgl' => ''.$tgl_ref_uji.'', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
				// ]);
			// } catch(Exception $e){
				// DB::table('examinations')->where('id', '=', ''.$exam_id.'')->delete();
				// DB::table('devices')->where('id', '=', ''.$device_id.'')->delete();
			// }
		// }
		
		
		$path_file = public_path().'/media/examination/'.$exam_id.'';
		if (!file_exists($path_file)) {
			mkdir($path_file, 0775);
		}
		
		/*$ext_file = $request->file('fuploadrefuji')->getClientOriginalName();
		$name_file = uniqid().'_file_.'.$ext_file;*/
		$name_file = 'ref_uji_'.$request->file('fuploadrefuji')->getClientOriginalName();
		if($request->file('fuploadrefuji')->move($path_file,$name_file)){
            $fuploadrefuji_name = $name_file;
		}else{
			$fuploadrefuji_name = '';
		}
		if($jns_pengujian == 1 and $jns_perusahaan !='Pabrikan'){
			/*$ext_file = $request->file('fuploadprinsipal')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'prinsipal_'.$request->file('fuploadprinsipal')->getClientOriginalName();
			if($request->file('fuploadprinsipal')->move($path_file,$name_file)){
				$fuploadprinsipal_name = $name_file;
			}else{
				$fuploadprinsipal_name = '';
			}
		}else if($jns_pengujian == 2){
			/*$ext_file = $request->file('fuploadsp3')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'sp3_'.$request->file('fuploadsp3')->getClientOriginalName();
			if($request->file('fuploadsp3')->move($path_file,$name_file)){
				$fuploadsp3_name = $name_file;
			}else{
				$fuploadsp3_name = '';
			}
		}
		
		if($jns_pengujian == 1){
			if($jns_perusahaan != 'Pabrikan'){
				DB::table('examination_attachments')->insert([
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'File Pembayaran', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Surat Dukungan Prinsipal', 'attachment' => ''.$fuploadprinsipal_name.'', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Tinjauan Kontrak', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Laporan Uji', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
				]);
			}else{
				DB::table('examination_attachments')->insert([
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'File Pembayaran', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Tinjauan Kontrak', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
					['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Laporan Uji', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
				]);
			}
		}else if($jns_pengujian == 2){
			DB::table('examination_attachments')->insert([
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'File Pembayaran', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'SP3', 'attachment' => ''.$fuploadsp3_name.'', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Tinjauan Kontrak', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Laporan Uji', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
			]);
		}else{
			DB::table('examination_attachments')->insert([
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'File Pembayaran', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Referensi Uji', 'attachment' => ''.$fuploadrefuji_name.'', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Tinjauan Kontrak', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').''],
				['id' => Uuid::uuid4(), 'examination_id' => ''.$exam_id.'', 'name' => 'Laporan Uji', 'attachment' => '', 'no' => '', 'tgl' => '', 'created_by' => ''.$user_id.'', 'updated_by' => ''.$user_id.'', 'created_at' => ''.date('Y-m-d h:i:s').'', 'updated_at' => ''.date('Y-m-d h:i:s').'']
			]);
		}
		
		$query_update = "UPDATE companies
			SET 
				npwp_file = '".$fuploadnpwp_name."',
				siup_number = '".$no_siupp."',
				siup_file = '".$fuploadsiupp_name."',
				siup_date = '".date("Y-m-d", strtotime($tgl_siupp))."',
				qs_certificate_number = '".$sertifikat_sistem_mutu."',
				qs_certificate_file = '".$fuploadlampiran_name."',
				qs_certificate_date = '".date("Y-m-d", strtotime($batas_waktu_sistem))."',
				updated_by = '".$user_id."',
				updated_at = '".date('Y-m-d h:i:s')."'
			WHERE id = (SELECT company_id FROM users WHERE id = '".$user_id."')
		";
		$data_update = DB::update($query_update);
		// if (count($data_update) == 0){
			// DB::table('examination_attachments')->where('examination_id', '=', ''.$exam_id.'')->delete();
			// DB::table('examinations')->where('id', '=', ''.$exam_id.'')->delete();
			// DB::table('devices')->where('id', '=', ''.$device_id.'')->delete();
		// }
		$logs = new Logs;
		$currentUser = Auth::user();
        $logs->user_id = $currentUser->id;
        $logs->id = Uuid::uuid4();
        $logs->action = "Create Examination";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Client Process";
        $logs->save();

		$exam_hist = new ExaminationHistory;
		$exam_hist->examination_id = $exam_id;
		$exam_hist->date_action = date('Y-m-d H:i:s');
		$exam_hist->tahap = 'Pengisian Form Permohonan';
		$exam_hist->status = 1;
		$exam_hist->keterangan = '';
		$exam_hist->created_by = $currentUser->id;
		$exam_hist->created_at = date('Y-m-d H:i:s');
		$exam_hist->save();
	}
	
	public function sendProgressEmail($message)
    {
		$data = DB::table('users')
				->where('role_id', 1)
				->where('is_active', 1)
				->get();
		
		Mail::send('client.permohonan.email', array('data' => $message), function ($m) use ($data) {
            $m->to($data[0]->email)->subject("Permohonan Pengujian Baru");
        });

        return true;		
		// return redirect()->back()->with('status', '');
    }

	public function update(Request $request)
	{
		print_r($request->all());
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$company_id = ''.$currentUser['attributes']['company_id'].'';
		$exam_id = 
			// $request->input('nama_pemohon');
			$request->input('hide_exam_id_edit');
				$request->session()->put('exam_id_edit', $exam_id);
		$device_id = 
			// $request->input('nama_pemohon');
			$request->input('hide_device_id_edit');
		$nama_pemohon = 
			// $request->input('nama_pemohon');
			$request->input('f1-nama-pemohon-edit');
		$alamat_pemohon = 
			// $request->input('alamat_pemohon');
			$request->input('f1-alamat-pemohon-edit');
		$telepon_pemohon = 
			// $request->input('telepon_pemohon');
			$request->input('f1-telepon-pemohon-edit');
		$faksimile_pemohon = 
			// $request->input('faksimile_pemohon');
			$request->input('f1-faksimile-pemohon-edit');
		$email_pemohon = 
			// $request->input('email_pemohon');
			$request->input('f1-email-pemohon-edit');
		$jns_perusahaan = 
			// $request->input('jns_perusahaan');
			$request->input('jns_perusahaan');
		$nama_perusahaan = 
			// $request->input('nama_perusahaan');
			$request->input('f1-nama-perusahaan-edit');
		$alamat_perusahaan = 
			// $request->input('alamat_perusahaan');
			$request->input('f1-alamat-perusahaan-edit');
		$telepon_perusahaan = 
			// $request->input('telepon_perusahaan');
			$request->input('f1-telepon-perusahaan-edit');
		$faksimile_perusahaan = 
			// $request->input('faksimile_perusahaan');
			$request->input('f1-faksimile-perusahaan-edit');
		$email_perusahaan = 
			// $request->input('email_perusahaan');
			$request->input('f1-email-perusahaan-edit');
		$jns_pengujian = 
			// $request->input('hide_jns_pengujian');
			$request->input('hide_jns_pengujian_edit');
				$exam_type = DB::table('examination_types')->where('id', ''.$jns_pengujian.'')->first();
			$jns_pengujian_name = ''.$exam_type->name.'';
			$jns_pengujian_desc = ''.$exam_type->description.'';
		$nama_perangkat = 
			// $request->input('nama_perangkat');
			$request->input('f1-nama-perangkat-edit');
		$merek_perangkat = 
			// $request->input('merek_perangkat');
			$request->input('f1-merek-perangkat-edit');
		$kapasitas_perangkat = 
			// $request->input('kapasitas_perangkat');
			$request->input('f1-kapasitas-perangkat-edit');
		$pembuat_perangkat = 
			// $request->input('pembuat_perangkat');
			$request->input('f1-pembuat-perangkat-edit');
		$serialNumber_perangkat = 
			// $request->input('serialNumber_perangkat');
			$request->input('f1-serialNumber-perangkat-edit');
		$model_perangkat = 
			// $request->input('model_perangkat');
			$request->input('f1-model-perangkat-edit');
		if($request->input('f1-jns-referensi-perangkat') == 1){
			$referensi_perangkat = 
				// $request->input('referensi_perangkat');
				$request->input('f1-cmb-ref-perangkat');
		}else{
			$referensi_perangkat = 
				// $request->input('referensi_perangkat');
				$request->input('f1-referensi-perangkat-edit');
		}
		$no_siupp = 
			// $request->input('no_siupp');
			$request->input('f1-no-siupp-edit');
		$tgl_siupp = 
			// $request->input('tgl_siupp');
			$request->input('f1-tgl-siupp-edit');
		$sertifikat_sistem_mutu = 
			// $request->input('sertifikat_sistem_mutu');
			$request->input('f1-sertifikat-sistem-mutu-edit');
		$batas_waktu_sistem = 
			// $request->input('batas_waktu_sistem');
			$request->input('f1-batas-waktu-edit');
		
		$path_file = public_path().'/media/examination/'.$exam_id.'';
		$path_file_company = public_path().'/media/company/'.$company_id.'';
		
		if ($request->hasFile('fuploadsiupp_edit')) {
			/*$ext_file = $request->file('fuploadsiupp_edit')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'siupp_'.$request->file('fuploadsiupp_edit')->getClientOriginalName();
			if($request->file('fuploadsiupp_edit')->move($path_file_company, $name_file)){
                $fuploadsiupp_name = $name_file;
				if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_siupp_file_edit'))){
					File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_siupp_file_edit'));
				}
            }else{
                $fuploadsiupp_name = $request->input('hide_siupp_file_edit');
            }
		}else{
			$fuploadsiupp_name = $request->input('hide_siupp_file_edit');
		}
		if ($request->hasFile('fuploadlampiran_edit')) {
			/*$ext_file = $request->file('fuploadlampiran_edit')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'serti_uji_mutu_'.$request->file('fuploadlampiran_edit')->getClientOriginalName();
			if($request->file('fuploadlampiran_edit')->move($path_file_company, $name_file)){
                $fuploadlampiran_name = $name_file;
				if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_sertifikat_file_edit'))){
					File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_sertifikat_file_edit'));
				}
            }else{
                $fuploadlampiran_name = $request->input('hide_sertifikat_file_edit');
            }
		}else{
			$fuploadlampiran_name = $request->input('hide_sertifikat_file_edit');
		}
		if ($request->hasFile('fuploadnpwp_edit')) {
			/*$ext_file = $request->file('fuploadnpwp_edit')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'npwp_'.$request->file('fuploadnpwp_edit')->getClientOriginalName();
			if($request->file('fuploadnpwp_edit')->move($path_file_company, $name_file)){
                $fuploadnpwp_name = $name_file;
				if (File::exists(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_npwp_file_edit'))){
					File::delete(public_path().'\media\\company\\'.$company_id.'\\'.$request->input('hide_npwp_file_edit'));
				}
            }else{
                $fuploadnpwp_name = $request->input('hide_npwp_file_edit');
            }
		}else{
			$fuploadnpwp_name = $request->input('hide_npwp_file_edit');
		}
		if ($request->hasFile('fuploadrefuji_edit')) {
			/*$ext_file = $request->file('fuploadrefuji_edit')->getClientOriginalName();
			$name_file = uniqid().'_file_.'.$ext_file;*/
			$name_file = 'ref_uji_'.$request->file('fuploadrefuji_edit')->getClientOriginalName();
			if($request->file('fuploadrefuji_edit')->move($path_file, $name_file)){
                $fuploadrefuji_name = $name_file;
				if (File::exists(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_ref_uji_file_edit'))){
					File::delete(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_ref_uji_file_edit'));
				}
            }else{
                $fuploadrefuji_name = $request->input('hide_ref_uji_file_edit');
            }
		}else{
			$fuploadrefuji_name = $request->input('hide_ref_uji_file_edit');
		}
		if($jns_pengujian == 1 and $jns_perusahaan !='Pabrikan'){
			if ($request->hasFile('fuploadprinsipal_edit')) {
				/*$ext_file = $request->file('fuploadprinsipal_edit')->getClientOriginalName();
				$name_file = uniqid().'_file_.'.$ext_file;*/
				$name_file = 'prinsipal_'.$request->file('fuploadprinsipal_edit')->getClientOriginalName();
				if($request->file('fuploadprinsipal_edit')->move($path_file, $name_file)){
					$fuploadprinsipal_name = $name_file;
					if (File::exists(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_prinsipal_file_edit'))){
						File::delete(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_prinsipal_file_edit'));
					}
				}else{
					$fuploadprinsipal_name = $request->input('hide_prinsipal_file_edit');
				}
			}else{
				$fuploadprinsipal_name = $request->input('hide_prinsipal_file_edit');
			}
		}else if($jns_pengujian == 2){
			if ($request->hasFile('fuploadsp3_edit')) {
				/*$ext_file = $request->file('fuploadsp3_edit')->getClientOriginalName();
				$name_file = uniqid().'_file_.'.$ext_file;*/
				$name_file = 'sp3_'.$request->file('fuploadsp3_edit')->getClientOriginalName();
				if($request->file('fuploadsp3_edit')->move($path_file, $name_file)){
					$fuploadsp3_name = $name_file;
					if (File::exists(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_sp3_file_edit'))){
						File::delete(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_sp3_file_edit'));
					}
				}else{
					$fuploadsp3_name = $request->input('hide_sp3_file_edit');
				}
			}else{
				$fuploadsp3_name = $request->input('hide_sp3_file_edit');
			}
		}
		
		if($request->ajax()){
			$data = Array([
				'nama_pemohon' => $nama_pemohon,
				'alamat_pemohon' => $alamat_pemohon,
				'telepon_pemohon' => $telepon_pemohon,
				'faksimile_pemohon' => $faksimile_pemohon,
				'email_pemohon' => $email_pemohon,
				'jns_perusahaan' => $jns_perusahaan,
				'nama_perusahaan' => $nama_perusahaan,
				'alamat_perusahaan' => $alamat_perusahaan,
				'telepon_perusahaan' => $telepon_perusahaan,
				'faksimile_perusahaan' => $faksimile_perusahaan,
				'email_perusahaan' => $email_perusahaan,
				'nama_perangkat' => $nama_perangkat,
				'merek_perangkat' => $merek_perangkat,
				'model_perangkat' => $model_perangkat,
				'kapasitas_perangkat' => $kapasitas_perangkat,
				'referensi_perangkat' => $referensi_perangkat,
				'pembuat_perangkat' => $pembuat_perangkat,
				'jnsPengujian' => $jns_pengujian,
				'initPengujian' => $jns_pengujian_name,
				'descPengujian' => $jns_pengujian_desc
			]);
		}
		
		$query_update_company = "UPDATE examinations
			SET 
				jns_perusahaan = '".$jns_perusahaan."',
				updated_by = '".$user_id."',
				updated_at = '".date('Y-m-d h:i:s')."'
			WHERE id = '".$exam_id."'
		";
		$data_update_company = DB::update($query_update_company);
		
		$query_update_device = "UPDATE devices
			SET 
				name = '".$nama_perangkat."',
				mark = '".$merek_perangkat."',
				capacity = '".$kapasitas_perangkat."',
				manufactured_by = '".$pembuat_perangkat."',
				serial_number = '".$serialNumber_perangkat."',
				model = '".$model_perangkat."',
				test_reference = '".$referensi_perangkat."',
				updated_by = '".$user_id."',
				updated_at = '".date('Y-m-d h:i:s')."'
			WHERE id = '".$device_id."'
		";
		$data_update_device = DB::update($query_update_device);
		
		$query_update_ref_uji = "UPDATE examination_attachments
			SET 
				attachment = '".$fuploadrefuji_name."'
			WHERE examination_id = '".$exam_id."' AND `name` = 'Referensi Uji'
		";
		
		$data_update_ref_uji = DB::update($query_update_ref_uji);
		if($jns_pengujian == 1 and $jns_perusahaan != 'Pabrikan'){
			$query_update_attach = "UPDATE examination_attachments
				SET 
					attachment = '".$fuploadprinsipal_name."'
				WHERE examination_id = '".$exam_id."' AND `name` = 'Surat Dukungan Prinsipal'
			";
			$data_update_attach = DB::update($query_update_attach);
		}else if($jns_pengujian == 2){
			$query_update_attach = "UPDATE examination_attachments
				SET 
					attachment = '".$fuploadsp3_name."'
				WHERE examination_id = '".$exam_id."' AND `name` = 'SP3'
			";
			$data_update_attach = DB::update($query_update_attach);
		}
		
		$query_update_companie = "UPDATE companies
			SET 
				npwp_file = '".$fuploadnpwp_name."',
				siup_number = '".$no_siupp."',
				siup_file = '".$fuploadsiupp_name."',
				siup_date = '".date("Y-m-d", strtotime($tgl_siupp))."',
				qs_certificate_number = '".$sertifikat_sistem_mutu."',
				qs_certificate_file = '".$fuploadlampiran_name."',
				qs_certificate_date = '".date("Y-m-d", strtotime($batas_waktu_sistem))."',
				updated_by = '".$user_id."',
				updated_at = '".date('Y-m-d h:i:s')."'
			WHERE id = (SELECT company_id FROM users WHERE id = '".$user_id."')
		";
		$data_update_companie = DB::update($query_update_companie);
		
		$request->session()->put('key', $data);
		
		$logs = new Logs;
		$currentUser = Auth::user();
        $logs->user_id = $currentUser->id;
        $logs->id = Uuid::uuid4();
        $logs->action = "Update Examination";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "Client Examination";
        $logs->save();

		$exam_hist = new ExaminationHistory;
		$exam_hist->examination_id = $exam_id;
		$exam_hist->date_action = date('Y-m-d H:i:s');
		$exam_hist->tahap = 'Edit Form Permohonan';
		$exam_hist->status = 1;
		$exam_hist->keterangan = '';
		$exam_hist->created_by = $currentUser->id;
		$exam_hist->created_at = date('Y-m-d H:i:s');
		$exam_hist->save();
	}

	public function upload(Request $request){
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$exam_id = $request->session()->get('exam_id');
		$path_file = public_path().'/media/examination/'.$exam_id.'';
		/*$ext_file = $request->file('fuploaddetailpengujian')->getClientOriginalName();
		$name_file = uniqid().'_file_.'.$ext_file;*/
		$name_file = 'form_uji_'.$request->file('fuploaddetailpengujian')->getClientOriginalName();
		$request->file('fuploaddetailpengujian')->move($path_file, $name_file);
		$fuploaddetailpengujian_name = $name_file;
		
		DB::table('examinations')
            ->where('id', ''.$exam_id.'')
            ->update(['attachment' => ''.$fuploaddetailpengujian_name.'']);
	}

	public function uploadEdit(Request $request){
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$exam_id = $request->session()->get('exam_id_edit');
		$path_file = public_path().'/media/examination/'.$exam_id.'';
		/*$ext_file = $request->file('fuploaddetailpengujian_edit')->getClientOriginalName();
		$name_file = uniqid().'_file_.'.$ext_file;*/
		$name_file = 'form_uji_'.$request->file('fuploaddetailpengujian_edit')->getClientOriginalName();
		$request->file('fuploaddetailpengujian_edit')->move($path_file, $name_file);
		$fuploaddetailpengujian_name = $name_file;
		if (File::exists(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_attachment_file_edit'))){
				File::delete(public_path().'\media\\examination\\'.$exam_id.'\\'.$request->input('hide_attachment_file_edit'));
			}
		DB::table('examinations')
            ->where('id', ''.$exam_id.'')
            ->update(['attachment' => ''.$fuploaddetailpengujian_name.'']);
	}
	
	public function cekSNjnsPengujian(Request $request){
		$query = "SELECT
					*
				FROM
					examinations e, devices d
				WHERE e.device_id = d.id
				AND	e.examination_type_id = '".$request->input('jnsPelanggan')."'
				AND	d.name = '".$request->input('nama_perangkat')."'
				AND	d.model = '".$request->input('model_perangkat')."'
				";
		$data = DB::select($query);
		echo count($data);
	}
		
	public function getInfo(Request $request){
		$currentUser = Auth::user();
		// print_r($currentUser);exit;
		$user_id = ''.$currentUser['attributes']['id'].'';
        if ($currentUser){
			$query = "SELECT
						u.id AS user_id, u.`name` AS namaPemohon, u.address AS alamatPemohon, u.phone_number AS telpPemohon, u.fax AS faxPemohon, u.email AS emailPemohon, u.email2 AS emailPemohon2, u.email3 AS emailPemohon3, u.company_id AS company_id,
						c.`name` AS namaPerusahaan, c.address AS alamatPerusahaan, c.phone_number AS telpPerusahaan, c.fax AS faxPerusahaan, c.email AS emailPerusahaan,
						c.qs_certificate_number AS noSertifikat, c.qs_certificate_file AS fileSertifikat, c.qs_certificate_date AS tglSertifikat,
						c.siup_number AS noSIUPP, c.siup_file AS fileSIUPP, c.siup_date AS tglSIUPP, c.npwp_file AS fileNPWP
					FROM
						users u,
						companies c
					WHERE
						u.company_id = c.id
					AND u.id = '".$user_id."'
					";
			$data = DB::select($query);
			if(count($data) > 0){			
				echo $data[0]->namaPemohon."|token|"; #0
				echo $data[0]->emailPemohon."|token|"; #1
				echo $data[0]->namaPerusahaan."|token|"; #2
				echo $data[0]->alamatPerusahaan."|token|"; #3
				echo $data[0]->telpPerusahaan."|token|"; #4
				echo $data[0]->faxPerusahaan."|token|"; #5
				echo $data[0]->emailPerusahaan."|token|"; #6
				echo $data[0]->noSertifikat."|token|"; #7
				echo $data[0]->fileSertifikat."|token|"; #8
				echo date("d-m-Y", strtotime($data[0]->tglSertifikat))."|token|"; #9
				echo $data[0]->noSIUPP."|token|"; #10
				echo $data[0]->fileSIUPP."|token|"; #11
				echo date("d-m-Y", strtotime($data[0]->tglSIUPP))."|token|"; #12
				echo $data[0]->fileNPWP."|token|"; #13
				echo $data[0]->user_id."|token|"; #14
				echo $data[0]->user_id."|token|"; #15
				echo $data[0]->company_id."|token|"; #16
				echo $data[0]->alamatPemohon."|token|"; #17
				echo $data[0]->telpPemohon."|token|"; #18
				echo $data[0]->faxPemohon."|token|"; #19
				echo $data[0]->emailPemohon2."|token|"; #20
				echo $data[0]->emailPemohon3."|token|"; #21
			}else{
				echo 0; //Tidak Ada Data
			}
		}else{
			return 0;
			// return redirect('/pengujian');
			// return redirect()->intended();
			// return redirect()->intended('/')->with('error_code', 5);
			// return back()->with('error_code', 5);
			// $request->session()->flash('message', 'New customer added successfully.');
			// $request->session()->flash('message-type', 'success');

			// return response()->json(['status'=>'Hooray']);
		}
	}
	
	public function feedback(Request $request)
	{
		// print_r($request->all());exit;
		$feedback = new Feedback;
        $feedback->id = Uuid::uuid4();
        $feedback->email = ''.$request->input('email').'';
        $feedback->subject = ''.$request->input('subject').'';
        $feedback->message = ''.$request->input('message').'';
        $feedback->created_at = ''.date('Y-m-d h:i:s').'';
        $feedback->updated_at = ''.date('Y-m-d h:i:s').'';

        try{
            $feedback->save();
            $logs = new Logs;
			$currentUser = Auth::user();
	        $logs->user_id = $currentUser->id;
	        $logs->id = Uuid::uuid4();
	        $logs->action = "Send Feedback";   
	        $logs->data = $feedback;
	        $logs->created_by = $currentUser->id;
	        $logs->page = "FEEDBACK";
	        $logs->save();

			$this->sendFeedbackEmail($request->input('email'),$request->input('subject'),$request->input('message'),$request->input('question'));
            Session::flash('message_feedback', 'Feedback successfully send');
        } catch(Exception $e){
            Session::flash('error_feedback', 'Send failed');
        }
            return redirect('/#contact1');
	}
	
	public function sendFeedbackEmail($email,$subject,$message,$question)
    {
		// $data = DB::table('users')
				// ->where('role_id', 1)
				// ->where('is_active', 1)
				// ->select('email')
				// ->get();
		$data = DB::table('users')
				->join('question_privileges', 'users.id', '=', 'question_privileges.user_id')
				->select('users.email')
				->where('question_privileges.question_id', '=', $request->input('question'))
				->where('users.is_active', 1)
				->get();
		if(count($data)>0){
			foreach($data as $row){
				$emails = $row->email;
				Mail::send('client.permohonan.email', array('data' => $message. ". This message from ".$email.""), function ($m) use ($emails,$subject) {
					$m->to($emails)->subject($subject);
				});
			}
		}else{
			$data = DB::table('users')
				->where('id', 1)
				->select('email')
				->get();
			$emails = $data[0]->email;
			Mail::send('client.permohonan.email', array('data' => $message. ". This message from ".$email.""), function ($m) use ($emails,$subject) {
				$m->to($emails)->subject($subject);
			});
		}

        return true;		
		// return redirect()->back()->with('status', '');
    }
}