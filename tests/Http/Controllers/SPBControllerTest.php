<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class SPBControllerTest extends TestCase
{

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function testIndexWitoutDataFound()
    {
        //truncate data
        //for mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        App\ExaminationAttach::truncate();
        App\Examination::truncate();
        App\Logs::truncate();
        App\User::where('id','!=', '1')->delete();
        App\Company::where('id','!=', '1')->delete();
        App\Device::truncate();
        //for mysql  DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -

        //make request
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET','admin/spb');

        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">SPB (Surat Pemberitahuan Biaya)</h1>')
            ->see('Data not found')
        ;

    }

    public function testIndexWithSearch()
    {
        //SPB
        $examinationAttach = factory(App\ExaminationAttach::class)->create(['name' => 'Kuitansi']);
        factory(App\ExaminationAttach::class)->create(['examination_id'=> $examinationAttach->examination_id,'name' => 'Faktur Pajak',]);
        factory(App\ExaminationAttach::class)->create(['examination_id'=> $examinationAttach->examination_id,'name' => 'Pembayaran','tgl' => '2020-01-01',]);
        $examination = App\Examination::find($examinationAttach->examination_id);
        $companyName = App\Company::find($examination->company_id)->name;

        //make request
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/spb?search=$companyName&before_date=2100-01-01&after_date=2020-01-01&spb=$examination->spb_number&type=$examination->examination_type_id&company=$companyName&payment_status=$examination->payment_status&sort_by=spb_number&sort_type=desc");

        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">SPB (Surat Pemberitahuan Biaya)</h1>')
            ->see($examination->spb_number)
            ->see($companyName)
        ;
    }


    public function testExcelWithFilter()
    {
        $examination = App\Examination::latest()->first();
        $companyName = App\Company::find($examination->company_id)->name;

        //visit with filter
        $admin = User::where('id', '=', '1')->first();
        $response = $this->actingAs($admin)->call('GET',"spb/excel?search=$companyName&before_date=2100-01-01&after_date=2020-01-01&spb=$examination->spb_number&type=$examination->examination_type_id&company=$companyName&payment_status=$examination->payment_status&sort_by=spb_number&sort_type=desc");

        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data SPB.xlsx"');

        //delete residual data
        //truncate data
        //for mysql  DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        App\ExaminationAttach::truncate();
        App\Examination::truncate();
        App\Logs::truncate();
        App\User::where('id','!=', '1')->delete();
        App\Company::where('id','!=', '1')->delete();
        App\Device::truncate();
        //for mysql  DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }
}
