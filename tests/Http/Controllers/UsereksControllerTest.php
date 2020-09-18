<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User; 
use App\Menu; 
use App\Company; 
class UsereksControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit_usereks()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/usereks');   
	   // dd($response->getContent());
       $this->assertEquals(200, $response->status());
	} 

	public function test_visit_usereks_with_search()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/usereks?search=cari&company=com&is_active=1');  
       $this->assertEquals(200, $response->status());
	}

	public function test_stores_user()
	{ 
	    $user = User::find(1);
       	$menu = Menu::get()->toArray(); 
		$response =  $this->actingAs($user)->call('POST', 'admin/usereks', 
		[ 
	        'name' => str_random(10), 
	        'role_id' => 1, 
	        'company_id' => 1, 
	        'email' => str_random(10), 
	        'password' => str_random(10), 
	        'is_active' => 1, 
	        'address' => str_random(10), 
	        'phone_number' => str_random(10), 
	        'fax' => str_random(10), 
	        'hide_admin_role' =>1,  
	        'menus' => $menu, 
	    ]);    

	    // dd($response->getContent());
        $this->assertEquals(200, $response->status());
	}
	public function test_visit_edit_usereks()
	{ 
 
	   $user = User::find(1); 
	   $response =  $this->actingAs($user)->call('GET', 'admin/usereks/'.$user->id.'/edit');  
	    
       $this->assertEquals(200, $response->status());
	}
    public function test_update_usereks()
	{ 
 
	    $user = User::find(1); 
       	$menu = Menu::get()->toArray();   
		$response =  $this->actingAs($user)->call('PUT', 'admin/usereks/'.$user->id, 
		[ 
	        'name' => str_random(10), 
	        'role_id' => 1, 
	        'company_id' => 0, 
	        'email' => str_random(10), 
	        'password' => str_random(10), 
	        'is_active' => 1, 
	        'address' => str_random(10), 
	        'phone_number' => str_random(10), 
	        'fax' => str_random(10),
	        'hide_admin_role' =>1,  
	        'menus' => $menu, 
	    ]);    
	    
        $this->assertEquals(200, $response->status());  
	}
	 public function test_softdelete_usereks()
	{ 
		$user = User::latest()->first(); 
		$response =  $this->actingAs($user)->call('POST', 'admin/usereks/'.$user->id.'/softDelete');  
        $this->assertEquals(200, $response->status()); 
	} 
    public function test_delete_usereks()
	{ 
		$user = User::latest()->first(); 
		$response =  $this->actingAs($user)->call('DELETE', 'admin/usereks/'.$user->id); 
		// dd($response->getContent()); 
        $this->assertEquals(200, $response->status()); 
	} 
}
