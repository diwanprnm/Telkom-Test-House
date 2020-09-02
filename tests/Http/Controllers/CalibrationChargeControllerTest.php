<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;  
use App\CalibrationCharge; 
use App\User;
class CalibrationChargeControllerTest extends TestCase
{

	public function testIndex()
	{
		$this->actingAs(User::find('1'))->call('GET', 'admin/calibration?search=cari&is_active=1');  
		$this->assertResponseStatus(200)->see('Tarif Kalibrasi');
	}

	public function testCreate()
	{
		$this->actingAs(User::find('1'))->call('GET','admin/calibration/create');
		$this->assertResponseStatus(200)->see('Tambah Tarif Kalibrasi Baru');
	}

	public function testStore()
	{
		$this->actingAs(User::find('1'))->call('POST', 'admin/calibration', [
			'device_name' => str_random(10),
			'price' => mt_rand(0,10000),
			'is_active' => mt_rand(0,1)
		]);
		$this->assertRedirectedTo('admin/calibration', ['message' => 'Charge successfully created']);
	}

	public function testEdit()
	{
		$calibration = factory(App\CalibrationCharge::class)->create();
		$this->actingAs(User::find('1'))->call('GET', "admin/calibration/$calibration->id/edit");
		$this->assertResponseStatus(200)->see('Edit Tarif Kalibrasi');
	}

	public function testUpdate()
	{
		$calibration = CalibrationCharge::latest()->first();
		$this->actingAs(User::find('1'))->call('PATCH', "admin/calibration/$calibration->id", [ 
			'device_name' => str_random(10),
			'price' => mt_rand(0,10000),
			'is_active' => mt_rand(0,1)
		]);
		$this->assertRedirectedTo('admin/calibration', ['message' => 'Charge successfully updated']);
	}

	public function testAutocomplete()
	{
		$this->actingAs(User::find('1'))->call('GET',"admin/adm_calibration_autocomplete/'cari'");
		$this->assertResponseStatus(200);
	}

	public function testExcel()
	{
		$response = $this->actingAs(User::find(1))->call('GET','calibration/excel?search=cari&is_active=1');
		$this->assertResponseStatus(200);
		$this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
		$this->assertTrue($response->headers->get('content-description') == 'File Transfer');
		$this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Tarif Kalibrasi.xlsx"');
	}

	public function testExcelData()
	{
		$response = $this->actingAs(User::find(1))->call('GET','calibration/excel');
		$this->assertResponseStatus(200);
		$this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
		$this->assertTrue($response->headers->get('content-description') == 'File Transfer');
		$this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Tarif Kalibrasi.xlsx"');
	}
	
	public function testDestroy()
	{
		$calibration = CalibrationCharge::latest()->first();
		$this->actingAs(User::find('1'))->call('DELETE', "admin/calibration/$calibration->id");  
		$this->assertRedirectedTo('admin/calibration', ['message' => 'Charge successfully deleted']);
	}

	public function testDestroyNotFound()
	{ 
		$this->actingAs(User::find('1'))->call('DELETE', 'admin/calibration/dataNotFound');  
		$this->assertRedirectedTo('admin/calibration', ['error' => 'Charge not found']);
	}
}
