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
