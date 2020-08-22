<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;
use App\Question;

class QuestionControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

     public function test_visit_question()
	 { 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/question');
        $this->assertEquals(200, $response->status());
	 }
	 public function test_search_question()
	 { 
		$admin = User::find('1');
		$response = $this->actingAs($admin)->call('GET', 'admin/question?search=asda');  
        $this->assertEquals(200, $response->status());
	 }

     public function test_create_question()
	 { 
		$admin = User::find('1');
	    $response = $this->actingAs($admin)->call('GET', 'admin/question/create');  
        $this->assertEquals(200, $response->status());
	 }

     public function test_stores()
	 { 
		$admin = User::find('1');
       
 		$response =  $this->actingAs($admin)->call('POST', 'admin/question', 
	 	[ 
	         'name' => str_random(10),
		]);   
		
         $this->assertEquals(302, $response->status());
	     $question = factory(App\Question::class)->make();  
	}

/*	public function test_edit_question()
	{ 
	   $admin = User::find('1');
	   $quest = Question::latest()->first();
	   $response = $this->actingAs($admin)->call('GET', 'admin/question/'.$quest->id.'/edit');  
	   $this->assertEquals(200, $response->status());
	}

     public function test_update_question()
	 { 
		$admin = User::find('1');
        $quest = Question::latest()->first();
	 	$response =  $this->actingAs($admin)->call('PUT', 'admin/question/'.$quest->id, 
	 	[ 
	         'name' => str_random(10),
	         'is_active' => mt_rand(0,1)
	     ]);   
		
        $this->assertEquals(302, $response->status());
	     
	}
     public function test_delete_question()
	 { 
		$admin = User::find('1');
        $quest = Question::latest()->first();
		$response =  $this->actingAs($admin)->call('DELETE', 'admin/question/'.$quest->id);   
		
        $this->assertEquals(302, $response->status());
	     
	 }*/
}
