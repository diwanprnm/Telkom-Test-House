<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Role;
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

    private $panjang_halaman_mm = 297; // Panjang kertas A4 dalam mm

    private function determineLongStringSize($data){
        $main_font_size = 0.90;
        $small_font_size = 0.8;
        $smaller_font_size = 0.7;
        $smallest_font_size = 0.6;

        if(strlen($data) >= 40){
            $size = $small_font_size;
            $this->panjang_halaman_mm += 2;
        }
        else if (strlen($data) >= 50) {
            $size = $smaller_font_size;
            $this->panjang_halaman_mm += 4;
        }
        else if (strlen($data) >= 60) {
            $size = $smallest_font_size;
            $this->panjang_halaman_mm += 6;
        }
        else{
            $size = $main_font_size;
        }

        return $size;
    }

    public function index($id = '65dd1840-205c-429a-ac0b-6a78e8b5aaef', $method = '')
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
		
        $image_top_url = 'images/telkom-logo-text.jpg';
		$image_background_url = 'images/tth-logo-opacity.jpg';
		$image_tth_motto_url = 'images/tth-logo-text-moto.jpg';
		$image_decorator_url = 'images/decorator-pattern-1.jpg';
		$image_qrcode_url = QrCode::format('png')->size(500)->merge($telkomLogoSquarePath)->errorCorrection('M')->generate($qrCodeLink);

		$qrCode_encodedBase64 = (base64_encode($image_qrcode_url));

        // dd($qrCode_encodedBase64);

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

        $main_font_size = 0.98;
        $small_font_size = 0.8;
        $max_length_resize = 40;
        $documentNumber = $examination->device->cert_number;

        $companyName = $examination->company->name;
        $companyNameSize = $this->determineLongStringSize($companyName);

        $brand = $examination->device->mark;
        $brandSize = $this->determineLongStringSize($brand);

        $deviceName = $examination->device->name;
        $deviceNameSize = $this->determineLongStringSize($deviceName);

        $deviceType = $examination->device->model;
        $deviceTypeSize = $this->determineLongStringSize($deviceType);

        $deviceCapacity = $examination->device->capacity;
        $deviceCapacitySize = $this->determineLongStringSize($deviceCapacity);

        $deviceSerialNumber = $examination->device->serial_number;
        $deviceSerialNumberSize = $this->determineLongStringSize($deviceSerialNumber);

        $examinationNumber = \App\ExaminationAttach::where('examination_id', $id)->where('name', 'Laporan Uji')->first()->no;
        $examinationReference = $examination->device->test_reference;
        $examinationReferenceSize = $this->determineLongStringSize($examinationReference);

        $signee = $signeeData->value;
        $isSigneePoh = $signeeData->code !== 'sm_urel';

        $pohStatus = $isSigneePoh ? 'For ' : '';
        $sm_role = Role::where('id', '3')->value('name');
        $sm_role = empty($sm_role) ? 'OSM Infrastructure Research & Assurance' : $sm_role;
        $signeeRole = $pohStatus . $sm_role;

        $signImagePath = Storage::disk('minio')->url("generalsettings/$signeeData->id/$signeeData->attachment");
        $qrCode = QrCode::format('png')->size(500)->merge($telkomLogoSquarePath)->errorCorrection('M')->generate($qrCodeLink);

		$html_sertifikatQA = "<html>

