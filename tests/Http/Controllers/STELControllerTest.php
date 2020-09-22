<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\STEL;

class STELControllerTest extends TestCase
{  
    public function test_visit_stel()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel');   
       $this->assertEquals(200, $response->status());
	} 

	public function test__visit_stel_with_search()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel?search=cari');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_stel()
	{ 
 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel/create');  
       $this->assertEquals(200, $response->status());
	}
    public function test_stores_stel()
	{ 
 
	    $user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', 'admin/stel', 
		[ 
	        'code' => str_random(10), 
	        'name' => str_random(10), 
	        'type' => str_random(2), 
	        'stel_type' =>1, 
	        'version' => str_random(2), 
	        'price' => 12000, 
	        'total' => 12000, 
	        'year' => 2020, 
	        'is_active' => 1, 
	    ]);    
	    
        $this->assertEquals(302, $response->status());
	}

    public function test_visit_edit_stel()
	{ 
 
	   $user = User::find(1); 

       $stel = STEL::latest()->first();
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel/'.$stel->id);  
	   
		// dd($response->getContent());
       $this->assertEquals(200, $response->status());
	}
    public function test_update_stel()
	{ 
 
	    $user = User::find(1);
       	$stel = STEL::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/stel/'.$stel->id, 
		[ 
	        'code' => str_random(10), 
	        'name' => str_random(10), 
	        'type' => str_random(2), 
	        'price' => 12000, 
	        'total' => 12000, 
	        'stel_type' =>1, 
	        'version' => str_random(2), 
	        'year' => 2020, 
	        'is_active' => 1, 
	    ]);    
	    
        $this->assertEquals(302, $response->status());  
	}


    public function test_autocomplete_stel()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->call('GET',"admin/adm_stel_autocomplete/query"); 
        $this->assertResponseStatus(200); 
    }


    public function test_delete_stel()
	{ 
		$user = User::find(1); 

       	$stel = STEL::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/stel/'.$stel->id);  
        $this->assertEquals(302, $response->status()); 
	} 
}
