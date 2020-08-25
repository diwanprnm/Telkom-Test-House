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
	public function testIndex()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs');  
		$this->assertEquals(200, $response->status());
	}

	public function testSearch()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs?search=asda');  
		$this->assertEquals(200, $response->status());
	}

	public function testCreate()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/labs/create');  
		$this->assertEquals(200, $response->status());
	}

	public function testStore()
	{ 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('POST', 'admin/labs', 
		[ 
			'name' => str_random(10),
			'lab_code' => str_random(10),
			'lab_init' => str_random(10), 
			'description' => str_random(10),
			'is_active' => mt_rand(0,1)
		]);
		$this->assertEquals(302, $response->status());
	}

	public function testEdit()
	{ 
		$admin = User::find('1');
		$lab = ExaminationLab::latest()->first();
		$response = $this->actingAs($admin)->call('GET', 'admin/labs/'.$lab->id.'/edit');  
		$this->assertEquals(200, $response->status());
	}

	public function testUpdate()
	{ 
		$admin = User::find('1');
		$lab = ExaminationLab::latest()->first();
		$response =  $this->actingAs($admin)->call('PUT', 'admin/labs/'.$lab->id, 
		[ 
			'name' => str_random(10),
			'lab_code' => str_random(10),
			'lab_init' => str_random(10) , 
			'description' => str_random(10) ,
			'is_active' => mt_rand(0,1)
		]);   
		$this->assertEquals(302, $response->status());
	}

	public function testDestroy()
	{ 
		$admin = User::find('1');
		$lab = ExaminationLab::latest()->first();
		$response =  $this->actingAs($admin)->call('DELETE', 'admin/labs/'.$lab->id);   
		$this->assertEquals(302, $response->status());
	}
}
