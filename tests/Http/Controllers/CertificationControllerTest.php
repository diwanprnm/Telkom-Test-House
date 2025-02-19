<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Certification; 

class CertificationControllerTest extends TestCase
{

    public function testIndexWithSearch()
    {
        $user = User::where('role_id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/certification?search=cari');
        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">Certification</h1>');
    }

    public function testCreate()
    {
        $user = User::where('role_id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/certification/create');
        //Status sukses dan judul TAMBAH CERTIFICATION BARU
        $this->assertResponseStatus(200)
        ->see('TAMBAH CERTIFICATION BARU');
    }

    public function testStore()
    {
        $user = User::where('role_id', '=', '1')->first();
        $this->actingAs($user)
            ->visit('admin/certification/create')
            ->see('TAMBAH CERTIFICATION BARU')
            ->type('Testing Certificate store', 'title')
            ->select('0', 'is_active')
            ->attach(base_path().'/public/images/logo_dds.png', 'image')
            ->press('submit');
        //check view and see flash message "certificates is successfully created"
        $this->assertResponseStatus(200)
            ->see('Certification successfully created');
    }

    public function testEdit()
    {
        $certification = Certification::latest()->first();
        $user = User::where('role_id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/certification/'.$certification->id.'/edit');
        //Status respond ok, dan terdapat judul h1 "Edit Certification"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Edit Certification</h1>');
    }

    public function testUpdate()
    {
        $certification = Certification::latest()->first();
        $user = User::where('role_id', '=', '1')->first();
        //visit edit, fill edit certification form, then submit
        $this->actingAs($user)
            ->visit('admin/certification/'.$certification->id.'/edit')
            ->see('<h1 class="mainTitle">Edit Certification</h1>')
            ->type('Testing Certificate update', 'title')
            ->select('1', 'is_active')
            ->attach(base_path().'/public/images/logo_dds.png', 'image')
            ->press('submit');
        //Response status must be ok, and see flash message "Certification successfully updated"
        $this->assertResponseStatus(200)
        ->see('Certification successfully updated');
    }

    public function testDestroy()
    {
        $certification = Certification::latest()->first();
        $user = User::where('role_id', '=', '1')->first();
        //visit index, delete a certificate
        $this->actingAs($user)->call('DELETE','admin/certification/'.$certification->id);
        //Response status redirect to certification.index
        $this->assertRedirectedTo('/admin/certification', ['message' => 'Certification successfully deleted']);
    }

    public function testDestroyNotFound()
    {
        $user = User::where('role_id', '=', '1')->first();
        //visit index, delete a certificate
        $this->actingAs($user)->call('DELETE','admin/certification/certificationnotfound');
        //Response status redirect to certification.index
        $this->assertRedirectedTo('/admin/certification', ['error' => 'Certification not found']);
        //Delete remaining residual certification data
        Certification::truncate();
    }

}
