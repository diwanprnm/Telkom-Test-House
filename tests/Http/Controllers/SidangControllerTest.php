<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Device;
use App\User;
use App\Sidang;
use App\Sidang_detail;
use App\Examination;

class SidangControllerTest extends TestCase
{
    public function testIndexAsNonAdmin()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user)->call('GET','admin/sidang');
        //status sukses, tapi ada bacaaan you dont have permission
        $this->assertResponseStatus(200)
        ->see("Unautorizhed. You do not have permission to access this page.");
    }
    

    public function testIndexAsAdmin()
    {
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang');
        //Status sukses dan judul Sidang QA
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Sidang QA</h1>');
    }


    public function testIndexWithSearch()
    {
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang?before=2022-10-06&after=2022-10-12?search2=test');
        //Status sukses dan judul Sidang QA
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">Sidang QA</h1>');
    }

    public function testIndexAsAdminSearch2()
    {
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang?search2=Test2&search3=Test2&tab=tab-perangkat');
        //Status sukses dan judul Sidang QA
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Sidang QA</h1>');
    }


    public function testIndexWithoutDataFound()
    {
        Sidang::truncate();
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang?before=2888-10-06&after=2888-10-12');
        //Status sukses, judul Sidang QA, dan Pemberitahuan "Data not found"
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">Sidang QA</h1>')
        ->see('Data not found');
    }

    public function testGetDataDaftarSidangQA()
    {
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang?type=1');
        //Status sukses, judul Sidang QA, dan Pemberitahuan "Data not found"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Sidang QA</h1>')
            ->see('Data not found');
    }

    public function testCreate()
    {
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang/create');
        //Status sukses, judul Buat Draft Sidang QA
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Buat Draft Sidang QA</h1>');
    }


    public function testStore()
    {
        App\Device::truncate();
        App\Company::truncate();
        Examination::truncate();
        App\ExaminationType::truncate();
        Sidang_detail::truncate();


        $device = factory(App\Device::class)->create([
            'name' => 'Test Sidang',
            'mark' => 'sidang test 1',
            'capacity' => '',
            'manufactured_by' => '',
            'serial_number' => '',
            'model' => '',
            'test_reference' => '',
            'valid_from' => null,
            'valid_thru' => null,
        ]);

        $company = factory(App\Company::class)->create([
            'name' => 'PT Sidang Test',
            'address' => '',
            'city' => null,
            'email' => '',
            'postal_code' => null,
            'phone_number' => null,
            'fax' => null,
            'npwp_number' => null,
            'siup_number' => null,
            'siup_date' => null,
            'qs_certificate_number' => null,
            'qs_certificate_date' => null,
        ]);

        $user = factory(App\User::class)->create([
            'role_id' => 2,
            'company_id' => $company->id,
            'name' => 'Sidang User',
            'address' => null,
            'phone_number' => null,
            'fax' => null,
        ]);

        $examinationType = factory(App\ExaminationType::class)->create([
            'name' => '',
            'description' => null,
        ]);

        factory(App\Examination::class)->create([
            'registration_status' => '-1',
            'function_status' => '-1',
            'contract_status' => '-1',
            'spb_status' => '-1',
            'payment_status' => '-1',
            'spk_status' => '-1',
            'examination_status'  => '-1',
            'resume_status' => '1',
            'qa_status'  => '1',
            'certificate_status'  => '1',
        ]);

        factory(App\Examination::class)->create([
            'examination_type_id' => $examinationType->id,
            'company_id' => $company->id,
            'device_id' => $device->id,
            'created_by' => $user->id,
            'registration_status' => '0',
            'function_status' => '0',
            'contract_status' => '0',
            'spb_status' => '0',
            'payment_status' => '0',
            'spk_status' => '0',
            'examination_status'  => '0',
            'resume_status' => '0',
            'qa_status'  => '0',
            'certificate_status'  => '0',
            'spk_date' => '',
        ]);

        $examination = factory(App\Examination::class)->create();

        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'POST',
            'admin/sidang',
            [
                // 'sidang_id' => $sidang['id'],
                'created_by' => 1,
                'date' => '2022-10-10',
                'audience' => 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (Manager URel TTH), I Gede Astawa (Senior Manager TTH).',
                'hidden_tab' => 'tab-draft',
                'chk-draft' => array($examination->id),
                'search2' => 'test'
            ]
        );

        $this->assertEquals(302, $response->status());
    }


    public function testStoreDraft()
    {
        $examination =  Examination::latest()->first();

        $admin = User::where('id', '=', '1')->first();
        $sidang = Sidang::latest()->first();
        $response =  $this->actingAs($admin)->call(
            'POST',
            'admin/sidang',
            [
                'sidang_id' => $sidang['id'],
                'created_by' => 1,
                'date' => '2022-10-10',
                'audience' => 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (Manager URel TTH), I Gede Astawa (Senior Manager TTH).',
                // 'hidden_tab' => 'tab-draft',
                'hidden_tab' => 'tab-draft',
                'chk-draft' => array($examination->id),
                'search2' => 'test'
            ]
        );
        $this->assertEquals(302, $response->status());
    }

    public function testStorePending()
    {
        $examination =  Examination::latest()->first();

        $admin = User::where('id', '=', '1')->first();
        $sidang = Sidang::latest()->first();
        $response =  $this->actingAs($admin)->call(
            'POST',
            'admin/sidang',
            [
                'sidang_id' => $sidang['id'],
                'created_by' => 1,
                'date' => '2022-10-10',
                'audience' => 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (Manager URel TTH), I Gede Astawa (Senior Manager TTH).',
                'hidden_tab' => 'tab-pending',
                'chk-pending' => array($examination->id),
                'search2' => 'test'
            ]
        );
        $this->assertEquals(302, $response->status());
    }

    public function testExcel()
    {
        $admin = User::where('id', '=', '1')->first();
        $sidang = Sidang::latest()->first();
        $response = $this->actingAs($admin)->call('GET', "admin/sidang/" . $sidang['id'] . '/excel');
        //Status sukses dan judul Sidang QA
        $this->assertResponseStatus(200);
        $this->assertContains('attachment', (string)$response);
        // ->see('Edit Data Perangkat');
    }

    public function testShow()
    {
        $admin = User::where('id', '=', '1')->first();
        $sidang = Sidang::latest()->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang/' . $sidang['id']);
        //Status sukses dan judul Detail Sidang QA
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Detail Sidang QA</h1>');
    }

    public function testEdit()
    {
        $admin = User::where('id', '=', '1')->first();
        $sidang = Sidang::latest()->first();
        $this->actingAs($admin)->call('GET', 'admin/sidang/'. $sidang['id'].'/edit');
        //Status sukses dan judul Edit Data Perangkat
        $this->assertResponseStatus(200)
            ->see('Edit Data Perangkat');
    }


    public function testPrint()
    {
        $admin = User::where('id', '=', '1')->first();
        $sidang = Sidang::latest()->first();
        $response = $this->actingAs($admin)->call('GET', 'admin/sidang/' . $sidang['id'] . '/print');
        //Status sukses
        // if($response){
        //     dd('ooga print');
        // }
        $this->assertResponseStatus(200);
    }


    public function testUpdate()
    {
        $examination =  Examination::latest()->first();
        $device =  Device::latest()->first();
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $sidang_detail = Sidang_detail::latest()->first();
        $response =  $this->actingAs($admin)->call(
            'PUT',
            'admin/sidang/'.$sidang->id,
            [
                'id' => array($sidang_detail['id']),
                'result' => array('PENDING'),
                'valid_range' => array(0),
                'created_by' => 1,
                'date' => '2022-10-10',
                'catatan' => array('Test'),
                'status' => 'PENDING',
                'audience' => 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (Manager URel TTH), I Gede Astawa (Senior Manager TTH).',
                // 'hidden_tab' => 'tab-draft',
                'chk-draft' => array($examination->id),
                'tag' => 'test_tag',
                'device_id' => $device->id,
                'search2' => 'test'
            ]
        );
        $this->assertEquals(302, $response->status());
    }

    public function testUpdateStatusDone()
    {
        $examination =  Examination::latest()->first();
        $device =  Device::latest()->first();
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $sidang_detail = Sidang_detail::latest()->first();
        $response =  $this->actingAs($admin)->call(
            'PUT',
            'admin/sidang/' . $sidang->id,
            [
                'id' => array($sidang_detail['id']),
                'result' => array('PENDING'),
                'valid_range' => array(0),
                'created_by' => 1,
                'date' => '2022-10-10',
                'catatan' => array('Test'),
                'status' => 'DONE',
                'audience' => 'Adi Permadi (Manager Lab IQA TTH), Eliandri Shintani Wulandari (Manager Lab DEQA TTH), Yudha Indah Prihatini (Manager URel TTH), I Gede Astawa (Senior Manager TTH).',
                // 'hidden_tab' => 'tab-draft',
                'chk-draft' => array($examination->id),
                'tag' => 'test_tag',
                'device_id' => $device->id,
                'search2' => 'test'
            ]
        );
        $this->assertEquals(302, $response->status());
    }


    public function testupdateExamination()
    {
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'GET',
            'admin/sidang/updateExamination/' . $sidang->id
        );
        $this->assertResponseStatus(200);
    }


    public function testDownload()
    {
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'GET',
            'admin/sidang/' . $sidang->id . '/download'
        );
        $this->assertResponseStatus(200);
    }

    public function testResetExamination()
    {
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'GET',
            'admin/sidang/resetExamination/' . $sidang->id
        );
        $this->assertResponseStatus(200);
    }

    public function testResetExaminationRoute2()
    {
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'GET',
            'admin/sidang/'. $sidang->id.'/reset'
        );
        $this->assertResponseStatus(200);
    }


    public function testDestroy()
    {
        $sidang = Sidang::latest()->first();
        $admin = User::where('id', '=', '1')->first();
        $response =  $this->actingAs($admin)->call(
            'GET',
            'admin/sidang/delete/' . $sidang->id . '/UnitTestingDestroy'
        );
        $this->assertResponseStatus(302);
    }



    // public function testUpdateDevice()
    // {
    //     $device =  Device::latest()->first();

    //     $admin = User::where('id', '=', '1')->first();
    //     $sidang_detail = Sidang_detail::latest()->first();
    //     $response =  $this->actingAs($admin)->call(
    //         'POST',
    //         'admin/sidang',
    //         [
    //             'device_id' => $device->id,
    //             'test_reference' => 'test reference test',
    //             'name' => 'test edit device',
    //             'mark' => 'test mark edit',
    //             'model' => 'test model',
    //             'capacity' => '1234 GAH',
    //             'manufactured_by' => 'test company',
    //             'serial_number' => 'SR-7357'
    //         ]
    //     );

    //     $this->assertEquals(302, $response->status());
    // }


    


    // public function testBackup()http://localhost:4001/admin/sidang/35771f60-95f6-48ab-ab95-d8ad46ee62bf/excel
    // {
    //     Sidang::truncate();
    //     $user = User::find(1);
    //     $this->actingAs($user)->call('GET','/do_backup');
    //     //Status redirect arah redirect adalah ke backup.index, Backup successfully created
    //     $this->assertRedirectedTo('/admin/backup', ['message' => 'Backup successfully created']);
    //     //check di database
    //     $this->seeInDatabase('backup_history', ['user_id' => '1']);
    // }


    // public function testRestore()
    // {
    //     $backupHistory = Sidang::latest()->first();
    //     $user = User::find(1);
    //     $this->actingAs($user)->call('GET','admin/backup/'.$backupHistory->id.'/restore');
    //     //Status redirect arah redirect adalah ke backup.index
    //     $this->assertRedirectedTo('/admin/backup', ['message' => 'Restore successfully created']);
    // }


    // public function testMedia()
    // {
    //     $user = User::find(1);
    //     $backupHistory = Sidang::latest()->first();
    //     $response = $this->actingAs($user)->call('GET','admin/backup/'.$backupHistory->id.'/media');
    //     // mengecek responds header adalah konten download sql, dengan nama sesuai dengan db.
    //     $this->assertTrue($response->headers->get('content-type') == 'text/sql; charset=UTF-8');
    //     $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
    //     $this->assertTrue($response->headers->get('content-disposition') == "attachment; filename={$backupHistory->file}");
    // }

    // public function testMediaNotfound()
    // {
    //     $user = User::find(1);
    //     $this->actingAs($user)->call('GET','admin/backup/BackupTidakAda/media');
    //     // mengecek responds header adalah konten download sql, dengan nama sesuai dengan db.
    //     $this->assertRedirectedTo('/admin/backup', ['message' => 'Data not found']);
    // }


    // public function testDestroy()
    // {
    //     $user = User::find(1);
    //     $backupHistory = Sidang::latest()->first();
    //     $this->actingAs($user)->call('GET','admin/backup/'.$backupHistory->id.'/delete');
    //     //Status redirect arah redirect adalah ke backup.index
    //     $this->assertRedirectedTo('/admin/backup', ['message' => 'Backup successfully deleted']);
    // }

    // public function testDestroyDataNotFound()
    // {
    //     $user = User::find(1);
    //     $this->actingAs($user)->call('GET','admin/backup/dataNotFound/delete');
    //     //Status redirect arah redirect adalah ke backup.index
    //     $this->assertRedirectedTo('/admin/backup', ['error' => 'Data not found']);
    // }


    // public function testRestoreWithoutDataFound()
    // {
    //     Sidang::truncate();
    //     $user = User::find(1);
    //     $this->actingAs($user)->call('GET','admin/backup/fake_id/restore');
    //     //Status redirect arah redirect adalah ke backup.index
    //     $this->assertRedirectedTo('/admin/backup', ['error' => 'Data not found']);
    // }


}
