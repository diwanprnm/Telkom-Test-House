<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;  
class NotificationControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit_index()
	{ 
	    
	    $user = factory(User::class)->make();
	    $response =  $this->actingAs($user)->call('GET', '/all_notifications');  
        
	 
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_index_admin()
	{ 
	    
	    $user = User::find(1);
	    $response =  $this->actingAs($user)->call('GET', '/all_notifications');  
        
	 
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_update_notif()
	{ 
	    
	    $user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', '/updateNotif', 
		[ 
	        'notif_id' => str_random(10), 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(200, $response->status());
	}
}
