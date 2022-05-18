<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Examination;
use Carbon\Carbon;
use Storage;

class TestController extends Controller
{
    //todo daniel delete (CLASS DELETE)
    
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexs(Request $request)
    {
        // $path = app_path('http/Controllers/Services/PDF/images/telkom-logo-square.jpg');
        // $images = QrCode::format('png')->size(500)->merge('/app/Services/PDF/images/telkom-logo-square.png')->generate('google.com');
        // return response($images, 200)->header('Content-Type', 'image/png');
        return view('authentikasi.sertifikat');
    }

    public function index($id = 'e28ce8c2-9dc5-4e28-9559-a17e35aa4023', $method = '')
	{
		$examination = Examination::where('id', $id)
			->with('company')
			->with('device')
			->with('media')
			->first()
		;

		$signDate = \App\Services\MyHelper::tanggalIndonesia($examination->qa_date);
		$start_certificate_period = Carbon::parse($examination->device->valid_from);
		$end_certificate_period = Carbon::parse($examination->device->valid_thru);
		$interval = round($start_certificate_period->diffInDays($end_certificate_period)/30);
		if ($interval % 12 == 0){
			$interval_year = (int)$interval/12;
			$period_id = "$interval_year tahun";
			$period_en = "$interval_year year".($interval_year > 1 ? 's': '');
		}else{
			$period_id = "$interval bulan";
			$period_en = "$interval month".($interval > 1 ? 's': '');
		}
		$signeeData = \App\GeneralSetting::whereIn('code', ['sm_urel', 'poh_sm_urel'])->where('is_active', '=', 1)->first();
		$certificateNumber = strval($examination->device->cert_number);
		$telkomLogoSquarePath = '/app/Services/PDF/images/telkom-logo-square.png';
		$qrCodeLink = url('/digitalSign/21003-132'); //todo daniel digitalSign page

		//dd($certificateNumber);

		$PDFData = [
			'documentNumber' => $examination->device->cert_number,
			'companyName' => $examination->company->name,
			'brand' => $examination->device->mark,
			'deviceName' => $examination->device->name,
			'deviceType' => $examination->device->model,
			'deviceCapacity' => $examination->device->capacity,
			'deviceSerialNumber' => $examination->device->serial_number,
			'examinationNumber' => \App\ExaminationAttach::where('examination_id', $id)->where('name', 'Laporan Uji')->first()->no,
			'examinationReference' => $examination->device->test_reference,
			'signDate' => $signDate,
			'period_id' => $period_id,
			'period_en' => $period_en,
			'signee' => $signeeData->value,
			'isSigneePoh' => $signeeData->code !== 'sm_urel',
			'signImagePath' => Storage::disk('minio')->url("generalsettings/$signeeData->id/$signeeData->attachment"),
			'method' => $method,
			'qrCode' => QrCode::format('png')->size(500)->merge($telkomLogoSquarePath)->errorCorrection('M')->generate($qrCodeLink)
		];
		$PDF = new \App\Services\PDF\PDFService();
		
		if ($method == 'getStream'){
			return [
				'stream' => $PDF->cetakSertifikatQA($PDFData),
				'fileName' => str_replace("/","",$certificateNumber).'.pdf'
			];
		}else{
			return $PDF->cetakSertifikatQA($PDFData);
		}
	}
}
