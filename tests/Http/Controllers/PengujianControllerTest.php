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
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=1&search=asd&status=1');  
        
	 
       $this->assertEquals(200, $response->status());
	}
     public function test_visit_jns_0_status_1_search()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	   $stel = factory(App\STEL::class)->create();
	   $questioner = factory(App\Questioner::class)->create();
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=0&search=asd&status=1');  
        
	 
       $this->assertEquals(200, $response->status());
	}
     public function test_visit_jns_1_status_0_search()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	   $stel = factory(App\STEL::class)->create();
	   $questioner = factory(App\Questioner::class)->create();
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=1&search=asd&status=0');  
        
	 
       $this->assertEquals(200, $response->status());
	}

    public function test_visit_no_search()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	   $stel = factory(App\STEL::class)->create();
	   $questioner = factory(App\Questioner::class)->create();
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=1&status=1');  
        
	 
       $this->assertEquals(200, $response->status());
	}

    public function test_visit_no_search_jns_0()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	   $stel = factory(App\STEL::class)->create();
	   $questioner = factory(App\Questioner::class)->create();
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=0&status=1');  
        
	 
       $this->assertEquals(200, $response->status());
	}


    public function test_visit_no_search_jns_1()
	{ 
	   // $user = User::find(1);
	   $user = factory(App\User::class)->create(['role_id' => 2]);
	   $stel = factory(App\STEL::class)->create();
	   $questioner = factory(App\Questioner::class)->create();
	   $response =  $this->actingAs($user)->call('GET', '/pengujian?jns=1&status=0');  
        
	 
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

	public function test_filterPengujian_status_0()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
		$response =  $this->actingAs($user)->call('POST', 'filterPengujian', 
		[ 
	        'pengujian' =>2,
	        'status' => 0, 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	} 

	public function test_filterPengujian_pengujian0()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
		$response =  $this->actingAs($user)->call('POST', 'filterPengujian', 
		[ 
	        'pengujian' =>0,
	        'status' => 1, 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	} 

	public function test_filterPengujian_pengujian1()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
		$response =  $this->actingAs($user)->call('POST', 'filterPengujian', 
		[ 
	        'pengujian' =>1,
	        'status' => 0, 
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
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id]);
	    $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id' => $examination->id]);
	    $examinationHistory = factory(App\ExaminationHistory::class)->create(['examination_id' => $examination->id]);
	    $response =  $this->actingAs($user)->call('GET', '/pengujian/'.$examination->id."/detail");
        
	 
       $this->assertEquals(200, $response->status());
	}

	public function test_pembayaran()
	{  
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id,'payment_method'=>'atm']);
	    $examinationAttach = factory(App\ExaminationAttach::class)->create(['examination_id' => $examination->id,'name'=>"File Pembayaran"]);
	    $examinationHistory = factory(App\ExaminationHistory::class)->create(['examination_id' => $examination->id]);
	    $response =  $this->actingAs($user)->call('GET', '/pengujian/'.$examination->id."/pembayaran");
        
	 
       $this->assertEquals(200, $response->status());
	}
	
	public function test_api_resend_va()
	{  
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id,'payment_method'=>'atm']);
	    
	    $response =  $this->actingAs($user)->call('GET', '/resend_va_spb/'.$examination->id);
        
	 
       $this->assertEquals(302, $response->status());
	}
	public function test_api_cancel_va()
	{  
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create(['company_id'=>$user->company_id,'created_by'=>$user->id,'payment_method'=>'atm']);
	    
	    $response =  $this->actingAs($user)->call('GET', '/cancel_va_spb/'.$examination->id);
        
	 
       $this->assertEquals(302, $response->status());
	}
	
	public function test_payment_confirmation_spb()
	{  
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $device = factory(App\Device::class)->create();
        $examination = factory(App\Examination::class)->create(["device_id"=>$device->id,"payment_method"=>"atm"]);  

	    $response =  $this->actingAs($user)->call('GET', '/payment_confirmation_spb/'.$examination->id);
        
	 
       $this->assertEquals(302, $response->status());
	}

	public function test_uploadPembayaran()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $examination = factory(App\Examination::class)->create();
	    $examAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id]);
		$response =  $this->actingAs($user)->call('POST', 'editPengujian', 
		[ 
	        'hide_id_exam' =>$examination->id ,
	        'hide_file_pembayaran' =>str_random(10) ,
	        'tgl-pembayaran' =>'2020-12-12',
	        'jml-pembayaran' =>mt_rand(0,120000),
	        'no-pembayaran' =>mt_rand(0,100),
	        'hide_id_attach' =>$examAttach->id 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}  

	public function test_doCheckoutSPB()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $device = factory(App\Device::class)->create();
        $examination = factory(App\Examination::class)->create(["device_id"=>$device->id]); 
	    $examAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id]);
		$response =  $this->actingAs($user)->call('POST', 'doCheckoutSPB', 
		[ 
	        'hide_id_exam' =>$examination->id ,
	        'is_pph' =>mt_rand(0,2) , 
	        'payment_method' =>"A||B||atm||C||DD" , 
	    ]);    
        $this->assertEquals(302, $response->status()); 
	}  
	public function test_doCheckoutSPB_noATM()
	{ 
	    $user = factory(App\User::class)->create(['role_id' => 2]);
	    $device = factory(App\Device::class)->create();
        $examination = factory(App\Examination::class)->create(["device_id"=>$device->id]); 
	    $examAttach = factory(App\ExaminationAttach::class)->create(['examination_id'=>$examination->id]);
		$response =  $this->actingAs($user)->call('POST', 'doCheckoutSPB', 
		[ 
	        'hide_id_exam' =>$examination->id ,
	        'is_pph' =>mt_rand(0,2) , 
	        'payment_method' =>"A||B||va||C||DD" , 
	    ]);    
        $this->assertEquals(302, $response->status()); 
	}  
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
