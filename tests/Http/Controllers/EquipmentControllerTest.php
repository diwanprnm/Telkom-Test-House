<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Equipment;
use App\User;
class EquipmentControllerTest extends TestCase
{
    
    /**
     * A basic test example.
     *
     * @return void
     */

	public function testDeleteSoon(){
        $this->assertTrue(true);
    }


   /* public function testVisit()
	 { 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/equipment');
        $this->assertEquals(200, $response->status());
	 }
	 public function test_search()
	 { $admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/equipment?search=asd');  
        $this->assertEquals(200, $response->status());
	 }
     public function testStores()
	 { 
	 	$user = factory(App\User::class)->create(); 
       
	 	$response =  $this->actingAs($user)->call('POST', 'admin/equipment', 
	 	[ 
             'examination_id' => str_random(10),
	         'name' => str_random(10),
	         'qty' => mt_rand(0,1),
	         'unit' => mt_rand(0,10) ,
	         'description' =>  str_random(10),
	         'pic' => str_random(10) ,
	         'remark' =>mt_rand(0,1) 
	     ]);   
		
         $this->assertEquals(302, $response->status());
	     
		}
     public function testUpdate()
	 { 
	 	$user = factory(App\User::class)->create(); 
        	$company = Equipment::latest()->first();
	 	$response =  $this->actingAs($user)->call('PUT', 'admin/equipment/', 
	 	[ 
	         'examination_id' => str_random(10),
	         'name' => str_random(10),
	         'qty' => mt_rand(0,10),
	         'unit' => mt_rand(0,10) ,
	         'description' =>  str_random(10),
	         'pic' => str_random(10) ,
	         'remark' =>mt_rand(0,1) 
	     ]);   
         $this->assertEquals(302, $response->status());
	 }
     public function testDelete()
	 { 
	 	$user = factory(App\User::class)->create(); 
        $company = Equipment::latest()->first();
	 	$response =  $this->actingAs($user)->call('DELETE', 'admin/equipment/');  
         $this->assertEquals(302, $response->status());  
	 } */
}
