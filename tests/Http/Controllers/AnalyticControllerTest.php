<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnalyticControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp()
    {
        parent::setUp();
    }

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    // public function test_index()
	// { 
	//    $response = $this->call('GET', 'admin/analytic');  
    //    $this->assertEquals(200, $response->status());
	// }
}
