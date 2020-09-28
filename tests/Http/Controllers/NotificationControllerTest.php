<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;  


use App\Http\Controllers\NotificationController; 
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
	    $response =  $this->actingAs($user)->call('GET', 'admin/all_notifications');  
        
	 
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


	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}



	public function test_createTree()
	{  
		$list = array();
		$parent = array();
		$object = app('App\Http\Controllers\NotificationController');
		$this->invokeMethod($object, 'createTree',array(&$list,$parent));
	}
}
