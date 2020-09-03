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
 //    public function test_purchase_history()
	// { 
	//    $user = User::find(1);
	//    $response =  $this->actingAs($user)->call('GET', '/purchase_history');  

	   
 //       $this->assertEquals(200, $response->status());
	// }
 //    public function test_payment_status()
	// { 
	//    $user = User::find(1);
	//    $response =  $this->actingAs($user)->call('GET', '/payment_status');  
	    
 //       $this->assertEquals(200, $response->status());
	// }
 //    public function test_payment_detail()
	// { 
	//    $user = User::find(1);
	//    $stelsSales = factory(App\STELSales::class)->create(['id'=>'00000000-aaaa-aaaa-aaaa-000000000000']);
	//    $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
 //       $stels = App\STEL::find($stelSalesDetail->stels_id);
	//    $response =  $this->actingAs($user)->call('GET', '/payment_detail/'.$stelsSales->id);  
	   
 //       $this->assertEquals(200, $response->status());
	// }
 //    public function test_upload_payment()
	// { 
	//    $user = User::find(1);
	//    $stelsSales = factory(App\STELSales::class)->create(['id'=>'00000000-aaaa-aaaa-aaaa-000000000000']);
	//    $stelSalesDetail = factory(App\STELSalesDetail::class)->create(['stels_sales_id' => $stelsSales->id]);
 //       $stels = App\STEL::find($stelSalesDetail->stels_id);
	//    $response =  $this->actingAs($user)->call('GET', '/upload_payment/'.$stelsSales->id);  
	   
 //       $this->assertEquals(200, $response->status());
	// }
}
