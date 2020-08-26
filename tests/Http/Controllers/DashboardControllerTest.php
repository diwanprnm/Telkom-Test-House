<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class DashboardControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    public function testIndex()
    {
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/');

        $this->assertResponseStatus(200);
    }

    public function testIndexWithSearch()
    {
        $company = App\Company::latest()->first();
        $device = App\Device::latest()->first();
        
        //Visit as Admin
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin?search=cari&type=1&status=2');

        //check search log in db
        $this->seeInDatabase('logs', [
            'page' => 'DASHBOARD',
            'data' => '{"search":"cari"}']);

        //Status sukses dan judul FEEDBACK DAN COMPLAINT
        $this->assertResponseStatus(200);
    }

    public function testAutocomplete()
    {
        $user = User::find('1'); 
		$response = $this->actingAs($user)->call('GET',"adm_dashboard_autocomplete/query");
        $this->assertEquals(200, $response->status());
    }
}
