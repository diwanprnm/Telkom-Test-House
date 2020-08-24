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
	 
  /*  public function test_visit()
	 {   
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/generalSetting');  
        $this->assertEquals(200, $response->status());
	 }*/
	
     
   /*  public function test_update()
	 { 
	 	$user =User::find('1');
		 
	 	$response =  $this->actingAs($user)->call('PUT', 'admin/generalSetting/', 
	 	[ 
	         'manager_urel' => str_random(10)
	       
	    ]);   
		//dd($response);
         $this->assertEquals(200, $response->status());
	   
	 }*/
	
}
