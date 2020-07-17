<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use app\videoTutorial;
class VideoTutorialControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/videoTutorial');
        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Manage URL Information</h1>');
    }

  

    

    public function testCreate()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/videoTutorial/create');
        $this->assertResponseStatus(200);
            
    }

    public function testStore()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)
            ->visit('admin/article/create')
            ->see('<h1 class="mainTitle">TAMBAH ARTIKEL BARU</h1>')
            ->type('Testing Article store', 'title')
            ->type('Good type', 'type')
            ->select('0', 'is_active')
            ->type('Teks ini mendeskripsikan isi artikel', 'description')
            ->type('This text descripted the article', 'description_english')
            ->press('submit');
        //check view and see flash message "certificates is successfully created"
        $this->assertResponseStatus(200)
            ->see('Article successfully created');
    } 

  
}
