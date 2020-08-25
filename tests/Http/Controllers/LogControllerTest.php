<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Log;
use App\User;
use App\Company;

class LogControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testIndex()
    { 
        $response = $this->call('GET', 'admin/log');  
        $this->assertEquals(200, $response->status());
    }
    
    public function testSearch()
    { 
        $admin = User::find('1');
        $response = $this->actingAs($admin)->call('GET', 'admin/log?search=test&after_date=&before_date=&username=&action='); 
        $this->seeInDatabase('logs', [
            'page' => 'LOG',
            'data' => '{"search":"test"}'
        ]); 
        $this->assertEquals(200, $response->status());
    }
    
    // public function testExcelWithFilter()
    // {
    //     // create and get data
    //     $equipment = factory(App\Equipment::class)->create();
    //     factory(App\EquipmentHistory::class)->create([
    //         'examination_id' => $equipment->examination_id,
    //     ]);
    //     $examination = App\Examination::find($equipment->examination_id);
    //     $company = App\Company::find($examination->company_id);

    //     //Make request as Admin
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"log/excel?search=$equipment->name&before_date=2100-01-01&after_date=2020-01-01&nogudang=$equipment->name&type=$examination->examination_type_id&company=$company->company_name&lab=$examination->examination_lab_id");

    //     //redirect, go to admin/nogudang, and message "Nomor Gudang"
    //     $this->assertResponseStatus(200);
        

    //   /*  //truncate table
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     DB::table('equipment_histories')->truncate();
    //     DB::table('equipments')->truncate();
    //     DB::table('examinations')->truncate();
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');*/
    // }
}
