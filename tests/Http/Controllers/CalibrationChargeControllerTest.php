<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;  
use App\CalibrationCharge; 
use App\User;
class CalibrationChargeControllerTest extends TestCase
{
	
	use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */ 

    /**
	 * @test
	 */
	
	public function delete_soon(){
        $this->assertTrue(true);
    }

	// public function testIndex()
    // {
		
		
	// 	$response = $this->call('GET', 'admin/calibration');	
    //     $this->assertResponseStatus(200);
    // } 
	// public function test_stores()
	// { 
	// 	$user = factory(App\User::class)->create(); 
	// 	$response =  $this->actingAs($user)->call('POST', 'admin/calibration', 
	// 	[ 
	// 		'device_name' => str_random(10),
	//         'price' => mt_rand(0,10000),
	//         'is_active' => mt_rand(0,1)
	// public function testIndexAsNonAdmin()
    // {
		
    //     $user = factory(User::class)->make();
    //     $this->actingAs($user)->call('GET','admin/calibration');
    //     $this->assertResponseStatus(200);
    // }


	// public function test_search_company()
	// { 
	//    $response = $this->call('GET', 'admin/calibration?search=asda&is_active=&after_date=&before_date=');  
    //    $this->assertEquals(200, $response->status());
	// }
	// public function test_stores()
	// { 
	// 	$user = factory(App\User::class)->create(); 
	// 	$response =  $this->actingAs($user)->call('GET', 'admin/calibration', 
	// 	[ 
	// 		'device_name' => str_random(10),
	//         'price' => mt_rand(0,10000),
	//         'is_active' => mt_rand(0,1)
	        
	//     ]);   
		
    //     $this->assertEquals(500, $response->status());
	    
	// }

	
	// public function test_update_data()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$company = CalibrationCharge::latest()->first();
	// 	$response =  $this->actingAs($user)->call('PUT', 'admin/calibration/'.$company->id, 
	// 	[ 
	// 		'device_name' => str_random(10),
	//         'price' => mt_rand(0,10000),
	//         'is_active' => mt_rand(0,1)
	// 		]);   
			
	// 		$this->assertEquals(302, $response->status());
			 
	// 	}
	

	
	// public function test_delete()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$company = CalibrationCharge::latest()->first();
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/calibration/'.$company->id);   
    //     $this->assertEquals(302, $response->status());
	    
	// }
}
