<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\STEL;

class STELControllerTest extends TestCase
{  
    public function test_visit_stel()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel');   
       $this->assertEquals(200, $response->status());
	} 

	public function test__visit_stel_with_search()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel?search=cari&category=VA&year=2020&is_active=1');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_stel()
	{
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel/create');  
       $this->assertEquals(200, $response->status());
	}

	public function test_show()
	{
		$response =  $this->actingAs(User::find(1))->call('GET', 'admin/stel/1234');  
		$this->assertResponseStatus(200);
	}

    public function test_stores_stel()
	{
	    $user = User::find(1);
  
		$response =  $this->actingAs($user)->call('POST', 'admin/stel', 
		[ 
	        'code' => str_random(10), 
	        'name' => str_random(10), 
	        'type' => str_random(2), 
	        'stel_type' =>1, 
	        'version' => str_random(2), 
	        'price' => 12000, 
	        'total' => 12000, 
	        'year' => 2020, 
	        'is_active' => 1, 
	    ]);    
	    
        $this->assertEquals(302, $response->status());
	}

    public function test_visit_edit_stel()
	{ 
 
	   $user = User::find(1); 

       $stel = STEL::latest()->first();
	   $response =  $this->actingAs($user)->call('GET', 'admin/stel/'.$stel->id."/edit");  
	   
		// dd($response->getContent());
       $this->assertEquals(200, $response->status());
	}
    public function test_update_stel()
	{ 
 
	    $user = User::find(1);
       	$stel = STEL::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/stel/'.$stel->id, 
		[ 
	        'code' => str_random(10), 
	        'name' => str_random(10), 
	        'type' => str_random(2), 
	        'price' => 12000, 
	        'total' => 12000, 
	        'stel_type' =>1, 
	        'version' => str_random(2), 
	        'year' => 2020, 
	        'is_active' => 1, 
	    ]);    
	    
        $this->assertEquals(302, $response->status());  
	}


    public function test_autocomplete_stel()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->call('GET',"admin/adm_stel_autocomplete/query"); 
        $this->assertResponseStatus(200); 
    }


    public function test_delete_stel()
	{ 
		$user = User::find(1); 

       	$stel = STEL::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/stel/'.$stel->id);  
        $this->assertEquals(302, $response->status()); 
	} 

	  public function testExcel()
    {         
        //make request
        $response = $this->actingAs( User::find(1))->call('GET',"/stel/excel");
        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data STEL-STD.xlsx"');
	}
	
	public function testExcelWithFilter()
    {         
        //make request
        $response = $this->actingAs( User::find(1))->call('GET',"/stel/excel?search=cari");
        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data STEL-STD.xlsx"');
	}
	
	public function testExcelWithOtherFilter()
    {         
        //make request
        $response = $this->actingAs( User::find(1))->call('GET',"/stel/excel?category=abc&is_active=1");
        //response ok, header download sesuai
        $this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
        $this->assertTrue($response->headers->get('content-description') == 'File Transfer');
        $this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data STEL-STD.xlsx"');
    }

     public function testViewMedia()
    {
         

        $stel = factory(App\STEL::class)->create(['attachment'=>str_random(10).".pdf"]); 
        //save file to minio
        $file = file_get_contents('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
		Storage::disk('minio')->put("stel/$stel->attachment", $file);

        //make request
        $admin = User::find(1); 
        $response = $this->actingAs($admin)->call('GET',"admin/stel/media/".$stel->id); 
		$this->assertResponseStatus(200);
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        // Delete file from minio
        \Storage::disk('minio')->delete("stel/$stel->attachment");
    }
}
