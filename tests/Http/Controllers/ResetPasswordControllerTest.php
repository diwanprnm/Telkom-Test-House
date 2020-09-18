<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\ResetPasswordController; 
use Illuminate\Http\Request;
use App\User;   
class ResetPasswordControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_stores_client_email()
	{ 
		$user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', '/client/password/email', 
		[ 
	        
	        'email' =>  str_random(10) 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $company = factory(App\Company::class)->make();  
	}
	// public function test_visit_get_reset()
	// { 
	//    $user = User::find(1);
	//    $response =  $this->actingAs($user)->call('GET', '/client/password/reset/123');  
        
	 
 //       $this->assertEquals(200, $response->status());
	// } 
    public function test_stores_client_reset()
	{ 
		$user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', '/client/password/reset', 
		[ 
	        
	        'email' =>  str_random(10), 
	        'password' =>  str_random(10)
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $company = factory(App\Company::class)->make();  
	}
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	public function test_getGuard()
	{  
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getGuard');
	}
	public function test_getBroker()
	{  
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getBroker');
	}
	public function test_cekEmail()
	{  
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'cekEmail',array("email@mail.com"));
	}
	public function test_getResetSuccessResponse()
	{  
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getResetSuccessResponse',array("email@mail.com"));
	}
	public function test_getResetFailureResponse()
	{  
		$request = new Request();
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getResetFailureResponse',array($request,"email@mail.com"));
	}
	// public function test_resetPassword()
	// {  
	// 	$user = User::find(1);
       
	// 	$object = app('App\Http\Controllers\ResetPasswordController');
	// 	$this->invokeMethod($object, 'resetPassword',array($user,"admin"));
	// }
	public function test_getResetCredentials()
	{  
		$request = new Request();
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getResetCredentials',array($request,"admin"));
	}
	public function test_reset()
	{  
		$request = new Request();
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'reset',array($request));
	}
	public function test_postReset()
	{  
		$request = new Request();
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'postReset',array($request));
	}
	public function test_showResetForm()
	{  
		$request = new Request();
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'showResetForm',array($request));
	}
	public function test_getSendResetLinkEmailFailureResponse()
	{   
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getSendResetLinkEmailFailureResponse',array(""));
	}
	public function test_getSendResetLinkEmailSuccessResponse()
	{   
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getSendResetLinkEmailSuccessResponse',array(""));
	}
	public function test_getEmailSubject()
	{   
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getEmailSubject');
	}
	public function test_resetEmailBuilder()
	{   
       
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'resetEmailBuilder');
	}
	public function test_getSendResetLinkEmailCredentials()
	{   
       	$request = new Request(['email'=>"admin@mail.com"]);
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'getSendResetLinkEmailCredentials',array($request));
	}
	public function test_validateSendResetLinkEmail()
	{  
		$request = new Request(['email'=>"admin@mail.com"]);
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'validateSendResetLinkEmail',array($request));
	} 
	public function test_showLinkRequestForm()
	{  
		 
		$object = app('App\Http\Controllers\ResetPasswordController');
		$this->invokeMethod($object, 'showLinkRequestForm');
	} 
}
