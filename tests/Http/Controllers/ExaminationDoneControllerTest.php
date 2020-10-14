<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class ExaminationDoneControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $this->actingAs(User::find(1))->call('GET','admin/examinationdone?search=cari&type=1&company=company&device=device&before_date=2100-01-01&after_date=2020-01-01');
        $this->assertResponseStatus(200)->see('Pengujian Lulus');
    }

    public function testShow()
    {
        $examination = factory(App\Examination::class)->create();
        $this->actingAs(User::find(1))->call('GET',"admin/examinationdone/$examination->id");
        $this->assertResponseStatus(200)->see('Detail pengujian');
    }

    public function testEdit()
    {
        $examination = factory(App\Examination::class)->create();
        $this->actingAs(User::find(1))->call('GET',"admin/examinationdone/$examination->id/edit");
        $this->assertResponseStatus(200);
    }

    public function testExcel()
    {
        $company = factory(App\Company::class)->create(['siup_date' => '', 'qs_certificate_date' => '',]);
        $device = factory(App\Device::class)->create(['valid_from' => '', 'valid_thru' => '']);
        factory(App\Examination::class)->create(['company_id' => $company->id, 'device_id' => $device->id,  'spk_date' => '' ]);


        $this->actingAs(User::find(1))->call('GET','admin/examinationdone');
        $response = $this->actingAs(User::find(1))->call('GET','examinationdone/excel' );
        
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Pengujian Lulus.xlsx"');
    }

    public function testAutocomplete()
    {
        $this->actingAs(User::find(1))->call('GET','admin/adm_exam_done_autocomplete/query');
        $this->assertResponseStatus(200);
    }

    public function testCetakKepuasanKonsumen()
    {
        $examination = factory(App\Examination::class)->create();
        $this->actingAs(User::find(1))->call('GET',"cetakKepuasanKonsumen/$examination->id");
        $this->assertResponseStatus(200);
    }


    public function testCetakComplaint()
    {
        $examination = factory(App\Examination::class)->create();
        $this->actingAs(User::find(1))->call('GET',"cetakComplaint/$examination->id");
        $this->assertResponseStatus(200);
    }
}