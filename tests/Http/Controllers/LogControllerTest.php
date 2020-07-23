<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit_company()
	{ 
	   $response = $this->call('GET', 'admin/log');  
       $this->assertEquals(302, $response->status());
	}
}
