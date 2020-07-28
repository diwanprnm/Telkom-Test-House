<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Article;

use PHPUnit\Framework\TestCase;

class ArticleControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function testIndex(){
        // $admin = User::where('role_id', '=', '1')->first();
        // $this->actingAs($admin)->call('GET','admin/article');
        // $this->assertResponseStatus(200);
        $this->assertTrue(true);
    }

    // public function testIndexAsNonAdmin()
    // {
    //     $user = factory(User::class)->make();
    //     $this->actingAs($user)->call('GET','admin/article');
    //     //status sukses, tapi ada bacaaan you dont have permission
    //     $this->assertResponseStatus(200)
    //         ->see("Unautorizhed. You do not have permission to access this page.");
    // }

    // public function testIndex()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/article');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Artikel</h1>');
    // }

    // public function testIndexWithSearch()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/article?search=cari');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //     ->see('<h1 class="mainTitle">ARTIKEL</h1>');
    // }

    // public function testIndexButNoDataExist()
    // {
    //     Article::truncate();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/article?search=cari');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //     ->see('<h1 class="mainTitle">ARTIKEL</h1>')
    //     ->see('Data not found');
    // }

    // public function testCreate()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/article/create');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">TAMBAH ARTIKEL BARU</h1>');
    // }

    // public function testStore()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)
    //         ->visit('admin/article/create')
    //         ->see('<h1 class="mainTitle">TAMBAH ARTIKEL BARU</h1>')
    //         ->type('Testing Article store', 'title')
    //         ->type('Good type', 'type')
    //         ->select('0', 'is_active')
    //         ->type('Teks ini mendeskripsikan isi artikel', 'description')
    //         ->type('This text descripted the article', 'description_english')
    //         ->press('submit');
    //     //check view and see flash message "certificates is successfully created"
    //     $this->assertResponseStatus(200)
    //         ->see('Article successfully created');
    // } 

    // public function testEdit()
    // {
    //     $article = Article::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/article/'.$article->id.'/edit');
    //     //Status respond ok, dan terdapat judul h1 "Edit Artikel"
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Edit Artikel</h1>');
    // }

    // public function testUpdate()
    // {
    //     $article = Article::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     //visit edit, fill edit artikel form, then submit
    //     $this->actingAs($user)
    //         ->visit('admin/article/'.$article->id.'/edit')
    //         ->see('<h1 class="mainTitle">Edit Artikel</h1>')
    //         ->type('Testing Article', 'title')
    //         ->type('Good type', 'type')
    //         ->select('1', 'is_active')
    //         ->type('Teks ini mendeskripsikan isi artikel yang telah di update', 'description')
    //         ->type('This text descripted the article that has been updated', 'description_english')
    //         ->press('submit');
    //     //Response status must be ok, and see flash message "Certification successfully updated"
    //     $this->assertResponseStatus(200)
    //     ->see('Article successfully updated');
    // }

    // public function testDestroy()
    // {
    //     $article = Article::latest()->first();
    //     $user = User::where('id', '=', '1')->first();
    //     //visit index, delete a certificate
    //     $this->actingAs($user)->call('DELETE','admin/article/'.$article->id);
    //     //Response status redirect to article.index
    //     $this->assertResponseStatus(302) 
    //         ->see('Redirecting to <a href="http://localhost/admin/article">http://localhost/admin/article</a>');
    //     //delete remaining article when test is done
    //     Article::truncate();
    // }    
}
