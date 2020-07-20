<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use app\videoTutorial;
use App\User;
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
            ->see('<h1 class="mainTitle">Manage URL Information</h1>')
            ->type('Testing url', 'profile_url')
            ->type('Testing url', 'buy_stel_url')
            ->type('Testing url', 'qa_url')
            ->type('Testing url', 'ta_url')
            ->type('Testing url', 'vt_url')
            ->type('Testing url', 'playlist_url')
            ->press('submit');
        //check view and see flash message "certificates is successfully created"
        $this->assertResponseStatus(200)
            ->see('Article successfully created');
    } 

  
}
