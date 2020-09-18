<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;  
class STELClientControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
     public function test_visit_index()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/STELclient');   
	 
       $this->assertEquals(200, $response->status());
	} 

	public function test_stores_filter()
	{ 
		$user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', '/filterSTEL', 
		[ 
	        'category' => str_random(10) 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}

	public function test_visit_autocomplete()
	{ 
		 $user = User::find(1);
        $this->actingAs($user)->call('GET',"/stel_autocomplete/query/asd");
        //Response status ok
        
        $this->assertResponseStatus(200); 
	}
}
