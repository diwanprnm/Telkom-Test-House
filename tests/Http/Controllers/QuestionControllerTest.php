<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Question;

class QuestionControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */

	public function delete_soon(){
        $this->assertTrue(true);
	}
	
    // public function test_visit_company()
	// { 
	//    $response = $this->call('GET', 'admin/question');  
    //    $this->assertEquals(200, $response->status());
	// }
	// public function test_search_company()
	// { 
	//    $response = $this->call('GET', 'admin/question?search=asda&is_active=&after_date=&before_date=');  
    //    $this->assertEquals(200, $response->status());
	// }
    // public function test_stores_company()
	// { 
	// 	$user = factory(App\User::class)->create(); 
       
	// 	$response =  $this->actingAs($user)->call('POST', 'admin/question', 
	// 	[ 
	//         'name' => str_random(10),
	       
	//     ]);   
	// 	// dd($response->getContent());
    //     $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }
    // public function test_update_company()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$quest = Question::latest()->first();
	// 	$response =  $this->actingAs($user)->call('PUT', 'admin/question/'.$quest->id, 
	// 	[ 
	//         'name' => str_random(10),
	//         'is_active' => mt_rand(0,1)
	//     ]);   
		
    //     $this->assertEquals(302, $response->status());
	     
	// }
    // public function test_delete_company()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$quest = Question::latest()->first();
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/question/'.$quest->id);   
		
    //     $this->assertEquals(302, $response->status());
	     
	// }
}
