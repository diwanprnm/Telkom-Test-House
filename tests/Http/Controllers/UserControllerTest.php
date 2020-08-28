<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\Menu; 

class UserControllerTest extends TestCase
{
     public function test_visit_user()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/user');   
	   // dd($response->getContent());
       $this->assertEquals(200, $response->status());
	} 

	public function test__visit_user_with_search()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/user?search=cari&company=com&role=admin&is_active=1');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_user()
	{ 
 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/user/create');  
       $this->assertEquals(200, $response->status());
	}
    public function test_stores_user()
	{ 
 
	    $user = User::find(1);
       	$menu = Menu::get()->toArray();
		$response =  $this->actingAs($user)->call('POST', 'admin/user', 
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
	        'menus' => $menu, 
	    ]);    

	    // dd($response->getContent());
        $this->assertEquals(302, $response->status());
	}

    public function test_visit_edit_user()
	{ 
 
	   $user = User::find(1); 
	   $response =  $this->actingAs($user)->call('GET', 'admin/user/'.$user->id.'/edit');  
	    
       $this->assertEquals(200, $response->status());
	}
    public function test_update_user()
	{ 
 
	    $user = User::find(1); 
       	$menu = Menu::get()->toArray(); 
		$response =  $this->actingAs($user)->call('PUT', 'admin/user/'.$user->id, 
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
	        'menus' => $menu, 
	    ]);    
	    
        $this->assertEquals(302, $response->status());  
	}
 //    public function test_update_profile_user()
	// {  
	//     $user = User::find(1);  
	// 	$response =  $this->actingAs($user)->call('PUT', '/user/profile/'.$user->id, 
	// 	[ 
	//         'name' => str_random(10)  
	//     ]);    
	    
 //        $this->assertEquals(302, $response->status());  
	// }


    public function test_autocomplete_user()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->call('GET',"admin/adm_user_autocomplete/query"); 
        $this->assertResponseStatus(200); 
    }


    public function test_softdelete_user()
	{ 
		$user = User::latest()->first(); 
		$response =  $this->actingAs($user)->call('POST', 'admin/user/'.$user->id.'/softDelete');  
        $this->assertEquals(302, $response->status()); 
	} 
    public function test_delete_user()
	{ 
		$user = User::latest()->first(); 
		$response =  $this->actingAs($user)->call('DELETE', 'admin/user/'.$user->id); 
		// dd($response->getContent()); 
        $this->assertEquals(302, $response->status()); 
	} 
}
