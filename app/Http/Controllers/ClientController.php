<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Alumnus;
use App\Role;
use App\User;
use App\UserSchool;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use Hash;
use Auth;
use Cart;
use App\Logs;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/**
* Class AuthenticationController
*
* AuthenticateController adalah controller yang digunakan untuk me-manage
* proses autentikasi user ke aplikasi koloni.
* AuthenticateController inheritance terhadap class Controller.
* 
* 
* @author Nurwin Hermansyah
* @since version 1.0
* 
*/
class ClientController extends Controller
{	
	protected $loginPath = '/client/login';
	
	public function cekLogin(Request $request)
    {
        $currentUser = Auth::user();
		if ($currentUser){
            return 1;
        }else{
			return 0;
		}
    }
		
	public function login() {
		Session()->forget('type_url');
		// return view('pages.login');
		return back()->with('error_code', 5);
	}
	
	public function logout() {
		$logs = new Logs;
		$currentUser = Auth::user();
        $logs->user_id = $currentUser->id;
        $logs->id = Uuid::uuid4();
        $logs->action = "Logout";   
        $logs->data = "";
        $logs->created_by = $currentUser->id;
        $logs->page = "LOGOUT";
        $logs->save();
        Cart::destroy();
		Auth::logout();
		return redirect('/');
	}
	
	/**
	* Fungsi untuk melakukan autentikasi user dengan email dan password
	* jika sukses akan memberikan token dengan format JWT 
	*
	* @author Nurwin Hermansyah
	* @since version 1.0
	*
	* @param Request $request Request object user_email dan user_password
	* @return JSON Returns JSON object status dan data token
	*/
	public function authenticate(Request $request)
	{
		// print_r($request->all());exit;
		// grab credentials from the request
		$email_deleted = $this->cekDeleted($request->input('email'));
		if($email_deleted == 0){
			$credentials = $request->only('email', 'password');
			if (Auth::attempt($credentials)) {
				$logs = new Logs;
				$currentUser = Auth::user();
		        $logs->user_id = $currentUser->id;
		        $logs->id = Uuid::uuid4();
		        $logs->action = "Login";   
		        $logs->data = "";
		        $logs->created_by = $currentUser->id;
		        $logs->page = "LOGIN";
		        $logs->save();
				if($request->input('type_url') == 1){
					return redirect('/#portfolio');
				}else if($request->input('type_url') == 2){
					return redirect('/');
				}else{
					return back();
				}
			}else{
				if($request->input('type_url') == 1){
					return redirect('/#portfolio')
					->with('type_url', 1)
					->with('error_code', 5)
					->withInput($request->all())
					->withErrors('Email or Password Not Match');
				}else if($request->input('type_url') == 2){
					return redirect('/')->with('error_code', 5)
					->with('type_url', 2)
					->with('error_code', 5)
					->withInput($request->all())
					->withErrors('Email or Password Not Match');
				}else{
					return back()->with('error_code', 5)
					->with('error_code', 5)
					->withInput($request->all())
					->withErrors('Email or Password Not Match');;
				}
			}
		}else{
			if($request->input('type_url') == 1){
				return redirect('/#portfolio')
				->with('type_url', 1)
				->with('error_code', 5)
				->withInput($request->all())
				->withErrors('User not found or User Banned by admin');
			}else if($request->input('type_url') == 2){
				return redirect('/')->with('error_code', 5)
				->with('type_url', 2)
				->with('error_code', 5)
				->withInput($request->all())
				->withErrors('User not found or User Banned by admin');
			}else{
				return back()->with('error_code', 5)
				->with('error_code', 5)
				->withInput($request->all())
				->withErrors('User not found or User Banned by admin');;
			}
		}
	}
	
	/**
	* Fungsi untuk melakukan proses registrasi user ke aplikasi Infia.
	*
	* @author Rully Primangesta
	* @since version 1.0
	*
	* @param Request $request Request object user_email dan user_password
	* @return JSON Returns JSON object status dan data token
	*/
	public function register(Request $request) {
		$admin = Auth::user();

		if (!$admin->is("Member_".$request->get('school_id'))){
			$user = new User;
	        $user->id = Uuid::uuid4();
	        $user->email = $request->input('email');
	        $user->fullname = $request->input('fullname');
	        $user->password = bcrypt($request->input('password'));
			
			try {
				$user->save();

				$user_role = Role::where('name', '=', 'Member_'.$request->get('school_id'))->first();
				$user->attachRole($user_role);

				$alumni = new Alumnus;
				$alumni->user_id = $user->id;
				$alumni->fullname = $user->fullname;
				$alumni->email = $user->email;

				try {
					$alumni->save();

					$userSchool = new UserSchool;
					$userSchool->id = Uuid::uuid4();
					$userSchool->user_id = $user->id;
					$userSchool->school_id = $request->get('school_id');
					$userSchool->generation_id = $request->get('generation_id');
					$userSchool->class_id = $request->get('class_id');
					$userSchool->save();

					$result = array();
					$result['message'] = 'Success';
					$result['data'] = $user;
					
					return response()->json($result,200);
				} catch (\Illuminate\Database\QueryException $ex) {
					return Response(['message' => 'failed save alumni data profile', 'data' => new \stdClass()], 500);
				}
			} catch (\Illuminate\Database\QueryException $ex) {
				return Response(['message' => 'Email already exist', 'data' => new \stdClass()], 500);
			}	
		} else{
			return Response(['message' => 'You don\'t have permission to registering new user' , 'data' => new \stdClass()], 400);	
		}
    }
	
