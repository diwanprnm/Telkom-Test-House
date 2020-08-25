<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\PopUpInformation;

class PopUpInformationControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    public function test_visit()
	 {  $admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/popupinformation');  
        $this->assertEquals(200, $response->status());
     }
     
     public function test_search()
	 { 
        $company = App\Company::latest()->first();
        $admin = User::find('1');
        $response = $this->actingAs($admin)->call('GET', 'admin/popupinformation?search=a'); 
		$this->assertEquals(200, $response->status());
		
     }

     public function testCreate()
	 {
		 $user = User::where('role_id', '=', '1')->first();
		 $this->actingAs($user)->call('GET','admin/popupinformation/create');
		 
		 $this->assertResponseStatus(200);
		 
	 }

	 public function test_stores()
	 { 
		$user = User::where('role_id', '=', '1')->first();
	 	$response =  $this->actingAs($user)->call('POST', 'admin/popupinformation', 
	 	[ 
	 		 'title' => str_random(10),
	         'is_active' => mt_rand(0,1)
	        
	     ]);   
		 
         $this->assertEquals(200, $response->status());
	    
	 }

	 public function testEdit()
	 {	
		 $user = User::where('role_id', '=', '1')->first(); 
        
		$response = $this->actingAs($user)->call('GET', 'admin/popupinformation/edit');
		
		 $this->assertEquals(200, $response->status());
		 
	 }
	
/*	 public function test_update_data()
	 { 
		$user = User::where('role_id', '=', '1')->first(); 
	 	$response =  $this->actingAs($user)->call('GET', 'admin/popupinformation/edit', 
	 	[ 
            'title' => str_random(10),
            'is_active' => mt_rand(0,1)
	 		]);   
	 		$this->assertEquals(200, $response->status());
			 
	 	}
	

	
	 public function test_delete()
	 { 
		$user = User::where('role_id', '=', '1')->first(); 
		 $response =  $this->actingAs($user)->call('DELETE', 'admin/popupinformation/');  
		 dd($response); 
         $this->assertEquals(302, $response->status());
	    
	}*/
     
}
