<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Certification; 

class CertificationControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($user)->call('GET','admin/certification');
        $this->assertEquals(200, $response->status());
    }


}
