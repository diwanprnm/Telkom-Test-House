<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\QuestionerQuestion; 

class QuestionerQuestionControllerTest extends TestCase
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
	
    // public function test_visit()
	// { 
	//    $response = $this->call('GET', 'admin/questionerquestion');  
    //    $this->assertEquals(200, $response->status());
	// }
	// public function test_search()
	// { 
	//    $response = $this->call('GET', 'admin/questionerquestion?search=asda&is_active=&after_date=&before_date=');  
    //    $this->assertEquals(200, $response->status());
	// }
    // public function test_stores()
	// { 
	// 	$user = factory(App\User::class)->create(); 
       
	// 	$response =  $this->actingAs($user)->call('POST', 'admin/questionerquestion', 
	// 	[ 
	//         'question' => str_random(10),
	//         'order_question' => str_random(10),
	//         'is_essay' => str_random(10) 
	        
	//     ]);   
		
    //     $this->assertEquals(302, $response->status());
	     
	// }
    // public function test_update()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$company = QuestionerQuestion::latest()->first();
	// 	$response =  $this->actingAs($user)->call('PUT', 'admin/questionerquestion/'.$company->id, 
	// 	[ 
	//         'question' => str_random(10),
	//         'order_question' => str_random(10),
	//         'is_essay' => str_random(10) 
	//     ]);   
	// 	// dd($response->getContent());
    //     $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }
    // public function test_delete_company()
	// { 
	// 	$user = factory(App\User::class)->create(); 
    //    	$company = QuestionerQuestion::latest()->first();
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/questionerquestion/'.$company->id);   
	// 	// dd($response->getContent());
    //     $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }
}
