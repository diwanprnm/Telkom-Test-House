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

		$bootstrap_url= "/images/bootstrap.min.css' rel='stylesheet'";
		$image_top_url = 'images/telkom-logo-text.jpg';

		$image_background_url = '/images/tth-logo-opacity.jpg';
		$image_tth_motto_url = '/images/tth-logo-text-moto.jpg';
		$image_decorator_url = '/images/decorator-pattern-1.jpg';
		$image_qrcode_url = QrCode::format('png')->size(500)->merge($telkomLogoSquarePath)->errorCorrection('M')->generate($qrCodeLink);

		// dd($image_top_url);

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

		$html_sertifikatQA = "<html>

<head>
    <!-- <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'> -->

    <style>
        html,
        body {
        
            font-size: 1em;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.2em;
        }
        
        td {
            vertical-align: text-top;
        }
        
        hr {
            border-color: black;
        }
        
        .data-row-wrapper-logo {
            background-image: url('../../../public/images/tth-logo-opacity.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 500px;
        }
        
        .table-data-margin {
            margin-left: 10%;
        }
        
        .text-center {
            text-align: center;
        }
        
        .small {
            font-size: smaller;
            margin-top: 0;
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
        }
        
        .img-top-logo {
            float: right;
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
    </style>
</head>

<body>
    <div class='wrapper py-3 px-2'>
        <div class='container'>

            <table class='w-100'>
                <tr class='row logo-top'>
                    <td class='w-100'>
                        <img height='120px' class='img-top-logo' src='../../../public/images/telkom-logo-text.jpg' />
                    </td>
                </tr>
            </table>


            <div class='col judul-utama text-center'>
                <h2 class='underline fw-bold text-center'><u>Quality Assurance Test</u></h2>
            </div>

            <div class='text-center'>
                <table class='table-data-margin'>
                    <tr class='property surat-keterangan-ini'>
                        <td class='w-40'>
                            Surat keterangan ini dikeluarkan untuk
                            <br/>
                            <span class='font-italic small'>This declaration letter is issued to</span>
                        </td>
                        <td class='semicolon'>
                            :
                        </td>
                        <td>
                            <p class='col col-data-right item-value'>PT. ARIF KONVERSI SURYA INOVASI INDONESIA</p>
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

                    <!-- Row 1 surat keterangan ini -->
                    <!-- Data Row -->
                    <div class='row row-main mb-2'>
                        <div class='row row-main-wrapper'>
                            <div class='col col-data-left'>
                                <div class='row'>Surat keterangan ini dikeluarkan untuk</div>
                                <div class='row small font-italic'>This declaration letter is issued to</div>
                            </div>
                            <div class='col-1 col-colon text-end'>:</div>
                            <div class='col col-data-right item-value'>PT. ARIF KONVERSI SURYA INOVASI INDONESIA</div>
                        </div>
                    </div>
                    <!-- End Data row -->

                    <!-- Data Row -->
                    <div class='row row-main mb-4'>
                        <div class='row row-main-wrapper'>
                            <div class='col col-data-left'>
                                <div class='row'>Sebagai Pabrikan/Agen/Perwakilan dari</div>
                                <div class='row small font-italic'>As a (or) an Manufacture/Agent/Representative of</div>
                            </div>
                            <div class='col-1 col-colon text-end'>:</div>
                            <div class='col col-data-right item-value'>Schwul Industries GmbH.</div>
                        </div>
                    </div>
                    <!-- End Data row -->
                    <!-- End Row 1 surat keterangan ini -->


                    <!-- Row 2 Dengan ini PT Telkom -->
                    <div class='row mb-4'>
                        <div class='col judul-utama text-center'>
                            Dengan ini <span class='underline item-value'>PT Telkom Indonesia (Persero) Tbk</span> menyatakan bahwa:
                            <br>
                            <span class='small font-italic'>Hereby, PT Telkom Indonesia (Persero) Tbk declared that:</span>
                        </div>
                    </div>
                    <!-- End Row 2 Dengan ini PT Telkom -->


                    <!-- Row 3 Nama Perangkat -->
                    <!-- Data Row -->
                    <div class='data-row-wrapper-logo'>
                        <div class='row row-main mb-2'>
                            <div class='row row-main-wrapper'>
                                <div class='col w-100 col-data-left'>
                                    <div class='row'>Nama perangkat</div>
                                    <div class='row small font-italic'>Equipment name</div>
                                </div>
                                <div class='col-1 col-colon text-end'>:</div>
                                <div class='col col-data-right item-value'>Tera Router</div>
                            </div>
                        </div>
                        <!-- End Data row -->

                        <!-- Data Row -->
                        <div class='row row-main mb-2'>
                            <div class='row row-main-wrapper'>
                                <div class='col w-100 col-data-left'>
                                    <div class='row'>Tipe/Model</div>
                                    <div class='row small font-italic'>Type/Model</div>
                                </div>
                                <div class='col-1 col-colon text-end'>:</div>
                                <div class='col col-data-right item-value'>THZ150G</div>
                            </div>
                        </div>
                        <!-- End Data row -->

                        <!-- Data Row -->
                        <div class='row row-main mb-2'>
                            <div class='row row-main-wrapper'>
                                <div class='col w-100 col-data-left'>
                                    <div class='row'>Kapasitas</div>
                                    <div class='row small font-italic'>Capacity</div>
                                </div>
                                <div class='col-1 col-colon text-end'>:</div>
                                <div class='col col-data-right item-value'>150 THz</div>
                            </div>
                        </div>
                        <!-- End Data row -->

                        <!-- Data Row -->
                        <div class='row row-main mb-2'>
                            <div class='row row-main-wrapper'>
                                <div class='col w-100 col-data-left'>
                                    <div class='row'>Nomor seri</div>
                                    <div class='row small font-italic'>Serial number</div>
                                </div>
                                <div class='col-1 col-colon text-end'>:</div>
                                <div class='col col-data-right item-value'>F0800.1234-Ausf. B</div>
                            </div>
                        </div>
                        <!-- End Data row -->

                        <!-- Data Row -->
                        <div class='row row-main mb-2'>
                            <div class='row row-main-wrapper'>
                                <div class='col w-100 col-data-left'>
                                    <div class='row'>Berdasarkan nomor laporan hasil uji</div>
                                    <div class='row small font-italic'>Based on the test report number</div>
                                </div>
                                <div class='col-1 col-colon text-end'>:</div>
                                <div class='col col-data-right item-value'>7775</div>
                            </div>
                        </div>
                        <!-- End Data row -->

                        <!-- Data Row -->
                        <div class='row row-main mb-2'>
                            <div class='row row-main-wrapper'>
                                <div class='col w-100 col-data-left'>
                                    <div class='row'>Telah memenuhi spesifikasi sebagai berikut</div>
                                    <div class='row small font-italic'>Has been complied the following specification(s)</div>
                                </div>
                                <div class='col-1 col-colon text-end'>:</div>
                                <div class='col col-data-right item-value'>STEL D-014-2008 Versi.1.0</div>
                            </div>
                        </div>
                        <!-- End Data row -->
                    </div>

                    <!-- End Row 3 Nama Perangkat -->






                </div>
                <!-- End Main padding-->

            </div>
        </div>


        <!-- Row 4 QA Test perlu dilakukan -->
        <div class='row mb-4 mx-5'>
            <div class='col judul-utama text-center'>
                <hr/>
                <span class='underline'>QA Test perlu dilakukan kembali dalam periode waktu 0 tahun, kecuali ditemukan kejanggalan sebelumnya.
                            <br>
                            <span class='small font-italic'>QA Test shall be repeated in a period of 0 year, except if there is/are nonconformity(s) found before that.</span>
                </span>
                <hr/>
            </div>
        </div>
        <!-- Row 4 QA Test perlu dilakukan -->


        <!-- Signature row -->
        <div class='row signature-row justify-content-evenly'>
            <div class='col text-center'>
                <span class='row-tanggal'>Bandung, 06 April 2022</span>
                <br>
                <span class='row-gambar-signature'><img height='120ox' src='../../../public/images/telkom-logo-text.jpg'/></span>
                <br>
                <span class='row-nama-title item-value'><u>I Gede Astawa</u></span>
                <br>
                <span class='item-value'>OSM Infrastructure Research & Assurance</span>
            </div>
        </div>

        <!-- End Signature row -->


        <!-- Contact row -->
        <div class='row contact-row small justify-content-evenly mt-4'>
            <div class='col text-center'>
                <span class='row-ptth'>PT Telkom Indonesia (Persero) Tbk - Telkom Test House</span>
                <br>
                <span class='row-alamat'>Jl. Gegerkalong Hilir No. 47 Bandung 40152 INDONESIA | Customer Service: (+62) 812-2483-7500; E-Mail: <a href='mailto:cstth@telkom.co.id'>cstth@telkom.co.id</a> </span>
            </div>
        </div>

        <!-- Contact row -->


        <div class='row kop-logo mx-5 mt-4 p-3' style='height:100px;'>
            <div class='col-4 p-0 m-0'>
                <img height='60%' class='mt-3' src='../../../public/images/tth-logo-text-moto.jpg' />
            </div>
            <div class='col-6  p-0 m-0'>
                <img width='95%' class='' src='../../../public/images/decorator-pattern-1.jpg' />
            </div>
            <div class='col  p-0 m-0'>
                <img height='70%' class='float-end mt-3' src='../../../public/images/telkom-logo-red-hand.jpg' />
            </div>
        </div>




    </div>
    <!-- end outermost container -->

</body>

</html>";

		// echo $html_sertifikatQA;
		// die;

		
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
