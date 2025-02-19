<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\DB;
use App\User;

class NewExaminationChargeControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */


    public function testIndexAsAdmin()
    {
        //truncate data
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges_detail')->truncate();
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/newcharge');

        //Status sukses dan judul "TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN BARU</h1>')
        ;
    }

    public function testIndexWithoutDataExsit()
    {
        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/newcharge');

        //Status sukses dan judul "TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN BARU</h1>')
            ->see('Data not found')
        ;
    }


    public function testCreate()
    {
        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/newcharge/create');

        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TAMBAH TARIF BARU</h1>')
        ;
    }

    public function testStore()
    {   
        //Make Request
        $admin = User::find(1);
        $this->actingAs($admin)
            ->visit('admin/newcharge/create')
            ->see('<h1 class="mainTitle">TAMBAH TARIF BARU</h1>')
            ->type('NewExaminationCharge name test', 'name')
            ->type('2020-01-01', 'valid_from')
            ->select('This is NewExaminationCharge description test', 'description')
            ->press('submit');
        //check view and see flash message "New Charge successfully created"
        $this->assertResponseStatus(200)
            ->see('New Charge successfully created');
    }


    public function testCreateDetail()
    {
        //Get Data
        $newExaminationCharge = App\NewExaminationCharge::latest()->first();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/newcharge/$newExaminationCharge->id/createDetail");

        //Status ok dan ada "TAMBAH TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Tambah Tarif Pengujian Baru</h1>')
        ;
    }

    public function testPostDetail()
    {
        //Get Data
        $newExaminationCharge = App\NewExaminationCharge::latest()->first();
        $lab = App\ExaminationLab::first();

        //Make request as Admin
        $admin = User::find(1);
        
        $this->actingAs($admin)
            ->visit("admin/newcharge/$newExaminationCharge->id/createDetail")
            ->see('<h1 class="mainTitle">TAMBAH TARIF PENGUJIAN BARU</h1>')
            ->type('Device name test', 'device_name')
            ->type('Stel of device', 'stel')
            ->select($lab->id, 'category')
            ->type('77', 'duration')
            ->type('712000', 'new_price')
            ->type('123000', 'new_vt_price')
            ->type('500000', 'new_ta_price')
            ->press('submit')
        ;

        //redirect, go to admin/examination/id, and message "New Charge successfully updated"
        $this->assertResponseStatus(200)
            ->seePageIs("admin/newcharge/$newExaminationCharge->id")
            ->see('New Charge successfully updated')
        ;
    }


    public function testIndexWithFilter()
    {
        //Get Data
        $newExaminationCharge = App\NewExaminationCharge::latest()->first();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/newcharge?search=$newExaminationCharge->name&is_implement=0&after_date=2020-01-01&before_date=2100-01-01");

        //Status sukses dan judul "TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN BARU</h1>')
            ->see($newExaminationCharge->name)
            ->see($newExaminationCharge->description)
        ;
    }


    public function testCreateButThereIsUnprocessedData()
    {
        //make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET','admin/newcharge/create');

        //You have not processing data!
        $this->assertRedirectedTo('admin/newcharge', ['error' => 'You have not processing data!']);

        //truncate data
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges_detail')->truncate();
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }


    public function testCreateDetailButImplemented()
    {
        $newExaminationCharge = factory(App\NewExaminationCharge::class)->create([
            'is_implement' => 1
        ]);

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/newcharge/$newExaminationCharge->id/createDetail");

        //Status ok dan ada erro "The data has been processed!" admin/newcharge/id
        $this->assertRedirectedTo("admin/newcharge/$newExaminationCharge->id", ['error' => 'The data has been processed!']);

        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges_detail')->truncate();
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }

    public function testShow()
    {
        $newExaminationChargeDetail = factory(App\NewExaminationChargeDetail::class)->create();
        $newExaminationCharge = App\NewExaminationCharge::latest()->first();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/newcharge/$newExaminationCharge->id");

        //Status sukses dan judul "TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TARIF PENGUJIAN BARU</h1>')
            // ->see($newExaminationChargeDetail->name)
            // ->see($newExaminationChargeDetail->category)
        ;

        // Remove Residual Data
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges_detail')->truncate();
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }

    public function testEdit()
    {
        $newExaminationCharge = factory(App\NewExaminationCharge::class)->create();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/newcharge/$newExaminationCharge->id/edit");

        //Status sukses dan judul "TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">EDIT TARIF BARU</h1>')
        ;

    }

    public function testUpdate()
    {   
        //Get Data
        factory(App\ExaminationCharge::class)->create();
        $newExaminationChargeDetail = factory(App\NewExaminationChargeDetail::class)->create();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('PATCH',"admin/newcharge/$newExaminationChargeDetail->new_exam_charges_id",[
            'name' => 'Change name',
            'valid_from' => '2020-01-01',
            'description' => 'New Description',
            'is_implement' => '1'
        ]);

        //Status redirect ke admin/newchrage dan pesan "New Charge successfully updated"
        $this->assertRedirectedTo("admin/newcharge", ['message' => 'New Charge successfully updated']);

        //delete residual
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }


    public function testEditDetail()
    {
        $newExaminationChargeDetail = factory(App\NewExaminationChargeDetail::class)->create();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('GET',"admin/newcharge/$newExaminationChargeDetail->new_exam_charges_id/editDetail/$newExaminationChargeDetail->id");

        //Status sukses dan judul "TARIF PENGUJIAN BARU"
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">TAMBAH TARIF PENGUJIAN BARU</h1>')
            ->see($newExaminationChargeDetail->name)
        ;

        //truncate data
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges_detail')->truncate();
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }

    public function testUpdateDetail()
    {
        $newExaminationChargeDetail = factory(App\NewExaminationChargeDetail::class)->create();
        $lab = App\ExaminationLab::first();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)
            ->visit("admin/newcharge/$newExaminationChargeDetail->new_exam_charges_id/editDetail/$newExaminationChargeDetail->id")
            ->see('<h1 class="mainTitle">TAMBAH TARIF PENGUJIAN BARU</h1>')
            ->type('Device name test updateDetail', 'device_name')
            ->type('Stel of device updateDetail', 'stel')
            ->select($lab->id, 'category')
            ->type('77', 'duration')
            ->type('712000', 'new_price')
            ->type('123000', 'new_vt_price')
            ->type('500000', 'new_ta_price')
            ->press('submit')
        ;

        //success in admin/examination/id, and message "New Charge successfully updated"
        $this->assertResponseStatus(200)
            ->seePageIs("admin/newcharge/$newExaminationChargeDetail->new_exam_charges_id")
            ->see('New Charge successfully updated')
        ;
    }


    public function testDeleteDetail()
    {
        $newExaminationChargeDetail = App\NewExaminationChargeDetail::latest()->first();
        $newExaminationCharge = App\NewExaminationCharge::latest()->first();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST',"admin/newcharge/$newExaminationCharge->id/deleteDetail/$newExaminationChargeDetail->id");

        //Status redirect ke admin/charge/id dan pesan "New Charge detail successfully deleted"
        $this->assertRedirectedTo("admin/newcharge/$newExaminationCharge->id", ['message' => 'New Charge detail successfully deleted']);
    }
    public function testDeleteDetailNotFound()
    {
        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('POST',"admin/newcharge/DataNotFound/deleteDetail/DataNotFound");

        //Status redirect ke admin/charge/id dan pesan "New Charge detail successfully deleted"
        $this->assertRedirectedTo("admin/newcharge/", ['error' => 'Data not found']);
    }

    public function testDestroy()
    {
        $newExaminationCharge = App\NewExaminationCharge::latest()->first();

        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('DELETE',"admin/newcharge/$newExaminationCharge->id");

        //Status redirect ke admin/newcharge dan pesan "New Charge successfully deleted"
        $this->assertRedirectedTo("admin/newcharge", ['message' => 'New Charge successfully deleted']);

        //truncate data
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        DB::table('new_examination_charges_detail')->truncate();
        DB::table('new_examination_charges')->truncate();
        DB::table('examination_charges')->truncate();
        //for Mysql DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }

    public function testDestroyNotFound()
    {
        //Make request as Admin
        $admin = User::find(1);
        $this->actingAs($admin)->call('DELETE',"admin/newcharge/DataNotFound");

        //Status redirect ke admin/newcharge dan pesan "New Charge successfully deleted"
        $this->assertRedirectedTo("admin/newcharge/", ['error' => 'Data not found']);

    }
}
