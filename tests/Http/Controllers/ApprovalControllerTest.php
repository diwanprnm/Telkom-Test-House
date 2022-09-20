@@ -0,0 +1,120 @@
<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Approval;

class ApprovalControllerTest extends TestCase
{
    public function testIndex()
    {
        $admin = User::where('role_id', '=', '1')->first();
        $this->actingAs($admin)->call('GET','admin/approval?search=cari');
        //Status sukses dan judul ARTIKEL
        $this->assertResponseStatus(200);
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

    // public function testCreate()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/approval/create');
    //     //Status sukses dan judul TAMBAH ARTIKEL BARU
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TAMBAH ARTIKEL BARU</h1>');
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

    // public function testEdit()
    // {
    //     $approval = Approval::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/approval/'.$approval->id.'/edit');
    //     //Status respond ok, dan terdapat judul h1 "Edit Artikel"
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Edit Artikel</h1>');
    // }

    // public function testUpdate()
    // {
    //     $approval = Approval::latest()->first();
    //     $user = User::where('id', '=', '1')->first();

    //     $this->actingAs($user)->call('PATCH', "admin/approval/$approval->id", 
	// 	[ 
	//         'title' => 'Testing Approval',
	//         'type' => 'Good type',
	//         'is_active' => 1,
	//         'description' =>  'Teks ini mendeskripsikan isi artikel yang telah di update',
	//         'description_english' => 'This text descripted the approval that has been updated',
    //     ]);
    //     //redirect ke index approval dengan pesan Approval succesfully updated
    //     $this->assertRedirectedTo('admin/approval/', ['message' => 'Approval successfully updated']);
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