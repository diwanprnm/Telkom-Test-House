<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Certification;

class PopUpInformationControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit()
	{  
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/popupinformation');  
		$this->assertEquals(200, $response->status());
	}
	
	public function test_search()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/popupinformation?search=a'); 
		/*   $this->seeInDatabase('logs', [
			'page' => 'CERTIFICATION',
			'data' => '{"search":"a"}']); 
			*/
		$this->assertEquals(200, $response->status());
	}

	public function testCreate()
	{
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET','admin/popupinformation/create');
		$this->assertEquals(200, $response->status());
	}

	public function test_stores()
	{ 
		$admin = User::find('1');
		$response =  $this->actingAs($admin)->call('POST', 'admin/popupinformation', 
		[ 
			'title' => str_random(10),
			'is_active' => mt_rand(0,1)
		]);
		
		$this->assertEquals(200, $response->status());
	
	}

	public function testEdit()
	{	
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/popupinformation/edit');
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
