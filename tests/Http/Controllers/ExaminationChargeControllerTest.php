<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\ExaminationCharge; 

class ExaminationChargeControllerTest extends TestCase
{

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    // public function testIndexAsNonAdmin()
    // {
    //     $user = factory(User::class)->make();
    //     $this->actingAs($user)->call('GET','admin/charge');
    //     //status sukses, tapi ada bacaaan you dont have permission
    //     $this->assertResponseStatus(200)
    //     ->see("Unautorizhed. You do not have permission to access this page.");
    // }


    // public function testIndexAsAdmin()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>');
    // }


    // public function testIndexWithSearchFilter()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge?search=cari');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>');
    // }


    // public function testIndexWithCategoryAndIsActiveFilter()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge?category=Lab+CPE&is_active=1');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>');
    // }
    

    // public function testIndexWithoutDataFound()
    // {
    //     ExaminationCharge::truncate();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>')
    //         ->see('Data not found');            
    // }

    // public function testCreate()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge/create');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TAMBAH TARIF</h1>');
    // }

    // public function testStore()
    // {
    //     $user = User::where('id', '=', '1')->first();

    //     $response = $this->actingAs($user)
    //     ->visit('admin/charge/create')
    //     ->type('Device name for testing', 'device_name')
    //     ->type('Referensi uji for testing', 'stel')
    //     ->select('Lab CPE', 'category')
    //     ->type('70', 'duration')
    //     ->type('7000000', 'price')
    //     ->type('700000', 'vt_price')
    //     ->type('70000', 'ta_price')
    //     ->select('0', 'is_active')
    //     ->press('submit');

    //     //Status sukses dan judul Certification
    //     $response->assertResponseStatus('200')
    //         ->seePageIs('admin/charge')
    //         ->see('Charge successfully created');

    //     //see in database
    // }

    // public function testStoreWithSameName()
    // {
    //     $user = User::where('id', '=', '1')->first();

    //     $response = $this->actingAs($user)
    //     ->visit('admin/charge/create')
    //     ->type('Device name for testing', 'device_name')
    //     ->type('Referensi uji for testing same name', 'stel')
    //     ->select('Lab CPE', 'category')
    //     ->type('701', 'duration')
    //     ->type('70000001', 'price')
    //     ->type('7000001', 'vt_price')
    //     ->type('700001', 'ta_price')
    //     ->select('0', 'is_active')
    //     ->press('submit');

    //     //Status sukses dan judul Certification
    //     $response->assertResponseStatus('200')
    //         ->see('Nama Perangkat sudah ada!');

    //     //check di database
    //     $this->seeInDatabase('examination_charges', ['device_name' => 'Device name for testing']);
    // }


    // public function testEdit()
    // {
    //     $examinationCharge = ExaminationCharge::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge/'.$examinationCharge->id.'/edit');
    //     //Status respond ok, dan terdapat judul h1 "EDIT TARIF"
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">EDIT TARIF</h1>')
    //         ->see($examinationCharge->device_name);
    // }

    // public function testUpdate()
    // {
    //     $examinationCharge = ExaminationCharge::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)
    //         ->visit('admin/charge/'.$examinationCharge->id.'/edit')
    //         ->type('Device name for testing updated', 'device_name')
    //         ->type('Referensi uji for testing updated', 'stel')
    //         ->select('Lab Device', 'category')
    //         ->type('771', 'duration')
    //         ->type('77000001', 'price')
    //         ->type('7700001', 'vt_price')
    //         ->type('770001', 'ta_price')
    //         ->select('1', 'is_active')
    //         ->press('submit');

    //     //Status ok, halaman sedang di charge.index, dan pesan "Charge successfully updated"
    //     $this->assertResponseStatus('200')
    //         ->seePageIs('admin/charge')
    //         ->see('Charge successfully updated');

    //     //check di database
    //     $this->seeInDatabase('examination_charges', ['device_name' => 'Device name for testing updated']);
    // }

    // public function testDestroy()
    // {
    //     $examinationCharge = ExaminationCharge::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('DELETE','admin/charge/'.$examinationCharge->id);

    //     //Status redirect, ke halaman charge.index, dan pesan "Charge successfully deleted"
    //     $this->assertRedirectedTo('/admin/charge', ['message' => 'Charge successfully deleted']);
    // }

    // public function testExcel()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/charge/excel');
    //     //Status Ok,
    //     $this->assertResponseStatus('200');

    //     //truncate data di examination
    //     ExaminationCharge::truncate();
    // }

}
