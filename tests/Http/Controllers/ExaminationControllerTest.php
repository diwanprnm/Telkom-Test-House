<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Examination;

use Illuminate\Support\Facades\Storage;
use App\Events\Notification;

use App\Services\ExaminationService;
class ExaminationControllerTest extends TestCase
{
    public function testIndex()
	{
        $device = factory(App\Device::class)->create([
            'name' => '',
            'mark' => '',
            'capacity' => '',
            'manufactured_by' => '',
            'serial_number' => '',
            'model' => '',
            'test_reference' => '',
            'valid_from' => null,
            'valid_thru' => null,
        ]);

        $company = factory(App\Company::class)->create([
            'name' => '',
            'address' => '',
            'city' => null,
            'email' => '',
            'postal_code' => null,
            'phone_number' => null,
            'fax' => null,
            'npwp_number' => null,
            'siup_number' => null,
            'siup_date' => null, 
            'qs_certificate_number' => null,
            'qs_certificate_date' => null,
        ]);

        $user = factory(App\User::class)->create([
                'role_id' =>2,
                'company_id' => $company->id,
                'name' => '',
                'address' => null,
                'phone_number' => null,
                'fax' => null,
        ]);

        $examinationType = factory(App\ExaminationType::class)->create([
            'name' => '',
            'description' => null,
        ]);

        factory(App\Examination::class)->create([
            'registration_status' => '-1',
            'function_status' => '-1',
            'contract_status' => '-1',
            'spb_status' => '-1',
            'payment_status' => '-1',
            'spk_status' => '-1',
            'examination_status'  => '-1',
            'resume_status' => '1',
            'qa_status'  => '1',
            'certificate_status'  => '1',
        ]);

        factory(App\Examination::class)->create([
            'examination_type_id' => $examinationType->id,
            'company_id' => $company->id,
            'device_id' => $device->id,
            'created_by' => $user->id,
            'registration_status' => '0',
            'function_status' => '0',
            'contract_status' => '0',
            'spb_status' => '0',
            'payment_status' => '0',
            'spk_status' => '0',
            'examination_status'  => '0',
            'resume_status' => '0',
            'qa_status'  => '0',
            'certificate_status'  => '0',
            'spk_date' => '',
        ]);
        
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
		$examinationLab = App\ExaminationLab::latest()->first();
        $income = factory(App\Income::class)->create(['reference_id'=>$examination->id]);
        $attachmentPathToMinio = "examination/$examination->id/$examinationAttach->attachment";
        $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
        Storage::disk('minio')->put($attachmentPathToMinio, $file);
        $post = array(
            'status' => 'Registrasi',
            'registration_status' => 1,
            'function_status' => 1,
            'contract_status' => 1,
            'spb_status' => 1,
            'spk_status' => 1,
            'examination_status' => 1,
            'payment_status' => 1,
            'resume_status' => 1,
            'qa_status' => 1,
            'certificate_status' => 1,
            'exam_price' => mt_rand(0,100),
            'cust_price_payment' => mt_rand(0,100),
            'examination_lab_id' => $examinationLab->id,
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
        );
        
        $response = $this->actingAs(User::find('1'))->call('PUT', "admin/examination/$examination->id", $post);
        $this->assertEquals(302, $response->status());

        $post['registration_status'] = -1;
        $response = $this->actingAs(User::find('1'))->call('PUT', "admin/examination/$examination->id", $post);
        $this->assertEquals(302, $response->status());

        //delete file testing
        Storage::disk('minio')->delete($attachmentPathToMinio);
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
    
    // public function testGenerateFromTPN_nonkuitansi(){
    //     $examination = factory(App\Examination::class)->create();
    //     $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id,"name"=>"Faktur Pajak"]);
    //     $response = $this->actingAs(User::find('1'))->call('POST', "admin/examination/'.$examination->id.'/generateFromTPN", [ 
    //         'id' => $examination->id,
    //         'type' => 'Faktur Pajak',
    //         'filelink' => '/exportpdf'
    //     ]);
    //     $this->assertEquals(200, $response->status());
    // }

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
        $exam = factory(App\Examination::class)->create(['device_id'=>$device->id]);
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
        // create response
        $response = $this->actingAs(User::find('1'))->call('get',"/examination/excel");
        //assert response
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Pengujian.xlsx"');
    }



