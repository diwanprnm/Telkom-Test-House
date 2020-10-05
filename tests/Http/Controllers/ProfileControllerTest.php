<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User; 
use App\Company; 
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Http\Controllers\ProfileController; 
class ProfileControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_visit()
	{ 
	   $user =   factory(App\User::class)->create(['role_id' => '2']);
	   $response =  $this->actingAs($user)->call('GET', '/client/profile?tabs=profile');  
       $this->assertEquals(200, $response->status());
	}
    public function test_update_user()
	{ 
	    $user = User::where("role_id","=","2")->first();  
		$response =  $this->actingAs($user)->call('POST', '/client/profile', 
		[ 
	        'hide_id_user' => $user->id,  
	        'username' => str_random(10),  
	        'email' => str_random(10), 
	        'address' => str_random(10), 
	        'password' => str_random(10), 
	        'phone_number' => 1, 
	        'fax' => str_random(10), 
	        'email2' => str_random(10), 
	        'email3' => str_random(10) 
	    ]);    
	    
        $this->assertEquals(302, $response->status()); 
	}
    public function test_update_company()
	{ 
	    $user = factory(App\User::class)->create(['role_id'=>2]);
		$response =  $this->actingAs($user)->call('POST', '/client/company', 
		[ 
	        'hide_id_company' => $user->company_id,   
	        'name' => str_random(10),   
	        'address' => str_random(10), 
	        'plg_id' => str_random(10), 
	        'nib' => 1, 
	        'city' => str_random(10), 
	        'email' => str_random(10), 
	        'postal_code' => str_random(10), 
	        'phone' => str_random(10) , 
	        'fax' => str_random(10) , 
	        'npwp_number' => str_random(10), 
	        'siup_number' => str_random(10) ,
	        'certificate_number' => str_random(10) ,
	        'certificate_number' => str_random(10) ,
	    ]);    
	    
        $this->assertEquals(302, $response->status()); 
	}
    public function test_check_registrasi_email()
	{ 
	    $user = User::find(1);   
		$response =  $this->actingAs($user)->call('POST', '/checkRegisterEmail', 
		[  
	        'email' => str_random(10) 
	    ]);    
	    
        $this->assertEquals(200, $response->status()); 
	}
    public function test_register()
	{ 
	    $user = User::find(1);    
		$response =  $this->actingAs($user)->call('GET', '/register');    
	    
        $this->assertEquals(302, $response->status()); 
	}
    public function test_clientregister_nocompany()
	{ 
	    $user = User::find(1);    
		$response =  $this->actingAs($user)->call('POST', '/client/register', 
		[  
	        'hide_is_company_too' => 0,
	        'email' => str_random(10),
	        'newPass' => "password",
	        'confnewPass' => "password"
	    ]);      
	    
        $this->assertEquals(302, $response->status()); 
	}

	public function test_clientregister_withcompany()
	{ 
	    $user = User::find(1);    
		$response =  $this->actingAs($user)->call('POST', '/client/register', 
		[  
	        'hide_is_company_too' => 1,
	        'email' => str_random(10),
	        'newPass' => str_random(10),
	        'confnewPass' => str_random(10),
	        'newPass' => str_random(10),
	        'username' => str_random(10),
	        'comp_qs_certificate_date' => '2020-12-12',
	    ]);      
	    
        $this->assertEquals(302, $response->status()); 
	}

	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	public function test_cekEmail()
	{   
		$object = app('App\Http\Controllers\ProfileController');
		$this->invokeMethod($object, 'cekEmail',array("email@mail.com"));
	}

	// public function test_sendEmail()
	// {   
	// 	$user = User::find(1);   
	// 	$object = app('App\Http\Controllers\ProfileController');
	// 	$this->invokeMethod($object, 'sendEmail',array($user->id,"","",""));
	// }
	// public function test_sendRegistrasi()
	// {   
	// 	$user = User::find(1);   
	// 	$object = app('App\Http\Controllers\ProfileController');
	// 	$this->invokeMethod($object, 'sendRegistrasi',array($user->user_name,$user->email,"",""));
	// }
	// public function test_sendRegistrasiwCompany()
	// {   
	// 	$user = User::find(1);   
	// 	$object = app('App\Http\Controllers\ProfileController');
	// 	$this->invokeMethod($object, 'sendRegistrasiwCompany',array($user->user_name,$user->email,"","","","","",""));
	// }

	public function test_checkRegisterEmail()
	{   
		
		$request = new Request(["email"=>"admin@mail.com"]);
		$object = app('App\Http\Controllers\ProfileController');
		$this->invokeMethod($object, 'checkRegisterEmail',array($request));
	}

	  public function test_login()
	{ 
	   $user =   factory(App\User::class)->create(['role_id' => '2']);
	   $response =  $this->actingAs($user)->call('GET', '/login');  
       $this->assertEquals(302, $response->status());
	}


	public function test_createUser()
	{    
		$user_id = Uuid::uuid4();
		$request = new Request([
			"user_name"=>str_random(10),
			"address"=>str_random(10),
			"phone"=>str_random(10),
			"fax"=>str_random(10),
			"email"=>str_random(10),
			"email2"=>str_random(10),
			"email3"=>str_random(10),
		]);

		$company =   factory(App\Company::class)->create();

		$object = app('App\Http\Controllers\ProfileController');
		$this->invokeMethod($object, 'createUser',array($request,$user_id,$company->id,"","","",""));
	}
}
