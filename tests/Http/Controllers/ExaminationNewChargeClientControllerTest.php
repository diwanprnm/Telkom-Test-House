<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExaminationNewChargeClientControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $this->call('GET','NewChargeclient');
        $this->assertResponseStatus(200)->see(factory(App\NewExaminationChargeDetail::class)->create()->duration);
    }

    public function testFilter()
    {
        $this->call('POST','filterNewCharge', [''] );
        $this->assertResponseStatus(200);
    }

    public function testFilterWithCategory()
    {
        $this->call('POST','filterNewCharge', ['category' => 'Lab CPE'] );
        $this->assertResponseStatus(200);
    }

    public function testAutocomplete()
    {
        $this->call('GET','new_charge_client_autocomplete/query');
        $this->assertResponseStatus(200);
    }
}