<head>
    <link rel='stylesheet' href=''>

    <style>
        @media print { @page { size: auto; } }

        html,
        body {
            font-size: $main_font_size em;
            font-family: 'Arial Rounded MT Bold', Arial, Helvetica, sans-serif;
            line-height: 1.0em;
            padding: 0;
            margin-top: 0;
        }
        
        td {
            vertical-align: text-top;
        }
        
        hr {
            border-color: black;
            color: black;
            height: 0.5px;
        }
        
        .data-row-wrapper-logo {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 500px;
        }
        
        .table-data-margin {
            margin-left: 7%;
        }
        
        .text-center {
            text-align: center;
        }
        
        .small {
            font-size: 0.7em;
            margin-top: 0;
        }

        .medium{
            font-size: 0.9em;
        }
        
        .row {
            width: 100%;
        }
        
        .row-main-wrapper {
            width: 80%;
            margin-left: 5%;
        }
        
        .row-main {
            margin-left: 0;
            margin-right: 0;
        }
        
        .fw-bold,
        .item-value {
            font-weight: bold;
        }
        
        .font-italic {
            font-style: italic;
        }
        
        .kop-logo {
            clear: both;
            width: 100%;
            margin-right:0;
            margin-left: 530px;
            display:block;
        }
        
        .img-top-logo, .logo-top {
            float: right;
        }

        .img-bg-data-opacity {
            z-index:-1;
            width: 450px;
            margin-left: 120px;
        }
        
        .semicolon {
            width: 40px;
            text-align: right;
            padding-right: 20px;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .w-80 {
            width: 80%;
        }
        
        .w-70 {
            width: 70%;
        }
        
        .w-60 {
            width: 60%;
        }
        
        .w-50 {
            width: 50%;
        }
        
        .w-40 {
            width: 40%;
        }
        
        .w-30 {
            width: 30%;
        }
        
        .w-20 {
            width: 20%;
        }

        .w-10 {
            width: 10%;
        }

        .table-property{
            width: 300px;
        }

        .mt-05{ margin-top: 3px; }
        .mt-1{ margin-top: 6px; }
        .mt-2{ margin-top: 15px; }
        .mt-3{ margin-top: 25px; }

        .mb-05{ margin-bottom: 5px; }
        .mb-1{ margin-bottom: 10px; }
        .mb-2{ margin-bottom: 20px; }
        .mb-3{ margin-bottom: 40px; }
    </style>
</head>

<body>
    <div class='wrapper py-3 px-2'>
        <div class='container'>

            <table class='w-100 kop-logo'>
                <tr class='row logo-top'>
                <td></td><td></td>
                    <td class='w-100'>
                        <img height='110px' class='img-top-logo' src='$image_top_url' />
                    </td>
                </tr>
            </table>


            <div class='col judul-utama text-center mb-2'>
                <h1 class='underline fw-bold text-center'><u>Quality Assurance Test</u></h1>
            </div>

            <div class='text-center'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Surat keterangan ini dikeluarkan untuk
                            <br/>
                            <span class='font-italic small'>This declaration letter is issued to</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$companyNameSize em;'>$companyName</p>
                        </td>
                    </tr>
                </table>
            </div>


            <div class='text-center'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Sebagai Pabrikan/Agen/Perwakilan dari
                            <br/>
                            <span class='font-italic small'>As a (or) an Manufacture/Agent/Representative of</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td>
                            <p class='col col-data-right item-value' style='font-size:$brandSize em;'>$brand</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class=''>
                <div class='row kop-logo'>
                    <div class='col'>

                    </div>
                </div>

                <div class='col ml-5 container-padding'>
                    <div class='row mb-4'>
                        <div class='col judul-utama text-center'>

                        </div>
                    </div>                   


                    <!-- Row 2 Dengan ini PT Telkom -->
                    <div class='row mt-3'>
                        <div class='col judul-utama text-center'>
                            Dengan ini <span class='underline item-value'>PT Telkom Indonesia (Persero) Tbk</span> menyatakan bahwa:
                            <br>
                            <span class='small font-italic'>Hereby, PT Telkom Indonesia (Persero) Tbk declared that:</span>
                        </div>
                    </div>
                    <!-- End Row 2 Dengan ini PT Telkom -->

        <div class='data-row-wrapper-logo'>
                <div class='text-center mt-3'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Nama perangkat
                            <br/>
                            <span class='font-italic small'>Equipment name</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$deviceNameSize em;'>$deviceName</p>
                        </td>
                    </tr>
                </table>
            </div>


            <div class='text-center mt-05'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Tipe/Model
                            <br/>
                            <span class='font-italic small'>Type/Model</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$deviceTypeSize em;'>$deviceType</p>
                        </td>
                    </tr>
                </table>
            </div>


            <div class='text-center mt-05'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Kapasitas
                            <br/>
                            <span class='font-italic small'>Capacity</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$deviceCapacitySize em;'>$deviceCapacity</p>
                        </td>
                    </tr>
                </table>
            </div>

                
            <div class='text-center mt-05'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Nomor seri
                            <br/>
                            <span class='font-italic small'>Serial number</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$deviceSerialNumberSize em;'>$deviceSerialNumber</p>
                        </td>
                    </tr>
                </table>
            </div>


            <div class='text-center mt-05'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Berdasarkan nomor laporan hasil uji
                            <br/>
                            <span class='font-italic small'>Based on the test report number</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$examinationReferenceSize em;'>$examinationNumber</p>
                        </td>
                    </tr>
                </table>
            </div>


             <div class='text-center mt-05'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='table-property'>
                            Telah memenuhi spesifikasi sebagai berikut
                            <br/>
                            <span class='font-italic small'>Has been complied the following specification(s)</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td  class=''>
                            <p class='col col-data-right item-value' style='font-size:$companyNameSize em;'>$examinationReference</p>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
                <!-- End Main padding-->

            </div>
        </div>


        <!-- Row 4 QA Test perlu dilakukan -->
        <div class='row mt-2'>
            <div class='col judul-utama text-center'>
                <hr/>
                <span class='underline medium'>QA Test perlu dilakukan kembali dalam periode waktu $period_id, kecuali ditemukan kejanggalan sebelumnya.
                            <br>
                            <span class='small font-italic'>QA Test shall be repeated in a period of $period_en, except if there is/are nonconformity(s) found before that.</span>
                </span>
                <hr/>
            </div>
        </div>
        <!-- Row 4 QA Test perlu dilakukan -->


        <!-- Signature row -->
        <div class='row signature-row justify-content-evenly mt-2'>
            <div class='col text-center'>
                <span class='row-tanggal'>Bandung, $signDate</span>
                <br>
                <span class='row-gambar-signature'><img class='mt-05' height='105ox' src='$signImagePath'/></span>
                <br>
                <span class='row-nama-title item-value'><u>$signee</u></span>
                <br>
                <span class='item-value'>$signeeRole</span>
            </div>
        </div>

        <!-- End Signature row -->


        <!-- Contact row -->
        <div class='row contact-row small justify-content-evenly mt-3'>
            <div class='col text-center'>
                <span class='row-ptth'>PT Telkom Indonesia (Persero) Tbk - Telkom <span style='color:red;'>Test</span> House</span>
                <br>
                <span class='row-alamat'>Jl. Gegerkalong Hilir No. 47 Bandung 40152 INDONESIA | Customer Service: (+62) 812-2483-7500; E-Mail: <a href='mailto:cstth@telkom.co.id'>cstth@telkom.co.id</a> </span>
            </div>
        </div>

        <!-- Contact row -->

        <table style='margin-left: 20px;'>
            <tr>
                <td><img height='60px'  style='margin-left: 10px; margin-top:35px;' class='' src='$image_tth_motto_url' /></td>
                <td><img width='375px' class=''  style='margin-left: 10px; margin-top:20px;'src='$image_decorator_url' /></td>
                <td><img height='130px' class='float-right'  style='margin-left: 10px; float:right;'src='data:image/png;base64,$qrCode_encodedBase64' /></td>
            </tr>
        </table>


    </div>
    <!-- end outermost container -->

</body>

</html>";

        // echo $html_sertifikatQA;
        // die;

        // dd($this->panjang_halaman_mm);
 
        $mpdf = new \mPDF('utf-8', array(210, $this->panjang_halaman_mm)); // long jadul = 310 mm

        $mpdf->SetWatermarkImage("$image_background_url", 1, array(120, 65), array(50, 105));
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;

        // $mpdf->SetHTMLHeader("<table class='w-100 kop-logo'>
        //         <tr class='row logo-top'>
        //         <td></td><td></td>
        //             <td class='w-100'>
        //                 <img height='110px' class='img-top-logo' src='$image_top_url' />
        //             </td>
        //         </tr>
        //     </table>", 'O');

        // Write some HTML code:
        $mpdf->WriteHTML($html_sertifikatQA);

        // Output a PDF file directly to the browser
        // $file_name = str_replace("/", "", $certificateNumber) . '.pdf';

        $file_name = 'SertifikatQA-Test.pdf';
        $mpdf->Output($file_name, 'D');

		
		// if ($method == 'getStream'){
		// 	return [
		// 		'stream' => $PDF->cetakSertifikatQA($PDFData),
		// 		'fileName' => str_replace("/","",$certificateNumber).'.pdf'
		// 	];
		// }else{
		// 	return $PDF->cetakSertifikatQA($PDFData);
		// }
	}
}
