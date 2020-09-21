<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\STELSales;  
use App\STEL;  
 
use App\Http\Controllers\ProductsController; 
class ProductsControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/products');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_search()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/products?search=asd');  
       $this->assertEquals(200, $response->status());
	}
    public function test_purchase_history()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/purchase_history');  

	   
       $this->assertEquals(200, $response->status());
	}
    public function test_payment_status()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', '/payment_status');  
	    
       $this->assertEquals(200, $response->status());
	}
    public function test_payment_detail()
	{ 
	   $user = User::find(1);
	   $stelsSales = factory(App\STELSales::class)->create();
	   $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
       $stels = App\STEL::find($stelSalesDetail->stels_id);
	   $response =  $this->actingAs($user)->call('GET', '/payment_detail/'.$stelsSales->id);  
	   
       $this->assertEquals(200, $response->status());
	}
    public function test_upload_payment()
	{ 
	   $user = User::find(1);
	   $stelsSales = factory(App\STELSales::class)->create();
	   $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
       $stels = App\STEL::find($stelSalesDetail->stels_id);
	   $response =  $this->actingAs($user)->call('GET', '/upload_payment/'.$stelsSales->id);  
	   
       $this->assertEquals(200, $response->status());
	}
    public function test_upload_pembayaranstel()
	{   
        $user = User::find(1); 
 
        
		$response =  $this->actingAs($user)->call('POST', '/pembayaranstel', 
		[ 
	        'jml-pembayaran' => mt_rand(0,10000), 
	        'filePembayaran' => NULL, 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());  
	}
    public function test_store()
	{   
        $user = User::find(1); 
 	
        
		$response =  $this->actingAs($user)->call('POST', '/products', 
		[ 
	        'id' => mt_rand(0,10000), 
	        'name' => str_random(10), 
	        'qty' => 1, 
	        'price' => 1
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());  
	}
    public function test_checkout()
	{   
        $user = User::where('role_id', '=', '2')->first();
		$response =  $this->actingAs($user)->call('POST', '/checkout', 
		[ 
	        'agree' => TRUE 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(200, $response->status());  
	}
    public function test_doCheckout()
	{   
        $user = User::where('role_id', '=', '2')->first();
		$response =  $this->actingAs($user)->call('POST', '/doCheckout', 
		[ 
	        'payment_method' => 'CC', 
	        'name' => str_random(10), 
	        'exp' => str_random(10), 
	        'cvv' => str_random(10), 
	        'cvc' => str_random(10), 
	        'type' => str_random(10), 
	        'no_card' => str_random(10), 
	        'no_telp' => str_random(10), 
	        'email' => str_random(10), 
	        'country' => str_random(2), 
	        'province' => str_random(2), 
	        'postal_code' => str_random(2), 
	        'birthdate' => str_random(2) 
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());  
	}


	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	public function test_api_billing()
	{  
		$data = [];
		$object = app('App\Http\Controllers\ProductsController');
		$this->invokeMethod($object, 'api_billing',array($data));
	} 


    public function test_destroy()
	{ 
		$user = User::latest()->first(); 
		$stel = factory(App\STEL::class)->create();
		$id = $stel->id;  
		$response =  $this->actingAs($user)->call('DELETE', '/products/'.$id); 
		// dd($response->getContent()); 
        $this->assertEquals(302, $response->status()); 
	} 

 //    public function test_downloadStel()
	// { 
	// 	$user = User::latest()->first(); 
	// 	$stel = factory(App\STEL::class)->create();
	// 	$response =  $this->actingAs($user)->call('GET', '/products/'.$stel->id.'/stel'); 
	// 	// dd($response->getContent()); 
 //        $this->assertEquals(302, $response->status()); 
	// } 
}
	