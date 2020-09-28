<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Examination;

use Illuminate\Support\Facades\Storage;
use App\Events\Notification;

class ExaminationControllerTest extends TestCase
{
    public function testIndex()
	{
		$response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination?search=&type=1&status=1&after_date=2020-09-01&before_date=2020-09-01');  
		$this->assertEquals(200, $response->status());
    }
    
    public function testShow()
	{
        $examination = factory(App\Examination::class)->create();
		$response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/'.$examination->id);  
		$this->assertEquals(200, $response->status());
    }
     
    public function testEdit()
	{
        $examination = factory(App\Examination::class)->create();
        $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id]);
        $examinationLab = factory(App\ExaminationLab::class)->create();
		$response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/'.$examination->id.'/edit');  
		$this->assertEquals(200, $response->status());
    }
    
    public function testUpdate()
	{
		$examination = factory(App\Examination::class)->create();
         $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id,"name"=>"Tinjauan Kontrak"]);
         $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id,"name"=>"SPB"]);
		$response = $this->actingAs(User::find('1'))->call('PUT', "admin/examination/$examination->id", [ 
            'status' => 'Registrasi',
            'registration_status' => mt_rand(0,1),
            'function_status' => mt_rand(0,1),
            'contract_status' => mt_rand(0,1),
            'spb_status' => mt_rand(0,1),
            'spk_status' => mt_rand(0,1),
            'examination_status' => mt_rand(0,1),
            'payment_status' => mt_rand(0,1),
            'resume_status' => mt_rand(0,1),
            'qa_status' => mt_rand(0,1),
            'certificate_status' => mt_rand(0,1),
            'exam_price' => mt_rand(0,100),
            'cust_price_payment' => mt_rand(0,100),
            'examination_lab_id' => '08242962-6386-4f57-af8b-6f06103cdc81',
            'is_loc_test' => '1',
            'lab_to_gudang_date' => '2020-12-12',
            'resume_date' => '2020-12-12',
            'qa_date' => '2020-12-12',
            'certificate_date' => '2020-12-12',
            'catatan' => str_random(20),
            'contract_date' => '2020-12-12',
            'testing_start' => '2020-12-12',
            'testing_end' => '2020-12-12',
            'spb_date' => '2020-12-12',
            'passed' => 1,
            'spb_number' => str_random(10),
            'PO_ID' => str_random(10),
            'keterangan' => 'OK'
		]);
		$this->assertEquals(302, $response->status());
    }
    
    public function testGenerateFromTPN(){
        $examination = Examination::latest()->first();
		$response = $this->actingAs(User::find('1'))->call('POST', "admin/examination/'.$examination->id.'/generateFromTPN", [ 
            'id' => $examination->id,
            'type' => 'Kuitansi',
            'filelink' => '/exportpdf'
        ]);
		$this->assertEquals(200, $response->status());
    }

    public function testDownloadForm()
    {
        $examination = Examination::latest()->first();
        $path = "examination/".$examination->id."/".$examination->attachment;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }

		$response = $this->actingAs(User::find(1))->call('GET','admin/examination/download/'.$examination->id);
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
    public function testDownloadMediaDevices()
    {
        $device = factory(App\Device::class)->create();
        $path = "device/".$device->id."/".$device->certificate;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }

        $response = $this->actingAs(User::find(1))->call('GET','admin/examination/media/download/'.$device->id."/certificate");
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
    public function testDownloadMediaExam()
    {
        $exam = factory(App\Examination::class)->create();
        $examAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$exam->id,'name'=>'exam']);
        $path = "examination/".$exam->id."/".$examAttach->attachment;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }

        $response = $this->actingAs(User::find(1))->call('GET','admin/examination/media/download/'.$exam->id."/exam");
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
    public function testDownloadRefUjiFile()
    {
        $examAttach = factory(App\ExaminationAttach::class)->create();
        $path = "examination/".$examAttach->examination_id."/".$examAttach->attachment;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }

        $response = $this->actingAs(User::find(1))->call('GET','admin/examination/media/download/'.$examAttach->id); 
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }


    public function testExcelWithFilter()
    { 
        //create response
        $response = $this->actingAs(User::find('1'))->call('get',"/examination/excel");
        //assert response
        $this->assertEquals(200, $response->status());
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Pengujian.xlsx"');
    }
}
