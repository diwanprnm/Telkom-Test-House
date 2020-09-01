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

    public function testIndexWithSearch()
    {
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=1');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=2');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=3');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=4');
        $this->assertResponseStatus(200)->see('Beranda');
    }

    public function testDownloadUserManual()
    {
		$this->actingAs(User::find(1))->call('GET',"adm_dashboard_autocomplete/query");
        $this->assertResponseStatus(200);
    }

    public function testAutocomplete()
    {
		$response = $this->actingAs(User::find('1'))->call('GET',"adm_dashboard_autocomplete/query");
        $this->assertEquals(200, $response->status());
    }
}
