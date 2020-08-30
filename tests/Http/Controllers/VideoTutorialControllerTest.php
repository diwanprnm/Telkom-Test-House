<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use app\VideoTutorial;
use App\User;

class VideoTutorialControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/videoTutorial');
        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('Video Tutorial');
    }

    public function testUpdate()
    {
        $user = User::find(1);
        $this->actingAs($user)
            ->visit('admin/videoTutorial')
            ->type('https://www.youtube.com/tth/test_profile_url', 'profile_url')
            ->type('https://www.youtube.com/tth/test_buy_stel_url', 'buy_stel_url')
            ->type('https://www.youtube.com/tth/test_qa_url', 'qa_url')
            ->type('https://www.youtube.com/tth/test_ta_url', 'ta_url')
            ->type('https://www.youtube.com/tth/test_vt_url url', 'vt_url')
            ->type('https://www.youtube.com/tth/test_playlist_url url', 'playlist_url')
            ->press('Submit');

         //status 200 & Video Tutorial header
        $this->assertResponseStatus(200)
            ->see('Video Tutorial');
    }

    public function testUpdateNotFound()
    {
        $videoSeed = $this->getSeed();
        $user = User::find(1);
        $this->actingAs($user)->call('PATCH',"admin/videoTutorial/NotFound", $videoSeed);

        //redirect 
        $this->assertRedirectedTo('admin/videoTutorial', ['error' => 'Data not found']);
    }

    public function testDestroy()
    {
        $videoTutorial = VideoTutorial::latest()->first();
        $user = User::find(1);
        $this->actingAs($user)->call('DELETE',"admin/videoTutorial/$videoTutorial->id");
        //Status sukses
        $this->assertRedirectedTo('admin/videoTutorial', ['message' => 'Video tutorial successfully deleted']);
    }

    public function testDestroyNotFound()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('DELETE',"admin/videoTutorial/DataNotFound");
        //Status redierct
        $this->assertRedirectedTo('admin/videoTutorial', ['error' => 'Video Tutorial Not Found']);
    }

    public function testIndexNotFound()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/videoTutorial');
        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('Video Tutorial');
        
        VideoTutorial::create($this->getSeed());
    }

    public function testCreate()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/videoTutorial/create');
        //Status sukses
        $this->assertResponseStatus(200);
    }

    public function testStore()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('POST','admin/videoTutorial',['id'=> 1]);
        //Status sukses
        $this->assertResponseStatus(200);
    }

    public function testShow()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/videoTutorial/id');
        //Status sukses
        $this->assertResponseStatus(200);
    }

    public function testEdit()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/videoTutorial/id/edit');
        //Status sukses
        $this->assertResponseStatus(200);
    }
    
    private function getSeed(){
        return array(
            'profile_url' => 'https://www.youtube.com/embed/TvYcs9g2RUo',
            'buy_stel_url' => 'https://www.youtube.com/embed/KMFCqbl9SFQ',
            'qa_url' => 'https://www.youtube.com/embed/4sL5-d9yxl8',
            'ta_url' => 'https://www.youtube.com/embed/Ju-uU2kJ3m8',
            'vt_url' => 'https://www.youtube.com/embed/uGxUzfekYIE',
            'playlist_url' => 'https://www.youtube.com/embed?list=PLl3Z5rVQaSyXdmOjIJ2pKhBAIAEOno63C',
            'created_by' => 1,
            'updated_by' => 1,
        );
    }
}
