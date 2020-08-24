<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

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
	 { $admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/devicenc');  
        $this->assertEquals(200, $response->status());
     }
     
     public function test_search()
	 { 
		$response = $this->call('GET', 'admin/devicenc?search=a&search2=&tab=tab-1&after_date=&before_date=');  
		
		$this->assertEquals(302, $response->status());
		
	 }

    /* public function testExcelWithFilter()
     {
        //create data
        $income = App\Income::latest()->first();
        $company = App\Company::find($income->company_id);

        visit with filter
        $admin = User::where('id', '=', '1')->first();
        $response = $this->actingAs($admin)->call('GET',"income/excel?type=$income->examination_type_id&status=6&lab=$income->examination_lab_id&before_date=2100-01-01&after_date=2020-01-01&search=$company->name");
     }*/
}
