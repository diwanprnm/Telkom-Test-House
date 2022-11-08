<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class ExaminationCancelControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndex()
    {
        $this->actingAs(User::find(1))->call('GET','admin/examinationcancel?search=cari&type=1&company=company&device=device&before_date=2100-01-01&after_date=2020-01-01');
        $this->assertResponseStatus(200)->see('Pengujian Batal');
    }

    public function testShow()
    {
        $examination = factory(App\Examination::class)->create();
        $this->actingAs(User::find(1))->call('GET',"admin/examinationcancel/$examination->id");
        $this->assertResponseStatus(200)->see('Detail pengujian');
    }

    public function testEdit()
    {
        $examination = factory(App\Examination::class)->create();
        $this->actingAs(User::find(1))->call('GET',"admin/examinationcancel/$examination->id/edit");
        $this->assertResponseStatus(200);
    }
}