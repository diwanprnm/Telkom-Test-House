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
    public function test_payment_confirmation()
	{ 
	   $user = User::find(1);
	   $stelsSales = factory(App\STELSales::class)->create();
	   $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);

	   $response =  $this->actingAs($user)->call('GET', '/payment_confirmation/'.$stelsSales->id);  
	    
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
        $user = factory(App\User::class)->create(['role_id'=>2]);
		$response =  $this->actingAs($user)->call('GET', '/checkout');   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());  
	}
 //    public function test_doCheckout()
	// {   
 //        $user = factory(App\User::class)->create(['role_id'=>2]);
	// 	$response =  $this->actingAs($user)->call('POST', '/doCheckout', 
	// 	[ 
	//         'payment_method' => 'ak||001||atm', 
	//         'name' => str_random(10), 
	//         'exp' => str_random(10), 
	//         'cvv' => str_random(10), 
	//         'cvc' => str_random(10), 
	//         'type' => str_random(10), 
	//         'no_card' => str_random(10), 
	//         'no_telp' => str_random(10), 
	//         'email' => str_random(10), 
	//         'country' => str_random(2), 
	//         'province' => str_random(2), 
	//         'postal_code' => str_random(2), 
	//         'birthdate' => str_random(2) 
	//     ]);   
	// 	// dd($response->getContent());
 //        $this->assertEquals(302, $response->status());  
	// }


	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}  
	public function test_api_purchase()
	{  
		$data = [];
		$object = app('App\Http\Controllers\ProductsController');
		$this->invokeMethod($object, 'api_purchase',array($data));
	} 
	public function test_api_get_payment_methods()
	{  
		$data = [];
		$object = app('App\Http\Controllers\ProductsController');
		$this->invokeMethod($object, 'api_get_payment_methods');
	} 
	// public function test_resend_va()
	// { 
	//    $user = User::find(1);
	//    $stelsSales = factory(App\STELSales::class)->create();
	//    $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);

	//    $response =  $this->actingAs($user)->call('GET', '/resend_va/'.$stelsSales->id);  
	    
 //       $this->assertEquals(302, $response->status());
	// } 

	public function test_cancel_va()
	{ 
	   $user = User::find(1);
	   $stelsSales = factory(App\STELSales::class)->create();
	   $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);

	   $response =  $this->actingAs($user)->call('GET', '/cancel_va/'.$stelsSales->id);  
	    
       $this->assertEquals(200, $response->status());
	}   

    public function test_doCancel()
	{   
        $user = factory(App\User::class)->create(["role_id"=>2]);
 
        $stelsSales = factory(App\STELSales::class)->create(["user_id"=>$user->id]);
	    $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
		$response =  $this->actingAs($user)->call('POST', '/doCancel', 
		[ 
	        'id' => $stelsSales->id,
	        'payment_method'=>"A||A||atm||1"
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());  
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

    public function test_downloadStel()
	{ 
		$user = User::latest()->first(); 
		$stel = factory(App\STEL::class)->create(); 
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("stel/$stel->attachment", $file);
	    
	    $response =  $this->actingAs($user)->call('GET', '/products/'.$stel->id.'/stel');  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("stel/$stel->attachment");

	} 
    public function test_downloadfakturstel()
	{ 
		$user = User::latest()->first(); 
		$stelsSales = factory(App\STELSales::class)->create(); 
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("stel/$stelsSales->id/$stelsSales->faktur_file", $file);
	    
	    $response =  $this->actingAs($user)->call('GET', 'client/downloadfakturstel/'.$stelsSales->id);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("stel/$stelsSales->id/$stelsSales->faktur_file");

	} 
    public function test_downloadkuitansistel()
	{ 
		$user = User::latest()->first(); 
		$id_kuitansi = mt_rand(0,4);
		$stelsSales = factory(App\STELSales::class)->create(['created_by'=>$user->id,"id_kuitansi"=>$id_kuitansi]); 
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("stel/$stelsSales->id/$id_kuitansi", $file);
	    
	    $response =  $this->actingAs($user)->call('GET', 'client/downloadkuitansistel/'.$id_kuitansi);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("stel/$stelsSales->id/$stelsSales->id_kuitansi");

	} 
     public function test_viewWatermark()
	{ 
		$user = User::latest()->first(); 
		$stelsSales = factory(App\STELSales::class)->create(['created_by'=>$user->id]); 
	    $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("stelAttach/$stelSalesDetail->id/$stelSalesDetail->attachment", $file);
	    
	    $response =  $this->actingAs($user)->call('GET', 'client/downloadstelwatermark/'.$stelSalesDetail->id);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("stelAttach/$stelSalesDetail->id/$stelSalesDetail->attachment");

	} 
}
	