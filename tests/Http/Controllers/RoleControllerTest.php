<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;  
use App\Role;  
class RoleControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit_role()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/role');   
       $this->assertEquals(200, $response->status());
	} 
    public function test_visit_edit_role()
	{ 
 
	   $user = User::find(1);
	   $role = Role::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/role/'.$role->id);  
	   
		// dd($response->getContent());
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_role()
	{ 
 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/role/create');  
       $this->assertEquals(200, $response->status());
	}
    public function test_stores_role()
	{ 
 
	   $user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', 'admin/role', 
		[ 
	        'name' => str_random(10) 
	    ]);    
        $this->assertEquals(302, $response->status());
	}
    public function test_update_role()
	{ 
 
	    $user = User::find(1);
       	$role = Role::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/role/'.$role->id, 
		[ 
	        'name' => str_random(10) 
	    ]);    
        $this->assertEquals(302, $response->status());  
	}


    public function test_delete_role()
	{ 
		$user = User::find(1);
       	$role = Role::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/role/'.$role->id);  
        $this->assertEquals(302, $response->status()); 
	} 
}
