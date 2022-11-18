<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use App\EmailEditor;  
use App\User;  

class EmailEditorControllerTest extends TestCase
{ 
    /**
     * A basic test example.
     *
     * @return void
     */ 
    public function test_visit_email_editors()
	{
	   $this->actingAs(User::find(1))->call('GET', 'admin/email_editors?search=cari');
	   $this->assertResponseStatus(200); 
	}

	public function test_search_email_editors()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/email_editors?search=cari');  
       $this->assertEquals(200, $response->status());
	}
	
    public function test_visit_create_email_editors()
	{ 
	   $email_editors = EmailEditor::find(1);
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/email_editors/create');  
       $this->assertEquals(200, $response->status());
	}

    public function test_stores_email_editors()
	{ 
		$user = User::find(1);
       	// $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
		$response =  $this->actingAs($user)->call('POST', 'admin/email_editors', 
		[ 
	        'name' => "testing_".mt_rand(0,10),
	        'subject' => str_random(10),
	        'content' => str_random(10) ,
	        'dir_name' =>  str_random(10),
	        'signature' => str_random(10) ,
	        'logo' =>mt_rand(0,10000) ,
	        'created_by' => mt_rand(0,1), 
	        'updated_by' => mt_rand(0,1)
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $email_editors = factory(App\EmailEditor::class)->make();  
	}

    public function test_visit_edit_email_editors()
	{ 
	   $email_editors = EmailEditor::find(1);
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/email_editors/'.$email_editors->id.'/edit');  
       $this->assertEquals(200, $response->status());
	}

    public function test_update_email_editors()
	{ 
		$user = User::find(1);
       	$email_editors = EmailEditor::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/email_editors/'.$email_editors->id, 
		[ 
	        'name' => str_random(10),
	        'subject' => str_random(10),
	        'content' => str_random(10) ,
	        'dir_name' =>  str_random(10),
	        'signature' => str_random(10),
	        'logo' =>mt_rand(0,10000),
	        'created_by' => mt_rand(0,1), 
	        'updated_by' => mt_rand(0,1)
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $email_editors = factory(App\EmailEditor::class)->make();  
	}

    public function test_update_logo_signature()
	{ 
		$user = User::find(1);
        $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
		$response =  $this->actingAs($user)->call('POST', 'admin/email_editors/update_logo_signature/', 
		[ 
	        'logo' => $file,
	        'signature' => $file
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $email_editors = factory(App\EmailEditor::class)->make();  
	}
    
    public function test_delete_email_editors()
	{ 
		$user = User::find(1);
       	$email_editors = EmailEditor::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/email_editors/'.$email_editors->id);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $email_editors = factory(App\EmailEditor::class)->make();  
	} 
}
