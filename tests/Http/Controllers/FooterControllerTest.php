<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Footer;

class FooterControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
     public function testVisit()
	 { 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/footer');
        $this->assertEquals(200, $response->status());
	 }
	 public function test_search()
	 { $admin = User::find('1');
        $response = $this->actingAs($admin)->call('GET', 'admin/footer?search=cari'); 
        $this->seeInDatabase('logs', [
            'page' => 'FOOTER',
            'data' => '{"search":"cari"}']);  
        $this->assertEquals(200, $response->status());
     }
     
     public function testCreate()
	 { 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/footer/create');
        $this->assertEquals(200, $response->status());
     }
     
     public function testStores()
	 { 
	 	$user = factory(App\User::class)->create(); 
       
	 	$response =  $this->actingAs($user)->call('POST', 'admin/footer', 
	 	[ 
             'description' => str_random(10),
	         'image' => str_random(10),
	         'is_active' =>mt_rand(0,1) 
	     ]);   
         $this->assertEquals(200, $response->status());
	     
        }
        public function testEdit()
        { 
            $admin = User::find('1');
           $response = $this->actingAs($admin)->call('GET', 'admin/footer/edit');
           $this->assertEquals(200, $response->status());
        }
        
        
   /*  public function testUpdate()
	 { 
        $admin = User::find('1');
        $footer = factory(App\Footer::class)->create();
	 	$response =  $this->actingAs($admin)->call('PATCH', "admin/footer/$footer->id", 
	 	[ 
	         'description' => str_random(10),
	         'image' => str_random(10),
	         'is_active' =>mt_rand(0,1) 
         ]);   
         dd($response);
         $this->assertEquals(302, $response->status());
	 }
     public function testDelete()
	 { 
	 	$user = factory(App\User::class)->create(); 
        $company = Footer::latest()->first();
	 	$response =  $this->actingAs($user)->call('DELETE', 'admin/Footer/');  
         $this->assertEquals(302, $response->status());  
	 } */
}
