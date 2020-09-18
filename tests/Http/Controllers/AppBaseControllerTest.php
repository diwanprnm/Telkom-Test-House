<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Http\Controllers\AppBaseController; 

use Illuminate\Http\Request;
class AppBaseControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
   public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	public function test_sendResponse()
	{  
		$request = new Request();
		$object = app('App\Http\Controllers\AppBaseController');
		$this->invokeMethod($object, 'sendResponse',array($request,""));
	}
	public function test_sendError()
	{   
		$object = app('App\Http\Controllers\AppBaseController');
		$this->invokeMethod($object, 'sendError',array("200","200"));
	}
}
