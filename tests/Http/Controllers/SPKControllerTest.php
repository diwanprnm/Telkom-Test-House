<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class SPKControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    // public function testIndexWitoutDataFound()
    // {
    //     // //truncate data
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     App\TbMSPK::truncate();
    //     App\ExaminationLab::truncate();
    //     App\Examination::truncate();
    //     App\Logs::truncate();
    //     App\User::where('id','!=', '1')->delete();
    //     App\Company::where('id','!=', '1')->delete();
    //     App\Device::truncate();
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET','admin/spk');

    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">SPK (Surat Perintah Kerja)</h1>')
    //         ->see('Data not found')
    //     ;
    // }

    // public function testIndexWithSearch()
    // {
    //     //Create TbMSPK
    //     $examination = factory(App\Examination::class)->create();
    //     $device = App\Device::find($examination->device_id);
    //     $company = App\Company::find($examination->company_id);
    //     $examinationType = App\ExaminationType::find($examination->examination_type_id);
    //     $examinationLab = App\ExaminationLab::find($examination->examination_lab_id);
    //     $tbmspk = factory(App\TbMSPK::class)->create([
    //         'ID' => $examination->id,
    //         'LAB_CODE' => $examinationLab->lab_code,
    //         'TESTING_TYPE' => $examinationType->name,
    //         'DEVICE_NAME' => $device->name,
    //         'COMPANY_NAME' => $company->name,
    //     ]);
    //     factory(App\TbHSPK::class,3)->create([
    //         'ID' => $examination->id,
    //         'SPK_NUMBER' => $tbmspk->SPK_NUMBER
    //     ]);
    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/spk?search=$tbmspk->SPK_NUMBER&before_date=2100-01-01&after_date=2020-01-01&spk=$tbmspk->SPK_NUMBER&type=$examinationType->name&company=$company->name&lab=$examinationLab->name&sort_by=spk_date&sort_type=desc");
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">SPK (Surat Perintah Kerja)</h1>')
    //         ->see($tbmspk->SPK_NUMBER)
    //         ->see($company->name)
    //     ;
    // }

    // public function testShow()
    // {
    //     //get data
    //     $examination = App\Examination::latest()->first();
    //     $tbhspk = App\TbHSPK::where('ID', $examination->id)->first();

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $this->actingAs($admin)->call('GET',"admin/spk/$tbhspk->ID");

    //     //Status sukses dan keluar data yang diinginkan
    //     $this->assertResponseStatus(200)
    //         ->see($tbhspk->SPK_NUMBER)
    //         ->see($tbhspk->ACTION)
    //         ->see($tbhspk->REMARK)
    //     ;
    // }

    // public function testExcel()
    // {
    //     //get data
    //     $examination = App\Examination::latest()->first();
    //     $tbmspk = App\TbMSPK::where('ID', $examination->id)->first();
    //     $company = App\Company::find($examination->company_id);
    //     $examinationType = App\ExaminationType::find($examination->examination_type_id);
    //     $examinationLab = App\ExaminationLab::find($examination->examination_lab_id);

    //     //make request
    //     $admin = User::where('id', '=', '1')->first();
    //     $response = $this->actingAs($admin)->call('GET',"spk/excel?search=$tbmspk->SPK_NUMBER&before_date=2100-01-01&after_date=2020-01-01&spk=$tbmspk->SPK_NUMBER&type=$examinationType->name&company=$company->name&lab=$examinationLab->name&sort_by=spk_date&sort_type=desc");
        
    //     //Status Ok, Header data download file excel
    //     $this->assertResponseStatus(200);
    //     $this->assertTrue($response->headers->get('content-type') == 'Application/Spreadsheet');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename=Data SPK.xlsx');
    // }

}
