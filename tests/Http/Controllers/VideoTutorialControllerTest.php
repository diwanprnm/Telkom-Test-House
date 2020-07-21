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
        $this->assertResponseStatus(200);
    }



  /*  public function testStore()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)
            ->visit('admin/videoTutorial')
            ->type('Testing url', 'profile_url')
            ->type('Testing url', 'buy_stel_url')
            ->type('Testing url', 'qa_url')
            ->type('Testing url', 'ta_url')
            ->type('Testing url', 'vt_url')
            ->type('Testing url', 'playlist_url')
            ->press('Submit');
        //check view and see flash message "certificates is successfully created"
        $this->assertResponseStatus(200);
    } */

    public function test_stores_company()
	{ 
//$user = factory(App\User::class)->create(); 
        $user = User::where('id', '=', '1')->first();
        
		$response =  $this->actingAs($user)->call('RESOURCE', 'admin/VideoTutorial/create', 
		[ 
	        'profile_url' => str_random(10),
	        'buy_stel_url' => str_random(10),
	        'qa_url' => str_random(10) ,
	        'ta_url' =>  str_random(10),
	        'vt_url' => str_random(10) ,
	        'playlist_url' =>str_random(10) 
	        
	    ]);   
		
        $this->assertEquals(302, $response->status());
	     
	}
  
}
