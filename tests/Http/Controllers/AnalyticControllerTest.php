<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnalyticControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function delete_soon(){
        $this->assertTrue(true);
    }

    // public function test_index()
	// { 
	//    $response = $this->call('GET', 'admin/analytic');  
    //    $this->assertEquals(200, $response->status());
	// }
}
