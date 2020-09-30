<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;  

class PengujianControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
     public function test_visit()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	   $stel = factory(App\STEL::class)->create();
	   $questioner = factory(App\Questioner::class)->create();
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=1');  
        
	 
       $this->assertEquals(200, $response->status());
	}

	public function test_filterPengujian()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
		$response =  $this->actingAs($user)->call('POST', 'filterPengujian', 
		[ 
	        'pengujian' =>2,
	        'status' => 1, 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	} 

	public function test_editPengujian()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
		$response =  $this->actingAs($user)->call('POST', 'editPengujian', 
		[ 
	        'id' =>$examination->id 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}  

	public function test_detailPengujian()
	{ 
	   
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id]);
	    $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id' => $examination->id]);
	    $examinationHistory = factory(App\ExaminationHistory::class)->create(['examination_id' => $examination->id]);
	    $response =  $this->actingAs($user)->call('GET', '/pengujian/'.$examination->id."/detail");  
        
	 
       $this->assertEquals(200, $response->status());
	} 

	  public function test_detail()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id]);
	    $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id' => $examination->id]);
	    $examinationHistory = factory(App\ExaminationHistory::class)->create(['examination_id' => $examination->id]);
	    $response =  $this->actingAs($user)->call('GET', '/pengujian/'.$examination->id."/detail");
        
	 
       $this->assertEquals(200, $response->status());
	}

	  public function test_pembayaran()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id]);
	    $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id' => $examination->id,'name'=>"File Pembayaran"]);
	    $examinationHistory = factory(App\ExaminationHistory::class)->create(['examination_id' => $examination->id]);
	    $response =  $this->actingAs($user)->call('GET', '/pengujian/'.$examination->id."/pembayaran");
        
	 
       $this->assertEquals(200, $response->status());
	}

	// public function test_uploadPembayaran()
	// { 
	//     $user = factory(App\User::class)->create(['role_id' => 2]);
	//     $examination = factory(App\Examination::class)->create();
	// 	$response =  $this->actingAs($user)->call('POST', 'editPengujian', 
	// 	[ 
	//         'id' =>$examination->id 
	//     ]);    
 //        $this->assertEquals(200, $response->status()); 
	// }  
	public function test_tanggalUji_tipe1()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
		$response =  $this->actingAs($user)->call('POST', '/pengujian/tanggaluji', 
		[ 
	        'hide_id_exam' =>$examination->id,
	        'hide_date_type' =>1,
	    ]);    
        $this->assertEquals(302, $response->status()); 
	}  
	public function test_tanggalUji_tipe2()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
		$response =  $this->actingAs($user)->call('POST', '/pengujian/tanggaluji', 
		[ 
	        'hide_id_exam2' =>$examination->id,
	        'hide_date_type' =>2,
	    ]);    
        $this->assertEquals(302, $response->status()); 
	}
	public function test_tanggalUji_tipe3()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
		$response =  $this->actingAs($user)->call('POST', '/pengujian/tanggaluji', 
		[ 
	        'hide_id_exam3' =>$examination->id,
	        'hide_date_type' =>3,
	    ]);    
        $this->assertEquals(302, $response->status()); 
	}  

	 public function test_details()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id]);
	    $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id' => $examination->id]);
	    $examinationHistory = factory(App\ExaminationHistory::class)->create(['examination_id' => $examination->id]);
	    $response =  $this->actingAs($user)->call('GET', '/cetakPengujian/'.$examination->id);
        
	 
       $this->assertEquals(302, $response->status());
	}


	public function test_testimonial()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
		$response =  $this->actingAs($user)->call('POST', '/testimonial', 
		[ 
	        'exam_id' =>$examination->id,
	        'message' =>str_random(10),
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}  
	public function test_cekAmbilBarang()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
		$response =  $this->actingAs($user)->call('POST', '/cekAmbilBarang', 
		[ 
	        'my_exam_id' =>$examination->id 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}  
	// public function test_checkKuisioner()
	// { 
	//     $user = factory(App\User::class)->create(['role_id' => 2]);
	//     $examination = factory(App\Examination::class)->create();
	// 	$response =  $this->actingAs($user)->call('POST', '/checkKuisioner', 
	// 	[ 
	//         'examination_id' =>$examination->id 
	//     ]);    
 //        $this->assertEquals(200, $response->status()); 
	// }

	// public function test_insertKuisioner()
	// { 
	//     $user = factory(App\User::class)->create(['role_id' => 2]);
	//     $examination = factory(App\Examination::class)->create(); 
	// 	$response =  $this->actingAs($user)->call('POST', '/insertKuisioner', 
	// 	[ 
	//         'exam_id' =>$examination->id,
	//         'question_id[]' =>array(
	//         	array("is_essay"=>1,"eks"=>1)
	//         ),
	//         'tanggal' =>'2020-12-12',
	//     ]);    
 //        $this->assertEquals(200, $response->status()); 
	// }  
	public function test_insertComplaint()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(); 
	    $questioner = factory(App\Questioner::class)->create(['examination_id'=>$examination->id]); 
		$response =  $this->actingAs($user)->call('POST', '/insertComplaint', 
		[ 
	        'my_exam_id' =>$examination->id,
	        'complaint' =>str_random(10),
	        'tanggal_complaint' =>'2020-12-12',
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}  

	public function test_autocomplete()
    {
        $user = factory(App\User::class)->create(['role_id' => 2]);
        $this->actingAs($user)->call('GET',"pengujian_autocomplete/query");
        //Response status ok
        $this->assertResponseStatus(200); 
    }

     public function test_downloadSPB()
    {
        $exam = factory(App\Examination::class)->create();
        $examAttach = factory(App\ExaminationAttach::class)->create(['name'=>'spb','examination_id'=>$exam->id]);
        $path = "examination/".$exam->id."/".$examAttach->attachment;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }
        $user = factory(App\User::class)->create(['role_id' => 2]);
        $response = $this->actingAs($user)->call('GET','pengujian/'.$exam->id."/downloadSPB");
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
     public function test_downloadLaporanPengujian()
    {
        $exam = factory(App\Examination::class)->create();
        $examAttach = factory(App\ExaminationAttach::class)->create(['name'=>'Laporan Uji','examination_id'=>$exam->id]);
        $path = "examination/".$exam->id."/".$examAttach->attachment;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }
        $user = factory(App\User::class)->create(['role_id' => 2]);
        $response = $this->actingAs($user)->call('GET','pengujian/'.$exam->id."/downloadLaporanPengujian");
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
     public function test_downloadSertifikat()
    {
        $device = factory(App\Device::class)->create();
        $exam = factory(App\Examination::class)->create(["device_id"=>$device->id]);
        $path = "device/".$device->id."/".$device->certificate;
        $isFileExist = Storage::disk('minio')->exists($path);

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put($path, $file);
        }
        $user = factory(App\User::class)->create(['role_id' => 2]);
        $response = $this->actingAs($user)->call('GET','pengujian/'.$exam->id."/downloadSertifikat");
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete($path);
        }
    }
}
