<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;


class TopDashboardControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
   /* public function test_visit()
    { 
       $admin = User::find('1');
       $response = $this->actingAs($admin)->call('GET', 'admin/topdashboard');
       dd($response);
       $this->assertEquals(200, $response->status());
    }*/
}
