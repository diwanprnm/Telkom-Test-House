<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CalibrationChargeClientControllerTest extends TestCase
{
    public function testIndexWithSearch()
    {
        $this->call('GET', 'CalibrationChargeclient?search=cari'); 
        $this->assertResponseStatus(200)->see('Tarif');
    }

    public function testIndex()
    {
        $this->call('GET', 'CalibrationChargeclient'); 
        $this->assertResponseStatus(200)->see('Tarif');
    }
}
