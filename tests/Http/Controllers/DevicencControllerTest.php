<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Device;
use App\Company;

class DevicencControllerTest extends TestCase
{

    public function testIndex()
    {
        $this->actingAs(User::find('1'))->call('GET', 'admin/devicenc?search=dataNotFound&search2=dataNotFound&tab=tab-1'); 
        $this->assertResponseStatus(200)->see('Perangkat Tidak Lulus Uji');
    }


    public function testExcelWithFilter()
    {
        //create data
        $deviceLayakUjiUlang = factory(App\Device::class)->create(['status' => 1]);
        factory(App\Examination::class)->create(['qa_passed' => -1, 'examination_type_id' => 1,'device_id' => $deviceLayakUjiUlang->id]);

        $deviceBelumLayakUjiUlang = factory(App\Device::class)->create(['status' => -1]);
        factory(App\Examination::class)->create(['qa_passed' => -1, 'examination_type_id' => 1,'device_id' => $deviceBelumLayakUjiUlang->id]);
        //create response
        $response = $this->actingAs(User::find('1'))->call('get',"devicenc/excel?tab=tab-1");
        //assert response
        $this->assertEquals(200, $response->status());
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Perangkat Tidak Lulus Uji.xlsx"');
    }

    public function testExcelTab1()
    {
        $deviceLayakUjiUlang = factory(App\Device::class)->create(['status' => 1]);
        factory(App\Examination::class)->create(['qa_passed' => -1, 'examination_type_id' => 1,'device_id' => $deviceLayakUjiUlang->id]);

        $response = $this->actingAs(User::find('1'))->call('get',"devicenc/excel?search=$deviceLayakUjiUlang->name&tab=tab-1");
        $this->assertEquals(200, $response->status());
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Perangkat Tidak Lulus Uji.xlsx"');
    }

    public function testExcelTab2()
    {
        $deviceBelumLayakUjiUlang = factory(App\Device::class)->create(['status' => -1]);
        factory(App\Examination::class)->create(['qa_passed' => -1, 'examination_type_id' => 1,'device_id' => $deviceBelumLayakUjiUlang->id]);

        $response = $this->actingAs(User::find('1'))->call('get',"devicenc/excel?search=$deviceBelumLayakUjiUlang->name&tab=tab-2");
        $this->assertEquals(200, $response->status());
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Perangkat Tidak Lulus Uji.xlsx"');
    }

    public function testMoveData()
    {
        $device = factory(App\Device::class)->create();
        $response = $this->actingAs(User::find('1'))->call('GET', "admin/devicenc/$device->id/test-reason/moveData", [
            'status'=>1
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testMoveDataNotFound()
    {
        $response = $this->actingAs(User::find('1'))->call('GET', "admin/devicenc/dataNotFound/test-reason/moveData", [
            'status'=>1
        ]);
        $this->assertEquals(302, $response->status());
    }
}
