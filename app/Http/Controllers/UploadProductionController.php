<?php

namespace App\Http\Controllers;

use App\Certification;
use App\Company;
use App\Device;
use App\Examination;
use App\ExaminationAttach;
use App\Footer;
use App\Slideshow;
use App\STEL;
use App\STELSales;
use App\STELSalesAttach;
use App\STELSalesDetail;
use App\TempCompany;
use App\User;

use Illuminate\Support\Facades\DB;
use App\Services\Logs\LogService;
use GuzzleHttp\Client;
use Storage;

use App\Income;
use App\Questioner;
use App\Equipment;
use App\EquipmentHistory;
use App\ExaminationHistory;
use App\QuestionerDynamic;

class UploadProductionController extends Controller
{
    private const PROD_URL = 'https://old-telkomtesthouse-telkomtesthouse-dev.vsan-apps.playcourt.id/media/';

    private const EXAMINATION_ID = 'examination_id';
    private const HEADERS = 'headers';
    private const CONTENT_TYPE = 'Content-type';
	private const APPLICATION_HEADER = 'application/x-www-form-urlencoded';
	private const BASE_URI = 'base_uri';
	private const APP_URI_API_BSP = 'app.url_api_bsp';
	private const TIMEOUT = 'timeout';
    private const SPK_NUMBER_URI = '&spkNumber=';
    private const ERROR = 'error';
    private const ADMIN_EXAMINATION = '/admin/examination';
    private const REFERENCE_ID = 'reference_id';
    private const MINIO = 'minio';
    private const EXAMINATION_LOC = 'examination\\';
    
