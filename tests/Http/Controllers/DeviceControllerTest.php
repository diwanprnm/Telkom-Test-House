<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
class DeviceControllerTest extends TestCase
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
        $this->actingAs(User::find('1'))->call('GET', 'admin/device');  
        $this->assertResponseStatus(200)->see('Perangkat Lulus Uji');
    }

    public function testtestIndexWithSearch()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/device?search=cari&after_date=2020-08-05&before_date=2020-08-20&category=aktif'); 
        $this->assertResponseStatus(200)->see('Perangkat Lulus Uji');
    }

    public function testIndexCaseCategoryAktif1()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/device?category=aktif1');  
        $this->assertResponseStatus(200)->see('Perangkat Lulus Uji');
    }

    public function testIndexCaseCategoryDefault()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/device?category=default');  
        $this->assertResponseStatus(200)->see('Perangkat Lulus Uji');
    }


    public function testExcelWithFilter()
    {
        $deviceAktif = factory(App\Device::class)->create(['valid_thru' => '2100-01-01 00:00:00']);
        $deviceNonAktif = factory(App\Device::class)->create(['valid_thru' => '2020-01-01 00:00:00']);
        factory(App\Examination::class)->create(['device_id' => $deviceAktif->id]);
        factory(App\Examination::class)->create(['device_id'=> $deviceNonAktif->id]);

        $this->actingAs(User::find('1'))->call('GET', 'admin/device');  
        $response = $this->actingAs(User::find('1'))->call('get','device/excel');

        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Perangkat Lulus Uji.xlsx"');
    }

    public function testEdit()
    {
        $device = App\Device::latest()->first();
        $this->actingAs(User::find('1'))->call('GET', 'admin/device/'.$device->id.'/edit');  
        $this->assertResponseStatus(200)->see($device->name);
    }

    public function testUpdate()
    { 
        $device = App\Device::latest()->first();
        $this->actingAs(User::find('1'))->call('PUT', 'admin/device/'.$device->id,  [ 
            'name' => str_random(10),
            'mark' => str_random(10),
            'capacity' => mt_rand(0,10),
            'manufactured_by' => str_random(10),
            'serial_number' =>mt_rand(0,10000),
            'model' => str_random(10),
            'test_reference' => str_random(10),
            'cert_number' => mt_rand(0,10000),
            'valid_from' => str_random(10),
            'valid_thru' => '2100-01-01',
        ]);   
        $this->assertRedirectedTo('admin/device', ['message' => 'Perangkat Lulus Uji successfully updated']);
        $this->disableExceptionHandling();
    }

    public function testShow()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/device/id');  
        $this->assertResponseStatus(200);
    }
}