    public function test_visit_revisi()
    {
        $examination = factory(App\Examination::class)->create();
        $stels = factory(App\STEL::class)->create();
        $response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/revisi/'.$examination->id);  
        $this->assertEquals(200, $response->status());
    }


    public function test_updaterevisi(){
        $examination = factory(App\Examination::class)->create();
        $device = factory(App\Device::class)->create();
        $user = User::find('1');
        $response = $this->actingAs($user)->call('POST', "admin/examination/revisi", 
        [ 
            'nama_perangkat' =>str_random(10),
            'hidden_nama_perangkat' =>str_random(10),
            'merk_perangkat' =>str_random(10),
            'hidden_merk_perangkat' =>str_random(10),
            'kapasitas_perangkat' =>str_random(10),
            'hidden_kapasitas_perangkat' =>str_random(10),
            'pembuat_perangkat' =>str_random(10),
            'hidden_pembuat_perangkat' =>str_random(10),
            'model_perangkat' =>str_random(10),
            'hidden_model_perangkat' =>str_random(10),
            'mb-ref-perangkat' =>str_random(10),
            'ref_perangkat' =>str_random(10),
            'hidden_ref_perangkat' =>str_random(10),
            'sn_perangkat' =>str_random(10),
            'hidden_sn_perangkat' =>str_random(10),
            'id_perangkat' =>$device->id,
            'exam_created' =>$user->id,
            'exam_type' =>1,
            'exam_desc' =>str_random(10),
            'id_exam' =>$examination->id,
            'cmb-ref-perangkat' => str_random(10)
        ]);
        $this->assertEquals(302, $response->status());
    }
    public function test_tanggalKontrak(){
        $examination = factory(App\Examination::class)->create(); 
        $user = User::find('1');
        $response = $this->actingAs($user)->call('POST', "admin/examination/".$examination->id."/tanggalkontrak", 
        [  
            'hide_id_exam' =>$examination->id,
            'contract_date' => '2020-12-12' 
        ]);
        $this->assertEquals(200, $response->status());
    }
    public function test_tandaterima(){
        $examination = factory(App\Examination::class)->create(); 
        $user = User::find('1');
        $response = $this->actingAs($user)->call('POST', "admin/examination/".$examination->id."/tandaterima", 
        [  
            'hide_id_exam' =>$examination->id,
            'contract_date' => '2020-12-12' 
        ]);
        $this->assertEquals(200, $response->status());
    }


    public function test_destroy()
    {
        $examination = factory(App\Examination::class)->create(); 
        $response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/harddelete/'.$examination->id.'/revisi/TESTING');  
        $this->assertEquals(302, $response->status());
    }

    public function test_resetUjiFungsi()
    {
        $examination = factory(App\Examination::class)->create(); 
        $response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/resetUjiFungsi/'.$examination->id.'/TESTING');  
        $this->assertEquals(302, $response->status());
    }

