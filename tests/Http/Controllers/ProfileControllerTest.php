<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\Company; 
class ProfileControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/client/profile?tabs=company');  
       $this->assertEquals(200, $response->status());
	}
    public function test_update_user()
	{ 
	    $user = User::find(1);  
		$response =  $this->actingAs($user)->call('POST', '/client/profile', 
		[ 
	        'hide_id_user' => $user->id,  
	        'username' => str_random(10),  
	        'email' => str_random(10), 
	        'address' => str_random(10), 
	        'password' => str_random(10), 
	        'phone_number' => 1, 
	        'fax' => str_random(10), 
	        'email2' => str_random(10), 
	        'email3' => str_random(10) 
	    ]);    
	    
        $this->assertEquals(302, $response->status()); 
	}
    public function test_update_company()
	{ 
	    $user = User::find(1);  
	    $company = Company::find(1);  
		$response =  $this->actingAs($user)->call('POST', '/client/company', 
		[ 
	        'hide_id_company' => $company->id,   
	        'name' => str_random(10),   
	        'address' => str_random(10), 
	        'plg_id' => str_random(10), 
	        'nib' => 1, 
	        'city' => str_random(10), 
	        'email' => str_random(10), 
	        'postal_code' => str_random(10), 
	        'phone' => str_random(10) , 
	        'fax' => str_random(10) , 
	        'npwp_number' => str_random(10), 
	        'siup_number' => str_random(10) ,
	        'certificate_number' => str_random(10) ,
	    ]);    
	    
        $this->assertEquals(302, $response->status()); 
	}
    public function test_check_registrasi_email()
	{ 
	    $user = User::find(1);   
		$response =  $this->actingAs($user)->call('POST', '/checkRegisterEmail', 
		[  
	        'email' => str_random(10) 
	    ]);    
	    
        $this->assertEquals(200, $response->status()); 
	}
    public function test_register()
	{ 
	    $user = User::find(1);    
		$response =  $this->actingAs($user)->call('GET', '/register');    
	    
        $this->assertEquals(302, $response->status()); 
	}
}
