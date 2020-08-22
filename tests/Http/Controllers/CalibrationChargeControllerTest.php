<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;  
use App\CalibrationCharge; 
use App\User;
class CalibrationChargeControllerTest extends TestCase
{
	
	
    /**
     * A basic test example.
     *
     * @return void
     */ 

    /**
	 * @test
	 */
	
	public function testDeleteSoon(){
        $this->assertTrue(true);
    }

	 


	 public function testIndexAdmin()
     {
		$user = User::where('role_id', '=', '1')->first();
         
         $this->actingAs($user)->call('GET','admin/calibration');
         $this->assertResponseStatus(200);
     }


	 public function test_search_company()
	 { 
		$response = $this->call('GET', 'admin/calibration?search=cari&is_active=-1');  
		
		$this->assertEquals(302, $response->status());
		
	 }

	 public function testCreate()
	 {
		 $user = User::where('role_id', '=', '1')->first();
		 $this->actingAs($user)->call('GET','admin/calibration/create');
		 
		 $this->assertResponseStatus(200);
		 
	 }

	 public function test_stores()
	 { 
		$user = User::where('role_id', '=', '1')->first();
	 	$response =  $this->actingAs($user)->call('POST', 'admin/calibration', 
	 	[ 
	 		'device_name' => str_random(10),
	         'price' => mt_rand(0,10000),
	         'is_active' => mt_rand(0,1)
	        
	     ]);   
		 
         $this->assertEquals(302, $response->status());
	    
	 }

	 public function testEdit()
	 {	$calibration = CalibrationCharge::latest()->first();
		 $user = User::where('role_id', '=', '1')->first(); 
        
		$response = $this->actingAs($user)->call('GET', 'admin/calibration/'.$calibration->id.'/edit');
		
		 $this->assertEquals(200, $response->status());
		 
	 }
	
	 public function test_update_data()
	 { 
		$user = User::where('role_id', '=', '1')->first(); 
        $calibration = CalibrationCharge::latest()->first();
	 	$response =  $this->actingAs($user)->call('PUT', 'admin/calibration/'.$calibration->id, 
	 	[ 
	 		'device_name' => str_random(10),
	         'price' => mt_rand(0,10000),
	         'is_active' => mt_rand(0,1)
	 		]);   
			
	 		$this->assertEquals(302, $response->status());
			 
	 	}
	

	
	 public function test_delete()
	 { 
		$user = User::where('role_id', '=', '1')->first(); 
        $company = CalibrationCharge::latest()->first();
		 $response =  $this->actingAs($user)->call('DELETE', 'admin/calibration/'.$company->id);  
		  
         $this->assertEquals(302, $response->status());
	    
	} 
}
