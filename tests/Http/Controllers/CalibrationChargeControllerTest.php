<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations; 
use App\CalibrationCharge; 
class CalibrationChargeControllerTest extends TestCase
{
	use DatabaseMigrations; 
	use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */ 

    /**
	 * @test
	 */
	public function test_stores_data()
	{
	    
	    //Membuat objek user yang otomatis menambahkannya ke database.
	    $calibration = factory(CalibrationCharge::class)->create(); 
	    
	    $response = $this->actingAs($calibration) 
	    ->post(route('calibration.store'), [ 
	        'device_name' => $this->faker->words(5, true), 
	        'price' => $this->faker->randomNumber(4),
	        'is_active' => $this->faker->randomNumber(1)
	    ]);

	    //Tuntutan status 302, yang berarti redirect status code.
	    $response->assertResponseStatus(200);

	    //Tuntutan bahwa abis melakukan POST URL akan dialihkan ke URL product atau routenya adalah product.index
	    // $response->assertRedirect(route('product.index'));
	}
}
