<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TopDashboardControllerTest extends TestCase
{
   // use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function delete_soon(){
        $this->assertTrue(true);
    }

    // public function test_visit_company()
	// { 
	//    $response = $this->call('GET', 'admin/topdashboard');  
    //    $this->assertEquals(200, $response->status());
	// }
	// public function test_search_company()
	// { 
	//    $response = $this->call('GET', 'admin/topdashboard?search=asda&is_active=&after_date=&before_date=');  
    //    $this->assertEquals(200, $response->status());
	// }
}