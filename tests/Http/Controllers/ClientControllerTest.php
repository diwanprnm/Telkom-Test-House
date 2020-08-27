<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;  
class ClientControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */ 
    public function testCekLogin()
    {
        
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('POST', '/cekLogin');  
       $this->assertEquals(200, $response->status());
    }
    public function testLogout()
    {
       $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/client/logout'); 
	   $this->assertEquals(302, $response->status());
    }
    public function testAuthenticate()
    {
    	$user = User::find(1);
    	$response =  $this->actingAs($user)->call('POST', '/client/login', 
		[ 
	        'email' => "admin@mail.com",
	        'password' => "admin" 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status()); 
    }
}
