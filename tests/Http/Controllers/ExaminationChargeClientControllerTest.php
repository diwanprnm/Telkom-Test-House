<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExaminationChargeClientControllerTest extends TestCase
{
    public function testIndexWithSearch()
    {
        $this->call('GET', 'Chargeclient?search=cari&category=Lab+CPE'); 
        $this->assertResponseStatus(200)->see('Tarif');
    }

    public function testIndex()
    {
        $this->call('GET', 'Chargeclient'); 
        $this->assertResponseStatus(200)->see('Tarif');
    }

    public function testFilterWithCategory()
    {
        $this->call('POST', 'filterCharge', ['category' => 'Lab CPE']);
        $this->assertResponseStatus(200);
    }

    public function testFilter()
    {
        $this->call('POST', 'filterCharge');
        $this->assertResponseStatus(200);
    }

    public function testAutocomplete()
    {
        $this->call('GET', 'charge_client_autocomplete/query');
        $this->assertResponseStatus(200);
    }
}
