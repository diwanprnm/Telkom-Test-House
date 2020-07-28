<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ExaminationLab;

class ExaminationLabControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */

	public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    // public function test_visit()
	// { 
	//    $response = $this->call('GET', 'admin/labs');  
    //    $this->assertEquals(200, $response->status());
	// }
	// public function test_searchy()
	// { 
	//    $response = $this->call('GET', 'admin/labs?search=asda&is_active=&after_date=&before_date=');  
    //    $this->assertEquals(200, $response->status());
	// }
    // public function test_stores()
	// { 
	// 	$user = factory(App\User::class)->create(); 
       
	// 	$response =  $this->actingAs($user)->call('POST', 'admin/labs', 
	// 	[ 
	//         'name' => str_random(10),
	//         'lab_code' => str_random(10),
    //         'lab_init' => str_random(10) , 
    //         'description' => str_random(10) ,
	//         'is_active' => mt_rand(0,1)
	        
	//     ]);   
    //     $this->assertEquals(500, $response->status());
	      
	// }
    // public function test_update()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$lab = ExaminationLab::latest()->first();
	// 	$response =  $this->actingAs($user)->call('PUT', 'admin/labs/'.$lab->id, 
	// 	[ 
	//         'name' => str_random(10),
	//         'lab_code' => str_random(10),
    //         'lab_init' => str_random(10) , 
    //         'description' => str_random(10) ,
	//         'is_active' => mt_rand(0,1)
	//     ]);   
	
    //     $this->assertEquals(302, $response->status());
	      
	// }
    // public function test_delete()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$lab = ExaminationLab::latest()->first();
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/labs/'.$lab->id);   
    //     $this->assertEquals(500, $response->status());
	     
	// }
}