     public function test_autocomplete()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET',"admin/adm_exam_autocomplete/query");
        //Response status ok
        $this->assertResponseStatus(200); 
    }

    public function test_generateSPKCode()
    {
        $spk_code = str_random(10);
        $examination = factory(App\Examination::class)->create(['spk_code'=>$spk_code.'/001/CAL/2020']); 
        $response = $this->actingAs(User::find('1'))->call('POST', 'admin/examination/'.$examination->id.'/generateSPKCode',
            [
                'lab_code'=>$spk_code,
                'exam_type'=>'CAL',
                'year'=>'2020',
            ]);  
 
        $this->assertEquals(200, $response->status());
    }
     public function test_generateSPB_get()
    {
        $device = factory(App\Device::class)->create(); 
        $examination = factory(App\Examination::class)->create(['device_id'=>$device->id,'examination_type_id'=>2]); 
        $examinationCharges = factory(App\NewExaminationCharge::class)->create(); 
 
        session(['key_exam_id_for_generate_spb' => $examination->id]);
        session(['key_spb_number_for_generate_spb' => '']);
        session(['key_spb_date_for_generate_spb' => '2020-12-12']);
        $response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/generateSPB');  
        $this->assertEquals(200, $response->status());
    }
    public function test_generateSPB()
    {
        $examinationService = new ExaminationService();
        $spb_number = $examinationService->generateSPBNumber();
        $examination = factory(App\Examination::class)->create(['spb_number'=>$spb_number]); 
        $response = $this->actingAs(User::find('1'))->call('POST', 'admin/examination/generateSPB',
            [
                'spb_number'=>$spb_number,
                'exam_id'=>$examination->id,
                'spb_date'=>'2020-12-12',
                'arr_biaya[]'=>array(1200,1200),
            ]);  
        $this->assertEquals(200, $response->status());
    }
    public function test_generateSPB_nospbnumber()
    {
        $examinationService = new ExaminationService();
        $spb_number = "01";
        $examination = factory(App\Examination::class)->create(['spb_number'=>$spb_number]); 
        $response = $this->actingAs(User::find('1'))->call('POST', 'admin/examination/generateSPB',
            [
                'spb_number'=>"02",
                'exam_id'=>$examination->id,
                'spb_date'=>'2020-12-12',
                'arr_biaya[]'=>array(1200,1200),
                'arr_nama_perangkat'=>array("b","a"),
            ]);  
        $this->assertEquals(200, $response->status());
    }

    public function test_generateSPBParam(){
        $examination = factory(App\Examination::class)->create(); 
        $user = User::find('1');
        $response = $this->actingAs($user)->call('POST', "admin/examination/".$examination->id."/generateSPBParam", 
        [  
            'exam_id' =>$examination->id,
            'spb_number' =>str_random(10),
            'spb_date' => '2020-12-12' 
        ]);
        $this->assertEquals(200, $response->status());
    }
    public function test_generateEquipParam(){
        $examination = factory(App\Examination::class)->create(); 
        $user = User::find('1');
        $response = $this->actingAs($user)->call('POST', "admin/examination/".$examination->id."/generateEquipParam", 
        [  
            'exam_id' =>$examination->id, 
            'in_equip_date' => '2020-12-12' 
        ]);
        $this->assertEquals(200, $response->status());
    }


    // public function test_visit_generateSPB()
    // {
    //     $examination = factory(App\Examination::class)->create(); 
    //     $response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/generateSPB');  
    //     $this->assertEquals(200, $response->status());
    // }
    
    public function test_cetakUjiFungsi()
    {
        $examination = factory(App\Examination::class)->create(); 
        $response = $this->actingAs(User::find('1'))->call('GET', 'cetakUjiFungsi/'.$examination->id);  
        $this->assertEquals(200, $response->status());
    }
    
    public function test_cetakFormBarang()
    {
        $device = factory(App\Device::class)->create(); 
        $examLab = factory(App\ExaminationLab::class)->create(); 
        $examination = factory(App\Examination::class)->create(['device_id'=>$device->id,"examination_lab_id"=>$examLab->id]); 
        $response = $this->actingAs(User::find('1'))->call('GET', 'cetakFormBarang/'.$examination->id);  
        $this->assertResponseStatus(302);

        factory(App\Equipment::class)->create(["examination_id"=>$examination->id]);
        $response = $this->actingAs(User::find('1'))->call('GET', 'cetakFormBarang/'.$examination->id);  
        $this->assertResponseStatus(200);
    }
    public function test_visit_generateEquip()
    { 
        $examination = factory(App\Examination::class)->create(); 
        $equipment = factory(App\Equipment::class)->create(['examination_id'=>$examination->id]);
        Session::push('key_exam_id_for_generate_equip_masuk', $examination->id);
        $response = $this->actingAs(User::find('1'))->call('GET', 'admin/examination/generateEquip');  
        $this->assertEquals(200, $response->status());
    } 

    public function test_deleteRevLapUji(){ 
        $examinationAttach = factory(App\ExaminationAttach::class)->create(); 
        $user = User::find('1');
        $response = $this->actingAs($user)->call('GET', "admin/examination/".$examinationAttach->id."/deleteRevLapUji");
        $this->assertEquals(302, $response->status());
    }



}
