<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;  
use App\TempCompany;

class TempCompanyControllerTest extends TestCase
{
   public function test_visit_tempcompany()
	{ 
	   $user = User::find(1);
	   factory(App\TempCompany::class)->create();
	   $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany');   
       $this->assertEquals(200, $response->status());
	} 

	public function test__visit_tempcompany_with_search()
	{ 
	   $user = User::find(1);
	   factory(App\TempCompany::class)->create();
	   $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany?search=cari');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_edit_tempcompany()
	{ 
 
	   $user = User::find(1);
	   $tempCompany = factory(App\TempCompany::class)->create();
	   $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany/'.$tempCompany->id);  
	    
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_tempcompany()
	{ 
 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany/create');  
       $this->assertEquals(200, $response->status());
	}
    public function test_stores_tempcompany()
	{ 
 
	    $user = User::find(1);
       
		$response =  $this->actingAs($user)->call('POST', 'admin/tempcompany',[]);    
        $this->assertEquals(200, $response->status());
	}
 //    public function test_update_tempcompany()
	// { 
 
	//     $user = User::find(1);  
	//     $tempCompany = factory(App\TempCompany::class)->create();
	// 	$response =  $this->actingAs($user)->call('PUT', 'admin/tempcompany/'.$tempCompany->id,[]);    
 //        $this->assertEquals(302, $response->status());  
	// }


    public function test_autocomplete_tempcompany()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET',"admin/adm_temp_company_autocomplete/query"); 
        $this->assertResponseStatus(200); 
    }


 //    public function test_delete_tempcompany()
	// { 
	// 	$user = User::find(1); 
	// 	$tempCompany = factory(App\TempCompany::class)->create();
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/tempcompany/'.$tempCompany->id);  

 //        $this->assertEquals(302, $response->status()); 
	// } 
}
