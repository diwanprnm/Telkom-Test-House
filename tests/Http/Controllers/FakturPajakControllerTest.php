<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class FakturPajakControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function delete_soon(){
        $this->assertTrue(true);
    }

    // public function testIndexWitoutDataFound()
    // {
    //     //truncate data
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     App\ExaminationAttach::truncate();
    //     App\Examination::truncate();
    //     App\STELSalesDetail::truncate();
    //     App\STEL::truncate();
    //     App\STELSalesAttach::truncate();
    //     App\STELSales::truncate();
    //     App\Logs::truncate();
    //     App\User::where('id','!=', '1')->delete();
    //     App\Company::where('id','!=', '1')->delete();
    //     App\Device::truncate();
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/fakturpajak');

    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Rekap Kuitansi dan Faktur Pajak</h1>')
    //         ->see('Data not found')
    //     ;

    // }

    // public function testIndexWithSearch()
    // {
    //     //SPB
    //     $examinationAttach = factory(App\ExaminationAttach::class)->create(['name' => 'Kuitansi']);
    //     factory(App\ExaminationAttach::class)->create(['examination_id'=> $examinationAttach->examination_id,'name' => 'Faktur Pajak',]);
    //     factory(App\ExaminationAttach::class)->create(['examination_id'=> $examinationAttach->examination_id,'name' => 'Pembayaran','tgl' => '2020-01-01',]);
    //     $examination = App\Examination::find($examinationAttach->examination_id);
    //     $deviceName = App\Device::find($examination->device_id)->name;

    //     //STEL
    //     $stelsSalesDetail =  factory(App\STELSalesDetail::class)->create();
    //     $stelsSales = App\STELSales::find($stelsSalesDetail->stels_sales_id);
    //     $stelsSalesAttachment = factory(App\STELSalesAttach::class)->create(['stel_sales_id' => $stelsSales->id]);

    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET',"admin/fakturpajak?search=$deviceName");

    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Rekap Kuitansi dan Faktur Pajak</h1>')
    //         ->see('SPB')
    //         ->see($deviceName)
    //     ;
    // }
}