	/**
	* Fungsi untuk melakukan proses reset password user
	* aplikasi akan mengirimkan email password baru ke email user yang terdaftar
	*
	* @author Rully Primangesta
	* @since version 1.0
	*
	* @param Request $request Request object user_email
	* @return JSON Returns JSON object status dan message
	*/
	public function forgot(Request $request)
	{
		$user = User::where('email',$request->input('email'))->first();
		if($user){
			date_default_timezone_set("Asia/Jakarta");
			$date = date ("Y-m-d H:i:s", time());
			$timestamp = strtotime($date);
			
			$token = $request->input('email').'-'.$timestamp;
			$token_encrypt = $this->encryptIt($token);
		
			Mail::send('mail_forget', array('firstname'=>$user->fullname,'token'=>$token_encrypt), function($message) use ($user) {
				$message->to($user->email, $user->fullname)->subject('Forgot Password Koloni');
			});
			
			$result['message'] = 'Success';
			$result['data'] = $user;
			return response()->json($result,200);
		}else{
			return response()->json(['message' => 'User not found'], 404);
		}
	}
	
	public function resetPassword(Request $request){
		$token = $request->input('token');
		$dec_token = $this->decryptIt($token);
		$explode = explode('-',$dec_token);
		$email = $explode[0];
		$expire_time = $explode[1];
		
		date_default_timezone_set("Asia/Jakarta");
		$date = date ("Y-m-d H:i:s", time());
		$timestamp = strtotime($date);
		
		if($timestamp-$expire_time>3600){
			return Response(['message' => 'Token expire', 'data' => new \stdClass()], 200);
		}
		
		$user = User::where('email',$email)->first();
		if($user){
			$user->password = bcrypt($request->input('password'));
			try {
				$user->save();
				return Response(['message' => 'Success', 'data' => $user], 200);
			} catch (\Illuminate\Database\QueryException $ex) {
				return Response(['message' => 'Save failed', 'data' => new \stdClass()], 500);
			}
		}else{
			return Response(['message' => 'Email not found', 'data' => new \stdClass()], 404);
		}
	}
	
	private function encryptIt( $q ) {
		$qEncoded  = base64_encode($q);
		return( $qEncoded );
	}

	private function decryptIt( $q ) {
		$qDecoded = base64_decode($q);
		return( $qDecoded );
	}
	
	public function changePassword(Request $request){
		$currentUser = Auth::user();
		$old_password = $request->input('old_password');
		$new_password = $request->input('new_password');
		
		if($currentUser){
			if(Hash::check($old_password, $currentUser->password)){
				$new_password = bcrypt($new_password);
				$currentUser->password = $new_password;
				try {
					$currentUser->save();
					return Response(['message' => 'Success', 'data' => $currentUser], 200);
				} catch (\Illuminate\Database\QueryException $ex) {
					return Response(['message' => 'Save failed', 'data' => new \stdClass()], 500);
				}
			}else{
				return Response(['message' => 'Wrong password', 'data' => new \stdClass()], 200);
			}
		}else{
			return Response(['message' => 'User not found', 'data' => new \stdClass()], 404);
		}
	}
	
	function cekDeleted($email)
    {
		$query = DB::table('users')
        ->join('companies', function ($join) use ($email){
            $join->on('users.company_id', '=', 'companies.id')
                 ->where('users.email','=',''.$email.'');
        });
		$query->where(function($q){
			$q->where('users.is_deleted', '=' , 1)
				->orWhere('users.is_active', '=' , 0);
		});
		$query->orWhere(function($q){
			$q->where('companies.is_active', '=' , 0);
		});
		$user = $query->get();
		// $user = $query->toSql();
		// dd($user);exit;
		
		return count($user);
    }
}

