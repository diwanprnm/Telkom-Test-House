<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class NoGudangControllerTest extends TestCase
{

    public function testIndexWithotDataFound()
    {
        // truncate data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('equipment_histories')->truncate();
        DB::table('equipments')->truncate();
        DB::table('examinations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/nogudang");

        //redirect, go to admin/nogudang, and message "Nomor Gudang"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Nomor Gudang</h1>')
            ->see('Data Not Found')
        ;
    }

    public function testIndexWithFilter()
    {
        // create and get data
        $equipment = factory(App\Equipment::class)->create();
        factory(App\EquipmentHistory::class)->create([
            'examination_id' => $equipment->examination_id,
        ]);
        $examination = App\Examination::find($equipment->examination_id);
        $company = App\Company::find($examination->company_id);

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/nogudang?search=$equipment->name&before_date=2100-01-01&after_date=2020-01-01&nogudang=$equipment->name&type=$examination->examination_type_id&company=$company->company_name&lab=$examination->examination_lab_id");

        //redirect, go to admin/nogudang, and message "Nomor Gudang"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Nomor Gudang</h1>')
            ->see($equipment->name)
        ;
    }

    public function testExcelWithFilter()
    {
        // create and get data
        $equipment = factory(App\Equipment::class)->create();
        factory(App\EquipmentHistory::class)->create([
            'examination_id' => $equipment->examination_id,
        ]);
        $examination = App\Examination::find($equipment->examination_id);
        $company = App\Company::find($examination->company_id);

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $response = $this->actingAs($admin)->call('GET',"nogudang/excel?search=$equipment->name&before_date=2100-01-01&after_date=2020-01-01&nogudang=$equipment->name&type=$examination->examination_type_id&company=$company->company_name&lab=$examination->examination_lab_id");

        //redirect, go to admin/nogudang, and message "Nomor Gudang"
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'Application/Spreadsheet');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=Data Gudang.xlsx');

        //truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('equipment_histories')->truncate();
        DB::table('equipments')->truncate();
        DB::table('examinations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    


}
