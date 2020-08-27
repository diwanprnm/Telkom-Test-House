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
        $response = $this->actingAs($admin)->call('GET', 'admin/device?search='.$device->name.'&after_date=2020-08-05&before_date=2020-08-20&category=aktif'); 
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

   public function testEdit()
   {
    $device = factory(App\Device::class)->create();
    $admin = User::find('1');
    $response = $this->actingAs($admin)->call('GET', 'admin/device/'.$device->id.'edit');  
    $this->assertEquals(200, $response->status());
   }

    public function test_update()
	 { 
        $device = factory(App\Device::class)->create();
	 	$user =User::find('1');
		 
	 	$response =  $this->actingAs($user)->call('GET', 'admin/device/'.$device->id.'/edit', 
	 	[ 
             'name' => str_random(10),
             'mark' => str_random(10),
             'capacity' => mt_rand(0,10),
             'manufactured_by' => str_random(10),
             'serial_number' =>mt_rand(0,10000),
             'model' => str_random(10),
             'test_reference' => str_random(10),
             'cert_number' => mt_rand(0,10000),
             'valid_from' => str_random(10),
             'valid_thru' => str_random(10),
	    ]);   
         $this->assertEquals(200, $response->status());
	   
	 }
}
