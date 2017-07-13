<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use App\Http\Requests;

use App\User;
use App\Company;
use App\TempCompany;
use App\Logs;

use Auth;
use File;
use Mail;
use Input;
use Session;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        // $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$query = "SELECT * FROM companies WHERE id != 1 AND is_active = 1";
		$data_company = DB::select($query);		
		
        $currentUser = Auth::user();
		if ($currentUser){
			$myComp = Company::where('id', $currentUser['attributes']['company_id'])->first();
            return view('client.profile.index')
                ->with('data', $currentUser['attributes'])
                ->with('data_company', $data_company)
                ->with('myComp', $myComp);
        }
    }
	
	public function update(Request $request)
    {
		// echo"<pre>";print_r($request->all());exit;
		$currentUser = Auth::user();
		// echo"<pre>";print_r($currentUser);
		if (Hash::check($request->input('currPass'), $currentUser['attributes']['password'])) {
			$hashedPassword = $currentUser['attributes']['password'];
		}else{
			return back()
			->with('error_pass', 1)
			->withInput($request->all());
		}
		if($request->input('newPass') == '' && $request->input('confnewPass') == ''){
		}
		else{
			if($request->input('newPass') == '' || $request->input('confnewPass') == ''){
				return back()
				->with('error_newpass', 1)
				->withInput($request->all());
			}
			if($request->input('newPass') == $request->input('confnewPass')){
				$hashedPassword = Hash::make($request->input('newPass'));
			}
			else{
				return back()
				->with('error_newpass', 2)
				->withInput($request->all());
			}
		}
			$path_file = public_path().'/media/user/'.$request->input('hide_id_user').'';
		if ($request->hasFile('userPicture')) {
			$type_file = $request->file('userPicture')->getMimeType();
			$data_type_file = explode('/',$type_file);
			if($data_type_file[0] != 'image')	{
				return redirect()->back()
				->with('error_img_type', 1)
				->withInput($request->all());
				exit;
			}else{
				/*$ext_file = $request->file('userPicture')->getClientOriginalName();
				$name_file = uniqid().'_user_'.$request->input('hide_id_user').'.'.$ext_file;*/
				$name_file = 'profile_'.$request->file('userPicture')->getClientOriginalName();
				$request->file('userPicture')->move($path_file, $name_file);
				$fuserPicture = $name_file;
				if (File::exists(public_path().'\media\user\\'.$request->input('hide_id_user').'\\'.$request->input('hide_pic_file'))){
					File::delete(public_path().'\media\user\\'.$request->input('hide_id_user').'\\'.$request->input('hide_pic_file'));
				}
			}
		}else{
			$fuserPicture = $request->input('hide_pic_file');
		}
		
		try{
			$query_update_user = "UPDATE users
				SET 
					name = '".$request->input('username')."',
					email = '".$request->input('email')."',
					password = '".$hashedPassword."',
					picture = '".$fuserPicture."',
					address = '".$request->input('address')."',
					phone_number = '".$request->input('phone')."',
					fax = '".$request->input('fax')."',
					email2 = '".$request->input('email2')."',
					email3 = '".$request->input('email3')."',
					updated_by = '".$currentUser['attributes']['id']."',
					updated_at = '".date('Y-m-d h:i:s')."'
				WHERE id = '".$request->input('hide_id_user')."'
			";

			$logs = new Logs;
			$currentUser = Auth::user();
	        $logs->user_id = $currentUser->id;
	        $logs->id = Uuid::uuid4();
	        $logs->action = "Update Profile";   
	        $logs->data = "";
	        $logs->created_by = $currentUser->id;
	        $logs->page = "PROFILE";
	        $logs->save();


			$data_update_user = DB::update($query_update_user);
            Session::flash('message_profile', 'Profile Has Been Updated');
        } catch(Exception $e){
            Session::flash('error_profile', 'Fail to Update Profile');
        }
		return redirect('/client/profile');
    }
	
	public function updateCompany(Request $request)
    {
		// echo"<pre>";print_r($request->all());
		$description = '';
		$count_commit = 0;
		$currentUser = Auth::user();
		// echo"<pre>";print_r($currentUser);
			$temp_id = Uuid::uuid4();
			$company_id = $request->input('hide_id_company');
			
		$temp = new TempCompany;
        $temp->id = $temp_id;
        $temp->company_id = $company_id;
        	
		if($request->input('name') != $request->input('hide_name')){
			$temp->name = $request->input('name');
			$count_commit ++ ;
			$description = $description.'Nama Perusahaan, ';
		}	
			
		if($request->input('address') != $request->input('hide_address')){
			$temp->address = $request->input('address');
			$count_commit ++ ;
			$description = $description.'Alamat, ';
		}	
			
		if($request->input('city') != $request->input('hide_city')){
			$temp->city = $request->input('city');
			$count_commit ++ ;
			$description = $description.'Kota, ';
		}	
			
		if($request->input('email') != $request->input('hide_email')){
			$temp->email = $request->input('email');
			$count_commit ++ ;
			$description = $description.'Email, ';
		}	
			
		if($request->input('postal_code') != $request->input('hide_postal_code')){
			$temp->postal_code = $request->input('postal_code');
			$count_commit ++ ;
			$description = $description.'Kode POS, ';
		}	
			
		if($request->input('phone') != $request->input('hide_phone')){
			$temp->phone_number = $request->input('phone');
			$count_commit ++ ;
			$description = $description.'No. Telepon, ';
		}	
			
		if($request->input('fax') != $request->input('hide_fax')){
			$temp->fax = $request->input('fax');
			$count_commit ++ ;
			$description = $description.'Faksimile, ';
		}	
			
		if($request->input('npwp_number') != $request->input('hide_npwp_number')){
			$temp->npwp_number = $request->input('npwp_number');
			$count_commit ++ ;
			$description = $description.'No. NPWP, ';
		}
		
		if ($request->hasFile('npwp_file')) {
            /*$ext_file = $request->file('npwp_file')->getClientOriginalExtension();
            $name_file = uniqid().'_npwp_'.$company_id.'.'.$ext_file;*/
            $name_file = 'npwp_'.$request->file('npwp_file')->getClientOriginalName();
			$path_file = public_path().'/media/tempCompany/'.$company_id.'';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
				$path_file = $path_file.'/'.$temp_id.'';
			if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('npwp_file')->move($path_file,$name_file)){
                $temp->npwp_file = $name_file;
				$count_commit ++ ;
				$description = $description.'File NPWP, ';
            }else{
                Session::flash('error_company', 'Upload NPWP failed');
                return redirect('/client/profile');
            }
        }     	
        
		if($request->input('siup_number') != $request->input('hide_siup_number')){
			$temp->siup_number = $request->input('siup_number');
			$count_commit ++ ;
			$description = $description.'No. SIUPP, ';
		}
			$timestamp = strtotime($request->input('siup_date'));
			$siupdate = date('Y-m-d', $timestamp);
		if($siupdate != $request->input('hide_siup_date')){
			$temp->siup_date = $siupdate;
			$count_commit ++ ;
			$description = $description.'Masa berlaku SIUPP, ';
		}
		
		if ($request->hasFile('siup_file')) {
            /*$ext_file = $request->file('siup_file')->getClientOriginalExtension();
            $name_file = uniqid().'_siupp_'.$company_id.'.'.$ext_file;*/
            $name_file = 'siupp_'.$request->file('siup_file')->getClientOriginalName();
			$path_file = public_path().'/media/tempCompany/'.$company_id.'';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
				$path_file = $path_file.'/'.$temp_id.'';
			if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('siup_file')->move($path_file,$name_file)){
                $temp->siup_file = $name_file;
				$count_commit ++ ;
				$description = $description.'File SIUPP, ';
            }else{
                Session::flash('error_company', 'Upload SIUPP failed');
                return redirect('/client/profile');
            }
        }     	
		
		if($request->input('certificate_number') != $request->input('hide_certificate_number')){
			$temp->qs_certificate_number = $request->input('certificate_number');
			$count_commit ++ ;
			$description = $description.'No. Sertifikat Uji Mutu, ';
		}
		
			$timestamp = strtotime($request->input('certificate_date'));
			$certificate = date('Y-m-d', $timestamp);
		if($certificate != $request->input('hide_certificate_date')){
			$temp->qs_certificate_date = $certificate;
			$count_commit ++ ;
			$description = $description.'Masa berlaku Sertifikat Uji Mutu, ';
		}
		
		if ($request->hasFile('certificate_file')) {
            /*$ext_file = $request->file('certificate_file')->getClientOriginalExtension();
            $name_file = uniqid().'_certificate_'.$company_id.'.'.$ext_file;*/
            $name_file = 'serti_uji_mutu_'.$request->file('certificate_file')->getClientOriginalName();
			$path_file = public_path().'/media/tempCompany/'.$company_id.'';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
				$path_file = $path_file.'/'.$temp_id.'';
			if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file('certificate_file')->move($path_file,$name_file)){
                $temp->qs_certificate_file = $name_file;
				$count_commit ++ ;
				$description = $description.'File Sertifikat Uji Mutu, ';
            }else{
                Session::flash('error_company', 'Upload Certificate failed');
                return redirect('/client/profile');
            }
        }     	
		
		if($count_commit == 0){
			Session::flash('error_company', 'Nothing to commit');
            return redirect('/client/profile');
		}
		
		try{
			$temp->is_commited = 0;
			$temp->created_by = $currentUser->id;
			$temp->updated_by = $currentUser->id;
			
			$temp->save();

				$logs = new Logs;
			$currentUser = Auth::user();
	        $logs->user_id = $currentUser->id;
	        $logs->id = Uuid::uuid4();
	        $logs->action = "Update Company";   
	        $logs->data = "";
	        $logs->created_by = $currentUser->id;
	        $logs->page = "PROFILE";
	        $logs->save();

			$this->sendEmail($currentUser->id, $description, "emails.editCompany", "Permintaan Edit Data Perusahaan");
            Session::flash('message_company', 'Company Data Has Been Commited');
        } catch(Exception $e){
            Session::flash('error_company', 'Fail to Commit');
        }
		return redirect('/client/profile');
    }
	
	public function register(Request $request)
    {
		$currentUser = Auth::user();
		if ($currentUser){
			return redirect()->back();
		}else{
			$query = "SELECT * FROM companies WHERE id != '1' AND is_active = 1 ORDER BY name";
			$data = DB::select($query);
			
			return view('client.profile.register')
				->with('data', $data);
		}
    }
	
	public function insert(Request $request)
    {
		// print_r($request->all());exit;
		$currentUser = Auth::user();
		$user_id = Uuid::uuid4();
		
		if($request->input('hide_is_company_too') == 0){
			// print_r($request->all());exit;
			if($request->input('email') == ''){
				return redirect()->back()
				->with('error_email', 1)
				// ->withInput($request->except("password"));
				->withInput($request->all());
			}
				$email_exists = $this->cekEmail($request->input('email'));
				if($email_exists == 1){
					return redirect()->back()
					->with('error_email', 2)
					->withInput($request->all());
				}
			// if($request->input('newPass') == '' || $request->input('confnewPass') == ''){
				// return redirect()->back()
				// ->with('error_newpass', 1)
				// ->withInput($request->all());
			// }
			if($request->input('newPass') == $request->input('confnewPass')){
				$password = $request->input('newPass');
			}
			else{
				return redirect()->back()
				->with('error_newpass', 2)
				->withInput($request->all());
			}
			
			$path_file = public_path().'/media/user/'.$user_id.'';
			if (!file_exists($path_file)) {
				mkdir($path_file, 0775);
			}
			if ($request->hasFile('userPicture')) {
				$type_file = $request->file('userPicture')->getMimeType();
				$data_type_file = explode('/',$type_file);
				if($data_type_file[0] != 'image')	{
					return redirect()->back()
					->with('error_img_type', 1)
					->withInput($request->all());
				}else{
					/*$ext_file = $request->file('userPicture')->getClientOriginalName();
					$name_file = uniqid().'_user_'.$user_id.'.'.$ext_file;*/
					$name_file = 'profile_'.$request->file('userPicture')->getClientOriginalName();
					if($request->file('userPicture')->move($path_file,$name_file)){
						$fuserPicture = $name_file;
					}
					else{
						Session::flash('error', 'Save Profile Picture to directory failed');
						return redirect()->back()
						->withInput($request->all());
					}
				}
			}else{
				$fuserPicture = '';
			}
			
			DB::table('users')->insert([
				[
					'id' => ''.$user_id.'', 
					'role_id' => '2',
					'company_id' => ''.$request->input('cmb-perusahaan').'', 
					'name' => ''.$request->input('username').'', 
					'address' => ''.$request->input('address').'', 
					'phone_number' => ''.$request->input('phone').'', 
					'fax' => ''.$request->input('fax').'', 
					'email' => ''.$request->input('email').'', 
					'password' => ''.bcrypt($password).'', 
					'is_active' => 0, 
					'remember_token' => ''.Str::random(60).'', 
					'created_by' => ''.$user_id.'', 
					'updated_by' => ''.$user_id.'', 
					'created_at' => ''.date('Y-m-d h:i:s').'', 
					'updated_at' => ''.date('Y-m-d h:i:s').'',
					'picture' => ''.$fuserPicture.'',
					'email2' => ''.$request->input('email2').'', 
					'email3' => ''.$request->input('email3').'', 
				]
			]);
			$logs = new Logs;
			// $currentUser = Auth::user();
	        $logs->user_id = $user_id;
	        $logs->id = Uuid::uuid4();
	        $logs->action = "Register";   
	        $logs->data = "";
	        $logs->created_by = $user_id;
	        $logs->page = "REGISTER";
	        $logs->save();

			$this->sendRegistrasi($request->input('username'), $request->input('email'), "emails.registrasiCust", "Permintaan Aktivasi Data Akun Baru");
			
			return redirect('/login')->with('send_new_user', 5);
		}else{
			$company = new Company;
			$company->id = Uuid::uuid4();
			$company->name = $request->input('comp_name');
			$company->address = $request->input('comp_address');
			$company->city = $request->input('comp_city');
			$company->email = $request->input('comp_email');
			$company->postal_code = $request->input('comp_postal_code');
			$company->phone_number = $request->input('comp_phone_number');
			$company->fax = $request->input('comp_fax');
			$company->npwp_number = $request->input('comp_npwp_number');
			$company->siup_number = $request->input('comp_siup_number');
			$company->siup_date = $request->input('comp_siup_date');
			$company->qs_certificate_number = $request->input('comp_qs_certificate_number');

			if ($request->hasFile('comp_npwp_file')) {
				/*$ext_file = $request->file('comp_npwp_file')->getClientOriginalExtension();
				$name_file = uniqid().'_npwp_'.$company->id.'.'.$ext_file;*/
				$name_file = 'npwp_'.$request->file('comp_npwp_file')->getClientOriginalName();
				$path_file = public_path().'/media/company/'.$company->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('comp_npwp_file')->move($path_file,$name_file)){
					$company->npwp_file = $name_file;
				}else{
					Session::flash('error', 'Save NPWP to directory failed');
					return redirect('/admin/company/create');
				}
			}        
			if ($request->hasFile('comp_siup_file')) {
				/*$ext_file = $request->file('comp_siup_file')->getClientOriginalExtension();
				$name_file = uniqid().'_siup_'.$company->id.'.'.$ext_file;*/
				$name_file = 'siupp_'.$request->file('comp_siup_file')->getClientOriginalName();
				$path_file = public_path().'/media/company/'.$company->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('comp_siup_file')->move($path_file,$name_file)){
					$company->siup_file = $name_file;
				}else{
					Session::flash('error', 'Save SIUP to directory failed');
					return redirect('/admin/company/create');
				}
			}
			if ($request->hasFile('comp_qs_certificate_file')) {
				/*$ext_file = $request->file('comp_qs_certificate_file')->getClientOriginalExtension();
				$name_file = uniqid().'_qs_certificate_'.$company->id.'.'.$ext_file;*/
				$name_file = 'serti_uji_mutu_'.$request->file('comp_qs_certificate_file')->getClientOriginalName();
				$path_file = public_path().'/media/company/'.$company->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file('comp_qs_certificate_file')->move($path_file,$name_file)){
					$company->qs_certificate_file = $name_file;
				}else{
					Session::flash('error', 'Save QS certificate to directory failed');
					return redirect('/admin/company/create');
				}
			}
			
			$company->qs_certificate_date = $request->input('comp_qs_certificate_date');
			$company->is_active = 0;
			$company->created_by = $user_id;
			$company->updated_by = $user_id;

			try{
				
				// print_r($request->all());exit;
				if($request->input('email') == ''){
					return redirect()->back()
					->with('error_email', 1)
					// ->withInput($request->except("password"));
					->withInput($request->all());
				}
					$email_exists = $this->cekEmail($request->input('email'));
					if($email_exists == 1){
						return redirect()->back()
						->with('error_email', 2)
						->withInput($request->all());
					}
				if($request->input('newPass') == '' || $request->input('confnewPass') == ''){
					return redirect()->back()
					->with('error_newpass', 1)
					->withInput($request->all());
				}
				if($request->input('newPass') == $request->input('confnewPass')){
					$password = $request->input('newPass');
				}
				else{
					return redirect()->back()
					->with('error_newpass', 2)
					->withInput($request->all());
				}
				$user_id = Uuid::uuid4();
				
				$path_file = public_path().'/media/user/'.$user_id.'';
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if ($request->hasFile('userPicture')) {
					$type_file = $request->file('userPicture')->getMimeType();
					$data_type_file = explode('/',$type_file);
					if($data_type_file[0] != 'image')	{
						return redirect()->back()
						->with('error_img_type', 1)
						->withInput($request->all());
					}else{
						/*$ext_file = $request->file('userPicture')->getClientOriginalName();
						$name_file = uniqid().'_user_'.$user_id.'.'.$ext_file;*/
						$name_file = 'profile_'.$request->file('userPicture')->getClientOriginalName();
						if($request->file('userPicture')->move($path_file,$name_file)){
							$fuserPicture = $name_file;
						}
						else{
							Session::flash('error', 'Save Profile Picture to directory failed');
							return redirect()->back()
							->withInput($request->all());
						}
					}
				}else{
					$fuserPicture = '';
				}
				$company->save();
				DB::table('users')->insert([
					[
						'id' => ''.$user_id.'', 
						'role_id' => '2',
						'company_id' => ''.$company->id.'', 
						'name' => ''.$request->input('username').'', 
						'address' => ''.$request->input('address').'', 
						'phone_number' => ''.$request->input('phone').'', 
						'fax' => ''.$request->input('fax').'', 
						'email' => ''.$request->input('email').'', 
						'password' => ''.bcrypt($password).'', 
						'is_active' => 0, 
						'remember_token' => ''.Str::random(60).'', 
						'created_by' => ''.$user_id.'', 
						'updated_by' => ''.$user_id.'', 
						'created_at' => ''.date('Y-m-d h:i:s').'', 
						'updated_at' => ''.date('Y-m-d h:i:s').'',
						'picture' => ''.$fuserPicture.'',
						'email2' => ''.$request->input('email2').'', 
						'email3' => ''.$request->input('email3').'', 
					]
				]);

				$logs = new Logs;
				$currentUser = Auth::user();
		        $logs->user_id = $currentUser->id;
		        $logs->id = Uuid::uuid4();
		        $logs->action = "Create Company";   
		        $logs->data = "";
		        $logs->created_by = $currentUser->id;
		        $logs->page = "REGISTER";
		        $logs->save();


				$this->sendRegistrasiwCompany(
					$request->input('username'), 
					$request->input('email'), 
					$request->input('comp_name'), 
					$request->input('comp_address').', '.$request->input('comp_city').' '.$request->input('comp_postal_code').'.', 
					$request->input('comp_email'), 
					$request->input('comp_phone_number'), 
					"emails.registrasiCustCompany", "Permintaan Aktivasi Data Perusahaan dan Akun Baru"
				);
				
				return redirect('/login')->with('send_new_user', 5);
			} catch(Exception $e){
				Session::flash('error', 'Save failed');
				return redirect('/admin/company/create');
			}
		}
		
    }
	
	
    function cekEmail($email)
    {
		$user = User::where('email','=',''.$email.'')->get();
		return count($user);
    }
	
	public function sendEmail($user, $description, $message, $subject)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			'user_name' => $data->name,
			'user_email' => $data->email,
			'desc' => $description
			), function ($m) use ($data,$subject) {
            $m->to('urelddstelkom@gmail.com')->subject($subject);
        });

        return true;
    }
	
	public function sendRegistrasi($user_name, $user_email, $message, $subject)
    {
        Mail::send($message, array(
			'user_name' => $user_name,
			'user_email' => $user_email
			), function ($m) use ($subject) {
            $m->to('urelddstelkom@gmail.com')->subject($subject);
        });

        return true;
    }
	
	public function sendRegistrasiwCompany($user_name, $user_email, $comp_name, $comp_address, $comp_email, $comp_phone, $message, $subject)
    {
        Mail::send($message, array(
			'user_name' => $user_name,
			'user_email' => $user_email,
			'comp_name' => $comp_name,
			'comp_address' => $comp_address,
			'comp_email' => $comp_email,
			'comp_phone' => $comp_phone
			), function ($m) use ($subject) {
            $m->to('urelddstelkom@gmail.com')->subject($subject);
        });

        return true;
    }

    public function login(){
    	$currentUser = Auth::user();
		if ($currentUser){
			return redirect("/");
		}else{
			$query = "SELECT * FROM companies WHERE id != '1' AND is_active = 1 ORDER BY name";
			$data = DB::select($query);
			
			return view('client.profile.login')
				->with('data', $data);
		}
    }
}
