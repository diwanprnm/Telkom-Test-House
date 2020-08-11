<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use App\Company;  
use App\User;  

class CompanyControllerTest extends TestCase
{
	use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */

	public function testDeleteSoon(){
        $this->assertTrue(true);
    }

 //    public function test_visit_company()
	// { 
	//    $response = $this->call('GET', 'admin/company');  
 //       $this->assertEquals(200, $response->status());
	// }
	// public function test_search_company()
	// { 
	//    $response = $this->call('GET', 'admin/company?search=asda&is_active=&after_date=&before_date=');  
 //       $this->assertEquals(200, $response->status());
	// }
 //    public function test_stores_company()
	// { 
	// 	$user = User::find(1);
       
	// 	$response =  $this->actingAs($user)->call('POST', 'admin/company', 
	// 	[ 
	//         'name' => str_random(10),
	//         'address' => str_random(10),
	//         'city' => str_random(10) ,
	//         'email' =>  str_random(10),
	//         'postal_code' => str_random(10) ,
	//         'phone_number' =>mt_rand(0,10000) ,
	//         'fax' => str_random(10) ,
	//         'npwp_number' => mt_rand(0,10000) ,
	//         'npwp_file' => str_random(10) ,
	//         'siup_number' => str_random(10) ,
	//         'siup_file' => str_random(10), 
	//         'siup_date' => str_random(10), 
	//         'qs_certificate_number' => str_random(10) ,
	//         'qs_certificate_file' => str_random(10) ,
	//         'qs_certificate_date' => str_random(10), 
	//         'is_active' => mt_rand(0,1),
	//         'created_by' => mt_rand(0,1), 
	//         'updated_by' => mt_rand(0,1), 
	//         'plg_id' => mt_rand(0,10),
	//         'nib' => mt_rand(0,1)
	//     ]);   
	// 	// dd($response->getContent());
 //        $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }
 //    public function test_update_company()
	// { 
	// 	$user = factory(App\User::class)->create(); 
 //       	$company = Company::latest()->first();
	// 	$response =  $this->actingAs($user)->call('PUT', 'admin/company/'.$company->id, 
	// 	[ 
	//         'name' => str_random(10),
	//         'address' => str_random(10),
	//         'city' => str_random(10) ,
	//         'email' =>  str_random(10),
	//         'postal_code' => str_random(10) ,
	//         'phone_number' =>mt_rand(0,10000) ,
	//         'fax' => str_random(10) ,
	//         'npwp_number' => mt_rand(0,10000) ,
	//         'npwp_file' => str_random(10) ,
	//         'siup_number' => str_random(10) ,
	//         'siup_file' => str_random(10), 
	//         'siup_date' => str_random(10), 
	//         'qs_certificate_number' => str_random(10) ,
	//         'qs_certificate_file' => str_random(10) ,
	//         'qs_certificate_date' => str_random(10), 
	//         'is_active' => mt_rand(0,1),
	//         'created_by' => mt_rand(0,1), 
	//         'updated_by' => mt_rand(0,1), 
	//         'plg_id' => mt_rand(0,10),
	//         'nib' => mt_rand(0,1)
	//     ]);   
	// 	// dd($response->getContent());
 //        $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }
 //    public function test_delete_company()
	// { 
	// 	$user = factory(App\User::class)->create(); 
 //       	$company = Company::latest()->first();
	// 	$response =  $this->actingAs($user)->call('DELETE', 'admin/company/'.$company->id);   
	// 	// dd($response->getContent());
 //        $this->assertEquals(302, $response->status());
	//     // $company = factory(App\Company::class)->make();  
	// }
}
