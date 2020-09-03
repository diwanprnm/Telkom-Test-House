<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class DevClientControllerTest extends TestCase
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

    public function testIndex()
    {
        $this->actingAs(User::find('1'))->call('GET', 'Devclient');  
        $this->assertResponseStatus(200)->see('Certified Device');
    }

    public function testIndexWithSearch()
    {
        $this->actingAs(User::find('1'))->call('GET', 'Devclient?search=device');  
        $this->assertResponseStatus(200)->see('Certified Device');
    }

    public function testAutocomplete()
    {
        $this->actingAs(User::find('1'))->call('GET', 'dev_client_autocomplete/query');
        $this->assertResponseStatus(200);
    }


}
