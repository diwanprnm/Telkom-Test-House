<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class FeedbackComplaintControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function testIndexAsNonAdmin()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user)->call('GET','admin/feedbackncomplaint');
        //status sukses, tapi ada bacaaan you dont have permission
        $this->assertResponseStatus(200)
            ->see("Unautorizhed. You do not have permission to access this page.");
    }

    public function testIndexAndPresistData()
    {
        factory(App\Questioner::class)->create();

        //Visit as Admin
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedbackncomplaint');

        //Status sukses dan judul FEEDBACK DAN COMPLAINT
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">FEEDBACK DAN COMPLAINT</h1>');
    }

    public function testIndexWithSearch()
    {
        $company = App\Company::latest()->first();
        $device = App\Device::latest()->first();
        
        //Visit as Admin
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedbackncomplaint?search='.$device->name);

        //check search log in db
        $this->seeInDatabase('logs', [
            'page' => 'Rekap Feedback dan Complaint',
            'data' => '{"search":"'.$device->name.'"}']);

        //Status sukses dan judul FEEDBACK DAN COMPLAINT
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">FEEDBACK DAN COMPLAINT</h1>')
            ->see($device->name)
            ->see($device->capacity)
            ->see($company->name);
    }

    public function testExcel()
    {
        $device = App\Device::latest()->first();
        $user = User::where('id', '=', '1')->first();
        $response = $this->actingAs($user)->call('get','feedbackncomplaint/excel?search='.$device->name);
        //Status Ok, Header data download file excel
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Feedback.xlsx"');

        //Delete Residual data in database
        App\Questioner::truncate();
        App\Examination::latest()->first()->delete();
        App\ExaminationLab::latest()->first()->delete();
        App\Company::latest()->first()->delete();
        App\Device::latest()->first()->delete();
    }

    public function testIndexWithoutDataFound()
    {
        //Visit as Admin
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/feedbackncomplaint');

        //Status sukses dan judul FEEDBACK DAN COMPLAINT
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">FEEDBACK DAN COMPLAINT</h1>')
            ->see('Data not found');
    }

    public function testExcelWithoutDataFound()
    {
        //Visit as Admin
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','feedbackncomplaint/excel');

        //Status sukses dan judul FEEDBACK DAN COMPLAINT
        $this->assertRedirectedTo('/admin/feedbackncomplaint', ['error' => 'Cannot download file - Data not found']);
    }
}
