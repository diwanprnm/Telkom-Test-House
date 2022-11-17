<?php

use App\User;
use App\Approval;
use App\Examination;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApprovalControllerTest extends TestCase
{
    public function testIndexAsNonAdmin()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user)->call('GET', 'admin/approval');
        //status sukses, tapi ada bacaaan you dont have permission
        $this->assertResponseStatus(200)
            ->see("Unautorizhed. You do not have permission to access this page.");
    }

    public function testIndex()
    {
        $admin = User::where('id', '=', '1')->first();
        // dd($admin);
        $this->actingAs($admin)->call('GET','admin/approval');
        //Status sukses dan judul ARTIKEL
        $this->assertResponseStatus(200);
    }

    public function testIndexWithSearch()
    {
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/approval?search=test');
        //Status sukses dan judul Sidang QA
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Approval</h1>');
    }

    public function testAssign()
    {
        $approval = factory(App\Approval::class)->create();
        $admin = User::where('id', '=', '1')->first();

        $response = $this->actingAs($admin)->call('GET', 'admin/approval/assign/'. $approval->id.'/admin');

        // dd($response);

		$this->assertEquals(200, $response->status());
    }

    public function testShow()
    {
        $approval = factory(App\Approval::class)->create();
        
        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'GET',
            'admin/approval/'. $approval->id 
        );

        // $admin = User::where('id', '=', '1')->first();
        // $approval = Approval::where('status', '=', '1')->first();
        // $this->actingAs($admin)->call('GET', 'admin/approval/'. $approval->id);
        //Status sukses dan judul Sidang QA
        $this->assertResponseStatus(200);
            // ->see('<h1 class="mainTitle">Approval</h1>');
    }

    // public function testIndexWithSearch()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/approval?search=cari');
    //     //Status sukses dan judul ARTIKEL
    //     $this->assertResponseStatus(200)
    //     ->see('<h1 class="mainTitle">ARTIKEL</h1>');
    // }

    // public function testIndexButNoDataExist()
    // {
    //     Approval::truncate();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/approval?search=cari');
    //     //Status sukses dan judul ARTIKEL
    //     $this->assertResponseStatus(200)
    //     ->see('<h1 class="mainTitle">ARTIKEL</h1>')
    //     ->see('Data not found');
    // }

    // public function testStore()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)
    //         ->visit('admin/approval/create')
    //         ->see('<h1 class="mainTitle">TAMBAH ARTIKEL BARU</h1>')
    //         ->type('Testing Approval store', 'title')
    //         ->type('Good type', 'type')
    //         ->select('0', 'is_active')
    //         ->type('Teks ini mendeskripsikan isi artikel', 'description')
    //         ->type('This text descripted the approval', 'description_english')
    //         ->press('submit');
    //     //check view and see flash message "certificates is successfully created"
    //     $this->assertResponseStatus(200)
    //         ->see('Approval successfully created');
    // } 

    

    // public function testDestroy()
    // {
    //     $approval = Approval::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     //visit index, delete a certificate
    //     $this->actingAs($user)->call('DELETE','admin/approval/'.$approval->id);
    //     //Response status redirect to approval.index
    //     $this->assertRedirectedTo('admin/approval/', ['message' => 'Approval successfully deleted']);
    //     //delete remaining approval when test is done
    //     Approval::truncate();
    // }
    
    // public function testDestroyNotFound()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('DELETE','admin/approval/approvalNotFound');
    //     //Response status redirect to approval.index
    //     $this->assertRedirectedTo('admin/approval/', ['error' => 'Approval not found']);
    // }

    // public function testAutocomplete()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET',"admin/adm_approval_autocomplete/query");
    //     //Response status ok
    //     $this->assertResponseStatus(200);
    // }
}