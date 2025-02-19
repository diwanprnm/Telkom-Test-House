<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\ExaminationCharge; 

class ExaminationChargeControllerTest extends TestCase
{

    public function testIndexAsAdmin()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/charge');
        //Status sukses dan judul TARIF PENGUJIAN
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>');
    }

    public function testIndexWithCategoryAndIsActiveFilter()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/charge?category=Lab+CPE&is_active=1');
        //Status sukses dan judul TARIF PENGUJIAN
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>');
    }

    public function testIndexWithSearchFilter()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/charge?search=cari');
        //Status sukses dan judul TARIF PENGUJIAN
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>');
    }
    

    public function testIndexWithoutDataFound()
    {
        ExaminationCharge::truncate();
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/charge');
        //Status sukses dan judul TARIF PENGUJIAN
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN</h1>')
            ->see('Data not found');            
    }

    public function testCreate()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/charge/create');
        //Status sukses dan judul TAMBAH TARIF
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TAMBAH TARIF</h1>');
    }

    public function testStore()
    {
        $lab = App\ExaminationLab::first();
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->visit('admin/charge/create')
            ->type('Device name for testing', 'device_name')
            ->type('Referensi uji for testing', 'stel')
            ->select($lab->id, 'category')
            ->type('70', 'duration')
            ->type('7000000', 'price')
            ->type('700000', 'vt_price')
            ->type('70000', 'ta_price')
            ->select('0', 'is_active')
            ->press('submit');

        //Status sukses dan pesan Charge successfully created
        $response->assertResponseStatus('200')
            ->seePageIs('admin/charge')
            ->see('Charge successfully created');
        //see in database
        $this->seeInDatabase('examination_charges', ['device_name' => 'Device name for testing']);
    }

    public function testStoreWithSameName()
    {
        $lab = App\ExaminationLab::first();
        $user = User::find(1);

        $response = $this->actingAs($user)
        ->visit('admin/charge/create')
        ->type('Device name for testing', 'device_name')
        ->type('Referensi uji for testing same name', 'stel')
        ->select($lab->id, 'category')
        ->type('701', 'duration')
        ->type('70000001', 'price')
        ->type('7000001', 'vt_price')
        ->type('700001', 'ta_price')
        ->select('0', 'is_active')
        ->press('submit');

        //Status sukses dan judul Nama perangkat sudah ada
        $response->assertResponseStatus('200')
            ->see('Nama Perangkat sudah ada!');
    }

    public function testEdit()
    {
        $examinationCharge = ExaminationCharge::latest()->first();
        $user = User::find(1);
        $this->actingAs($user)->call('GET',"admin/charge/$examinationCharge->id/edit");
        //Status respond ok, dan terdapat judul h1 "EDIT TARIF"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">EDIT TARIF</h1>')
            ->see($examinationCharge->device_name);
    }

    public function testUpdate()
    {
        $lab = App\ExaminationLab::first();
        $examinationCharge = ExaminationCharge::latest()->first();
        $user = User::find(1);
        $this->actingAs($user)
            ->visit('admin/charge/'.$examinationCharge->id.'/edit')
            ->type('Device name for testing updated', 'device_name')
            ->type('Referensi uji for testing updated', 'stel')
            ->select($lab->id, 'category')
            ->type('771', 'duration')
            ->type('77000001', 'price')
            ->type('7700001', 'vt_price')
            ->type('770001', 'ta_price')
            ->select('1', 'is_active')
            ->press('submit');

        //Status ok, halaman sedang di charge.index, dan pesan "Charge successfully updated"
        $this->assertResponseStatus('200')
            ->seePageIs('admin/charge')
            ->see('Charge successfully updated');

        //check di database
        $this->seeInDatabase('examination_charges', ['device_name' => 'Device name for testing updated']);
    }

    public function testExcel()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->call('GET','charge/excel?category=Lab+Device&is_active=1');
        //Status Ok,
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Tarif Pengujian.xlsx"');
    }

    public function testExcelWithSearch()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->call('GET','charge/excel?search=cari');
        //Status Ok,
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Tarif Pengujian.xlsx"');
    }

    public function testDestroy()
    {
        $examinationCharge = ExaminationCharge::latest()->first();
        $user = User::find(1);
        $this->actingAs($user)->call('DELETE','admin/charge/'.$examinationCharge->id);

        //Status redirect, ke halaman charge.index, dan pesan "Charge successfully deleted"
        $this->assertRedirectedTo('/admin/charge', ['message' => 'Charge successfully deleted']);
    }

    public function testDestroyNotFound()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('DELETE','admin/charge/chargenotfound');

        //Status redirect, ke halaman charge.index, dan pesan "Charge successfully deleted"
        $this->assertRedirectedTo('/admin/charge', ['error' => 'Charge not found']);

        //truncate data di examination
        ExaminationCharge::truncate();
    }

    public function testAutoclomplete()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/adm_charge_autocomplete/asd');
        //Status sukses
        $this->assertResponseStatus(200);
    }

}