	/**
     * Create a new controller instance.
     *
     * @return void
     */

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload($dir,$name){
        // ini_set('memory_limit','-1');
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ]; 
        $path = self::PROD_URL.$dir.rawurlencode($name);
        $headers = get_headers($path,false, stream_context_create($stream_opts));
        if(substr($headers[0], 9, 3) == 200){
            $file = file_get_contents($path,false, stream_context_create($stream_opts));  
            if($file){
                \Storage::disk('minio')->put($dir.$name, $file);
            }
        }
    }

    public function uploadCertification()
    {
        $data = Certification::where('type', 1)->get();
        foreach($data as $value){
            $this->upload('certification/',$value->image);
        }
    }

    public function uploadCompany()
    {
        $data = Company::all();
        foreach($data as $value){
            $this->upload('company/'.$value->id.'/',$value->npwp_file);
            $this->upload('company/'.$value->id.'/',$value->siup_file);
            $this->upload('company/'.$value->id.'/',$value->qs_certificate_file);
        }
    }

    public function uploadDevice()
    {
        $data = Device::where('certificate', '!=', '')->get();
        foreach($data as $value){
            $this->upload('device/'.$value->id.'/',$value->certificate);
        }
    }

    public function uploadExamination()
    {
        // dariTabel examination
        $data = Examination::where('attachment', '!=', '')->get();
        foreach($data as $value){
            $this->upload('examination/'.$value->id.'/',$value->attachment);
        }
    }

    public function uploadExaminationAttach()
    {
        // dariTabel examination_attachments
        $data = ExaminationAttach::where('attachment', '!=', '')->orderBy('examination_id')->skip(0)->take(1)->get();
        foreach($data as $value){
            $this->upload('examination/'.$value->examination_id.'/',$value->attachment);
        }
    }

    public function uploadFooter()
    {
        $data = Footer::all();
        foreach($data as $value){
            $this->upload('footer/',$value->image);
        }
    }

    public function uploadPopUpInformation()
    {
        $data = Certification::where('type', 0)->get();
        foreach($data as $value){
            $this->upload('popupinformation/',$value->image);
        }
    }

    public function uploadSlideshow()
    {
        $data = Slideshow::all();
        foreach($data as $value){
            $this->upload('slideshow/',$value->image);
        }
    }

    public function uploadStel()
    {
        // dariTabel stels
        $data = STEL::where('attachment', '!=', '')->get();
        foreach($data as $value){
            $this->upload('stel/',$value->attachment);
        }
    }

    public function uploadStelSales1()
    {
        // dariTabel stels_sales
        $data = STELSales::where('id_kuitansi', '!=', '')->orWhere('faktur_file', '!=', '')->get();
        foreach($data as $value){
            if($value->id_kuitansi != ''){
                $this->upload('stel/'.$value->id.'/',$value->id_kuitansi);
            }
            if($value->faktur_file != ''){
                $this->upload('stel/'.$value->id.'/',$value->faktur_file);
            }
        }
    }

    public function uploadStelSales2()
    {
        // dariTabel stels_sales_attachment
        $data = STELSalesAttach::all();
        foreach($data as $value){
            $this->upload('stel/'.$value->stel_sales_id.'/',$value->attachment);
        }
    }

    public function uploadStelAttach()
    {
        // dariTabel stels_sales_detail
        $data = STELSalesDetail::select('stels_sales_detail.id','stels_sales_detail.attachment')->where('stels_sales_detail.attachment', '!=', '')
            ->join('stels_sales','stels_sales.id', "=" ,'stels_sales_detail.stels_sales_id')
            ->get();
        foreach($data as $value){
            $this->upload('stelAttach/'.$value->id.'/',$value->attachment);
        }
    }

    public function uploadTempCompany()
    {
        $data = TempCompany::where('npwp_file', '!=', '')->orWhere('siup_file', '!=', '')->orWhere('qs_certificate_file', '!=', '')->get();
        foreach($data as $value){
            $this->upload('tempCompany/'.$value->company_id.'/'.$value->id.'/',$value->npwp_file);
            $this->upload('tempCompany/'.$value->company_id.'/'.$value->id.'/',$value->siup_file);
            $this->upload('tempCompany/'.$value->company_id.'/'.$value->id.'/',$value->qs_certificate_file);
        }
    }
    
    public function uploadUser()
    {
        $data = User::where('picture', '!=', '')->get();
        foreach($data as $value){
            $this->upload('user/'.$value->id.'/',$value->picture);
        }
    }

    public function deletePengujian()
    {
        $data = Examination::where(DB::raw('YEAR(created_at)'), '<', '2021')->where('spk_code', NULL)->get();
        print_r(count($data).'<br>');
        $count = 0;
        foreach($data as $value){
            $delete = $this->destroy($value->id, 'Pengujian', '-');
            $count += $delete;
        }
        print_r($count.' deleted');
    }

    public function destroy($id,$page,$reason = null)
	{ 
		$logs_a_device = NULL;
		$logService = new LogService();
		
		$exam_attach = ExaminationAttach::where(self::EXAMINATION_ID, '=' ,''.$id.'')->get();
		$exam = Examination::find($id);
			$device_id = $exam['device_id'];
		$device = Device::find($device_id);
		if ($exam_attach && $exam && $device){
			/* DELETE SPK FROM OTR */
			if($exam->spk_code){
				$client = new Client([
					self::HEADERS => [self::CONTENT_TYPE => self::APPLICATION_HEADER],
					// Base URI is used with relative requests
					// self::BASE_URI => 'http://37.72.172.144/telkomtesthouse/public/v1/',
					self::BASE_URI => config(self::APP_URI_API_BSP),
					// You can set any number of default request options.
					self::TIMEOUT  => 60.0,
				]);
				
				$res_delete_spk = $client->get('spk/delete?examId='.$exam->id.self::SPK_NUMBER_URI.$exam->spk_code)->getBody();
				$delete_spk = json_decode($res_delete_spk);
				if(!$delete_spk->status){
					Session::flash(self::ERROR, $delete_spk->message.' (message from OTR)');
					return redirect(self::ADMIN_EXAMINATION);
				}
			}
			/* END DELETE SPK FROM OTR */
			try{
				$logs_a_exam = $exam;
				$logs_a_device = $device;
				Income::where(self::REFERENCE_ID, '=' ,''.$id.'')->delete();
				Questioner::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				Equipment::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				EquipmentHistory::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				ExaminationHistory::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				ExaminationAttach::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				QuestionerDynamic::where(self::EXAMINATION_ID, '=' ,''.$id.'')->delete();
				$exam->delete();
				$device->delete();
				
				if (Storage::disk(self::MINIO)->exists(self::EXAMINATION_LOC.$id)){
					Storage::disk(self::MINIO)->deleteDirectory(self::EXAMINATION_LOC.$id);
				}

				$logService->createAdminLog("Hapus Data Pengujian", $page, $logs_a_exam.$logs_a_device, urldecode($reason));

				return 1;
			}catch (Exception $e){ return 0; }
		}
	}

}
