<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\BackupHistory; 

class BackupControllerTest extends TestCase
{


    public function testIndexAsNonAdmin()
    {
        $user = factory(User::class)->make();
        $this->actingAs($user)->call('GET','admin/backup');
        //status sukses, tapi ada bacaaan you dont have permission
        $this->assertResponseStatus(200)
        ->see("Unautorizhed. You do not have permission to access this page.");
    }
    

    public function testIndexAsAdmin()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup');
        //Status sukses dan judul BACKUP & RESTORE
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">BACKUP & RESTORE</h1>');
    }


    public function testIndexWithSearch()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup?search=cari');
        //Status sukses dan judul BACKUP & RESTORE
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">BACKUP & RESTORE</h1>');
    }


    public function testIndexWithoutDataFound()
    {
        BackupHistory::truncate();
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup?search=cari');
        //Status sukses, judul BACKUP & RESTORE, dan Pemberitahuan "Data not found"
        $this->assertResponseStatus(200)
        ->see('<h1 class="mainTitle">BACKUP & RESTORE</h1>')
        ->see('Data not found');
    }


    public function testBackup()
    {
        BackupHistory::truncate();
        $user = User::find(1);
        $this->actingAs($user)->call('GET','/do_backup');
        //Status redirect arah redirect adalah ke backup.index, Backup successfully created
        $this->assertRedirectedTo('/admin/backup', ['message' => 'Backup successfully created']);
        //check di database
        $this->seeInDatabase('backup_history', ['user_id' => '1']);
    }


    public function testRestore()
    {
        $backupHistory = BackupHistory::latest()->first();
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup/'.$backupHistory->id.'/restore');
        //Status redirect arah redirect adalah ke backup.index
        $this->assertRedirectedTo('/admin/backup', ['message' => 'Restore successfully created']);
    }


    public function testMedia()
    {
        $user = User::find(1);
        $backupHistory = BackupHistory::latest()->first();
        $response = $this->actingAs($user)->call('GET','admin/backup/'.$backupHistory->id.'/media');
        // mengecek responds header adalah konten download sql, dengan nama sesuai dengan db.
        $this->assertTrue($response->headers->get('content-type') == 'text/sql; charset=UTF-8');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == "attachment; filename={$backupHistory->file}");
    }

    public function testMediaNotfound()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup/BackupTidakAda/media');
        // mengecek responds header adalah konten download sql, dengan nama sesuai dengan db.
        $this->assertRedirectedTo('/admin/backup', ['message' => 'Data not found']);
    }


    public function testDestroy()
    {
        $user = User::find(1);
        $backupHistory = BackupHistory::latest()->first();
        $this->actingAs($user)->call('GET','admin/backup/'.$backupHistory->id.'/delete');
        //Status redirect arah redirect adalah ke backup.index
        $this->assertRedirectedTo('/admin/backup', ['message' => 'Backup successfully deleted']);
    }

    public function testDestroyDataNotFound()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup/dataNotFound/delete');
        //Status redirect arah redirect adalah ke backup.index
        $this->assertRedirectedTo('/admin/backup', ['error' => 'Data not found']);
    }


    public function testRestoreWithoutDataFound()
    {
        BackupHistory::truncate();
        $user = User::find(1);
        $this->actingAs($user)->call('GET','admin/backup/fake_id/restore');
        //Status redirect arah redirect adalah ke backup.index
        $this->assertRedirectedTo('/admin/backup', ['error' => 'Data not found']);
    }


}
