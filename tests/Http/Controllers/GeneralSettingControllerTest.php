<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\GeneralSetting;

class GeneralSettingControllerTest extends TestCase
{
	use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */

	public function testDeleteSoon(){
        $this->assertTrue(true);
    }
	 
	public function testVisit()
	{
		$admin = User::find('1');
		$this->actingAs($admin)->call('GET', 'admin/generalSetting');
		$this->assertResponseStatus(200);
	}

	public function testVisitNotFOund()
	{
		GeneralSetting::truncate();
		$admin = User::find('1');
		$this->actingAs($admin)->call('GET', 'admin/generalSetting');
		$this->assertResponseStatus(200)->see('Data not found');
	}
	
	public function testUpdate()
	{ 
		$this->actingAs(User::find('1'))->call('PATCH', 'admin/generalSetting/1', [ 
			'is_poh' => null,
			'status' => 'is_poh',
			'manager_urel' => 'Bapak Sontang Hutapea',
			'keterangan' => 'Diganti karena naik jabatan'
		]);
        $this->assertRedirectedTo('admin/generalSetting', ['message' => 'General Setting successfully updated']);
	}

	public function testUpdateIsPoh()
	{ 
		$this->actingAs(User::find('1'))->call('PATCH', 'admin/generalSetting/2', [ 
			'is_poh' => true,
			'status' => 'is_poh',
			'poh_manager_urel' => 'Ibu Henrina',
			'keterangan' => 'Diganti karena naik jabatan'
		]);
        $this->assertRedirectedTo('admin/generalSetting', ['message' => 'General Setting successfully updated']);
	}
	
}
