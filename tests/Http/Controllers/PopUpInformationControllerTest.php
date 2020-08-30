<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

use App\User;
use App\Certification;

class PopUpInformationControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
	{
		Certification::truncate();
		$this->actingAs(User::find('1'))->call('GET', 'admin/popupinformation');  
		$this->assertResponseStatus(200)
			->see('Data not found');
	}
	
	public function testIndexWithSearch()
	{
		$this->actingAs(User::find('1'))->call('GET', 'admin/popupinformation?search=cari'); 
		$this->assertResponseStatus(200);
	}

	public function testCreate()
	{
		$this->actingAs(User::find('1'))->call('GET','admin/popupinformation/create');
		$this->assertResponseStatus(200)->see('Tambah Pop Up Information Baru');
	}

	public function testStore()
	{ 
		$this->actingAs(User::find('1'))
			->visit('admin/popupinformation/create')
			->type('pop up information', 'title')
			->type(1, 'is_active')
			->attach(base_path().'/public/images/testing.jpg', 'image')
            ->press('submit')
		;
		$this->assertResponseStatus(200)->see('Pop Up Information successfully created');
	}

	public function testShow()
	{	
		$this->actingAs(User::find('1'))->call('GET', 'admin/popupinformation/id');
		$this->assertResponseStatus(200);
	}

	public function testEdit()
	{
		$popUpInformation = Certification::whereNotNull('created_at')->where('type',0)->first();
		$this->actingAs(User::find('1'))->call('GET', "admin/popupinformation/$popUpInformation->id/edit");
		$this->assertResponseStatus(200)->see('Edit Pop Up Information');
	}

	public function testUpdate()
	{
		$popUpInformation = Certification::whereNotNull('created_at')->where('type',0)->first();
		$this->actingAs(User::find('1'))
			->visit("admin/popupinformation/$popUpInformation->id/edit")
			->type('pop up information updated', 'title')
			->type(1, 'is_active')
			->attach(base_path().'/public/images/testing.jpg', 'image')
            ->press('submit')
		;
		$this->assertResponseStatus(200)->see('Pop Up Information successfully updated');
	}


	public function testDelete()
	{
		$popUpInformation = Certification::whereNotNull('created_at')->where('type',0)->first();
		$this->actingAs(User::find('1'))->call('DELETE', "admin/popupinformation/$popUpInformation->id");
		$this->assertRedirectedTo('admin/popupinformation');
	}

	public function testDeleteDataNotFound()
	{
		$this->actingAs(User::find('1'))->call('DELETE', "admin/popupinformation/dataNotFound");
		$this->assertRedirectedTo('admin/popupinformation');
	}

	public function testAutocomplete()
	{
		$this->actingAs(User::find('1'))->call('DELETE', "admin/popupinformation/dataNotFound");
		$this->assertRedirectedTo('admin/popupinformation');
	}
     
}
