<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class SlideshowControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testDeleteSoon(){
        $this->assertTrue(true);
    }

    // public function testIndexWithoutDataFound()
    // {
    //     //truncate data
    //     App\Slideshow::truncate();
    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/slideshow');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Slideshow</h1>')
    //         ->see('Data not found')
    //     ;
    // }

    
    // public function testCreate()
    // {
    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET','admin/slideshow/create');
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Tambah Slideshow Baru</h1>')
    //     ;
    // }


    // public function testStore()
    // {
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)
    //         ->visit('admin/slideshow/create')
    //         ->see('TAMBAH SLIDESHOW BARU')
    //         ->type('Testing Slideshow store', 'title')
    //         ->type('Testing Slideshow Headline store', 'headline')
    //         ->type('5', 'timeout')
    //         ->select('0', 'is_active')
    //         ->attach(base_path().'/public/images/logo_dds.png', 'image')
    //         ->press('submit');
    //     //check view and see flash message "Slideshow successfully created"
    //     $this->assertResponseStatus(200)
    //         ->see('Slideshow successfully created')
    //     ;
    // }


    // public function testIndexWithFilter()
    // {   
    //     //get Slideshow
    //     $slideshow = App\Slideshow::latest()->first();
    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET',"admin/slideshow?search=$slideshow->title");
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Slideshow</h1>')
    //         ->see($slideshow->title)
    //     ;
    // }


    // public function testEdit()
    // {
    //     //get Slideshow
    //     $slideshow = App\Slideshow::latest()->first();

    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('GET',"admin/slideshow/$slideshow->id/edit");
    //     //Status sukses dan judul Certification
    //     $this->assertResponseStatus(200)
    //         ->see('<h1 class="mainTitle">Edit Slideshow</h1>')
    //         ->see($slideshow->title)
    //     ;
    // }

    // public function testUpdate()
    // {
    //     //get Slideshow
    //     $slideshow = App\Slideshow::latest()->first();

    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)
    //         ->visit("admin/slideshow/$slideshow->id/edit")
    //         ->see('Edit Slideshow')
    //         ->type('Testing Slideshow store updated', 'title')
    //         ->type('Testing Slideshow Headline store updated', 'headline')
    //         ->type('7', 'timeout')
    //         ->select('1', 'is_active')
    //         ->attach(base_path().'/public/images/logo_telkom.png', 'image')
    //         ->press('submit');
    //     //check view and see flash message "Slideshow successfully updated"
    //     $this->assertResponseStatus(200)
    //         ->see('Slideshow successfully updated')
    //     ;
    // }


    // public function testOrderSlideshow()
    // {
    //     //get Slideshow
    //     $slideshow = App\Slideshow::latest()->first();
    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('POST',"admin/orderSlideshow",[
    //         'position' => array(
    //             0 => $slideshow->id,
    //         ),
    //     ]);
        
    //     $this->assertResponseStatus(200)
    //         ->see('1')
    //     ;
    // }

    // public function testDestroy()
    // {
    //     //get Slideshow
    //     $slideshow = App\Slideshow::latest()->first();
    //     //make request
    //     $user = User::where('id', '=', '1')->first();
    //     $this->actingAs($user)->call('DELETE',"admin/slideshow/$slideshow->id");
    //     //Status sukses dan judul FEEDBACK DAN COMPLAINT
    //     $this->assertRedirectedTo('admin/slideshow', ['message' => 'Slideshow successfully deleted']);
    //     //truncate data
    //     App\Slideshow::truncate();
    // }

}
