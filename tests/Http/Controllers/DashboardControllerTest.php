<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;

use App\User;

class DashboardControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testIndexWithSearch()
    {
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=1');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=2');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=3');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=4');
        $this->assertResponseStatus(200)->see('Beranda');
        $this->actingAs(User::find(1))->call('GET','admin?search=cari&type=1&status=default');
        $this->assertResponseStatus(200)->see('Beranda');
    }

    public function testDownloadUserManual()
    {
        $fileName = 'User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Admin].pdf';
        $isFileExist = Storage::disk('minio')->exists("usman/$fileName");

        if(!$isFileExist){
            $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
            Storage::disk('minio')->put("usman/$fileName", $file);
        }

		$response = $this->actingAs(User::find(1))->call('GET','admin/downloadUsman');
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        if(!$isFileExist){
            Storage::disk('minio')->delete("usman/$fileName");
        }

    }

    public function testAutocomplete()
    {
		$this->actingAs(User::find('1'))->call('GET',"adm_dashboard_autocomplete/query");
        $this->assertResponseStatus(200);
    }
}
