<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;  
class HomeControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_visit_about()
	{ 
	    
	   $response =  $this->call('GET', '/about');  
        
	 
       $this->assertEquals(200, $response->status());
	} 

    public function test_visit_sertifikasi()
	{ 
	    
	   $response =  $this->call('GET', '/sertifikasi');  
        
	 
       $this->assertEquals(200, $response->status());
	} 
    public function test_visit_contact()
	{ 
	    
	   $response =  $this->call('GET', '/contact');  
        
	 
       $this->assertEquals(200, $response->status());
	} 
    public function test_visit_faq()
	{ 
	    
	   $response =  $this->call('GET', '/faq');  
        
	 
       $this->assertEquals(200, $response->status());
	} 
    public function test_visit_procedure()
	{ 
	    
	   $response =  $this->call('GET', '/procedure');  
        
	 
       $this->assertEquals(200, $response->status());
	} 
    public function test_visit_process()
	{ 
	    
	    $user = factory(User::class)->make();
	    $response =  $this->actingAs($user)->call('GET', '/process');  
        
	 
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_detail_process()
	{ 
	    
	  $user = factory(User::class)->make();
	   $response =  $this->actingAs($user)->call('GET', '/detailprocess/1');  
        
	 
       $this->assertEquals(200, $response->status());
	} 
    public function test_visit_edit_process()
	{ 
	    
	   	$user = factory(User::class)->make();
	   	$response =  $this->actingAs($user)->call('GET', '/editprocess/qa/1');  
        
	 
       	$this->assertEquals(302, $response->status());
	} 
    public function test_visit_language()
	{ 
	    
	   	$user = User::find(1);
	   	$response =  $this->actingAs($user)->call('GET', '/language/en');  
        
	 
       	$this->assertEquals(302, $response->status());
	} 
    public function test_visit_search()
	{ 
	    
	   	$user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', '/global/search', 
		[ 
	        'globalSearch' => str_random(10) 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(200, $response->status());
	} 
    public function test_download_usman()
	{ 
	    
	    $file_name = "User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Customer].pdf";
        $file = \Storage::disk('local_public')->get("images/testing.jpg");
        \Storage::disk('minio')->put($file_name, $file);

        //make request
        $admin = User::find(1); 
        $response = $this->actingAs($admin)->call('GET',"/client/downloadUsman"); 
 	echo $response->headers->get('content-type') ;
        $this->assertTrue($response->headers->get('content-type') == 'application/pdf');

        // Delete file from minio
        \Storage::disk('minio')->delete("usman/".$file_name);
	} 
}
