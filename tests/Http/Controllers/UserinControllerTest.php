<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\Menu; 
use App\Examination;
class UserinControllerTest extends TestCase
{
    public function test_visit_userin()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/userin');   
	   // dd($response->getContent());
       $this->assertEquals(200, $response->status());
	} 

	public function test__visit_userin_with_search()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/userin?search=cari&company=com&role=admin&is_active=1');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_userin()
	{ 
 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/userin/create');  
       $this->assertEquals(200, $response->status());
	}
    public function test_stores_user()
	{ 
 
	    $user = User::find(1);
       	$menu = Menu::get()->toArray();
       	$examinations = Examination::get()->toArray();
		$response =  $this->actingAs($user)->call('POST', 'admin/userin', 
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
	        'examinations' =>$examinations, 
	        'menus' => $menu, 
	    ]);    

	    // dd($response->getContent());
        $this->assertEquals(200, $response->status());
	}

    public function test_visit_edit_userin()
	{ 
 
	   $user = User::find(1); 
	   $response =  $this->actingAs($user)->call('GET', 'admin/userin/'.$user->id.'/edit');  
	    
       $this->assertEquals(200, $response->status());
	}
    public function test_update_userin()
	{ 
 
	    $user = User::find(1); 
       	$menu = Menu::get()->toArray();  
       	$examinations = Examination::get()->toArray();
		$response =  $this->actingAs($user)->call('PUT', 'admin/userin/'.$user->id, 
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
	        'examinations' =>$examinations, 
	        'menus' => $menu, 
	    ]);    
	    
        $this->assertEquals(200, $response->status());  
	}
 //    public function test_update_profile_userin()
	// {  
	//     $user = User::find(1);  
	// 	$response =  $this->actingAs($user)->call('PUT', '/userin/profile/'.$user->id, 
	// 	[ 
	//         'name' => str_random(10)  
	//     ]);    
	    
 //        $this->assertEquals(302, $response->status());  
	// } 

    public function test_softdelete_userin()
	{ 
		$user = User::latest()->first(); 
		$response =  $this->actingAs($user)->call('POST', 'admin/userin/'.$user->id.'/softDelete');  
        $this->assertEquals(200, $response->status()); 
	} 
    // public function test_delete_userin()
	// { 
	// 	$user = User::latest()->first(); 
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/userin/'.$user->id); 
	// 	// dd($response->getContent()); 
    //     $this->assertEquals(200, $response->status()); 
	// } 
}
