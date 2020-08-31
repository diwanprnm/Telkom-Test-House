<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Equipment;
use App\EquipmentHistory;
use App\User;
class EquipmentControllerTest extends TestCase
{
	public function testIndexWithSearch()
	{
		
		$this->actingAs(User::find('1'))->call('GET', 'admin/equipment?search=cari');  
        $this->assertResponseStatus(200)->see('Penerimaan Barang');
	}

	public function testCreate()
	{
		$this->actingAs(User::find('1'))->call('GET', 'admin/equipment/create');
		$this->assertResponseStatus(200)->see('Tambah Barang Baru');
	}

	public function testStores()
	{
		$this->actingAs(User::find('1'))->call('POST', 'admin/equipment', [ 
			'examination_id' => factory(App\Examination::class)->create()->id,
			'name' => 'Nama equipment',
			'qty' => array(1),
			'unit' => array('unit uji device'),
			'description' => array('deskripsi device'),
			'pic' => 'Daniel',
			'remarks' => array('The remarks'),
			'equip_date' => Carbon\Carbon::now(),
		]);
		$this->assertRedirectedTo('/admin/equipment', ['message' => 'Equipment successfully created']);
	}

	public function testShow()
	{
		$equipmentHistory = factory(App\EquipmentHistory::class)->create();
		$this->actingAs(User::find('1'))->call('GET', "admin/equipment/$equipmentHistory->examination_id");
		$this->assertResponseStatus(200)->see('Detail Barang');
	}

	public function testEdit()
	{
		$equipmentHistory = EquipmentHistory::latest()->first();
		$this->actingAs(User::find('1'))->call('GET', "admin/equipment/$equipmentHistory->examination_id");
		$this->assertResponseStatus(200)->see('Detail Barang');
	}

	public function testUpdate()
	{
		$equipmentHistory = EquipmentHistory::latest()->first();
		$this->actingAs(User::find('1'))->call('PATCH', "admin/equipment/$equipmentHistory->examination_id", [
			'location' => 1,
			'pic' => 'Daniel',
			'location_id' => 2,
			'equip_date' => Carbon\Carbon::now(),

			// 'examination_id' => factory(App\Examination::class)->create()->id,
			// 'name' => 'Nama equipment',
			// 'qty' => array(1),
			// 'unit' => array('unit uji device'),
			// 'description' => array('deskripsi device'),
			// 'remarks' => array('The remarks'),
			
		]);
		$this->assertRedirectedTo('/admin/equipment', ['message' => 'Equipment successfully updated']);
	}

	public function testDelete()
	{
		$equipmentHistory = EquipmentHistory::latest()->first();
		$this->actingAs(User::find('1'))->call('DELETE','admin/equipment/'.$equipmentHistory->examination_id);
		$this->assertRedirectedTo('/admin/equipment', ['message' => 'Equipment successfully deleted']);
	}

}
