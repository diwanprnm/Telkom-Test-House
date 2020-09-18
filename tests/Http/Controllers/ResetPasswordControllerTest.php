<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;  
class ResetPasswordControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_stores_client_email()
	{ 
		$user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', '/client/password/email', 
		[ 
	        
	        'email' =>  str_random(10) 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $company = factory(App\Company::class)->make();  
	}
	public function test_visit_get_reset()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/client/password/reset/123');  
        
	 
       $this->assertEquals(200, $response->status());
	} 
 //    public function test_stores_client_reset()
	// { 
	// 	$user = User::find(1);
       
	// 	$response =  $this->actingAs($user)->call('POST', '/client/password/reset', 
	// 	[ 
	        
	//         'email' =>  str_random(10), 
	//         'password' =>  str_random(10)
	//     ]);   
	// 	// dd($response->getContent());
 //        $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }

	
}
