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
        $examination = Examination::latest()->first();
		$response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/'.$examination->id);  
		$this->assertEquals(200, $response->status());
    }
    
    public function testUpdate()
	{
		$examination = Examination::latest()->first();
		$response = $this->actingAs(User::find('1'))->call('PUT', "admin/examination/$examination->id", [ 
            'status' => 'Registrasi',
            'registration_status' => mt_rand(0,1),
            'examination_lab_id' => '08242962-6386-4f57-af8b-6f06103cdc81',
            'is_loc_test' => '1',
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
}
