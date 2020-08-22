<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\QuestionerQuestion; 
use App\User;
class QuestionerQuestionControllerTest extends TestCase
{
    
    /**
     * A basic test example.
     *
     * @return void
     */

	public function testDeleteSoon(){
        $this->assertTrue(true);
	}
	
     public function test_visit()
	 {
		$user = User::where('role_id', '=', '1')->first();
    	$response = $this->actingAs($user)->call('GET', 'admin/questionerquestion');  
        $this->assertEquals(200, $response->status());
	 }
	 public function test_search()
	 { 
		$user = User::where('role_id', '=', '1')->first();
	    $response = $this->actingAs($user)->call('GET', 'admin/questionerquestion?search=asda');  
        $this->assertEquals(200, $response->status());
	 }

	 public function test_create()
	 { 
		$admin = User::find('1');
	    $response = $this->actingAs($admin)->call('GET', 'admin/questionerquestion/create');  
        $this->assertEquals(200, $response->status());
	 }

     public function test_stores()
	 { 
	 	
		$user = User::where('role_id', '=', '1')->first();
	 	$response =  $this->actingAs($user)->call('POST', 'admin/questionerquestion', 
	 	[ 
	         'question' => str_random(10),
	         'order_question' => str_random(10),
	         'is_essay' => str_random(10) 
	        
	     ]);   
		
		 $this->assertEquals(200, $response->status());
		 }
		 
		 public function test_edit()
		 { 
		$admin = User::find('1');
		$questioner = QuestionerQuestion::latest()->first();
	    $response = $this->actingAs($admin)->call('GET', 'admin/questionerquestion/'.$questioner->id.'edit');  
        $this->assertEquals(200, $response->status());
	 	}

	     
	 
     public function test_update()
	 { 
		$user = User::find('1');
        $questioner = QuestionerQuestion::latest()->first();
	 	$response =  $this->actingAs($user)->call('PUT', 'admin/questionerquestion/'.$questioner->id, 
		[ 
	         'question' => str_random(10),
	         'order_question' => str_random(10),
	         'is_essay' => str_random(10) 
	     ]);   
	 	// dd($response->getContent());
         $this->assertEquals(200, $response->status());
	      $company = factory(App\Company::class)->make();  
	 }
     public function test_delete_company()
	 { 
		$user = User::find('1');
        $company = QuestionerQuestion::latest()->first();
	 	$response =  $this->actingAs($user)->call('DELETE', 'admin/questionerquestion/'.$company->id);   
	 	// dd($response->getContent());
         $this->assertEquals(200, $response->status());
	      $company = factory(App\Company::class)->make();  
	 }
}
