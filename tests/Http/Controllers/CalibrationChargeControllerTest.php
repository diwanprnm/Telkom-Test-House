<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;  
use App\CalibrationCharge; 
use App\User;
class CalibrationChargeControllerTest extends TestCase
{
	
	
    /**
     * A basic test example.
     *
     * @return void
     */ 

    /**
	 * @test
	 */
	public function testIndexAdmin()
	{
		$user = User::find('1');
		$this->actingAs($user)->call('GET','admin/calibration');
		$this->assertResponseStatus(200);
	}

	public function testSearch()
	{ 
		$user = User::find('1');
		$response = $this->actingAs($user)->call('GET', 'admin/calibration?search=cari&is_active=-1');  
		$this->assertEquals(200, $response->status());
	}

	public function testCreate()
	{
		$user = User::find('1');
		$this->actingAs($user)->call('GET','admin/calibration/create');
		
		$this->assertResponseStatus(200);
		
	}

	public function testStore()
	{ 
		$user = User::find('1');
		$response =  $this->actingAs($user)->call('POST', 'admin/calibration', 
		[ 
			'device_name' => str_random(10),
			'price' => mt_rand(0,10000),
			'is_active' => mt_rand(0,1)
		]);   
		$this->assertEquals(302, $response->status());
	}

	public function testEdit()
	{	
		$user = User::find('1'); 
		$calibration = CalibrationCharge::latest()->first();
		$response = $this->actingAs($user)->call('GET', 'admin/calibration/'.$calibration->id.'/edit');
		$this->assertEquals(200, $response->status());
	}

	public function testUpdate()
	{ 
		$user = User::find('1'); 
		$calibration = CalibrationCharge::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/calibration/'.$calibration->id, 
		[ 
			'device_name' => str_random(10),
			'price' => mt_rand(0,10000),
			'is_active' => mt_rand(0,1)
		]);   
		$this->assertEquals(302, $response->status());
	}

	public function testDestroy()
	{ 
		$user = User::find('1'); 
		$calibration = CalibrationCharge::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/calibration/'.$calibration->id);  
		$this->assertEquals(302, $response->status());
	}
}
