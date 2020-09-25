<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Controllers\PermohonanController; 
use App\User; 
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
class PermohonanControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	 public function test_submit_permohonan()
	{ 
	   
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/');   
       $this->assertEquals(200, $response->status());
	} 

	 public function test_uploadPermohonan()
	{ 
	   
	   $user = User::find(1);
	   $path = public_path("images/testing.jpg"); 
	   $name = "testing.jpg";
	   $file = new UploadedFile($path, $name, null, 'image/png', null, true);

	   $response =  $this->actingAs($user)->call('POST', '/uploadPermohonan', [ 
	        
	        'fuploaddetailpengujian' =>  $file
	    ]);   
       $this->assertEquals(200, $response->status());
	} 


	 public function test_uploadPermohonanEdit()
	{  
		$examination = factory(App\Examination::class)->create();
	    Session::push('hide_exam_id', $examination->id);
	    $user = User::find(1);
	    $path = public_path("images/testing.jpg"); 
	    $name = "testing.jpg";
	    $file = new UploadedFile($path, $name, null, 'image/png', null, true);

	    $response =  $this->actingAs($user)->call('POST', '/uploadPermohonanEdit', [ 
	       'fuploaddetailpengujian' =>  $file
	    ]);   
       $this->assertEquals(200, $response->status());
	} 

	
	 public function test_cekPermohonan()
	{ 
	   
	   $user = User::find(1);
	     $response =  $this->actingAs($user)->call('POST', '/cekPermohonan', [ 
	       'jnsPelanggan' => 1, 
	       'nama_perangkat' => 1, 
	       'model_perangkat' => 1, 
	       'kapasitas_perangkat' => 1, 
	       'merk_perangkat' => 1
	    ]);   
       $this->assertEquals(200, $response->status());
	}  public function test_submitPermohonan()
	{ 
	   
	   $user = User::find(1);
	    $stel = factory(App\STEL::class)->create();
	    $response =  $this->actingAs($user)->call('POST', '/submitPermohonan', [ 
	       'f1-nama-pemohon' => str_random(10), 
	       'f1-alamat-pemohon' => str_random(10), 
	       'f1-alamat-pemohon' => str_random(10), 
	       'f1-telepon-pemohon' => str_random(10), 
	       'f1-faksimile-pemohon' => str_random(10), 
	       'f1-faksimile-pemohon' => str_random(10), 
	       'jns_perusahaan' => 1, 
	       'f1-nama-perusahaan' => str_random(10), 
	       'f1-alamat-perusahaan' => str_random(10), 
	       'f1-plg_id-perusahaan' => str_random(10), 
	       'f1-nib-perusahaan' => str_random(10), 
	       'f1-telepon-perusahaan' => str_random(10), 
	       'f1-faksimile-perusahaan' => str_random(10), 
	       'f1-email-perusahaan' => str_random(10), 
	       'hide_npwpPerusahaan' => str_random(10), 
	       'hide_jns_pengujian' => 1, 
	       'lokasi_pengujian' => str_random(10), 
	       'f1-nama-perangkat' => str_random(10), 
	       'f1-merek-perangkat' => str_random(10), 
	       'f1-kapasitas-perangkat' => str_random(10), 
	       'f1-pembuat-perangkat' => str_random(10), 
	       'f1-serialNumber-perangkat' => str_random(10), 
	       'f1-model-perangkat' => str_random(10), 
	       'f1-jns-referensi-perangkat' => 1,  
	       'f1-cmb-ref-perangkat' => $stel->code.",1",
	       'f1-no-siupp' => str_random(10),
	       'f1-tgl-siupp' => "2020-09-20",
	       'f1-batas-waktu' => "12",
	    ]);   
       $this->assertEquals(200, $response->status());
	} 
	 public function test_getPemohon()
	{ 
	   
	   $user = User::find(1);
	    $response =  $this->actingAs($user)->call('POST', '/getPemohon');   
       $this->assertEquals(200, $response->status());
	}  

	// public function test_feedback()
	// { 
	//    $question = factory(App\Question::class)->create();
	//    $user = User::find(1);
	//      $response =  $this->actingAs($user)->call('POST', '/client/feedback', [ 
	//        'question' => $question->id,  
	//        'subject' => str_random(10),
	//        'message' => str_random(10), 
	//        'email' => "admin@gmail.com" 
	//     ]);   
 //       $this->assertEquals(200, $response->status());
	// } 


 
}
