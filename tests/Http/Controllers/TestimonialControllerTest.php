<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class TestimonialControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    public function testIndexWithoutDataFound()
    {
        //truncate data
        //for mysql DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        App\Testimonial::truncate();
        App\Examination::truncate();
        App\Logs::truncate();
        App\Company::where('id','!=', '1')->delete();
        App\User::where('id','!=', '1')->delete();
        App\Device::truncate();
        //for mysql  DB::statement('SET FOREIGN_KEY_CHECKS=1;'); --

        //make request
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/testimonial');
        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Testimonial</h1>')
            ->see('Data not found')
        ;
    }


    public function testIndexWithSearch()
    {
        //Presist Data
        $testimonial = factory(App\Testimonial::class)->create(['message' => 'ini adalah testimonial_testing']);

        //make request with search
        $user = User::where('id', '=', '1')->first();
        $this->actingAs($user)->call('GET','admin/testimonial?search=testimonial_testing');

        //Status sukses dan judul Certification
        $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Testimonial</h1>')
            ->see($testimonial->message)
        ;
    }

    public function testCreate()
    {
        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/testimonial/create");
        //assert status ok
        $this->assertResponseStatus(200);
    }

    public function testStore()
    {
        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('POST',"admin/testimonial");
        //assert status ok
        $this->assertResponseStatus(200);
    }

    public function testShow()
    {
        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/testimonial/id");
        //assert status ok
        $this->assertResponseStatus(200);
    }

    public function testEdit()
    {
        $testimonial = App\Testimonial::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('GET',"admin/testimonial/$testimonial->id/edit");

        //assert status ok, title benar, dan ada data yang dicari
            $this->assertResponseStatus(200)
            ->see('<h1 class="mainTitle">Edit Testimonial</h1>')
            ->see($testimonial->message)
        ;
    }

    public function testUpdate()
    {
        $testimonial = App\Testimonial::latest()->first();

        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)
            ->visit("admin/testimonial/$testimonial->id/edit")
            ->see('<h1 class="mainTitle">Edit Testimonial</h1>')
            ->see($testimonial->message)
            ->select('1', 'is_active')
            ->press('submit')
        ;

        //assert expected response
        $this->assertResponseStatus(200)
            ->seePageIs("admin/testimonial")
            ->see('<h1 class="mainTitle">Testimonial</h1>')
            ->see('Information successfully updated')
        ;
    }

    public function testDestroy()
    {
        //Make request as Admin
        $admin = User::where('id', '=', '1')->first();
        $this->actingAs($admin)->call('DELETE',"admin/testimonial/id");
        //assert status ok
        $this->assertResponseStatus(200);

        //truncate in ends of test
        //for mysql  DB::statement('SET FOREIGN_KEY_CHECKS=0;'); -
        App\Testimonial::truncate();
        App\Examination::truncate();
        App\Logs::truncate();
        App\Company::where('id','!=', '1')->delete();
        App\User::where('id','!=', '1')->delete();
        App\Device::truncate();
        //for mysql  DB::statement('SET FOREIGN_KEY_CHECKS=1;'); -
    }
}
