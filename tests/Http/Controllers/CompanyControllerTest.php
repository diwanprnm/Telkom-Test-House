<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use App\Company;  
use App\User;  

class CompanyControllerTest extends TestCase
{ 
    /**
     * A basic test example.
     *
     * @return void
     */ 
    public function test_visit_company()
	{
	   $this->actingAs(User::find(1))->call('GET', 'admin/company?is_active=1&search=cari&before_date=2020-12-31&after_date=2020-01-01&sort_by=$sort_type=desc');
	   $this->assertResponseStatus(200); 
	} 
    public function test_visit_edit_company()
	{ 

	   $company = Company::find(1);

	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/company/'.$company->id.'/edit');  
       $this->assertEquals(200, $response->status());
	}
    public function test_visit_create_company()
	{ 

	   $company = Company::find(1);
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/company/create');  
       $this->assertEquals(200, $response->status());
	}
	public function test_search_company()
	{ 
	   $user = User::find(1);
	   $response =  $this->actingAs($user)->call('GET', 'admin/company?search=cari');  
       $this->assertEquals(200, $response->status());
	}
	
    public function test_stores_company()
	{ 
		$user = User::find(1);
       	$file = \Storage::disk('local_public')->get("images/testing.jpg"); 
		$response =  $this->actingAs($user)->call('POST', 'admin/company', 
		[ 
	        'name' => "testing_".mt_rand(0,10),
	        'address' => str_random(10),
	        'city' => str_random(10) ,
	        'email' =>  str_random(10),
	        'postal_code' => str_random(10) ,
	        'phone_number' =>mt_rand(0,10000) ,
	        'fax' => str_random(10) ,
	        'npwp_number' => mt_rand(0,10000) , 
	        'siup_number' => str_random(10) , 
	        'siup_date' => str_random(10), 
	        'qs_certificate_number' => str_random(10) , 
	        'qs_certificate_date' => str_random(10), 
	        'is_active' => mt_rand(0,1),
	        'created_by' => mt_rand(0,1), 
	        'updated_by' => mt_rand(0,1), 
	        'plg_id' => mt_rand(0,10),
	        'npwp_file' => $file,
	        'siup_file' => "test_siup_file_".str_random(0,12),
	        'qs_certificate_file' => "test_qs_file_".str_random(0,12),
	        'nib' => mt_rand(0,1)
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $company = factory(App\Company::class)->make();  
	}
	public function test_view_media_npwp()
	{ 
	   
	   $user = User::find(1);
       $company = factory(App\Company::class)->create();
	   $id = $company->id;
	   $name = "npwp";
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("company/$company->id/$company->npwp_file", $file);
	    $response =  $this->actingAs($user)->call('GET', 'admin/company/media/'.$id.'/'.$name);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("company/$company->id/$company->npwp_file");
	}
	public function test_view_media_siup()
	{ 
	   
	   $user = User::find(1);
       $company = factory(App\Company::class)->create();
	   $id = $company->id;
	   $name = "siup";
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("company/$company->id/$company->siup_file", $file);
	    $response =  $this->actingAs($user)->call('GET', 'admin/company/media/'.$id.'/'.$name);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("company/$company->id/$company->siup_file");
	}
	public function test_view_media_qs()
	{ 
	   
	   $user = User::find(1);
       $company = factory(App\Company::class)->create();
	   $id = $company->id;
	   $name = "qs";
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("company/$company->id/$company->qs_certificate_file", $file);
	    $response =  $this->actingAs($user)->call('GET', 'admin/company/media/'.$id.'/'.$name);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'image/jpeg');

        \Storage::disk('minio')->delete("company/$company->id/$company->qs_certificate_file");
	}

	public function test_view_media_default()
	{
		$company = factory(App\Company::class)->create();
		$this->actingAs(User::find(1))->call('GET', "admin/company/media/$company->id/default");  
		$this->assertResponseStatus(200)->see(0);
	}

	public function test_export_excel()
	{ 
		$user = User::find(1);
		$this->actingAs($user)->call('GET', 'admin/company');
		$response =  $this->actingAs($user)->call('GET', '/company/excel'); 

		$this->assertEquals(200, $response->status());
		$this->assertTrue($response->headers->get('content-type') == 'application/vnd.ms-excel');
		$this->assertTrue($response->headers->get('content-description') == 'File Transfer');
		$this->assertTrue($response->headers->get('content-disposition') == 'attachment; filename="Data Perusahaan.xlsx"');
	}
	public function test_import_excel()
	{ 
	   
	   $user = User::find(1); 
	   $response =  $this->actingAs($user)->call('POST', '/company/importExcel');  

       $this->assertEquals(302, $response->status());
	}
    public function test_update_company()
	{ 
		$user = User::find(1);
       	$company = Company::latest()->first();
		$response =  $this->actingAs($user)->call('PUT', 'admin/company/'.$company->id, 
		[ 
	        'name' => str_random(10),
	        'address' => str_random(10),
	        'city' => str_random(10) ,
	        'email' =>  str_random(10),
	        'postal_code' => str_random(10) ,
	        'phone_number' =>mt_rand(0,10000) ,
	        'fax' => str_random(10) ,
	        'npwp_number' => mt_rand(0,10000) ,
	        'npwp_file' => str_random(10) ,
	        'siup_number' => str_random(10) ,
	        'siup_file' => str_random(10), 
	        'siup_date' => str_random(10), 
	        'qs_certificate_number' => str_random(10) ,
	        'qs_certificate_file' => str_random(10) ,
	        'qs_certificate_date' => str_random(10), 
	        'is_active' => mt_rand(0,1),
	        'created_by' => mt_rand(0,1), 
	        'updated_by' => mt_rand(0,1), 
	        'plg_id' => mt_rand(0,10),
	        'nib' => mt_rand(0,1)
	    ]);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $company = factory(App\Company::class)->make();  
	}
    public function test_delete_company()
	{ 
		$user = User::find(1);
       	$company = Company::latest()->first();
		$response =  $this->actingAs($user)->call('DELETE', 'admin/company/'.$company->id);   
		// dd($response->getContent());
        $this->assertEquals(302, $response->status());
	    // $company = factory(App\Company::class)->make();  
	} 
    public function test_autocomplete_company()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET',"admin/adm_company_autocomplete/query");
        //Response status ok
        $this->assertResponseStatus(200); 
    }
}
