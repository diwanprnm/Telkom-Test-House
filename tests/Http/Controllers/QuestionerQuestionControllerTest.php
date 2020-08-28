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
	
     public function testVisit()
	 {
		$user = User::where('role_id', '=', '1')->first();
    	$response = $this->actingAs($user)->call('GET', 'admin/questionerquestion');  
        $this->assertEquals(200, $response->status());
	 }
	 public function testSearch()
	 { 
		$user = User::where('role_id', '=', '1')->first();
		$response = $this->actingAs($user)->call('GET', 'admin/questionerquestion?search=cari'); 
		$this->seeInDatabase('logs', [
            'page' => 'FOOTER',
            'data' => '{"search":"cari"}']);   
        $this->assertEquals(200, $response->status());
	 }

	 public function testCreate()
	 { 
		$admin = User::find('1');
	    $response = $this->actingAs($admin)->call('GET', 'admin/questionerquestion/create');  
        $this->assertEquals(200, $response->status());
	 }

     public function testStores()
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
		 
		 public function testEdit()
		 { 
		$admin = User::find('1');
		$questioner = QuestionerQuestion::latest()->first();
	    $response = $this->actingAs($admin)->call('GET', 'admin/questionerquestion/'.$questioner->id.'edit');  
        $this->assertEquals(200, $response->status());
	 	}

	     
	 
     public function testUpdate()
	 { 
		$user = User::find('1');
        $questioner = QuestionerQuestion::latest()->first();
	 	$response =  $this->actingAs($user)->call('PUT', 'admin/questionerquestion/'.$questioner->id, 
		[ 
	         'question' => str_random(10),
	         'order_question' => str_random(10),
	         'is_essay' => str_random(10) 
	     ]);   
         $this->assertEquals(200, $response->status());
	      $company = factory(App\Company::class)->make();  
	 }
     public function testDelete()
	 { 
		$user = User::find('1');
        $company = QuestionerQuestion::latest()->first();
	 	$response =  $this->actingAs($user)->call('DELETE', 'admin/questionerquestion/'.$company->id);   
         $this->assertEquals(200, $response->status());
	      $company = factory(App\Company::class)->make();  
	 }
}
