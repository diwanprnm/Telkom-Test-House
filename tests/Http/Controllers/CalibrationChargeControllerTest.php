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
	public function testIndex()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/calibration');

        $this->assertResponseStatus(200);
            
    }

	public function testCreate()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/calibration/create');
        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Tambah Tarif Kalibrasi Baru</h1>');
    }
	public function test_stores_data()
	{
	    
	    //Membuat objek user yang otomatis menambahkannya ke database.
	    $calibration = factory(CalibrationCharge::class)->create(); 
	    
		$response = $this->actingAs($calibration) 
		->visit('admin/calibration/'.$calibration->id.'/edit')
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
	public function testEdit()
    {
        $article = CalibrationCharge::latest()->first();
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/articlecalibration/'.$article->id.'/edit');
       
        $this->assertResponseStatus(200);
           
    }
	public function test_update_data()
	{
	    
	    //Membuat objek user yang otomatis menambahkannya ke database.
	    $calibration = factory(CalibrationCharge::class)->create(); 
	    
		$response = $this->actingAs($calibration) 
		->visit('admin/calibration/'.$calibration->id.'/edit')
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

	public function testDestroy()
    {
        $article = CalibrationCharge::latest()->first();
        $user = User::where('id', '=', '1')->first();
        //visit index, delete a certificate
        $this->actingAs($user)->call('DELETE','admin/calibration/'.$article->id);
        //Response status redirect to article.index
        $this->assertResponseStatus(302) 
            ->see('Redirecting to <a href="http://localhost/admin/calibration">http://localhost/admin/calibration</a>');
        //delete remaining article when test is done
        CalibrationCharge::truncate();
    }
}
