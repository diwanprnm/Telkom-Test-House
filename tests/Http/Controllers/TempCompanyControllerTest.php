<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

use App\User;  
use App\TempCompany;

class TempCompanyControllerTest extends TestCase
{
   public function test_visit_tempcompany()
	{ 
	   $user = User::find(1);
	   $tempCompany = factory(App\TempCompany::class)->create();
	   factory(App\User::class)->create(["company_id"=>$tempCompany->company_id]);
	   $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany');   
       $this->assertEquals(200, $response->status());
	} 

	public function test__visit_tempcompany_with_search()
	{ 
		$this->actingAs(User::find(1))->call('GET', 'admin/tempcompany?is_commited=1&search=cari&before_date=2020-12-31&after_date=2020-01-01&sort_by=created_at&sort_type=desc');  
    	$this->assertResponseStatus(200);
	}

    public function test_visit_show_tempcompany()
	{ 
	   $tempCompany = App\TempCompany::latest()->first();
	   $this->actingAs(User::find(1))->call('GET', "admin/tempcompany/$tempCompany->id");  
	   $this->assertResponseStatus(200);
	}

	public function test_visit_edit_tempcompany()
	{ 
	   $tempCompany = App\TempCompany::latest()->first();
	   $this->actingAs(User::find(1))->call('GET', "admin/tempcompany/$tempCompany->id/edit");  
	   $this->assertResponseStatus(200);
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
    public function test_update_tempcompany()
	{ 
 
	    $user = User::find(1);  
		$tempCompany = factory(App\TempCompany::class)->create();
		$path = public_path('images').'/testing.jpg';
		$file = new UploadedFile($path, 'testing.jpg', filesize($path), 'image/*', null, true);

		$response =  $this->actingAs($user)->call('PATCH', 'admin/tempcompany/'.$tempCompany->id,[
			'is_commited'=>1,
			'name'=>str_random(10),
			'address'=>str_random(10),
			'plg_id'=>str_random(10),
			'nib'=>str_random(10),
			'city'=>str_random(10),
			'email'=>str_random(10),
			'postal_code'=>str_random(10),
			'phone_number'=>str_random(10),
			'fax'=>str_random(10),
			'npwp_number'=>str_random(10),
			'siup_number'=>str_random(10),
			'qs_certificate_number'=>str_random(10),
			'siup_date'=>'2020-12-12',
			'qs_certificate_date' => Carbon\Carbon::now(),
		],[],['npwp_file' => $file, 'siup_file' => $file, 'qs_certificate_file' => $file]);    
        $this->assertEquals(302, $response->status());  
	}


    public function test_autocomplete_tempcompany()
    {
        $user = User::find(1);
        $this->actingAs($user)->call('GET',"admin/adm_temp_company_autocomplete/query"); 
        $this->assertResponseStatus(200); 
    }

	public function test_delete_not_found_tempcompany()
	{
		$this->actingAs(User::find(1))->call('DELETE', 'admin/tempcompany/noDataFound');  
        $this->assertResponseStatus(302); 
	} 

    public function test_delete_tempcompany()
	{
		$tempCompany = factory(App\TempCompany::class)->create();
		$this->actingAs(User::find(1))->call('DELETE', 'admin/tempcompany/'.$tempCompany->id);  
        $this->assertResponseStatus(302); 
	} 

	public function test_view_media_npwp()
	{ 
	   
	   $user = User::find(1);
       $company = factory(App\TempCompany::class)->create();
	   $id = $company->id;
	   $name = "npwp"; 
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("tempCompany/$company->company_id/$company->id/$company->npwp_file", $file);
	    $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany/media/'.$id.'/'.$name);  
	  	 
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        \Storage::disk('minio')->delete("tempCompany/$company->id/$company->npwp_file");
	}
	public function test_view_media_siup()
	{ 
	   
	   $user = User::find(1);
       $company = factory(App\TempCompany::class)->create();
	   $id = $company->id;
	   $name = "siup";
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("tempCompany/$company->company_id/$company->id/$company->siup_file", $file);
	    $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany/media/'.$id.'/'.$name);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        \Storage::disk('minio')->delete("tempCompany/$company->id/$company->siup_file");
	}
	public function test_view_media_qs()
	{ 
	   
	   $user = User::find(1);
       $company = factory(App\TempCompany::class)->create();
	   $id = $company->id;
	   $name = "qs";
	    
	    $file = \Storage::disk('local_public')->get("images/testing.jpg"); 
        \Storage::disk('minio')->put("tempCompany/$company->company_id/$company->id/$company->qs_certificate_file", $file);
	    $response =  $this->actingAs($user)->call('GET', 'admin/tempcompany/media/'.$id.'/'.$name);  
	  
        $this->assertTrue($response->headers->get('content-type') == 'application/octet-stream');

        \Storage::disk('minio')->delete("tempCompany/$company->id/$company->qs_certificate_file");
	}

	public function test_view_media_default()
	{
		$company = factory(App\TempCompany::class)->create();
		$this->actingAs(User::find(1))->call('GET', "admin/tempcompany/media/$company->id/default");  
		$this->assertResponseStatus(200)->see(0);
	}
}
