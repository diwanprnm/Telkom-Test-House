<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\STELSales;  
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
}
	