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


use App\Events\Notification;
use App\NotificationTable;

class ProfileController extends Controller
{ 


    private const PASS_TEXT = 'password';
    private const NEW_PASS = 'newPass';
    private const CONFNEWPASS = 'confnewPass';
    private const ERROR_NEW_PASS = 'error_newpass';
    private const MEDIA_USER = '/media/user/';
    private const HIDE_ID_USER = 'hide_id_user';
    private const USER_PICTURE = 'userPicture';
    private const IMAGE = 'image';
    private const ERROR_IMG_TYPE = 'error_img_type';
    private const PATH_PROFILE = 'profile_'; 
    private const EMAIL = 'email';
    private const ADDRESS = 'address';
    private const PHONE = 'phone';
    private const EMAIL2 = 'email2';
    private const EMAIL3 = 'email3';
    private const FORMAT_DATE = 'Y-m-d H:i:s';
    private const PAGE_PROFILE = '/client/profile';
    private const NPWP_FILE = 'npwp_file';
    private const MEDIA_COMPANY = '/media/tempCompany/';
    private const ERROR_COMPANY = 'error_company';
    private const SIUP_FILE = 'siup_file';
    private const CERTIFICATE_FILE = 'certificate_file';
    private const ADMIN_TEXT = 'admin';
    private const MESSAGE = 'message';
    private const ADMIN_EDIT = '/edit';
    private const CREATED_AT = 'created_at';
    private const UPDATED_AT = 'updated_at';
    private const ERROR_EMAIL = 'error_email';
    private const ERROR = 'error';
    private const COMP_NAME = 'comp_name';
    private const COMP_ADDRESS = 'comp_address';
    private const COMP_EMAIL = 'comp_email';
    private const COMP_NPWP_FILE = 'comp_npwp_file'; 
    private const PAGE_COMPANY_CREATE = '/admin/company/create';
    private const COMP_SIUP_FILE = 'comp_siup_file';
    private const COMP_QS_CERTIFICATE_FILE = 'comp_qs_certificate_file';
    private const USER_NAME = 'user_name';
    private const USER_EMAIL = 'user_email';
    private const EMAIL_STEL= 'urelddstelkom@gmail.com';
    private const STATUS = 'status';
    private const COMPANY_ID = 'company_id';
    private const ATTRIBUTES = 'attributes';
    private const HIDE_PIC_FILE = 'hide_pic_file';
    private const IS_READ = 'is_read';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$tabs = $request->input('tabs') == 'company' ? 'company' : 'profile';
		$query = "SELECT * FROM companies WHERE id != 1 AND is_active = 1";
		$data_company = DB::select($query);		
		$currentUser = Auth::user();
		if ($currentUser){
			$myComp = Company::where('id', $currentUser[self::ATTRIBUTES][self::COMPANY_ID])->first();
            return view('client.profile.index')
                ->with('data', $currentUser[self::ATTRIBUTES])
                ->with('data_company', $data_company)
                ->with('tabs', $tabs)
                ->with('myComp', $myComp);
        }
    }
	
	public function update(Request $request)
    { 
		$currentUser = Auth::user(); 
		if (Hash::check($request->input('currPass'), $currentUser[self::ATTRIBUTES][self::PASS_TEXT])) {
			$hashedPassword = $currentUser[self::ATTRIBUTES][self::PASS_TEXT];
		}else{
			return back()
			->with('error_pass', 1)
			->withInput($request->all());
		}
		if($request->input(self::NEW_PASS) == '' && $request->input(self::CONFNEWPASS) == ''){
		}
		else{
			if($request->input(self::NEW_PASS) == '' || $request->input(self::CONFNEWPASS) == ''){
				return back()
				->with(self::ERROR_NEW_PASS, 1)
				->withInput($request->all());
			}
			if($request->input(self::NEW_PASS) == $request->input(self::CONFNEWPASS)){
				$hashedPassword = Hash::make($request->input(self::NEW_PASS));
			}
			else{
				return back()
				->with(self::ERROR_NEW_PASS, 2)
				->withInput($request->all());
			}
		}
 		$path_file = public_path().self::MEDIA_USER.$request->input(self::HIDE_ID_USER).'';
		if ($request->hasFile(self::USER_PICTURE)) {
			$type_file = $request->file(self::USER_PICTURE)->getMimeType();
			$data_type_file = explode('/',$type_file);
			if($data_type_file[0] != self::IMAGE)	{
				return redirect()->back()
				->with(self::ERROR_IMG_TYPE, 1)
				->withInput($request->all()); 
			}else{ 
				$name_file = self::PATH_PROFILE.$request->file(self::USER_PICTURE)->getClientOriginalName();
				$request->file(self::USER_PICTURE)->move($path_file, $name_file);
				$fuserPicture = $name_file;
				if (File::exists(public_path().'\media\user\\'.$request->input(self::HIDE_ID_USER).'\\'.$request->input(self::HIDE_PIC_FILE))){
					File::delete(public_path().'\media\user\\'.$request->input(self::HIDE_ID_USER).'\\'.$request->input(self::HIDE_PIC_FILE));
				}
			}
		}else{
			$fuserPicture = $request->input(self::HIDE_PIC_FILE);
		}
		
		try{
			$query_update_user = "UPDATE users
				SET 
					name = '".$request->input(self::USER_NAME)."',
					email = '".$request->input(self::EMAIL)."',
					password = '".$hashedPassword."',
					picture = '".$fuserPicture."',
					address = '".$request->input(self::ADDRESS)."',
					phone_number = '".$request->input(self::PHONE)."',
					fax = '".$request->input('fax')."',
					email2 = '".$request->input(self::EMAIL2)."',
					email3 = '".$request->input(self::EMAIL3)."',
					updated_by = '".$currentUser[self::ATTRIBUTES]['id']."',
					updated_at = '".date(self::FORMAT_DATE)."'
				WHERE id = '".$request->input(self::HIDE_ID_USER)."'
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


			DB::update($query_update_user);
            Session::flash('message_profile', 'Profile Has Been Updated');
        } catch(Exception $e){
            Session::flash('error_profile', 'Fail to Update Profile');
        }
		return redirect(self::PAGE_PROFILE);
    }
	
	public function updateCompany(Request $request)
    { 
		$description = '';
		$count_commit = 0;
		$currentUser = Auth::user(); 
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
			
		if($request->input(self::ADDRESS) != $request->input('hide_address')){
			$temp->address = $request->input(self::ADDRESS);
			$count_commit ++ ;
			$description = $description.'Alamat, ';
		}	
			
		if($request->input('plg_id') != $request->input('hide_plg_id')){
			$temp->plg_id = $request->input('plg_id');
			$count_commit ++ ;
			$description = $description.'PLG_ID, ';
		}	
			
		if($request->input('nib') != $request->input('hide_nib')){
			$temp->nib = $request->input('nib');
			$count_commit ++ ;
			$description = $description.'NIB, ';
		}	
			
		if($request->input('city') != $request->input('hide_city')){
			$temp->city = $request->input('city');
			$count_commit ++ ;
			$description = $description.'Kota, ';
		}	
			
		if($request->input(self::EMAIL) != $request->input('hide_email')){
			$temp->email = $request->input(self::EMAIL);
			$count_commit ++ ;
			$description = $description.'Email, ';
		}	
			
		if($request->input('postal_code') != $request->input('hide_postal_code')){
			$temp->postal_code = $request->input('postal_code');
			$count_commit ++ ;
			$description = $description.'Kode POS, ';
		}	
			
		if($request->input(self::PHONE) != $request->input('hide_phone')){
			$temp->phone_number = $request->input(self::PHONE);
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
		
		if ($request->hasFile(self::NPWP_FILE)) { 
            $name_file = 'npwp_'.$request->file(self::NPWP_FILE)->getClientOriginalName();
			$path_file = public_path().self::MEDIA_COMPANY.$company_id.'';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
				$path_file = $path_file.'/'.$temp_id.'';
			if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::NPWP_FILE)->move($path_file,$name_file)){
                $temp->npwp_file = $name_file;
				$count_commit ++ ;
				$description = $description.'File NPWP, ';
            }else{
                Session::flash(self::ERROR_COMPANY, 'Upload NPWP failed');
                return redirect(self::PAGE_PROFILE);
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
		
		if ($request->hasFile(self::SIUP_FILE)) { 
            $name_file = 'siupp_'.$request->file(self::SIUP_FILE)->getClientOriginalName();
			$path_file = public_path().self::MEDIA_COMPANY.$company_id.'';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
				$path_file = $path_file.'/'.$temp_id.'';
			if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::SIUP_FILE)->move($path_file,$name_file)){
                $temp->siup_file = $name_file;
				$count_commit ++ ;
				$description = $description.'File SIUPP, ';
            }else{
                Session::flash(self::ERROR_COMPANY, 'Upload SIUPP failed');
                return redirect(self::PAGE_PROFILE);
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
		
		if ($request->hasFile(self::CERTIFICATE_FILE)) { 
            $name_file = 'serti_uji_mutu_'.$request->file(self::CERTIFICATE_FILE)->getClientOriginalName();
			$path_file = public_path().self::MEDIA_COMPANY.$company_id.'';
            if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
				$path_file = $path_file.'/'.$temp_id.'';
			if (!file_exists($path_file)) {
                mkdir($path_file, 0775);
            }
            if($request->file(self::CERTIFICATE_FILE)->move($path_file,$name_file)){
                $temp->qs_certificate_file = $name_file;
				$count_commit ++ ;
				$description = $description.'File Sertifikat Uji Mutu, ';
            }else{
                Session::flash(self::ERROR_COMPANY, 'Upload Certificate failed');
                return redirect(self::PAGE_PROFILE);
            }
        }     	
		
		if($count_commit == 0){
			Session::flash(self::ERROR_COMPANY, 'Nothing to commit');
            return redirect(self::PAGE_PROFILE);
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


	        $currentUser = Auth::user();
			$data= array( 
	        "from"=>$currentUser->id,
	        "to"=>self::ADMIN_TEXT,
	        self::MESSAGE=>$currentUser->company->name." mengedit data Perusahaan ",
	        "url"=>"tempcompany/".$temp->id.self::ADMIN_EDIT,
	        "is_read"=>0,
	        self::CREATED_AT=>date(self::FORMAT_DATE),
	        self::UPDATED_AT=>date(self::FORMAT_DATE)
	        );
		  	$notification = new NotificationTable();
			$notification->id = Uuid::uuid4();
	      	$notification->from = $data['from'];
	      	$notification->to = $data['to'];
	      	$notification->message = $data[self::MESSAGE];
	      	$notification->url = $data['url'];
	      	$notification->is_read = $data[self::IS_READ];
	      	$notification->created_at = $data[self::CREATED_AT];
	      	$notification->updated_at = $data[self::UPDATED_AT];
	      	$notification->save();
	      	$data['id'] = $notification->id; 
	        event(new Notification($data));



			$this->sendEmail($currentUser->id, $description, "emails.editCompany", "Permintaan Edit Data Perusahaan");
            Session::flash('message_company', 'Company Data Has Been Commited');
        } catch(Exception $e){
            Session::flash(self::ERROR_COMPANY, 'Fail to Commit');
        }
		return redirect(self::PAGE_PROFILE);
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
		$user_id = Uuid::uuid4();
		
		if($request->input('hide_is_company_too') == 0){ 
			if($request->input(self::EMAIL) == ''){
				return redirect()->back()
				->with(self::ERROR_EMAIL, 1) 
				->withInput($request->all());
			}
			$email_exists = $this->cekEmail($request->input(self::EMAIL));
			if($email_exists == 1){
				return redirect()->back()
				->with(self::ERROR_EMAIL, 2)
				->withInput($request->all());
			} 
			if($request->input(self::NEW_PASS) == $request->input(self::CONFNEWPASS)){ 
				$hashedPassword = Hash::make($request->input(self::NEW_PASS));
			}
			else{
				return redirect()->back()
				->with(self::ERROR_NEW_PASS, 2)
				->withInput($request->all());
			}
			
			$path_file = public_path().self::MEDIA_USER.$user_id.'';
			if (!file_exists($path_file)) {
				mkdir($path_file, 0775);
			}
			if ($request->hasFile(self::USER_PICTURE)) {
				$type_file = $request->file(self::USER_PICTURE)->getMimeType();
				$data_type_file = explode('/',$type_file);
				if($data_type_file[0] != self::IMAGE)	{
					return redirect()->back()
					->with(self::ERROR_IMG_TYPE, 1)
					->withInput($request->all());
				}else{ 
					$name_file = self::PATH_PROFILE.$request->file(self::USER_PICTURE)->getClientOriginalName();
					if($request->file(self::USER_PICTURE)->move($path_file,$name_file)){
						$fuserPicture = $name_file;
					}
					else{
						Session::flash(self::ERROR, 'Save Profile Picture to directory failed');
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
					self::COMPANY_ID => ''.$request->input('cmb-perusahaan').'', 
					'name' => ''.$request->input(self::USER_NAME).'', 
					self::ADDRESS => ''.$request->input(self::ADDRESS).'', 
					'phone_number' => ''.$request->input(self::PHONE).'', 
					'fax' => ''.$request->input('fax').'', 
					self::EMAIL => ''.$request->input(self::EMAIL).'', 
					self::PASS_TEXT => ''.$hashedPassword.'', 
					'is_active' => 0, 
					'remember_token' => ''.Str::random(60).'', 
					'created_by' => ''.$user_id.'', 
					'updated_by' => ''.$user_id.'', 
					self::CREATED_AT => ''.date(self::FORMAT_DATE).'', 
					self::UPDATED_AT => ''.date(self::FORMAT_DATE).'',
					'picture' => ''.$fuserPicture.'',
					self::EMAIL2 => ''.$request->input(self::EMAIL2).'', 
					self::EMAIL3 => ''.$request->input(self::EMAIL3).'', 
				]
			]);
			$logs = new Logs; 
	        $logs->user_id = $user_id;
	        $logs->id = Uuid::uuid4();
	        $logs->action = "Register";   
	        $logs->data = "";
	        $logs->created_by = $user_id;
	        $logs->page = "REGISTER";
	        $logs->save(); 


			$data= array( 
	        "from"=>$user_id,
	        "to"=>self::ADMIN_TEXT,
	        self::MESSAGE=>"Permohonan Aktivasi Akun Baru",
	        "url"=>"usereks/".$user_id.self::ADMIN_EDIT,
	        "is_read"=>0,
	        self::CREATED_AT=>date(self::FORMAT_DATE),
	        self::UPDATED_AT=>date(self::FORMAT_DATE)
	        );
		  	$notification = new NotificationTable();
			$notification->id = Uuid::uuid4();
	      	$notification->from = $data['from'];
	      	$notification->to = $data['to'];
	      	$notification->message = $data[self::MESSAGE];
	      	$notification->url = $data['url'];
	      	$notification->is_read = $data[self::IS_READ];
	      	$notification->created_at = $data[self::CREATED_AT];
	      	$notification->updated_at = $data[self::UPDATED_AT];
	      	$notification->save();
	      	$data['id'] = $notification->id; 
	        event(new Notification($data)); 

			$this->sendRegistrasi($request->input(self::USER_NAME), $request->input(self::EMAIL), "emails.registrasiCust", "Permintaan Aktivasi Data Akun Baru");
			
			return redirect('/login')->with('send_new_user', 5);
		}else{
			$company = new Company;
			$company->id = Uuid::uuid4();
			$company->name = $request->input(self::COMP_NAME);
			$company->address = $request->input(self::COMP_ADDRESS);
			$company->plg_id = $request->input('comp_plg_id');
			$company->nib = $request->input('comp_nib');
			$company->city = $request->input('comp_city');
			$company->email = $request->input(self::COMP_EMAIL);
			$company->postal_code = $request->input('comp_postal_code');
			$company->phone_number = $request->input('comp_phone_number');
			$company->fax = $request->input('comp_fax');
			$company->npwp_number = $request->input('comp_npwp_number');
			$company->siup_number = $request->input('comp_siup_number');
			$company->siup_date = $request->input('comp_siup_date');
			$company->qs_certificate_number = $request->input('comp_qs_certificate_number');

			if ($request->hasFile(self::COMP_NPWP_FILE)) { 
				$name_file = 'npwp_'.$request->file(self::COMP_NPWP_FILE)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_COMPANY.$company->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::COMP_NPWP_FILE)->move($path_file,$name_file)){
					$company->npwp_file = $name_file;
				}else{
					Session::flash(self::ERROR, 'Save NPWP to directory failed');
					return redirect(self::PAGE_COMPANY_CREATE);
				}
			}        
			if ($request->hasFile(self::COMP_SIUP_FILE)) { 
				$name_file = 'siupp_'.$request->file(self::COMP_SIUP_FILE)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_COMPANY.$company->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::COMP_SIUP_FILE)->move($path_file,$name_file)){
					$company->siup_file = $name_file;
				}else{
					Session::flash(self::ERROR, 'Save SIUP to directory failed');
					return redirect(self::PAGE_COMPANY_CREATE);
				}
			}
			if ($request->hasFile(self::COMP_QS_CERTIFICATE_FILE)) { 
				$name_file = 'serti_uji_mutu_'.$request->file(self::COMP_QS_CERTIFICATE_FILE)->getClientOriginalName();
				$path_file = public_path().self::MEDIA_COMPANY.$company->id;
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if($request->file(self::COMP_QS_CERTIFICATE_FILE)->move($path_file,$name_file)){
					$company->qs_certificate_file = $name_file;
				}else{
					Session::flash(self::ERROR, 'Save QS certificate to directory failed');
					return redirect(self::PAGE_COMPANY_CREATE);
				}
			}
			
			$company->qs_certificate_date = $request->input('comp_qs_certificate_date');
			$company->is_active = 0;
			$company->created_by = $user_id;
			$company->updated_by = $user_id;

			try{ 
				if($request->input(self::EMAIL) == ''){
					return redirect()->back()
					->with(self::ERROR_EMAIL, 1) 
					->withInput($request->all());
				}
					$email_exists = $this->cekEmail($request->input(self::EMAIL));
					if($email_exists == 1){
						return redirect()->back()
						->with(self::ERROR_EMAIL, 2)
						->withInput($request->all());
					}
				if($request->input(self::NEW_PASS) == '' || $request->input(self::CONFNEWPASS) == ''){
					return redirect()->back()
					->with(self::ERROR_NEW_PASS, 1)
					->withInput($request->all());
				}
				if($request->input(self::NEW_PASS) == $request->input(self::CONFNEWPASS)){ 
					$hashedPassword = Hash::make($request->input(self::NEW_PASS));
				}
				else{
					return redirect()->back()
					->with(self::ERROR_NEW_PASS, 2)
					->withInput($request->all());
				}
				$user_id = Uuid::uuid4();
				
				$path_file = public_path().self::MEDIA_USER.$user_id.'';
				if (!file_exists($path_file)) {
					mkdir($path_file, 0775);
				}
				if ($request->hasFile(self::USER_PICTURE)) {
					$type_file = $request->file(self::USER_PICTURE)->getMimeType();
					$data_type_file = explode('/',$type_file);
					if($data_type_file[0] != self::IMAGE)	{
						return redirect()->back()
						->with(self::ERROR_IMG_TYPE, 1)
						->withInput($request->all());
					}else{ 
						$name_file = self::PATH_PROFILE.$request->file(self::USER_PICTURE)->getClientOriginalName();
						if($request->file(self::USER_PICTURE)->move($path_file,$name_file)){
							$fuserPicture = $name_file;
						}
						else{
							Session::flash(self::ERROR, 'Save Profile Picture to directory failed');
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
						self::COMPANY_ID => ''.$company->id.'', 
						'name' => ''.$request->input(self::USER_NAME).'', 
						self::ADDRESS => ''.$request->input(self::ADDRESS).'', 
						'phone_number' => ''.$request->input(self::PHONE).'', 
						'fax' => ''.$request->input('fax').'', 
						self::EMAIL => ''.$request->input(self::EMAIL).'', 
						self::PASS_TEXT => ''.$hashedPassword.'', 
						'is_active' => 0, 
						'remember_token' => ''.Str::random(60).'', 
						'created_by' => ''.$user_id.'', 
						'updated_by' => ''.$user_id.'', 
						self::CREATED_AT => ''.date(self::FORMAT_DATE).'', 
						self::UPDATED_AT => ''.date(self::FORMAT_DATE).'',
						'picture' => ''.$fuserPicture.'',
						self::EMAIL2 => ''.$request->input(self::EMAIL2).'', 
						self::EMAIL3 => ''.$request->input(self::EMAIL3).'', 
					]
				]);

				$logs = new Logs; 
		        $logs->user_id = $user_id;
		        $logs->id = Uuid::uuid4();
		        $logs->action = "Create Company";   
		        $logs->data = "";
		        $logs->created_by = $user_id;
		        $logs->page = "REGISTER";
		        $logs->save();

		        $data= array( 
		        "from"=>$user_id,
		        "to"=>self::ADMIN_TEXT,
		        self::MESSAGE=>"Permohonan Aktivasi Akun Baru dan Perusahaan Baru",
		        "url"=>"usereks/".$user_id.self::ADMIN_EDIT,
		        "is_read"=>0,
		        self::CREATED_AT=>date(self::FORMAT_DATE),
		        self::UPDATED_AT=>date(self::FORMAT_DATE)
		        );
			  	$notification = new NotificationTable();
				$notification->id = Uuid::uuid4();
		      	$notification->from = $data['from'];
		      	$notification->to = $data['to'];
		      	$notification->message = $data[self::MESSAGE];
		      	$notification->url = $data['url'];
		      	$notification->is_read = $data[self::IS_READ];
		      	$notification->created_at = $data[self::CREATED_AT];
		      	$notification->updated_at = $data[self::UPDATED_AT];
		      	$notification->save();
		      	$data['id'] = $notification->id; 
		        event(new Notification($data)); 

				$this->sendRegistrasiwCompany(
					$request->input(self::USER_NAME), 
					$request->input(self::EMAIL), 
					$request->input(self::COMP_NAME), 
					$request->input(self::COMP_ADDRESS).', '.$request->input('comp_city').' '.$request->input('comp_postal_code').'.', 
					$request->input(self::COMP_EMAIL), 
					$request->input('comp_phone_number'), 
					"emails.registrasiCustCompany", 
					"Permintaan Aktivasi Data Perusahaan dan Akun Baru"
				);
				
				return redirect('/login')->with('send_new_user', 5);
			} catch(Exception $e){
				Session::flash(self::ERROR, 'Save failed');
				return redirect(self::PAGE_COMPANY_CREATE);
			}
		}
		
    }
	
	
    function cekEmail($email)
    {
		$user = User::where(self::EMAIL,'=',''.$email.'')->get();
		return count($user);
    }
	
	public function sendEmail($user, $description, $message, $subject)
    {
        $data = User::findOrFail($user);
		
        Mail::send($message, array(
			self::USER_NAME => $data->name,
			self::USER_EMAIL => $data->email,
			'desc' => $description
			), function ($m) use ($subject) {
            $m->to(self::EMAIL_STEL)->subject($subject);
        });

        return true;
    }
	
	public function sendRegistrasi($user_name, $user_email, $message, $subject)
    {
        Mail::send($message, array(
			self::USER_NAME => $user_name,
			self::USER_EMAIL => $user_email
			), function ($m) use ($subject) {
            $m->to(self::EMAIL_STEL)->subject($subject);
        });

        return true;
    }
	
	public function sendRegistrasiwCompany($user_name, $user_email, $comp_name, $comp_address, $comp_email, $comp_phone, $message, $subject)
    {
        Mail::send($message, array(
			self::USER_NAME => $user_name,
			self::USER_EMAIL => $user_email,
			self::COMP_NAME => $comp_name,
			self::COMP_ADDRESS => $comp_address,
			self::COMP_EMAIL => $comp_email,
			'comp_phone' => $comp_phone
			), function ($m) use ($subject) {
            $m->to(self::EMAIL_STEL)->subject($subject);
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

    public function checkRegisterEmail(Request $request){
    	$email = $request->input(self::EMAIL);
    	if(isset($email)){
    		$email_exists = $this->cekEmail($email);
		 	if($email_exists == 1){ 
    			return response()->json([self::STATUS=>false,self::MESSAGE=>'Email Already Exist']);	
    		}else{
    			return response()->json([self::STATUS=>true,self::MESSAGE=>'Email is Available']);
    		}
    		
    	}else{
    		return response()->json([self::STATUS=>false,self::MESSAGE=>'Email Is Required']);
    	}
    }
}
