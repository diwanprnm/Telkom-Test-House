<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ExaminationLab;

use App\User;

class ExaminationLabControllerTest extends TestCase
{
   
    /**
     * A basic test example.
     *
     * @return void
     */
	public function test_visit()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs');  
		$this->assertEquals(200, $response->status());
	}
	
	public function test_search()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs?search=asda');  
		$this->assertEquals(200, $response->status());
	}

	public function test_create()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs/create');  
		$this->assertEquals(200, $response->status());
	}

	public function test_stores()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('POST', 'admin/labs', 
		[ 
			'name' => str_random(10),
			'lab_code' => str_random(10),
			'lab_init' => str_random(10) , 
			'description' => str_random(10) ,
			'is_active' => mt_rand(0,1)
		
		]);   
		$this->assertEquals(302, $response->status());
	}

	public function test_edit()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs/edit');  
		$this->assertEquals(200, $response->status());
	}

	public function test_update()
	{ 
		$user = factory(App\User::class)->create(); 
		$lab = ExaminationLab::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/labs/'.$lab->id, 
		[ 
			'name' => str_random(10),
			'lab_code' => str_random(10),
			'lab_init' => str_random(10) , 
			'description' => str_random(10) ,
			'is_active' => mt_rand(0,1)
		]);   
		$this->assertEquals(200, $response->status());
	}

	public function test_delete()
	{ 
		$user = factory(App\User::class)->create(); 
		$lab = ExaminationLab::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/labs/'.$lab->id);   
		$this->assertEquals(200, $response->status());
	}
}
