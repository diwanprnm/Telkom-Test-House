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
		// $response =  $this->call('GET', '/STELclient');  
       	// $this->assertEquals(200, $response->status());
	} 

	public function test_stores_filter()
	{ 
		$response =  $this->call('POST', '/filterSTEL', 
		[ 
	        'category' => str_random(10) 
	    ]);    
        $this->assertEquals(200, $response->status()); 
	}

	public function test_visit_autocomplete()
	{ 
		$this->call('GET',"/stel_autocomplete/query/asd");
        //Response status ok
        
        $this->assertResponseStatus(200); 
	}
}
