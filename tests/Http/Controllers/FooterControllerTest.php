<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Footer;

class FooterControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/footer?search=cari'); 
        $this->assertResponseStatus(200)->see('Footer');
    }
     
    public function testCreate()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/footer/create');
        $this->assertResponseStatus(200)->see('Tambah Informasi Baru di Footer');
    }
     
    public function testStores()
    { 
        $this->actingAs(User::find('1'))
            ->visit('admin/footer/create')
            ->type('Deskripsi footer', 'description')
            ->type('1', 'is_active')
            ->attach(base_path().'/public/images/logo_dds.png', 'image')
            ->press('submit')
        ;
        $this->assertResponseStatus(200)->see('Footer successfully created');
    }

    public function testEdit()
    {
        $footer = Footer::latest()->first();
        $this->actingAs(User::find('1'))->call('GET', "admin/footer/$footer->id/edit");
        $this->assertResponseStatus(200)->see('Edit Informasi');
    }
        
        
    public function testUpdate()
    {
        $footer = Footer::latest()->first();
        $this->actingAs(User::find('1'))
            ->visit("admin/footer/$footer->id/edit")
            ->type('Deskripsi tentang footer', 'description')
            ->select('1', 'is_active')
            ->attach(base_path().'/public/images/testing.jpg', 'image')
            ->press('submit')
        ;
		$this->assertResponseStatus(200)->see('Footer successfully updated');
    }

    public function testDelete()
    {
        $footer = Footer::latest()->first();
        $this->actingAs(User::find('1'))->call('DELETE', "admin/footer/$footer->id");  
        $this->assertRedirectedTo('admin/footer',['message' => 'Footer successfully deleted']);
    }

    public function testDeleteNotFound()
    {
        $this->actingAs(User::find('1'))->call('DELETE', "admin/footer/dataNotFound");  
        $this->assertRedirectedTo('admin/footer',['error' => 'Footer not found']);
    }

    public function testShow()
    {
        $this->actingAs(User::find('1'))->call('GET', "admin/footer/id");  
        $this->assertResponseStatus(200);
    }

    public function testAutocomplete()
    {
        $this->actingAs(User::find('1'))->call('GET', "admin/adm_footer_autocomplete/query");  
        $this->assertResponseStatus(200);
    }
}
