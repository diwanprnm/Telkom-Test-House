<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ExaminationLab;

use App\User;

class ExaminationLabControllerTest extends TestCase
{
	public function testSearch()
	{ 
		$this->actingAs(User::find('1'))->call('GET', 'admin/labs');  
		$this->assertResponseStatus(200);
	}

	public function testSearchWithSearch()
	{ 
		$this->actingAs(User::find('1'))->call('GET', 'admin/labs?search=asda');  
		$this->assertResponseStatus(200);
	}

	public function testCreate()
	{
		$this->actingAs(User::find('1'))->call('GET', 'admin/labs/create');  
		$this->assertResponseStatus(200);
	}

	public function testStore()
	{ 
		$this->actingAs(User::find('1'))->call('POST', 'admin/labs', [ 
			'name' => 'Lab Device',
			'lab_code' => 123,
			'lab_init' => 1234, 
			'description' => 'Ini teks deskripsi',
			'is_active' => 1,
			'close_until' => Carbon\Carbon::now(),
			'open_at' => Carbon\Carbon::now()
		]);
		$this->assertRedirectedTo('admin/labs', ['message' => 'Labs successfully created']);
	}

	public function testEdit()
	{
		$lab = ExaminationLab::latest()->first();
		$this->actingAs(User::find('1'))->call('GET', "admin/labs/$lab->id/edit");  
		$this->assertResponseStatus(200);
	}

	public function testUpdate()
	{
		$lab = ExaminationLab::latest()->first();
		$this->actingAs(User::find('1'))->call('PUT', 'admin/labs/'.$lab->id, [ 
			'name' => 'Lab Device',
			'lab_code' => 123,
			'lab_init' => 1234, 
			'description' => 'Ini teks deskripsi',
			'is_active' => 1,
			'close_until' => Carbon\Carbon::now(),
			'open_at' => Carbon\Carbon::now()
		]);
		$this->assertRedirectedTo('admin/labs', ['message' => 'Labs successfully updated']);
	}

	public function testDestroy()
	{
		$lab = ExaminationLab::latest()->first();
		$this->actingAs(User::find('1'))->call('DELETE', "admin/labs/$lab->id");   
		$this->assertRedirectedTo('admin/labs', ['message' => 'Labs successfully deleted']);
	}

	public function testDestroyNotFound()
	{
		$this->actingAs(User::find('1'))->call('DELETE', "admin/labs/dataNotFound");   
		$this->assertRedirectedTo('admin/labs', ['error' => 'Lab not found']);
	}

	public function testAutocomplete()
    {
		$this->actingAs(User::find('1'))->call('GET',"admin/adm_labs_autocomplete/cari");
		$this->assertResponseStatus(200);
    }
}
