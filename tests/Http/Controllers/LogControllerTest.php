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

    public function testIndexNotfound()
    { 
        $this->actingAs(User::find('1'))->call('GET', 'admin/log?search=Notfound&before_date=2100-01-01&after_date=2100-01-01&username=no&action=Action&sort_by=logs.created_at&sort_type=desc'); 
        $this->assertResponseStatus(200)->see('Log');
    }
    
    public function testLogIndex()
    { 
        $this->actingAs(User::find('1'))->call('GET', 'admin/log?search=cari&before_date=2100-01-01&after_date=2020-01-01&username=admin&action=Action&sort_by=logs.created_at&sort_type=desc'); 
        $this->assertResponseStatus(200)->see('Log');
    }

    public function testLogAdministratorIndex()
    { 
        $this->actingAs(User::find('1'))->call('GET', 'admin/log_administrator?search=cari&before_date=2100-01-01&after_date=2020-01-01&username=admin&action=Action&sort_by=logs_administrator.created_at&sort_type=desc'); 
        $this->assertResponseStatus(200)->see('Administrator Log');
    }

    public function testLogExcel()
    {
        $logs = factory(App\Logs::class)->create();

        $response = $this->actingAs(User::find('1'))->call('GET', "log/excel?search=$logs->action&before_date=2100-01-01&after_date=2020-01-01&username=admin&action=$logs->action&sort_by=logs.created_at&sort_type=desc"); 
        $this->assertResponseStatus(200);
        
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Aktivitas User.xlsx"');
    }

    public function testLogAdministratorExcel()
    {
        $LogsAdministrator = factory(App\LogsAdministrator::class)->create();

        $response = $this->actingAs(User::find('1'))->call('GET', "log_administrator/excel?search=$LogsAdministrator->action&before_date=2100-01-01&after_date=2020-01-01&username=admin&action=$LogsAdministrator->action&sort_by=logs_administrator.created_at&sort_type=desc"); 
        $this->assertResponseStatus(200);

        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Aktivitas Administrator.xlsx"');
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
