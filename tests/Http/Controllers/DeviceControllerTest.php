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
    public function test_visit()
	 {  $admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/device');  
        $this->assertEquals(200, $response->status());
     }
     
     public function test_search()
	 { 
        $device = factory(App\Device::class)->create();
        $admin = User::find('1');
        $response = $this->actingAs($admin)->call('GET', 'admin/device?search='.$device->name.'&after_date=&before_date=&category='); 
        $this->seeInDatabase('logs', [
            'page' => 'DEVICE',
            'data' => '{"search":"'.$device->name.'"}']); 
           
		$this->assertEquals(200, $response->status());
		
     }
     

  /*  public function testExcelWithFilter()
     {
        //create data
        $company = App\Company::latest()->first();
        $user = User::find('1');
        $response = $this->actingAs($user)->call('get','devicenc/excel?search='.$company->name);
        $this->assertEquals(200, $response->status());
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Perangkat Tidak Lulus Uji.xlsx"');

        }*/

   
}
