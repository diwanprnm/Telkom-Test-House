<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\GeneralSetting;

class GeneralSettingControllerTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

	public function testDeleteSoon(){
        $this->assertTrue(true);
    }
	 
     public function test_visit()
	 { 
	    $response = $this->call('GET', 'admin/generalSetting');  
        $this->assertEquals(302, $response->status());
	 }
	
     public function test_stores()
	 { 
	 	$user = factory(App\User::class)->create(); 
       
	 	$response =  $this->actingAs($user)->call('POST', 'admin/generalSetting', 
	 	[ 
             'question' => str_random(10),
	         'answer' => str_random(10)
	     ]);   
		
         $this->assertEquals(200, $response->status());
	      
	 }
     public function test_update_company()
	 { 
	 	$user =User::find('1');
        	$company = GeneralSetting::latest()->first();
	 	$response =  $this->actingAs($user)->call('PUT', 'admin/generalSetting/', 
	 	[ 
	         'manager_urel' => str_random(10),
	        
	       
	       
	    ]);   
		
         $this->assertEquals(302, $response->status());
	   
	 }
	 public function test_delete_question()
	 { 
		$admin = User::find('1');
        $general = GeneralSetting::latest()->first();
		$response =  $this->actingAs($admin)->call('DELETE', 'admin/generalSetting/'.$general->id);   
		
        $this->assertEquals(302, $response->status());
	     
	 }
}
