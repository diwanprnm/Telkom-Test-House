<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\QuestionerQuestion; 
use App\User;
class QuestionerQuestionControllerTest extends TestCase
{
    use DatabaseTransactions;
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
		$response = $this->actingAs(User::find('1'))->call('GET', 'admin/questionerquestion');
		$this->assertEquals(200, $response->status());
	}

	public function testVisitwithSearch()
	{
		$response = $this->actingAs(User::find('1'))->call('GET', 'admin/questionerquestion?search=pengajuan');  
		$this->assertEquals(200, $response->status());
	}


	public function testCreate()
	{ 
		$this->actingAs(User::find('1'))->call('GET', 'admin/questionerquestion/create');
		$this->assertResponseStatus(200);
	}

	public function testStore()
	{ 
		$admin = User::find('1');
		$this->actingAs($admin)->call('POST', 'admin/questionerquestion',[
			'question' => 'Saya mau bertanya pak',
			'order_question' => '30'
		]);
		$this->assertRedirectedTo('admin/questionerquestion',['message' => 'Question successfully created']);

		$this->actingAs($admin)->call('POST', 'admin/questionerquestion',[
			'question' => 'Saya mau bertanya lagi pak',
			'order_question' => '31',
			'is_essay' => 'on'
		]);
		$this->assertRedirectedTo('admin/questionerquestion',['message' => 'Question successfully created']);
	}
		 
	public function testEdit()
	{ 
		$questioner = QuestionerQuestion::latest()->first();
		$this->actingAs(User::find('1'))->call('GET', "admin/questionerquestion/$questioner->id/edit");  
		$this->assertResponseStatus(200);
	}

	public function testUpdate()
	{ 
		$questioner = QuestionerQuestion::latest()->first();
		$this->actingAs(User::find('1'))->call('PATCH', "admin/questionerquestion/$questioner->id", [ 
			'question' => 'Saya bingung pak',
			'order_question' => '31',
			'is_essay' => 'on',
			'is_active' => 1
		]);
		$this->assertRedirectedTo('admin/questionerquestion',['message' => 'Question successfully updated']);
	}

	public function testDelete()
	{ 
		$company = QuestionerQuestion::latest()->first();
		$this->actingAs(User::find('1'))->call('DELETE', "admin/questionerquestion/$company->id");  
		$this->assertRedirectedTo('admin/questionerquestion',['message' => 'Question successfully deleted']);

		$this->actingAs(User::find('1'))->call('DELETE', "admin/questionerquestion/NotFound");
		$this->assertRedirectedTo('admin/questionerquestion',['error' => 'Question Not Found']);
	}

	//after delete
	public function testVisitNotFound()
	{
		QuestionerQuestion::truncate();
		$this->actingAs(User::find('1'))->call('GET', 'admin/questionerquestion');  
		$this->assertResponseStatus(200)->see('Data not found');
	}

	public function testCreateWithoutData()
	{ 
	   $this->actingAs(User::find('1'))->call('GET', 'admin/questionerquestion/create');
	   $this->assertResponseStatus(200)->see(1);
	}

	//seed back
}
