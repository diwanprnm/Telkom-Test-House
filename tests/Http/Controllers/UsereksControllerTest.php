<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User; 
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
		$admin = User::find('1');
	   	$response = $this->actingAs($admin)->call('GET', 'admin/usereks');   
	   	// dd($response->getContent());
       	$this->assertEquals(200, $response->status());
	}

	public function test_visit_usereks_with_search()
	{ 
		$admin = User::find('1');
	   	$response =  $this->actingAs($admin)->call('GET', 'admin/usereks?search=cari&company=com&is_active=1');  
       	$this->assertEquals(200, $response->status());
	}

	public function testCreate()
	{
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET','admin/usereks/create');
		$this->assertEquals(200, $response->status());
	}

	public function test_stores_user()
	{ 
		$admin = User::find('1');
		   
		$response = $this->actingAs($admin)->call('POST', 'admin/usereks', 
		[ 
	        'name' => str_random(10), 
	        'role_id' => 2, 
	        'company_id' => 1, 
	        'email' => str_random(10), 
	        'password' => str_random(10), 
	        'is_active' => 1, 
	        'address' => str_random(10), 
	        'phone_number' => str_random(10), 
	        'fax' => str_random(10), 
	        'hide_admin_role' =>1
	    ]);    

		$this->assertEquals(302, $response->status());
		$user = factory(App\User::class)->make();  
	}

	public function testShow()
	{
		$admin = User::find('1');
		$user = User::latest()->first(); 
		$response = $this->actingAs($admin)->call('GET', 'admin/usereks/'.$user->id);

		$this->assertEquals(200, $response->status());
	}

	public function test_visit_edit_usereks()
	{ 
		$admin = User::find('1');
		$user = User::latest()->first(); 
	   	$response =  $this->actingAs($admin)->call('GET', 'admin/usereks/'.$user->id.'/edit');  
	    
       	$this->assertEquals(200, $response->status());
	}

    public function test_update_usereks()
	{ 
		$admin = User::find('1');
		$user = User::latest()->first(); 
		$response =  $this->actingAs($admin)->call('PUT', 'admin/usereks/'.$user->id, 
		[ 
	        'name' => str_random(10), 
	        'role_id' => 2, 
	        'company_id' => 0, 
	        'email' => str_random(10), 
	        'password' => str_random(10), 
	        'is_active' => 1, 
	        'address' => str_random(10), 
	        'phone_number' => str_random(10), 
	        'fax' => str_random(10),
	        'hide_admin_role' =>1,
	    ]);    
	    
        $this->assertEquals(302, $response->status());  
	}

	public function test_softdelete_usereks()
	{ 
		$admin = User::find('1');
		$user = User::latest()->first(); 
		$response = $this->actingAs($admin)->call('POST', 'admin/usereks/'.$user->id.'/softDelete');  
        $this->assertEquals(302, $response->status()); 
	} 

    public function test_delete_usereks()
	{ 
		$admin = User::find('1');
		$user = User::latest()->first(); 
		$response = $this->actingAs($admin)->call('DELETE', 'admin/usereks/'.$user->id); 
		// dd($response->getContent()); 
        $this->assertEquals(302, $response->status()); 
	} 
}
