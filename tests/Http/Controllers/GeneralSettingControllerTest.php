<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GeneralSettingControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit()
	{ 
	   $response = $this->call('GET', 'admin/generalSetting');  
       $this->assertEquals(200, $response->status());
	}
	
    public function test_stores()
	{ 
		$user = factory(App\User::class)->create(); 
       
		$response =  $this->actingAs($user)->call('POST', 'admin/generalSetting', 
		[ 
            'question' => str_random(10),
	        'answer' => str_random(10)
	    ]);   
		
        $this->assertEquals(302, $response->status());
	      
	}
    public function test_update_company()
	{ 
		$user = factory(App\User::class)->create(); 
       	$company = Company::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/generalSetting/', 
		[ 
	        'manager_urel' => str_random(10),
	        
	       
	       
	    ]);   
		
        $this->assertEquals(302, $response->status());
	   
	}
    public function test_delete_company()
	{ 
		$user = factory(App\User::class)->create(); 
       	$company = Company::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/generalSetting/');   
		
        $this->assertEquals(302, $response->status());
	  
	}
}
