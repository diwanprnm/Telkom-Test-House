<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class CalibrationChargeControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
    	$response = $this->get('/');

        $response->assertStatus(200); 
    }

    /**
	 * @test
	 */
	public function test_stores_data()
	{
	    
	    //Membuat objek user yang otomatis menambahkannya ke database.
	    $user = factory(User::class)->create(); 
	    
	    $response = $this->actingAs($user)
	    //Hit post ke method store, fungsinya ya akan lari ke fungsi store.
	    ->post(route('calibration.store'), [
	        //isi parameter sesuai kebutuhan request
	        'device_name' => $this->faker->words(5, true), 
	        'quantity' => $this->faker->randomNumber(4),
	        'is_active' => $this->faker->randomNumber(1)
	    ]);

	    //Tuntutan status 302, yang berarti redirect status code.
	    $response->assertStatus(302);

	    //Tuntutan bahwa abis melakukan POST URL akan dialihkan ke URL product atau routenya adalah product.index
	    // $response->assertRedirect(route('product.index'));
	}
}
