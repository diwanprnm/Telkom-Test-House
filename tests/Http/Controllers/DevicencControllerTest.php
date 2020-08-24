<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Device;
use App\Company;

class DevicencControllerTest extends TestCase
{
    
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function test_visit()
	 {  $admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/devicenc');  
        $this->assertEquals(200, $response->status());
     }
     
     public function test_search()
	 { 
        $company = App\Company::latest()->first();
        $device = App\Device::latest()->first();
        $admin = User::find('1');
        $response = $this->actingAs($admin)->call('GET', 'admin/devicenc?search='.$company->name.'&search2='.$company->name.'&tab=tab-1'); 
        $this->seeInDatabase('logs', [
            'page' => 'DEVICE',
            'data' => '{"search":"'.$company->name.'"}']); 
           
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

   /* public function testMoveData()
     {
        $device = App\Device::latest()->first();
        $admin = User::find('1');
        $response = $this->actingAs($admin)->call($device->status=-1);
        
        $this->assertEquals(200, $response->status());
        }*/
}
