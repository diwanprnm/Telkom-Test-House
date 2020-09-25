<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class FakturPajakControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function testIndexWitoutDataFound()
    {
        
        //make request
        $user = User::where('id', '=', '1')->first();
        $response = $this->actingAs($user)->call('GET','admin/fakturpajak');
 
         $this->assertEquals(200, $response->status());
    }

    // public function testIndexWithSearch()
    // { 
    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($user)->call('GET',"admin/fakturpajak?search=asd");

    //     //Status sukses dan judul Certification
    //     // $this->assertResponseStatus(200)
    //     //     ->see('<h1 class="mainTitle">Rekap Kuitansi dan Faktur Pajak</h1>')
    //     //     ->see('SPB')
    //     //     ->see($deviceName)
    //     // ;
    //      $this->assertEquals(200, $response->status());
    // }
}
