<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class IncomeControllerTest extends TestCase
{

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function testIndexWithFilter()
    {
        //create data
        $income = factory(App\Income::class)->create();
        $company = App\Company::find($income->company_id);

        //visit with filter
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/income?type=$income->examination_type_id&status=6&lab=$income->examination_lab_id&before_date=2100-01-01&after_date=2020-01-01&search=$company->name");

        //Status sukses dan judul "REKAP PENGUJIAN PERANGKAT" dan terdapat nomer referensi di view
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">REKAP PENGUJIAN PERANGKAT</h1>');
    }

    public function testIndexAsAdmin()
    {
        //make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/income');

        //Status sukses dan judul "REKAP PENGUJIAN PERANGKAT"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">REKAP PENGUJIAN PERANGKAT</h1>');
    }

    public function testExcelWithFilter()
    {
        //create data
        $income = App\Income::latest()->first();
        $company = App\Company::find($income->company_id);

        //visit with filter
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET',"income/excel?type=$income->examination_type_id&status=6&lab=$income->examination_lab_id&before_date=2100-01-01&after_date=2020-01-01&search=$company->name");
        
        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Pendapatan.xlsx"');

        //delete residual data
        App\Income::truncate();
        App\Examination::latest()->first()->delete();
        App\ExaminationLab::latest()->first()->delete();
        App\Device::latest()->first()->delete();
        App\Company::latest()->first()->delete();
    }


    public function testCreate()
    {
        //make request with session
        $admin = User::find(1);
        $response = $this
            ->actingAs($admin)
            ->withSession([
                'key_kode_for_kuitansi' => 'this is key kode for testing',
                'key_from_for_kuitansi' => 'this is key from kuitansi',
                'key_price_for_kuitansi' => 5000000,
                'key_for_for_kuitansi' => 'this is key for kuitansi'
            ])
            ->get('admin/kuitansi/create')
        ;

        //check respone match request session
        $response->assertResponseStatus(200)
            ->see('this is key kode for testing')
            ->see('this is key from kuitansi')
        ;
    }

    public function testStore()
    {
        //create request
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST','admin/kuitansi',[
            'number' => 123456,
            'from' => 'Income testing',
            'price' => 5000000,
            'for' => 'Biaya testing',
            'kuitansi_date' => Carbon\Carbon::now()
        ]);
        
        //see if data presisted
        $this->seeInDatabase('kuitansi', ['number' => 123456,]);

        //check response
        $this->assertRedirectedTo('admin/kuitansi', ['message' => 'Kuitansi successfully created']);
    }

    public function testStoreWithAlreadyExistNumber()
    {
        //create request
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST','admin/kuitansi',[
            'number' => 123456,
            'from' => 'Income testing',
            'price' => 5000000,
            'for' => 'Biaya pengujian testing',
            'kuitansi_date' => Carbon\Carbon::now()
        ]);
        
        //check response
        $this->assertRedirectedTo('admin/kuitansi/create', ['error' => '"Nomor Kuitansi" is already exist']);
    }

    public function testKuitansi()
    {
        //Create Request
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/kuitansi?search=123&before_date=2100-01-01&after_date=2020-01-01&type=spb');

        //Status sukses dan judul "KUITANSI"
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">KUITANSI</h1>')
        ->see('123456');

        //delete residual data
        App\Kuitansi::truncate();
    }

    public function testGenerateKuitansiManual()
    {
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST','admin/kuitansi/generateKuitansi');

        //status ok, and response is text containing "DDS-73" in it
        $this->assertResponseStatus(200)
            ->see('/DDS-73/');
    }

    public function testGenerateKuitansiManualCaseAlreadyExist()
    {
        factory(App\Kuitansi::class)->create(['number' => '001/DDS-73/'.date('Y')]);
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST','admin/kuitansi/generateKuitansi');

        //status ok, and response is text containing "DDS-73" in it
        $this->assertResponseStatus(200)
            ->see('002/DDS-73/'.date('Y'));
    }

    public function testCetakKuitansi()
    {
        $kuitansi = factory(App\Kuitansi::class)->create();

        $this->actingAs(User::find(1))->call('GET',"/cetakKuitansi/$kuitansi->id");
        $this->assertResponseStatus(200);
    }


    public function testExcelImproveCoverage()
    {
        //create data
        $income = factory(App\Income::class)->create();
        $company = App\Company::find($income->company_id);

        //visit with filter
        $admin = User::find(1);
        $response = $this->actingAs($admin)->call('GET',"income/excel?type=$income->examination_type_id&status=6&lab=$income->examination_lab_id&before_date=2100-01-01&after_date=2020-01-01&search=$company->name");
        
        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Pendapatan.xlsx"');
    }

    public function testAutoComplete()
    {
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/adm_inc_autocomplete/query');

        //status ok,
        $this->assertResponseStatus(200);

        App\Income::truncate();
        App\Examination::truncate();
        App\Device::truncate();
        App\Company::where('id','!=', '1')->delete();
        App\Kuitansi::truncate();
    }


    public function testKuitansiWithoutDataFound()
    {
        //Create Request
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/kuitansi');

        //Status ok, judul "KUITANSI", dan pesan Data not found
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">KUITANSI</h1>')
        ->see('Data not found');
    }

    
}
