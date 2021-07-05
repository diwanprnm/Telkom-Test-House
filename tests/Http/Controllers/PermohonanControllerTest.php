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
	}

	public function test_submitPermohonan()
	{ 
	   	$user = User::find(1);
	    $stel = factory(App\STEL::class)->create();
	    $response =  $this->actingAs($user)->call('POST', '/submitPermohonan', [ 
			'kode_jenis_pengujian' => 1, 
			'jns_perusahaan' => 1, 
			'examination_location' => str_random(10), 
			'device_name' => str_random(10), 
			'device_mark' => str_random(10), 
			'device_capacity' => str_random(10), 
			'device_made_in' => str_random(10), 
			'device_serial_number' => str_random(10), 
			'device_model' => str_random(10), 
			'test_reference' => $stel->code,
	    ]);
	   $this->assertEquals(200, $response->status());
	   $examination = App\Examination::latest()->first();
	   Storage::disk('minio')->delete("examination/$examination->id/dummy.pdf");
	} 
	
	public function test_updatePermohonan()
	{ 
	   
	   $user = User::find(1);
	    $stel = factory(App\STEL::class)->create();
	    $exam = factory(App\Examination::class)->create();
	    $response =  $this->actingAs($user)->call('POST', '/updatePermohonan', [
			'hide_exam_id' => $exam->id,
			'hide_device_id' => $exam->device_id,
			'kode_jenis_pengujian' => 1,
			'jns_perusahaan' => 1, 
			'examination_location' => str_random(10), 
			'device_name' => str_random(10), 
			'device_mark' => str_random(10), 
			'device_capacity' => str_random(10), 
			'device_made_in' => str_random(10), 
			'device_serial_number' => str_random(10), 
			'device_model' => str_random(10), 
			'test_reference' => $stel->code,
	    ]);   
       $this->assertEquals(200, $response->status());
	} 
	 public function test_getPemohon()
	{ 
	   
	   $user = User::find(1);
	    $response =  $this->actingAs($user)->call('POST', '/getPemohon');   
       $this->assertEquals(200, $response->status());
	}  

	public function test_feedback()
	{ 
	   $question = factory(App\Question::class)->create();
	   $user = User::find(1);
	     $response =  $this->actingAs($user)->call('POST', '/client/feedback', [ 
	       'question' => $question->id,  
	       'subject' => str_random(10),
	       'message' => str_random(10), 
	       'email' => "admin@gmail.com" 
	    ]);   
       $this->assertEquals(200, $response->status());
	} 


 
}
